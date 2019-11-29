<?php
/**
 * Utility class.
 *
 * @package Analog
 */

namespace Analog;

use Elementor\Plugin;
use WP_Query;

/**
 * Helper functions.
 *
 * @package Analog
 */
class Utils extends Base {
	public function __construct() {
		add_action( 'admin_notices', [ $this, 'display_flash_notices' ] );
	}

	/**
	 * Debugging log.
	 *
	 * @param mixed $log Log data.
	 * @return void
	 */
	public static function log( $log ) {
		if ( ! defined( 'WP_DEBUG_LOG' ) || ! WP_DEBUG_LOG ) {
			return;
		}

		if ( is_array( $log ) || is_object( $log ) ) {
			error_log( print_r( $log, true ) ); // @codingStandardsIgnoreLine
		} else {
			error_log( $log ); // @codingStandardsIgnoreLine
		}
	}

	/**
	 * Get import logo data.
	 *
	 * @since  1.1
	 * @return array
	 */
	public static function get_import_log() {
		return \get_option( '_ang_import_history' );
	}

	/**
	 * Recording import details on import.
	 *
	 * @since 1.1
	 *
	 * @param int    $id Template ID.
	 * @param int    $post_id Post ID, in which this template was imported to.
	 * @param string $method How was this template imported.
	 *
	 * @return void
	 */
	public static function add_import_log( $id, $post_id, $method ) {
		$imports = self::get_import_log();
		if ( ! $imports ) {
			$imports = [];
		}

		$time = time();

		$imports[] = compact( 'id', 'post_id', 'method', 'time' );

		update_option( '_ang_import_history', $imports );
	}

	/**
	 * Get registered tokens.
	 *
	 * @param boolean $prefix Where to prefix selected Global Style Kit with 'Global: '.
	 * @return array
	 */
	public static function get_tokens( $prefix = true ) {
		$posts = \get_posts(
			[
				'post_type'      => 'ang_tokens',
				'posts_per_page' => -1,
			]
		);

		$tokens = [];

		foreach ( $posts as $post ) {
			$global_token = (int) \get_option( 'elementor_ang_global_kit' );

			$title = $post->post_title;

			if ( $global_token && $post->ID === $global_token && $prefix ) {
				/* translators: Global Style Kit post title. */
				$title = sprintf( __( 'Global: %s', 'ang' ), $title );
			}

			$tokens[ $post->ID ] = $title;
		}

		return $tokens;
	}

	/**
	 * Return the Post ID for Global Style Kit.
	 *
	 * @return int Post ID.
	 */
	public static function get_global_kit_id() {
		return (int) \get_option( 'elementor_ang_global_kit' );
	}

	/**
	 * Get global token post metadata.
	 *
	 * @return array|bool Tokens data.
	 */
	public static function get_global_token_data() {
		$global_token = self::get_global_kit_id();

		if ( $global_token ) {
			return \get_post_meta( $global_token, '_tokens_data', true );
		}

		return false;
	}

	/**
	 * Clear cache.
	 *
	 * Delete all meta containing files data. And delete the actual
	 * files from the upload directory.
	 *
	 * @since 1.2.1
	 * @access public
	 */
	public static function clear_elementor_cache() {
		Plugin::instance()->files_manager->clear_cache();
	}

	/**
	 * Display admin notices.
	 *
	 * @since 1.2.3
	 * @return void
	 */
	public function display_flash_notices() {
		$notices = \get_option( 'ang_notices', [] );

		// Iterate through our notices to be displayed and print them.
		foreach ( $notices as $notice ) {
			printf(
				'<div class="notice notice-%1$s %2$s"><p>%3$s</p></div>',
				$notice['type'],
				$notice['dismissible'],
				$notice['notice']
			);
		}

		// Now we reset our options to prevent notices being displayed forever.
		if ( ! empty( $notices ) ) {
			delete_option( 'ang_notices' );
		}
	}

	/**
	 * Add an admin notice.
	 *
	 * @param string $notice Notice text.
	 * @param string $type Notice type.
	 * @param bool   $dismissible Is notification dismissable.
	 *
	 * @since 1.2.3
	 *
	 * @return void
	 */
	public static function add_notice( $notice = '', $type = 'warning', $dismissible = true ) {
		// Here we return the notices saved on our option, if there are not notices, then an empty array is returned.
		$notices = \get_option( 'ang_notices', [] );

		$dismissible_text = ( $dismissible ) ? 'is-dismissible' : '';

		// We add our new notice.
		array_push(
			$notices,
			array(
				'notice'      => $notice,
				'type'        => $type,
				'dismissible' => $dismissible_text,
			)
		);

		// Then we update the option with our notices array.
		update_option( 'ang_notices', $notices );
	}

