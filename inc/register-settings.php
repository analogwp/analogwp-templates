<?php
/**
 * Register admin screen.
 *
 * @package AnalogWP
 */

namespace Analog\Settings;

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

	add_menu_page(
		esc_html__( 'Style Kits for Elementor', 'ang' ),
		esc_html__( 'Style Kits', 'ang' ),
		$permission,
		'analogwp_templates',
		'Analog\Settings\settings_page',
		ANG_PLUGIN_URL . 'assets/img/triangle.svg',
		'58.6'
	);

	add_submenu_page(
		'analogwp_templates',
		__( 'Style Kits Library', 'ang' ),
		__( 'Templates', 'ang' ),
		$permission,
		'analogwp_templates'
	);

	add_submenu_page(
		'analogwp_templates',
		__( 'Style Kits', 'ang' ),
		__( 'Style Kits', 'ang' ),
		$permission,
		admin_url( 'admin.php?page=analogwp_templates#styleKits' )
	);

	add_submenu_page(
		'analogwp_templates',
		__( 'Blocks', 'ang' ),
		__( 'Blocks', 'ang' ),
		$permission,
		admin_url( 'admin.php?page=analogwp_templates#blocks' )
	);

	add_submenu_page(
		'analogwp_templates',
		__( 'Style Kits Settings', 'ang' ),
		__( 'Settings', 'ang' ),
		'manage_options',
		'ang-settings',
		'Analog\Settings\new_settings_page'
	);

	add_action( 'load-style-kits_page_settings', 'Analog\Settings\settings_page_init' );

	add_submenu_page(
		'analogwp_templates',
		__( 'Style Kits', 'ang' ),
		__( 'Manage Style Kits', 'ang' ),
		'manage_options',
		'edit.php?post_type=ang_tokens'
	);

}

add_action( 'admin_menu', 'Analog\Settings\register_menu' );

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
	<style>body { background: #E3E3E3; }</style>
	<div id="analogwp-templates"></div>
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

	$settings = Admin_Settings::get_settings_pages();

	foreach ( $settings as $section ) {
		if ( ! method_exists( $section, 'get_settings' ) ) {
			continue;
		}
		$subsections = array_unique( array_merge( array( '' ), array_keys( $section->get_sections() ) ) );

		foreach ( $subsections as $subsection ) {
			foreach ( $section->get_settings( $subsection ) as $value ) {
				if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
					$autoload = isset( $value['autoload'] ) ? (bool) $value['autoload'] : true;
					add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
				}
			}
		}
	}
}
add_action( 'init', 'Analog\Settings\create_options' );

/**
 * Register plugin settings.
 *
 * @return void
 */
function register_settings() {
	register_setting(
		'ang',
		'ang_import_count',
		array(
			'type'              => 'number',
			'description'       => esc_html__( 'Imported Count', 'ang' ),
			'sanitize_callback' => 'absint',
			'show_in_rest'      => true,
			'default'           => 0,
		)
	);

	register_setting(
		'ang',
		'ang_imported_templates',
		array(
			'type'         => 'string',
			'description'  => esc_html__( 'Imported templates', 'ang' ),
			'show_in_rest' => true,
			'default'      => '',
		)
	);
}
