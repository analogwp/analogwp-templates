<?php
/**
 * Plugin Name: AnalogWP Templates
 * Plugin URI:  https://analogwp.com/
 * Description: Handfully crafted Elementor templates packs.
 * Version:     0.0.1
 * Author:      AnalogWP
 * Author URI:  https://analogwp.com/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ang
 *
 * @package AnalogWP
 */

namespace Analog;

defined( 'ABSPATH' ) || exit;

final class Analog_Templates {
	/**
	 * @var Analog_Templates The one true Analog_Templates
	 */
	private static $instance;

	/**
	 * Main Analog_Templates instance.
	 *
	 * @return object|Analog_Templates The one true Analog_Templates.
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Analog_Templates ) ) {
			self::$instance = new Analog_Templates();
			self::$instance->setup_constants();

			// add_action( 'plugins_loaded', [ self::$instance, 'load_textdomain' ] );
			add_action( 'admin_enqueue_scripts', [ self::$instance, 'scripts' ] );

			self::$instance->includes();
		}
	}

	/**
	 * Throw error on object clone.
	 *
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'ang' ), '1.0' );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'ang' ), '1.0' );
	}

	/**
	 * Setup plugin constants.
	 *
	 * @access private
	 * @return void
	 */
	private function setup_constants() {
		// Plugin version.
		if ( ! defined( 'ANG_VERSION' ) ) {
			define( 'ANG_VERSION', '1.0.0' );
		}

		// Plugin Folder Path.
		if ( ! defined( 'ANG_PLUGIN_DIR' ) ) {
			define( 'ANG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		// Plugin Folder URL.
		if ( ! defined( 'ANG_PLUGIN_URL' ) ) {
			define( 'ANG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		// Plugin Root File.
		if ( ! defined( 'ANG_PLUGIN_FILE' ) ) {
			define( 'ANG_PLUGIN_FILE', __FILE__ );
		}
	}

	/**
	 * Include required files.
	 *
	 * @access private
	 * @since 1.4
	 * @return void
	 */
	private function includes() {
		require_once ANG_PLUGIN_DIR . 'inc/register-settings.php';
		require_once ANG_PLUGIN_DIR . 'inc/class-elementor.php';
	}

	public function scripts( $hook ) {
		if ( 'toplevel_page_analogwp_templates' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'wp-components' );

		wp_enqueue_script( 'analogwp-app', ANG_PLUGIN_URL . 'assets/js/app.js', [ 'react', 'react-dom', 'wp-components' ], ANG_VERSION, true );
	}
}

/**
 * The main function for that returns Analog_Templates.
 *
 * @return object|Analog_Templates The one true Analog_Templates Instance.
 */
function ANG() {
	return Analog_Templates::instance();
}

ANG();
