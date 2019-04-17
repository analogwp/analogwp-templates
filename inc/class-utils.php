<?php
/**
 * Utility class.
 *
 * @package Analog
 */

namespace Analog;

/**
 * Helper functions.
 *
 * @package Analog
 */
class Utils extends Base {
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
	 * @return array
	 */
	public static function get_tokens() {
		$query = new \WP_Query(
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
			$post_id = \get_the_ID();

			$global_token = Options::get_instance()->get( 'global_token' );
			if ( $global_token && ! empty( $global_token ) && $post_id === $global_token['id'] ) {
				$title = \get_the_title() . __( ' - Global', 'ang' );
			} else {
				$title = \get_the_title();
			}

			$tokens[ $post_id ] = $title;
		}

		wp_reset_postdata();

		return $tokens;
	}
}

new Utils();
