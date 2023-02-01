<?php
/**
 * Register admin screen.
 *
 * @package AnalogWP
 */

namespace Analog\Settings;

use Analog\Utils;
use WP_Screen;

/**
 * Register plugin menu.
 *
 * @return void
 */
function register_menu() {
	$permission = 'manage_options';
	if ( has_filter( 'ang_user_roles_enabled', '__return_true' ) ) {
		$permission = 'read';
	}

	$menu_slug = 'analogwp_templates';

	add_menu_page(
		esc_html__( 'Style Kits for Elementor', 'ang' ),
		esc_html__( 'Style Kits', 'ang' ),
		$permission,
		$menu_slug,
		'Analog\Settings\settings_page',
		ANG_PLUGIN_URL . 'assets/img/triangle.svg',
		'58.6'
	);

	add_submenu_page(
		$menu_slug,
		__( 'Style Kits Library', 'ang' ),
		__( 'Library', 'ang' ),
		$permission,
		'analogwp_templates'
	);

	add_submenu_page(
		$menu_slug,
		__( 'Style Kits Settings', 'ang' ),
		__( 'Settings', 'ang' ),
		'manage_options',
		'ang-settings',
		'Analog\Settings\new_settings_page'
	);

	add_action( 'load-style-kits_page_settings', 'Analog\Settings\settings_page_init' );

	add_submenu_page(
		$menu_slug,
		__( 'Local Style Kits', 'ang' ),
		__( 'Local Style Kits', 'ang' ),
		'manage_options',
		'style-kits',
		'Analog\Elementor\Kit\ang_kits_list'
	);

	// Hidden instances menu. Maybe delete later if not needed.
	//	add_submenu_page(
	//		$menu_slug,
	//		__( 'Instances', 'ang' ),
	//		__( 'Instances List', 'ang' ),
	//		'manage_options',
	//		'ang-instance-list',
	//		'Analog\Elementor\Kit\ang_instance_list'
	//	);

	if ( ! defined( 'ANG_PRO_VERSION' ) ) {
		add_submenu_page(
			$menu_slug,
			'',
			'<img width="12" src="' . esc_url( ANG_PLUGIN_URL . 'assets/img/triangle.svg' ) . '"> ' . __( 'Go Pro', 'ang' ),
			'manage_options',
			'go_style_kits_pro',
			__NAMESPACE__ . '\handle_external_redirects'
		);
	}
}

add_action( 'admin_menu', __NAMESPACE__ . '\register_menu' );

/**
 * Redirect external links.
 *
 * Fired by `admin_init` action.
 *
 * @since 1.6
 * @access public
 */
function handle_external_redirects() {
	if ( empty( $_GET['page'] ) ) {
		return;
	}

	if ( 'go_style_kits_pro' === $_GET['page'] ) {
		wp_redirect( Utils::get_pro_link( array( 'utm_source' => 'wp-menu' ) ) );
		exit();
	}
}
add_action( 'admin_init', __NAMESPACE__ . '\handle_external_redirects' );

/**
 * Loads methods into memory for use within settings.
 */
function settings_page_init() {

	// Include settings pages.
	Admin_Settings::get_settings_pages();

	// Add any posted messages.
	if ( ! empty( $_GET['ang_error'] ) ) { // phpcs:ignore
		Admin_Settings::add_error( wp_kses_post( wp_unslash( $_GET['ang_error'] ) ) ); // phpcs:ignore
	}

	if ( ! empty( $_GET['ang_message'] ) ) { // phpcs:ignore
		Admin_Settings::add_message( wp_kses_post( wp_unslash( $_GET['ang_message'] ) ) ); // phpcs:ignore
	}

	do_action( 'ang_settings_page_init' );
}

/**
 * Handle saving of settings.
 *
 * @return void
 */
function save_settings() {
	global $current_tab, $current_section;

	// We should only save on the settings page.
	if ( ! is_admin() || ! isset( $_GET['page'] ) || 'ang-settings' !== $_GET['page'] ) { // phpcs:ignore
		return;
	}

	// Include settings pages.
	Admin_Settings::get_settings_pages();

	// Get current tab/section.
	$current_tab     = empty( $_GET['tab'] ) ? 'general' : sanitize_title( wp_unslash( $_GET['tab'] ) ); // phpcs:ignore
	$current_section = empty( $_REQUEST['section'] ) ? '' : sanitize_title( wp_unslash( $_REQUEST['section'] ) ); // phpcs:ignore

	// Save settings if data has been posted.
	if ( '' !== $current_section && apply_filters( "ang_save_settings_{$current_tab}_{$current_section}", ! empty( $_POST['save'] ) ) ) { // phpcs:ignore
		Admin_Settings::save();
	} elseif ( '' === $current_section && apply_filters( "ang_save_settings_{$current_tab}", ! empty( $_POST['save'] ) || isset( $_POST['ang-license_activate'] ) ) ) { // phpcs:ignore
		Admin_Settings::save();
	}
}


// Handle saving settings earlier than load-{page} hook to avoid race conditions in conditional menus.
add_action( 'wp_loaded', 'Analog\Settings\save_settings' );

/**
 * Add settings page.
 *
 * @return void
 */
function new_settings_page() {
	Admin_Settings::output();
}

/**
 * Add settings page.
 *
 * @return void
 */
function settings_page() {
	do_action( 'ang_loaded_templates' );
	?>
	<style>body { background: #F1F1F1; }</style>
	<div id="analogwp-templates" class=""></div>
	<?php
}

/**
 * Default options.
 *
 * Sets up the default options used on the settings page.
 */
function create_options() {
	if ( ! is_admin() ) {
		return false;
	}
	// Include settings so that we can run through defaults.
	include_once dirname( __FILE__ ) . '/class-admin-settings.php';

	$settings = array_filter( Admin_Settings::get_settings_pages() );

	foreach ( $settings as $section ) {
		if ( ! method_exists( $section, 'get_settings' ) ) {
			continue;
		}
		$subsections = array_unique( array_merge( array( '' ), array_keys( $section->get_sections() ) ) );

		foreach ( $subsections as $subsection ) {
			foreach ( $section->get_settings( $subsection ) as $value ) {
				if ( isset( $value['default'], $value['id'] ) ) {
					$autoload = isset( $value['autoload'] ) ? (bool) $value['autoload'] : true;
					add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
				}
			}
		}
	}
}
add_action( 'init', 'Analog\Settings\create_options' );
