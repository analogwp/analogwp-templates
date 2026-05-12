<?php
/**
 * Register admin screen.
 *
 * @package AnalogWP
 */

namespace Analog\Settings;

use Analog\Options;
use Analog\Utils;
use AnalogPro\LicenseManager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
		esc_html__( 'Style Kits for Elementor', 'analogwp-templates' ),
		esc_html__( 'Style Kits', 'analogwp-templates' ),
		$permission,
		$menu_slug,
		'Analog\Settings\settings_page',
		ANG_PLUGIN_URL . 'assets/img/triangle.svg',
		'58.6'
	);

	add_submenu_page(
		$menu_slug,
		__( 'Style Kits Library', 'analogwp-templates' ),
		__( 'Library', 'analogwp-templates' ),
		$permission,
		'analogwp_templates'
	);

	add_submenu_page(
		$menu_slug,
		__( 'Style Kits Settings', 'analogwp-templates' ),
		__( 'Settings', 'analogwp-templates' ),
		'manage_options',
		'ang-settings',
		'Analog\Settings\new_settings_page'
	);

	add_action( 'load-style-kits_page_settings', 'Analog\Settings\settings_page_init' );

	add_submenu_page(
		$menu_slug,
		__( 'Local Style Kits', 'analogwp-templates' ),
		__( 'Local Style Kits', 'analogwp-templates' ),
		'manage_options',
		'style-kits',
		'Analog\Elementor\Kit\ang_kits_list'
	);

	if ( ! defined( 'ANG_PRO_VERSION' ) ) {
		add_submenu_page(
			$menu_slug,
			'',
			'<img width="12" src="' . esc_url( ANG_PLUGIN_URL . 'assets/img/triangle.svg' ) . '"> ' . __( 'Go Pro', 'analogwp-templates' ),
			'manage_options',
			'go_style_kits_pro',
			__NAMESPACE__ . '\handle_external_redirects'
		);
	}
}

add_action( 'admin_menu', __NAMESPACE__ . '\register_menu' );

// Register AJAX handlers for settings.
add_action( 'wp_ajax_ang_hide_promo', array( 'Analog\Settings\Admin_Settings', 'ajax_hide_promo' ) );

/**
 * Redirect external links.
 *
 * Fired by `admin_init` action.
 *
 * @since 1.6
 * @access public
 */
function handle_external_redirects() {
	$page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

	if ( empty( $page ) ) {
		return;
	}

	if ( 'go_style_kits_pro' === $page ) {
		wp_safe_redirect( Utils::get_pro_link( array( 'utm_source' => 'wp-menu' ) ) );
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
	$ang_error = filter_input( INPUT_GET, 'ang_error', FILTER_DEFAULT );
	if ( ! empty( $ang_error ) ) {
		Admin_Settings::add_error( wp_kses_post( wp_unslash( $ang_error ) ) );
	}

	$ang_message = filter_input( INPUT_GET, 'ang_message', FILTER_DEFAULT );
	if ( ! empty( $ang_message ) ) {
		Admin_Settings::add_message( wp_kses_post( wp_unslash( $ang_message ) ) );
	}

	do_action( 'ang_settings_page_init' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
}

/**
 * Handle saving of settings.
 *
 * @return void
 */
function save_settings() {
	global $ang_current_tab, $ang_current_section;

	$page               = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
	$current_tab_input  = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
	$current_section_in = filter_input( INPUT_POST, 'section', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
	if ( null === $current_section_in ) {
		$current_section_in = filter_input( INPUT_GET, 'section', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
	}
	$has_save           = null !== filter_input( INPUT_POST, 'save', FILTER_DEFAULT );
	$has_license_action = null !== filter_input( INPUT_POST, 'ang-license_activate', FILTER_DEFAULT );

	// We should only save on the settings page.
	if ( ! is_admin() || 'ang-settings' !== $page ) {
		return;
	}

	// Include settings pages.
	Admin_Settings::get_settings_pages();

	// Get current tab/section.
	$ang_current_tab     = empty( $current_tab_input ) ? 'general' : sanitize_title( wp_unslash( $current_tab_input ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$ang_current_section = empty( $current_section_in ) ? '' : sanitize_title( wp_unslash( $current_section_in ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

	// Save settings if data has been posted.
	if ( '' !== $ang_current_section && apply_filters( "ang_save_settings_{$ang_current_tab}_{$ang_current_section}", $has_save ) ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		Admin_Settings::save();
	} elseif ( '' === $ang_current_section && apply_filters( "ang_save_settings_{$ang_current_tab}", $has_save || $has_license_action ) ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
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
	do_action( 'ang_loaded_templates' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
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
	include_once __DIR__ . '/class-admin-settings.php';

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


/**
 * Adds a notice for the Freemius transition conditionally for existing EDD Users.
 *
 * @return void
 */
function sk_freemius_switch_notice() {
	$options = Options::get_instance();

	if ( $options->get( 'ang_license_key' ) && class_exists( LicenseManager::class ) && ! method_exists( LicenseManager::class, 'get_freemius_product_query_data' ) ) {
		$message      = sprintf(
			'<strong>%1$s</strong> %2$s <a href="mailto:%3$s">%3$s</a> %4$s',
			esc_html__( 'Style Kits is switching to a new experience for our Pro plugin,', 'analogwp-templates' ),
			esc_html__( 'that in turn requires us to move away from our old licensing system. If you are an existing Style Kits Pro user please email us at', 'analogwp-templates' ),
			esc_html( 'support@analogwp.com' ),
			esc_html__( 'or click on Contact Us link available in the left plugin menu, include your license key and we will provide a discount as per the license for a smooth transition.', 'analogwp-templates' )
		);
		$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );

		echo wp_kses_post( $html_message );
	}
}
add_action( 'admin_notices', __NAMESPACE__ . '\sk_freemius_switch_notice' );

/**
 * Adds Freemius license notice for existing EDD SK Pro users.
 *
 * @param array $settings
 * @return array
 */
function sk_add_freemius_switch_settings_notice( $settings ) {
	$settings = array_merge(
		array(
			array(
				'type'  => 'title',
				'id'    => 'ang_freemius_license_switch_notice',
				'title' => __( 'Experience the all new Style Kits Pro', 'analogwp-templates' ),
				'desc'  => sprintf(
					'<strong>%1$s</strong> %2$s <a href="mailto:%3$s">%3$s</a> %4$s <br/><br/> %5$s',
					esc_html__( 'Style Kits is switching to a new experience for our Pro plugin,', 'analogwp-templates' ),
					esc_html__( 'that in turn requires us to move away from our old licensing system. If you are an existing Style Kits Pro user please email us at', 'analogwp-templates' ),
					esc_html( 'support@analogwp.com' ),
					esc_html__( 'or click on Contact Us link available in the left plugin menu, include your license key and we will provide a discount as per the license for a smooth transition.', 'analogwp-templates' ),
					esc_html__( 'Please also know that coming future updates are now moved to this new licensing system and once you migrate to it everything else will work as is and better while with this switch we are working hard to bring you a more fine-tuned experience and all the more support for latest of Elementor.', 'analogwp-templates' )
				),
			),
			array(
				'type' => 'sectionend',
				'id'   => 'ang_freemius_license_switch_notice',
			),
		),
		$settings
	);

	return $settings;
}
add_filter( 'ang_license_settings', __NAMESPACE__ . '\sk_add_freemius_switch_settings_notice' );
