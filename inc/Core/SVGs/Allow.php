<?php
/**
 * Class Analog\Core\SVGs\Allow
 *
 * @package   Analog
 * @copyright 2022 Dashwork Studio Pvt. Ltd.
 */

namespace Analog\Core\SVGs;

use Analog\Options;
use Analog\Dependencies\enshrined\svgSanitize\Sanitizer;

/**
 * Class enabling SVG uploads and imports.
 *
 * @since 1.9.5
 * @access private
 * @ignore
 */
final class Allow {
	/**
	 * The sanitizer
	 *
	 * @var Sanitizer
	 */
	protected $sanitizer;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$options = Options::get_instance();

		// By default, set it to allow.
		$allowed = $options->has( 'allow_svg_uploads' ) ? $options->get( 'allow_svg_uploads' ) : true;

		// Return early if this is not disabled.
		if ( ! $allowed ) {
			return;
		}

		$this->sanitizer = new Sanitizer();
		$this->sanitizer->minify( true );

		add_filter( 'upload_mimes', array( $this, 'allow_svg' ) );
		add_filter( 'wp_handle_upload_prefilter', array( $this, 'check_for_svg' ) );
		add_filter( 'wp_prepare_attachment_for_js', array( $this, 'fix_admin_preview' ), 10, 3 );
		add_filter( 'wp_get_attachment_image_src', array( $this, 'fix_dimensions' ), 10, 4 );
		add_action( 'get_image_tag', array( $this, 'get_image_tag_override' ), 10, 6 );
		add_filter( 'wp_generate_attachment_metadata', array( $this, 'skip_regeneration' ), 10, 2 );
		add_filter( 'wp_get_attachment_metadata', array( $this, 'fix_metadata_error' ), 10, 2 );
		add_filter( 'wp_calculate_image_srcset_meta', array( $this, 'disable_srcset' ), 10, 4 );
	}

	/**
	 * Enable SVG Uploads
	 *
	 * @param array $mimes Mime types keyed by the file extension regex corresponding to those types.
	 *
	 * @return mixed
	 */
	public function allow_svg( $mimes ) {
		$mimes['svg']  = 'image/svg+xml';
		$mimes['svgz'] = 'image/svg+xml';

		return $mimes;
	}

	/**
	 * Check if the file is an SVG, if so handle appropriately
	 *
	 * @param array $file An array of data for a single file.
	 *
	 * @return mixed
	 */
	public function check_for_svg( $file ) {

		// Ensure we have a proper file path before processing.
		if ( ! isset( $file['tmp_name'] ) ) {
			return $file;
		}

		$file_name   = isset( $file['name'] ) ? $file['name'] : '';
		$wp_filetype = wp_check_filetype_and_ext( $file['tmp_name'], $file_name );
		$type        = ! empty( $wp_filetype['type'] ) ? $wp_filetype['type'] : '';

		if ( 'image/svg+xml' === $type ) {
			if ( ! $this->sanitize( $file['tmp_name'] ) ) {
				$file['error'] = __( "Unable to sanitize this file hence it wasn't uploaded!", 'ang' );
			}
		}

		return $file;
	}

	/**
	 * Sanitize the SVG
	 *
	 * @param string $file Temp file path.
	 *
	 * @return bool
	 */
	protected function sanitize( $file ) {
		$dirty = file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

		// Is the SVG gzipped? If so we try and decode the string.
		$is_zipped = $this->is_gzipped( $dirty );
		if ( $is_zipped ) {
			$dirty = gzdecode( $dirty );

			// If decoding fails, bail as we're not secure.
			if ( false === $dirty ) {
				return false;
			}
		}

		$clean = $this->sanitizer->sanitize( $dirty );

		if ( false === $clean ) {
			return false;
		}

		// If we were gzipped, we need to re-zip.
		if ( $is_zipped ) {
			$clean = gzencode( $clean );
		}

		file_put_contents( $file, $clean ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents

		return true;
	}

	/**
	 * Check if the contents are gzipped
	 *
	 * @see http://www.gzip.org/zlib/rfc-gzip.html#member-format
	 *
	 * @param string $contents Content to check.
	 *
	 * @return bool
	 */
	protected function is_gzipped( $contents ) {
		// phpcs:disable Generic.Strings.UnnecessaryStringConcat.Found
		if ( function_exists( 'mb_strpos' ) ) {
			return 0 === mb_strpos( $contents, "\x1f" . "\x8b" . "\x08" );
		} else {
			return 0 === strpos( $contents, "\x1f" . "\x8b" . "\x08" );
		} // phpcs:enable
	}

	/**
	 * Filters the attachment data prepared for JavaScript to add the sizes array to the response
	 *
	 * @param array      $response Array of prepared attachment data.
	 * @param int|object $attachment Attachment ID or object.
	 * @param array      $meta Array of attachment meta data.
	 *
	 * @return array
	 */
	public function fix_admin_preview( $response, $attachment, $meta ) {
		if ( 'image/svg+xml' === $response['mime'] ) {
			$dimensions = $this->get_dimensions( get_attached_file( $attachment->ID ) );

			if ( $dimensions ) {
				$response = array_merge( $response, $dimensions );
			}

			$possible_sizes = apply_filters(
				'image_size_names_choose',
				array(
					'full'      => __( 'Full Size', 'ang' ),
					'thumbnail' => __( 'Thumbnail', 'ang' ),
					'medium'    => __( 'Medium', 'ang' ),
					'large'     => __( 'Large', 'ang' ),
				)
			);

			$sizes = array();

			foreach ( $possible_sizes as $size => $label ) {
				$default_height = 2000;
				$default_width  = 2000;

				if ( 'full' === $size && $dimensions ) {
					$default_height = $dimensions['height'];
					$default_width  = $dimensions['width'];
				}

				$sizes[ $size ] = array(
					'height'      => get_option( "{$size}_size_w", $default_height ),
					'width'       => get_option( "{$size}_size_h", $default_width ),
					'url'         => $response['url'],
					'orientation' => 'portrait',
				);
			}

			$response['sizes'] = $sizes;
			$response['icon']  = $response['url'];
		}

		return $response;
	}

	/**
	 * Filters the image src result.
	 * If the image size doesn't exist, set a default size of 100 for width and height
	 *
	 * @param array|false  $image Either array with src, width & height, icon src, or false.
	 * @param int          $attachment_id Image attachment ID.
	 * @param string|array $size Size of image. Image size or array of width and height values
	 *                                    (in that order). Default 'thumbnail'.
	 * @param bool         $icon Whether the image should be treated as an icon. Default false.
	 *
	 * @return array
	 */
	public function fix_dimensions( $image, $attachment_id, $size, $icon ) {
		if ( 'image/svg+xml' === get_post_mime_type( $attachment_id ) ) {
			$dimensions = $this->get_dimensions( get_attached_file( $attachment_id ) );

			if ( $dimensions ) {
				$image[1] = $dimensions['width'];
				$image[2] = $dimensions['height'];
			} else {
				$image[1] = 100;
				$image[2] = 100;
			}
		}

		return $image;
	}

	/**
	 * Override the default height and width string on an SVG
	 *
	 * @param string       $html HTML content for the image.
	 * @param int          $id Attachment ID.
	 * @param string       $alt Alternate text.
	 * @param string       $title Attachment title.
	 * @param string       $align Part of the class name for aligning the image.
	 * @param string|array $size Size of image. Image size or array of width and height values (in that order).
	 *                            Default 'medium'.
	 *
	 * @return mixed
	 */
	public function get_image_tag_override( $html, $id, $alt, $title, $align, $size ) {
		$mime = get_post_mime_type( $id );

		if ( 'image/svg+xml' === $mime ) {
			if ( is_array( $size ) ) {
				$width  = $size[0];
				$height = $size[1];
				// phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.Found, Squiz.PHP.DisallowMultipleAssignments.FoundInControlStructure
			} elseif ( 'full' === $size && $dimensions = $this->get_dimensions( get_attached_file( $id ) ) ) {
				$width  = $dimensions['width'];
				$height = $dimensions['height'];
			} else {
				$width  = get_option( "{$size}_size_w", false );
				$height = get_option( "{$size}_size_h", false );
			}

			if ( $height && $width ) {
				$html = str_replace( 'width="1" ', sprintf( 'width="%s" ', $width ), $html );
				$html = str_replace( 'height="1" ', sprintf( 'height="%s" ', $height ), $html );
			} else {
				$html = str_replace( 'width="1" ', '', $html );
				$html = str_replace( 'height="1" ', '', $html );
			}

			$html = str_replace( '/>', ' role="img" />', $html );
		}

		return $html;
	}

	/**
	 * Skip regenerating SVGs
	 *
	 * @param array $metadata      An array of attachment meta data.
	 * @param int   $attachment_id Attachment Id to process.
	 *
	 * @return mixed Metadata for attachment.
	 */
	public function skip_regeneration( $metadata, $attachment_id ) {
		$mime = get_post_mime_type( $attachment_id );
		if ( 'image/svg+xml' === $mime ) {
			$additional_image_sizes = wp_get_additional_image_sizes();
			$svg_path               = get_attached_file( $attachment_id );
			$upload_dir             = wp_upload_dir();
			// get the path relative to /uploads/.
			$relative_path = str_replace( trailingslashit( $upload_dir['basedir'] ), '', $svg_path );
			$filename      = basename( $svg_path );

			$dimensions = $this->get_dimensions( $svg_path );

			if ( ! $dimensions ) {
				return $metadata;
			}

			$metadata = array(
				'width'  => intval( $dimensions['width'] ),
				'height' => intval( $dimensions['height'] ),
				'file'   => $relative_path,
			);

			$sizes = array();
			foreach ( get_intermediate_image_sizes() as $s ) {
				$sizes[ $s ] = array(
					'width'  => '',
					'height' => '',
					'crop'   => false,
				);

				if ( isset( $additional_image_sizes[ $s ]['width'] ) ) {
					// For theme-added sizes.
					$sizes[ $s ]['width'] = intval( $additional_image_sizes[ $s ]['width'] );
				} else {
					// For default sizes set in options.
					$sizes[ $s ]['width'] = get_option( "{$s}_size_w" );
				}

				if ( isset( $additional_image_sizes[ $s ]['height'] ) ) {
					// For theme-added sizes.
					$sizes[ $s ]['height'] = intval( $additional_image_sizes[ $s ]['height'] );
				} else {
					// For default sizes set in options.
					$sizes[ $s ]['height'] = get_option( "{$s}_size_h" );
				}

				if ( isset( $additional_image_sizes[ $s ]['crop'] ) ) {
					// For theme-added sizes.
					$sizes[ $s ]['crop'] = intval( $additional_image_sizes[ $s ]['crop'] );
				} else {
					// For default sizes set in options.
					$sizes[ $s ]['crop'] = get_option( "{$s}_crop" );
				}

				$sizes[ $s ]['file']      = $filename;
				$sizes[ $s ]['mime-type'] = $mime;
			}
			$metadata['sizes'] = $sizes;
		}

		return $metadata;
	}

	/**
	 * Filters the attachment metadata.
	 *
	 * @param array|bool $data Array of metadata for the given attachment, or false
	 *                            if the object does not exist.
	 * @param int        $post_id Attachment ID.
	 */
	public function fix_metadata_error( $data, $post_id ) {

		// If it's a WP_Error regenerate metadata and save it.
		if ( is_wp_error( $data ) ) {
			$data = wp_generate_attachment_metadata( $post_id, get_attached_file( $post_id ) );
			wp_update_attachment_metadata( $post_id, $data );
		}

		return $data;
	}

	/**
	 * Disable the creation of srcset on SVG images.
	 *
	 * @param array  $image_meta The image meta data.
	 * @param int[]  $size_array    {
	 *     An array of requested width and height values.
	 *
	 *     @type int $0 The width in pixels.
	 *     @type int $1 The height in pixels.
	 * }
	 * @param string $image_src     The 'src' of the image.
	 * @param int    $attachment_id The image attachment ID.
	 */
	public function disable_srcset( $image_meta, $size_array, $image_src, $attachment_id ) {
		if ( $attachment_id && 'image/svg+xml' === get_post_mime_type( $attachment_id ) ) {
			$image_meta['sizes'] = array();
		}

		return $image_meta;
	}

	/**
	 * Get SVG size from the width/height or viewport.
	 *
	 * @param string|false $svg The file path to where the SVG file should be, false otherwise.
	 *
	 * @return array|bool
	 */
	protected function get_dimensions( $svg ) {
		$svg    = @simplexml_load_file( $svg ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		$width  = 0;
		$height = 0;
		if ( $svg ) {
			$attributes = $svg->attributes();

			if ( isset( $attributes->viewBox ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$sizes = explode( ' ', $attributes->viewBox ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				if ( isset( $sizes[2], $sizes[3] ) ) {
					$viewbox_width  = floatval( $sizes[2] );
					$viewbox_height = floatval( $sizes[3] );
				}
			}

			if ( isset( $attributes->width, $attributes->height ) && is_numeric( (float) $attributes->width ) && is_numeric( (float) $attributes->height ) && ! $this->str_ends_with( (string) $attributes->width, '%' ) && ! $this->str_ends_with( (string) $attributes->height, '%' ) ) {
				$attr_width  = floatval( $attributes->width );
				$attr_height = floatval( $attributes->height );
			}

			if ( isset( $viewbox_width, $viewbox_height ) ) {
				$width  = $viewbox_width;
				$height = $viewbox_height;
			} elseif ( isset( $attr_width, $attr_height ) ) {
				$width  = $attr_width;
				$height = $attr_height;
			}

			if ( ! $width && ! $height ) {
				return false;
			}
		}

		return array(
			'width'       => $width,
			'height'      => $height,
			'orientation' => ( $width > $height ) ? 'landscape' : 'portrait',
		);
	}

	/**
	 * Polyfill for `str_ends_with()` function added in PHP 8.0.
	 *
	 * Performs a case-sensitive check indicating if
	 * the haystack ends with needle.
	 *
	 * @param string $haystack The string to search in.
	 * @param string $needle   The substring to search for in the `$haystack`.
	 * @return bool True if `$haystack` ends with `$needle`, otherwise false.
	 */
	protected function str_ends_with( $haystack, $needle ) {
		if ( function_exists( 'str_ends_with' ) ) {
			return str_ends_with( $haystack, $needle );
		}

		if ( '' === $haystack && '' !== $needle ) {
			return false;
		}

		$len = strlen( $needle );
		return 0 === substr_compare( $haystack, $needle, -$len, $len );
	}
}


new Allow();
