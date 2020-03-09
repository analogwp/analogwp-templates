<?php
/**
 * Plugin main file.
 *
 * @package     Analog
 * @copyright   2019 Dashwork Studio Pvt. Ltd.
 * @link        https://analogwp.com
 *
 * @wordpress-plugin
 * Plugin Name: Style Kits for Elementor
 * Plugin URI:  https://analogwp.com/
 * Description: Style Kits adds intuitive styling controls in the Elementor editor that power-up your design workflow.
 * Version:     1.6.2
 * Author:      AnalogWP
 * Author URI:  https://analogwp.com/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ang
 */

defined( 'ABSPATH' ) || exit;

define( 'ANG_ELEMENTOR_MINIMUM', '2.9.0' );
define( 'ANG_PHP_MINIMUM', '5.6.0' );
define( 'ANG_WP_MINIMUM', '5.0' );
define( 'ANG_VERSION', '1.6.2' );
define( 'ANG_PLUGIN_FILE', __FILE__ );
define( 'ANG_PLUGIN_URL', plugin_dir_url( ANG_PLUGIN_FILE ) );
define( 'ANG_PLUGIN_DIR', plugin_dir_path( ANG_PLUGIN_FILE ) );
define( 'ANG_PLUGIN_BASE', plugin_basename( ANG_PLUGIN_FILE ) );

/**
 * Handles plugin activation.
 *
 * Throws an error if the plugin is activated on an older version than PHP 5.6.
 *
 * @since 1.6.0
 * @access private
 * @return void
 */
function analog_activate_plugin() {
	if ( version_compare( PHP_VERSION, ANG_PHP_MINIMUM, '<' ) ) {
		wp_die(
		/* translators: %s: version number */
			esc_html( sprintf( __( 'Style Kit for Elementor requires PHP version %s', 'ang' ), '5.6.0' ) ),
			esc_html__( 'Error Activating', 'ang' )
		);
	}

	do_action( 'analog_activation' );
}

register_activation_hook( __FILE__, 'analog_activate_plugin' );

/**
 * Handles plugin deactivation.
 *
 * @since 1.6.0
 * @access private
 * @return void
 */
function analog_deactivate_plugin() {
	if ( version_compare( PHP_VERSION, ANG_PHP_MINIMUM, '<' ) ) {
		return;
	}

	do_action( 'analog_deactivation' );
}

register_deactivation_hook( __FILE__, 'analog_deactivate_plugin' );

/**
 * Fail loading, if WordPress version requirements not met.
 *
 * @since 1.1
 * @return void
 */
function analog_fail_wp_version() {
	/* translators: %s: WordPress version */
	$message      = sprintf( esc_html__( 'Style Kits requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT RUNNING.', 'ang' ), ANG_WP_MINIMUM );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );

	echo wp_kses_post( $html_message );
}

/**
 * Elementor version requirements are not met.
 *
 * @return mixed
 */
function analog_require_minimum_elementor() {
	$link = add_query_arg(
		array(
			'action' => 'upgrade-plugin',
			'plugin' => 'elementor/elementor.php',
		),
		self_admin_url( 'update.php' )
	);

	$update_url = wp_nonce_url( $link );

	/* translators: %s: Minimum required Elementor version. */
	$message = '<p>' . sprintf( __( 'Style Kits requires Elementor v%s or newer in order to work. Please update Elementor to the latest version.', 'ang' ), ANG_ELEMENTOR_MINIMUM ) . '</p>';

	$message .= '<p>' . sprintf( '<a href="%s" class="button-secondary">%s</a>', $update_url, __( 'Update Elementor Now', 'ang' ) ) . '</p>';

	echo '<div class="error"><p>' . $message . '</p></div>'; // @codingStandardsIgnoreLine
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
 * Fire up plugin instance.
 *
 * @since 1.6.0 Add PHP version check.
 */
add_action(
	'plugins_loaded',
	static function() {
		if ( version_compare( PHP_VERSION, '5.6.0', '<' ) ) {
			wp_die(
			/* translators: %s: version number */
				esc_html( sprintf( __( 'Style Kit for Elementor requires PHP version %s', 'ang' ), '5.6.0' ) ),
				esc_html__( 'Error Activating', 'ang' )
			);
		}

		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', 'analog_fail_load' );
			return;
		}

		if ( ! version_compare( ELEMENTOR_VERSION, ANG_ELEMENTOR_MINIMUM, '>=' ) ) {
			add_action( 'admin_notices', 'analog_require_minimum_elementor' );
			return;
		}

		if ( ! version_compare( get_bloginfo( 'version' ), '5.0', '>=' ) ) {
			add_action( 'admin_notices', 'analog_fail_wp_version' );
			return;
		}

		require_once ANG_PLUGIN_DIR . 'inc/Plugin.php';

		\Analog\Plugin::load( ANG_PLUGIN_FILE );
	}
);
