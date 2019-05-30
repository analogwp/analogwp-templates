<?php
/**
 * Utility class.
 *
 * @package Analog
 */

namespace Analog;

use Elementor\Plugin;
use function get_option;
use function get_post_meta;
use function get_the_ID;
use function get_the_title;
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
		return get_option( '_ang_import_history' );
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
		$query = new WP_Query(
			[
				'post_type'      => 'ang_tokens',
				'posts_per_page' => -1,
			]
		);

		if ( ! $query->have_posts() ) {
			return [];
		}

		$tokens = [];
		while ( $query->have_posts() ) {
			$query->the_post();
			$post_id = get_the_ID();

			$global_token = (int) get_option( 'elementor_ang_global_kit' );
			if ( $global_token && $post_id === $global_token && $prefix ) {
				$title = __( 'Global: ', 'ang' ) . get_the_title();
			} else {
				$title = get_the_title();
			}

			$tokens[ $post_id ] = $title;
		}

		wp_reset_postdata();

		return $tokens;
	}

	/**
	 * Return the Post ID for Global Style Kit.
	 *
	 * @return int Post ID.
	 */
	public static function get_global_kit_id() {
		return (int) get_option( 'elementor_ang_global_kit' );
	}

	/**
	 * Get global token post metadata.
	 *
	 * @return array|bool Tokens data.
	 */
	public static function get_global_token_data() {
		$global_token = self::get_global_kit_id();

		if ( $global_token ) {
			return get_post_meta( $global_token, '_tokens_data', true );
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
		$notices = get_option( 'ang_notices', [] );

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
		$notices = get_option( 'ang_notices', [] );

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
			$settings = get_post_meta( $post_id, '_elementor_page_settings', true );

			/**
			 * This conditional divides results in for two conditions:
			 *
			 * 1. When a kit ID is provided to check against.
			 * 2. When there is a global style kit active or either blank. We return all Elementor posts.
			 */
			if ( $kit_id ) {
				if ( (int) $settings['ang_action_tokens'] === (int) $kit_id ) {
					$posts[] = $post_id;
				}
			} elseif ( ! $kit_id && '' !== self::get_global_kit_id() ) {
				if (
					'' === $settings['ang_action_tokens']
					|| ! isset( $settings['ang_action_tokens'] )
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

			update_post_meta( $post_id, '_elementor_page_settings', $tokens );
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
}

new Utils();
