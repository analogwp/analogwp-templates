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

use \Analog\Options;

defined( 'ABSPATH' ) || exit;

final class Analog_Templates {
	/**
	 * @var Analog_Templates The one true Analog_Templates
	 */
	private static $instance;

	/**
	 * Holds key for Favorite templates user meta.
	 *
	 * @var string
	 */
	public static $user_meta_prefix = 'analog_library_favorites';

	/**
	 * Main Analog_Templates instance.
	 *
	 * @return object|Analog_Templates The one true Analog_Templates.
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Analog_Templates ) ) {
			self::$instance = new Analog_Templates();
			self::$instance->setup_constants();

			add_action( 'plugins_loaded', [ self::$instance, 'load_textdomain' ] );
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
		require_once ANG_PLUGIN_DIR . 'inc/class-base.php';
		require_once ANG_PLUGIN_DIR . 'inc/class-options.php';
		require_once ANG_PLUGIN_DIR . 'inc/class-licensemanager.php';
		require_once ANG_PLUGIN_DIR . 'inc/api/class-remote.php';
		require_once ANG_PLUGIN_DIR . 'inc/api/class-local.php';
		require_once ANG_PLUGIN_DIR . 'inc/class-analog-importer.php';
		require_once ANG_PLUGIN_DIR . 'inc/class-elementor.php';
	}

	public function scripts( $hook ) {
		if ( 'toplevel_page_analogwp_templates' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'wp-components' );
		wp_enqueue_style( 'analog-google-fonts', 'https://fonts.googleapis.com/css?family=Poppins:400,500,600,700', [], '20190128' );

		wp_enqueue_script(
			'analogwp-app',
			ANG_PLUGIN_URL . 'assets/js/app.js',
			[
				'react',
				'react-dom',
				'wp-components',
				'wp-i18n',
			],
			filemtime( ANG_PLUGIN_DIR . 'assets/js/app.js' ),
			true
		);
		wp_set_script_translations( 'analogwp-app', 'ang' );

		$favorites = get_user_meta( get_current_user_id(), self::$user_meta_prefix, true );

		if ( ! $favorites )  $favorites = [];

		wp_localize_script(
			'analogwp-app', 'AGWP', [
				'ajaxurl'          => admin_url( 'admin-ajax.php' ),
				'is_settings_page' => ( 'toplevel_page_analogwp_templates' === $hook ) ? true : false,
				'favorites'        => $favorites,
				'elementorURL'     => admin_url( 'edit.php?post_type=elementor_library' ),
				'debugMode'        => defined( 'ANALOG_DEV_DEBUG' ),
				'isPro'            => false,
				'permission'       => (bool) current_user_can( 'manage_options' ),
				'license'          => [
					'status'  => Options::get_instance()->get( 'ang_license_key_status' ),
					'message' => get_transient( 'ang_license_message' ),
				],
			]
		);

		$helpscout = '!function(e,t,n){function a(){var e=t.getElementsByTagName("script")[0],n=t.createElement("script");n.type="text/javascript",n.async=!0,n.src="https://beacon-v2.helpscout.net",e.parentNode.insertBefore(n,e)}if(e.Beacon=n=function(t,n,a){e.Beacon.readyQueue.push({method:t,options:n,data:a})},n.readyQueue=[],"complete"===t.readyState)return a();e.attachEvent?e.attachEvent("onload",a):e.addEventListener("load",a,!1)}(window,document,window.Beacon||function(){});';
		wp_add_inline_script( 'analogwp-app', $helpscout );
		wp_add_inline_script( 'analogwp-app', "window.Beacon('init', 'a7572e82-da95-4f09-880e-5c1f071aaf07')" );

		$current_user = wp_get_current_user();
		$current_user = $current_user->data;
		$version      = ANG_VERSION;
		$url          = home_url();

		$identify_customer = "Beacon('identify', {
			name: '{$current_user->display_name}',
			email: '{$current_user->user_email}',
			'Plugin Version': '{$version}',
			'Website': '{$url}',
		});
		Beacon('prefill', {
			name: '{$current_user->display_name}',
			email: '{$current_user->user_email}',
		});";

		wp_add_inline_script( 'analogwp-app', $identify_customer );
	}

	/**
	 * Load plugin language files.
	 *
	 * @access public
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'ang', false, dirname( plugin_basename( ANG_PLUGIN_DIR ) ) . '/languages/' );
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

// Fire up plugin instance.
add_action( 'plugins_loaded', function() {
	ANG();
} );