	public static function get_public_post_types( $args = [] ) {
		$post_type_args = [
			// Default is the value $public.
			'show_in_nav_menus' => true,
		];

		// Keep for backwards compatibility.
		if ( ! empty( $args['post_type'] ) ) {
			$post_type_args['name'] = $args['post_type'];
			unset( $args['post_type'] );
		}

		$post_type_args = wp_parse_args( $post_type_args, $args );

		$_post_types = get_post_types( $post_type_args, 'objects' );

		$post_types = [];

		foreach ( $_post_types as $post_type => $object ) {
			$post_types[ $post_type ] = $object->label;
		}

		/**
		 * Public Post types
		 *
		 * Allow 3rd party plugins to filters the public post types elementor should work on
		 *
		 * @since 2.3.0
		 *
		 * @param array $post_types Elementor supported public post types.
		 */
		return apply_filters( 'analog/utils/get_public_post_types', $post_types );
	}

	/**
	 * Returns an array of Post IDs using global kit.
	 *
	 * Pass Style kit ID to find posts/pages using specific style kit.
	 *
	 * @param int|bool $kit_id Style Kit ID to search for.
	 * @return array
	 */
	public static function posts_using_stylekit( $kit_id = false ) {
		$post_types  = get_post_types( [ 'public' => true ] );
		$post_types += [ 'elementor_library' ];
		unset( $post_types['attachment'] );

		$query_args = [
			'post_type'      => apply_filters( 'analog/stylekit/posttypes', $post_types ),
			'post_status'    => 'any',
			'meta_key'       => '_elementor_page_settings',
			'meta_values'    => [ 'ang_action_tokens' ],
			'meta_compare'   => 'IN',
			'fields'         => 'ids',
			'posts_per_page' => -1,
		];

		$query = new WP_Query( $query_args );

		$posts = [];

		foreach ( $query->posts as $post_id ) {
			$settings = \get_post_meta( $post_id, '_elementor_page_settings', true );

			/**
			 * This conditional divides results in for two conditions:
			 *
			 * 1. When a kit ID is provided to check against.
			 * 2. When there is a global style kit active or either blank. We return all Elementor posts.
			 */
			if ( $kit_id ) {
				if ( isset( $settings['ang_action_tokens'] ) && (int) $settings['ang_action_tokens'] === (int) $kit_id ) {
					$posts[] = $post_id;
				}
			} elseif ( ! $kit_id && '' !== self::get_global_kit_id() ) {
				if (
					! isset( $settings['ang_action_tokens'] )
					|| '' === $settings['ang_action_tokens']
					|| (int) self::get_global_kit_id() === (int) $settings['ang_action_tokens']
				) {
					$posts[] = $post_id;
				}
			}
		}

		wp_reset_postdata();

		return $posts;
	}

	/**
	 * Refresh all posts using a specific Style Kit.
	 *
	 * @param array $token Token/Style Kit data.
	 * @param bool  $kit_id Style Kit ID.
	 * @param bool  $current_id ID of post being edited.
	 *
	 * @since 1.2.3
	 *
	 * @return bool
	 */
	public static function refresh_posts_using_stylekit( $token, $kit_id = false, $current_id = false ) {
		if ( self::get_global_kit_id() === (int) $kit_id ) {
			$posts = self::posts_using_stylekit();
		} else {
			$posts = self::posts_using_stylekit( $kit_id );
		}

		// Return early if there aren't any posts.
		if ( ! $posts ) {
			return false;
		}

		foreach ( $posts as $post_id ) {
			if ( (int) $current_id === $post_id ) {
				continue;
			}

			self::add_to_stylekit_queue( $post_id );

			$tokens = json_decode( $token, ARRAY_A );

			$tokens['ang_action_tokens'] = $kit_id;
			
			self::update_style_kit_for_post( $post_id, $tokens );
		}

		return true;
	}

	/**
	 * Add a post to Stylekit queue.
	 *
	 * A style kit queue holds an array of posts
	 * that needs their style kit data refreshed.
	 *
	 * @since 1.2.3
	 * @param int $posts Post ID.
	 *
	 * @return void
	 */
	public static function add_to_stylekit_queue( $posts ) {
		$queue = Options::get_instance()->get( 'stylekit_refresh_queue' );

		if ( ! $queue ) {
			$queue = [];
		}

		$queue[] = $posts;

		Options::get_instance()->set( 'stylekit_refresh_queue', array_unique( $queue ) );
	}

	/**
	 * Remove a post from Stylekit.
	 *
	 * @param int $item Post ID.
	 * @since 1.2.3
	 * @return void
	 */
	public static function remove_from_stylekit_queue( $item ) {
		$queue = Options::get_instance()->get( 'stylekit_refresh_queue' );
		$key   = array_search( (int) $item, $queue, true );

		if ( false !== $key ) {
			unset( $queue[ $key ] );
		}

		Options::get_instance()->set( 'stylekit_refresh_queue', $queue );
	}

