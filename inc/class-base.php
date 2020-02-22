<?php
/**
 * Base class.
 *
 * @package AnalogWP
 */

namespace Analog;

defined( 'ABSPATH' ) || exit;

/**
 * Analog plugin.
 *
 * The main plugin handler class is responsible for initializing Analog. The
 * class registers and all the components required to run the plugin.
 */
class Base {
	/**
	 * Holds the plugin instance.
	 *
	 * @access protected
	 * @static
	 *
	 * @var Base
	 */
	private static $instances = array();

	/**
	 * Disable class cloning and throw an error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object. Therefore, we don't want the object to be cloned.
	 *
	 * @access public
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'ang' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * @access public
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'ang' ), '1.0.0' );
	}

	/**
	 * Sets up a single instance of the plugin.
	 *
	 * @access public
	 * @static
	 *
	 * @return static An instance of the class.
	 */
	public static function get_instance() {
		$module = get_called_class();
		if ( ! isset( self::$instances[ $module ] ) ) {
			self::$instances[ $module ] = new $module();
		}

		return self::$instances[ $module ];
	}

	/**
	 * Checks current memory limit and sets a new one if required.
	 *
	 * Used during importing to ensure we don't run out of memory on large imports.
	 *
	 * @access public
	 */
	public function check_memory_limit() {
		$memory_limit = ini_get( 'memory_limit' );
		if ( $memory_limit !== - 1 ) { // @codingStandardsIgnoreLine
			$last = $memory_limit[ strlen( $memory_limit ) - 1 ];
			$val  = rtrim( $memory_limit, $last );
			switch ( strtolower( $last ) ) {
				case 'g':
					$val *= 1024;
					// no break.
				case 'm':
					$val *= 1024;
					// no break.
				case 'k':
					$val *= 1024;
			}
			if ( $val < ( 1024 * 1024 * 1024 ) ) {
				@ini_set( 'memory_limit', '512M' ); // @codingStandardsIgnoreLine
			}
		}
	}

	/**
	 * Regenrate the CSS for an Elementor post.
	 *
	 * @param int $post_id Post ID to regenerate CSS for.
	 * @return void
	 */
	public function regenerate_elementor_css( $post_id ) {
		if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
			$post_css = new \Elementor\Core\Files\CSS\Post( $post_id );
			$post_css->enqueue();
			$post_css->update();

			\Elementor\Plugin::$instance->frontend->enqueue_styles();
		}
	}

	/**
	 * Ensure arguments exist.
	 *
	 * Checks whether the required arguments exist in the specified arguments.
	 *
	 * @since 1.2.1
	 * @access private
	 *
	 * @param array $required_args  Required arguments to check whether they
	 *                              exist.
	 * @param array $specified_args The list of all the specified arguments to
	 *                              check against.
	 *
	 * @return \WP_Error|true True on success, 'WP_Error' otherwise.
	 */
	private function ensure_args( array $required_args, array $specified_args ) {
		$not_specified_args = array_diff( $required_args, array_keys( array_filter( $specified_args ) ) );

		if ( $not_specified_args ) {
			return new \WP_Error( 'arguments_not_specified', sprintf( 'The required argument(s) "%s" not specified.', implode( ', ', $not_specified_args ) ) );
		}

		return true;
	}
}
