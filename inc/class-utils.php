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
			error_log( print_r( $log, true ) );
		} else {
			error_log( $log );
		}
	}
}

new Utils();