	/**
	 * Get a list of posts in style kit queue.
	 *
	 * @since 1.2.3
	 * @return array|bool
	 */
	public static function get_stylekit_queue() {
		return Options::get_instance()->get( 'stylekit_refresh_queue' );
	}

	/**
	 * Get a list of Style Kits that were imported from library.
	 *
	 * @since 1.3.4
	 * @return array
	 */
	public static function imported_remote_kits() {
		$kits = [];

		$query = new WP_Query(
			[
				'post_type'              => 'ang_tokens',
				'post_status'            => 'publish',
				'posts_per_page'         => -1,
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			]
		);

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				$kits[] = get_post_field( 'post_title' );
			}
		}

		wp_reset_postdata();

		return $kits;
	}

	/**
	 * Get valid rollback versions.
	 *
	 * @since 1.3.7
	 * @return array|mixed
	 */
	public static function get_rollback_versions() {
		$rollback_versions = get_transient( 'ang_rollback_versions_' . ANG_VERSION );

		if ( false === $rollback_versions ) {
			$max_versions = 30;

			require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

			$plugin_information = plugins_api(
				'plugin_information',
				[ 'slug' => 'analogwp-templates' ]
			);

			if ( empty( $plugin_information->versions ) || ! is_array( $plugin_information->versions ) ) {
				return [];
			}

			krsort( $plugin_information->versions );

			$rollback_versions = [];

			$current_index = 0;

			foreach ( $plugin_information->versions as $version => $download_link ) {
				if ( $max_versions <= $current_index ) {
					break;
				}

				if ( preg_match( '/(trunk|beta|rc)/i', strtolower( $version ) ) ) {
					continue;
				}

				if ( version_compare( $version, ANG_VERSION, '>=' ) ) {
					continue;
				}

				$current_index++;
				$rollback_versions[] = $version;
			}

			set_transient( 'ang_rollback_versions_' . ANG_VERSION, $rollback_versions, WEEK_IN_SECONDS );
		}

		return $rollback_versions;
	}

	/**
	 * Convert string to boolean.
	 *
	 * @param array $data Array object.
	 * @since 1.3.8
	 * @return array
	 */
	public static function convert_string_to_boolean( $data ) {
		if ( ! is_array( $data ) ) {
			return $data;
		}

		array_walk_recursive(
			$data,
			function( &$value, $key ) {
				if ( 'isInner' === $key || 'isLinked' === $key ) {
					$value = (bool) $value;
				}
			}
		);

		return $data;
	}

	/**
	 * This function strips off the allowed keys that are part of a Style Kit.
	 *
	 * @param array $settings Post Meta settings.
	 *
	 * @since 1.3.15
	 * @return array Modified settings array.
	 */
	public static function remove_stored_kit_keys( $settings ) {
		/**
		 * List of settings key prefixes that needs to be removed prior to updating an SK.
		 *
		 * @since 1.3.15
		 */
		$allowed = apply_filters(
			'analog/stylekit/allowed/setting/prefixes',
			[ 'ang_', 'hide', 'background_background', 'background_color', 'background_grad', 'custom_css' ]
		);

		$keep = array_filter(
			$settings,
			function( $key ) use ( $allowed ) {
				foreach ( $allowed as $allow ) {
					if ( strpos( $key, $allow ) === 0 ) {
						return false;
					}
				}

				return true;
			},
			ARRAY_FILTER_USE_KEY
		);

		return $keep;
	}

	/**
	 * Update Style Kit for a specific post.
	 *
	 * @param int   $post_id Post ID for which Style Kit will be updated.
	 * @param array $tokens Style Kit data.
	 *
	 * @since 1.3.15
	 * @return void
	 */
	public static function update_style_kit_for_post( int $post_id, array $tokens ) {
		$page_settings = \get_post_meta( $post_id, '_elementor_page_settings', true );

		$allowed_types = [ 'post', 'wp-post', 'page', 'wp-page', 'global-widget', 'popup', 'section', 'header', 'footer', 'single', 'archive' ];

		$document_type = \get_post_meta( $post_id, '_elementor_template_type', true );

		if ( ! $document_type || ! in_array( $document_type, $allowed_types, true ) ) {
			return;
		}

		$preserved_settings = self::remove_stored_kit_keys( $page_settings );
		$modified_settings  = array_merge( $preserved_settings, $tokens );

		\update_post_meta( $post_id, '_elementor_page_settings', wp_slash( $modified_settings ), true );
	}
}

new Utils();
