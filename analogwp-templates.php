<?php
/**
 * Plugin Name: Style Kits for Elementor
 * Plugin URI:  https://analogwp.com/
 * Description: Style Kits adds intuitive styling controls in the Elementor editor that power-up your design workflow.
 * Version:     1.5.6
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

/**
 * Main Plugin Analog_Templates class.
 */
final class Analog_Templates {
	/**
	 * Plugin instance.
	 *
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
	 * Holds key for Favorite blocks user meta.
	 *
	 * @var string
	 */
	public static $user_meta_block_prefix = 'analog_block_favorites';

	/**
	 * Current plugin version.
	 *
	 * @var string
	 */
	public static $version = '1.5.6';

	/**
	 * Main Analog_Templates instance.
	 *
	 * @return void
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
			self::$instance->setup_constants();

			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
			add_action( 'admin_enqueue_scripts', array( self::$instance, 'scripts' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( ANG_PLUGIN_FILE ), array( self::$instance, 'plugin_action_links' ) );
			add_filter( 'analog/app/strings', array( self::$instance, 'send_strings_to_app' ) );

			self::$instance->includes();

			( new Consumer() )->register();
			( new Admin\Notices() )->register();
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
			define( 'ANG_VERSION', self::$version );
		}

		// Plugin Folder Path.
		if ( ! defined( 'ANG_PLUGIN_DIR' ) ) {
			define( 'ANG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		if ( ! defined( 'ANG_PLUGIN_BASE' ) ) {
			define( 'ANG_PLUGIN_BASE', plugin_basename( __FILE__ ) );
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
	 * @return void
	 */
	private function includes() {
		require_once ANG_PLUGIN_DIR . 'inc/register-settings.php';
		require_once ANG_PLUGIN_DIR . 'inc/settings-helpers.php';
		require_once ANG_PLUGIN_DIR . 'inc/class-base.php';
		require_once ANG_PLUGIN_DIR . 'inc/class-import-image.php';
		require_once ANG_PLUGIN_DIR . 'inc/class-options.php';
		require_once ANG_PLUGIN_DIR . 'inc/Consumer.php';
		require_once ANG_PLUGIN_DIR . 'inc/admin/Notice.php';
		require_once ANG_PLUGIN_DIR . 'inc/admin/Notices.php';
		require_once ANG_PLUGIN_DIR . 'inc/class-utils.php';
		require_once ANG_PLUGIN_DIR . 'inc/api/class-remote.php';
		require_once ANG_PLUGIN_DIR . 'inc/api/class-local.php';
		require_once ANG_PLUGIN_DIR . 'inc/class-analog-importer.php';
		require_once ANG_PLUGIN_DIR . 'inc/class-elementor.php';
		require_once ANG_PLUGIN_DIR . 'inc/class-tracker.php';
		require_once ANG_PLUGIN_DIR . 'inc/class-cron.php';

		require_once ANG_PLUGIN_DIR . 'inc/elementor/Reset_Default_Style_Trait.php';
		require_once ANG_PLUGIN_DIR . 'inc/elementor/trait-document.php';
		require_once ANG_PLUGIN_DIR . 'inc/elementor/class-typography.php';
		require_once ANG_PLUGIN_DIR . 'inc/elementor/class-colors.php';
		require_once ANG_PLUGIN_DIR . 'inc/elementor/class-post-type.php';
		require_once ANG_PLUGIN_DIR . 'inc/elementor/class-tools.php';
		require_once ANG_PLUGIN_DIR . 'inc/elementor/class-analog-settings.php';
		require_once ANG_PLUGIN_DIR . 'inc/upgrade-functions.php';
		require_once ANG_PLUGIN_DIR . 'inc/class-quick-edit.php';

		require_once ANG_PLUGIN_DIR . 'inc/admin/class-admin.php';

		require_once ANG_PLUGIN_DIR . 'inc/class-beta-testers.php';

		require_once ANG_PLUGIN_DIR . 'inc/elementor/sections/background-color-classes.php';
	}

	/**
	 * Enqueue plugin assets.
	 *
	 * @param string $hook Current page hook.
	 */
	public function scripts( $hook ) {
		if ( 'toplevel_page_analogwp_templates' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'wp-components' );
		wp_enqueue_style( 'analog-google-fonts', 'https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap', array(), '20190716' );

		wp_enqueue_script(
			'analogwp-app',
			ANG_PLUGIN_URL . 'assets/js/app.js',
			array(
				'react',
				'react-dom',
				'jquery',
				'wp-components',
				'wp-hooks',
				'wp-i18n',
				'wp-api-fetch',
				'wp-html-entities',
			),
			filemtime( ANG_PLUGIN_DIR . 'assets/js/app.js' ),
			true
		);
		wp_set_script_translations( 'analogwp-app', 'ang' );

		$i10n = apply_filters( // phpcs:ignore
			'analog/app/strings',
			array(
				'is_settings_page'  => 'toplevel_page_analogwp_templates' === $hook,
				'rollback_url'      => wp_nonce_url( admin_url( 'admin-post.php?action=ang_rollback&version=VERSION' ), 'ang_rollback' ),
				'rollback_versions' => Utils::get_rollback_versions(),
			)
		);

		wp_localize_script( 'analogwp-app', 'AGWP', $i10n );
	}

