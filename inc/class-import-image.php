<?php
/**
 * Image importer class.
 *
 * @package Analog
 */

namespace Analog\Classes;

use Analog\Base;

/**
 * Image Importer class.
 *
 * @since 1.3.4
 */
class Import_Image extends Base {
	/**
	 * Images IDs
	 *
	 * @var array   The Array of already image IDs.
	 */
	private $already_imported_ids = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
		}

		WP_Filesystem();
	}

	/**
	 * Get image hash.
	 *
	 * Retrieve the sha1 hash of the image URL.
	 *
	 * @access private
	 * @param string $attachment_url The attachment URL.
	 * @return string Image hash.
	 */
	private function get_hash_image( $attachment_url ) {
		return sha1( $attachment_url );
	}

	/**
	 * Process Image Download
	 *
	 * @param  array $attachments Attachment array.
	 * @return array              Attachment array.
	 */
	public function process( $attachments ) {
		$downloaded_images = array();

		foreach ( $attachments as $key => $attachment ) {
			$downloaded_images[] = $this->import( $attachment );
		}

		return $downloaded_images;
	}

	/**
	 * Get Saved Image.
	 *
	 * @param  array $attachment Attachment Data.
	 * @return bool|array        Hash string.
	 */
	private function get_saved_image( $attachment ) {
		if ( isset( $this->already_imported_ids[ $attachment['id'] ] ) ) {
			return $this->already_imported_ids[ $attachment['id'] ];
		}

		$post_id = $this->find_attachment_id_by_meta( '_analog_image_hash', $this->get_hash_image( $attachment['url'] ) );

		if ( empty( $post_id ) ) {
			$filename = basename( $attachment['url'] );

			$post_id = $this->find_attachment_id_by_meta( '_wp_attached_file', $filename, 'LIKE' );
		}

		if ( $post_id ) {
			$new_attachment = array(
				'id'  => $post_id,
				'url' => \wp_get_attachment_url( $post_id ),
			);

			$this->already_imported_ids[ $attachment['id'] ] = $new_attachment;

			return $new_attachment;
		}

		return false;
	}

	/**
	 * Find an attachment by meta value using core post queries.
	 *
	 * @param string $meta_key   Meta key.
	 * @param string $meta_value Meta value.
	 * @param string $compare    Meta compare operator.
	 * @return int
	 */
	private function find_attachment_id_by_meta( $meta_key, $meta_value, $compare = '=' ) {
		$attachments = get_posts(
			array(
				'post_type'              => 'attachment',
				'post_status'            => 'inherit',
				'fields'                 => 'ids',
				'posts_per_page'         => 1,
				'no_found_rows'          => true,
				'cache_results'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'meta_query'             => array(
					array(
						'key'     => $meta_key,
						'value'   => $meta_value,
						'compare' => $compare,
					),
				),
			)
		);

		return empty( $attachments ) ? 0 : (int) $attachments[0];
	}

	/**
	 * Import image.
	 *
	 * Import a single image from a remote server, upload the image WordPress
	 * uploads folder, create a new attachment in the database and updates the
	 * attachment metadata.
	 *
	 * @access public
	 * @param array $attachment Attachment data.
	 *
	 * @return array|false Imported image data, or false.
	 */
	public function import( $attachment ) {
		$saved_image = $this->get_saved_image( $attachment );

		if ( $saved_image ) {
			return $saved_image;
		}

		$file_content = \wp_remote_retrieve_body(
			\wp_safe_remote_get(
				$attachment['url'],
				array(
					'timeout'   => '60',
					'sslverify' => false,
				)
			)
		);

		if ( empty( $file_content ) ) {
			return false;
		}

		// Extract the file name and extension from the url.
		$filename = basename( $attachment['url'] );

		$upload = \wp_upload_bits(
			$filename,
			null,
			$file_content
		);

		$post = array(
			'post_title' => $filename,
			'guid'       => $upload['url'],
		);

		$info = \wp_check_filetype( $upload['file'] );

		if ( $info ) {
			$post['post_mime_type'] = $info['type'];
		} else {
			// For now just return the origin attachment.
			return $attachment;
		}

		$post_id  = \wp_insert_attachment( $post, $upload['file'] );
		$metadata = \wp_generate_attachment_metadata( $post_id, $upload['file'] );

		\wp_update_attachment_metadata( $post_id, $metadata );
		\update_post_meta( $post_id, '_analog_image_hash', $this->get_hash_image( $attachment['url'] ) );

		$new_attachment = array(
			'id'  => $post_id,
			'url' => $upload['url'],
		);

		$this->already_imported_ids[ $attachment['id'] ] = $new_attachment;

		return $new_attachment;
	}
}

Import_Image::get_instance();