	/**
	 * Prepare text strings to be sent to app.
	 *
	 * @param array $domains List of translatable strings.
	 *
	 * @since 1.3.4
	 * @return array
	 */
	public function send_strings_to_app( $domains ) {
		if ( ! is_array( $domains ) ) {
			$domains = array();
		}

		$favorites       = get_user_meta( get_current_user_id(), self::$user_meta_prefix, true );
		$block_favorites = get_user_meta( get_current_user_id(), self::$user_meta_block_prefix, true );

		if ( ! $favorites ) {
			$favorites = array();
		}
		if ( ! $block_favorites ) {
			$block_favorites = array();
		}

		$new_domains = array(
			'ajaxurl'        => admin_url( 'admin-ajax.php' ),
			'favorites'      => $favorites,
			'blockFavorites' => $block_favorites,
			'isPro'          => false,
			'version'        => ANG_VERSION,
			'elementorURL'   => admin_url( 'edit.php?post_type=elementor_library' ),
			'debugMode'      => ( defined( 'ANALOG_DEV_DEBUG' ) && ANALOG_DEV_DEBUG ),
			'pluginURL'      => plugin_dir_url( __FILE__ ),
			'license'        => array(
				'status'  => Options::get_instance()->get( 'ang_license_key_status' ),
				'message' => get_transient( 'ang_license_message' ),
			),
			'installed_kits' => Utils::imported_remote_kits(),
		);

		$domains += $new_domains;

		return $domains;
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

	/**
	 * Plugin action links.
	 *
	 * Adds action links to the plugin list table
	 *
	 * Fired by `plugin_action_links` filter.
	 *
	 * @since 1.3.1
	 * @access public
	 *
	 * @param array $links An array of plugin action links.
	 *
	 * @return array An array of plugin action links.
	 */
	public function plugin_action_links( $links ) {
		$settings_link = sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=ang-settings' ), __( 'Settings', 'ang' ) );

		array_unshift( $links, $settings_link );

		return $links;
	}
}

/**
 * The main function for that returns Analog_Templates.
 *
 * @return void|Analog_Templates The one true Analog_Templates Instance.
 */
function ANG() { // @codingStandardsIgnoreLine
	return Analog_Templates::instance();
}

/**
 * Fail plugin initiialization if requirements are not met.
 *
 * @return mixed|bool
 */
function analog_fail_load() {
	if ( ! function_exists( 'get_current_screen' ) ) {
		require_once ABSPATH . 'wp-admin/includes/screen.php';
	}
	$screen = get_current_screen();
	if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
		return;
	}

	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$file_path         = 'elementor/elementor.php';
	$installed_plugins = get_plugins();
	$elementor         = isset( $installed_plugins[ $file_path ] );

	if ( $elementor ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $file_path . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $file_path );
		$message        = '<p>' . __( 'Style Kits is not working because you need to activate the Elementor plugin.', 'ang' ) . '</p>';
		$message       .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Elementor Now', 'ang' ) ) . '</p>';
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
		$message     = '<p>' . __( 'Style Kits is not working because you need to install the Elementor plugin.', 'ang' ) . '</p>';
		$message    .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install Elementor Now', 'ang' ) ) . '</p>';
	}

	echo '<div class="error"><p>' . $message . '</p></div>'; // @codingStandardsIgnoreLine
}

/**
 * Fail loading, if WordPress version requirements not met.
 *
 * @since 1.1
 * @return void
 */
function analog_fail_wp_version() {
	/* translators: %s: WordPress version */
	$message      = sprintf( esc_html__( 'Style Kits requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT RUNNING.', 'ang' ), '5.0' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

// Fire up plugin instance.
add_action(
	'plugins_loaded',
	static function() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', __NAMESPACE__ . '\analog_fail_load' );
			return;
		}

		if ( ! version_compare( get_bloginfo( 'version' ), '5.0', '>=' ) ) {
			add_action( 'admin_notices', __NAMESPACE__ . '\analog_fail_wp_version' );
			return;
		}

		ANG();
	}
);
