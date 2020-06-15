<?php
/**
 * Register admin screen.
 *
 * @package AnalogWP
 */

namespace Analog\Settings;

use Analog\Utils;
use Analog\Options;
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
		__( 'Style Kits', 'ang' ),
		__( 'Theme Style Kits', 'ang' ),
		'manage_options',
		'style-kits',
		'Analog\Elementor\Kit\ang_kits_list'
	);

	add_submenu_page(
		null,
		__( 'Welcome to Style Kits', 'ang' ),
		__( 'Welcome to Style Kits', 'ang' ),
		'manage_options',
		'analog_onboarding',
		__NAMESPACE__ . '\theme_style_kit_onboarding'
	);

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
 * Admin page contents for Theme Style Kit migration screen.
 *
 * @since 1.6.0
 * @return void
 */
function theme_style_kit_onboarding() {
	wp_enqueue_style(
		'analog-onboarding-screen',
		ANG_PLUGIN_URL . 'assets/css/onboarding-screen.css',
		array(),
		ANG_VERSION
	);

	$wp_embed = new \WP_Embed();

	?>
		<div id="analog-welcome-screen" class="analog-welcome-screen">
			<div class="after-migration">
				<div class="entry-header">
					<div class="logo">
						<span class="brand-icon">
							<svg width="16" viewBox="0 0 40 36" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M20 11.5709L29.8055 28.8713L10.1945 28.8713L20 11.5709ZM17.716 1.34329C18.7311 -0.44776 21.2689 -0.44776 22.284 1.34329L39.6427 31.9702C40.6579 33.7612 39.3889 36 37.3587 36L2.64131 36C0.611056 36 -0.657853 33.7612 0.357276 31.9702L17.716 1.34329Z" fill="white"/>
							</svg>
						</span>
						<span class="brand-name">
							<svg width="99" height="21" viewBox="0 0 99 21" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M9.05823 5.50358V6.83453C8.37129 5.78265 6.97594 5.28891 5.70939 5.28891C3.06895 5.28891 0.793457 7.28534 0.793457 10.5054C0.793457 13.704 3.09042 15.7433 5.73086 15.7433C6.95447 15.7433 8.37129 15.2496 9.05823 14.1762V15.5286H11.5484V5.50358H9.05823ZM6.11726 13.4034C4.57164 13.4034 3.30509 12.1583 3.30509 10.4839C3.30509 8.80949 4.57164 7.60734 6.11726 7.60734C7.55555 7.60734 9.0153 8.72362 9.0153 10.4839C9.0153 12.2442 7.64141 13.4034 6.11726 13.4034Z" fill="white"/>
								<path d="M20.1208 5.28891C19.0903 5.28891 17.5233 5.84705 17.008 7.09213V5.50358H14.5179V15.5286H17.008V10.2907C17.008 8.35868 18.4249 7.73614 19.5626 7.73614C20.6789 7.73614 21.6449 8.55188 21.6449 10.1404V15.5286H24.1351V9.9043C24.1351 6.9848 22.6968 5.28891 20.1208 5.28891Z" fill="white"/>
								<path d="M34.6341 5.50358V6.83453C33.9472 5.78265 32.5518 5.28891 31.2853 5.28891C28.6448 5.28891 26.3693 7.28534 26.3693 10.5054C26.3693 13.704 28.6663 15.7433 31.3067 15.7433C32.5303 15.7433 33.9472 15.2496 34.6341 14.1762V15.5286H37.1243V5.50358H34.6341ZM31.6931 13.4034C30.1475 13.4034 28.881 12.1583 28.881 10.4839C28.881 8.80949 30.1475 7.60734 31.6931 7.60734C33.1314 7.60734 34.5912 8.72362 34.5912 10.4839C34.5912 12.2442 33.2173 13.4034 31.6931 13.4034Z" fill="white"/>
								<path d="M40.0937 15.5286H42.5839V0.136841H40.0937V15.5286Z" fill="white"/>
								<path d="M50.2456 15.7433C53.1007 15.7433 55.5909 13.661 55.5909 10.4839C55.5909 7.3068 53.1007 5.28891 50.2456 5.28891C47.3905 5.28891 44.9218 7.3068 44.9218 10.4839C44.9218 13.661 47.3905 15.7433 50.2456 15.7433ZM50.2456 13.4249C48.7 13.4249 47.4334 12.2442 47.4334 10.4839C47.4334 8.76655 48.7 7.60734 50.2456 7.60734C51.7912 7.60734 53.0792 8.76655 53.0792 10.4839C53.0792 12.2442 51.7912 13.4249 50.2456 13.4249Z" fill="white"/>
								<path d="M65.5977 5.50358V6.81306C64.8892 5.78265 63.5368 5.28891 62.2488 5.28891C59.6084 5.28891 57.3329 7.28534 57.3329 10.5054C57.3329 13.704 59.6298 15.7433 62.2703 15.7433C63.4939 15.7433 64.8892 15.2281 65.5977 14.1977V14.348C65.5977 17.2031 64.3955 18.3193 62.4635 18.3193C61.3687 18.3193 60.2738 17.6753 59.7801 16.6879L57.7193 17.6324C58.5994 19.4571 60.4027 20.5734 62.4635 20.5734C65.8982 20.5734 68.0878 18.6199 68.0878 14.1118V5.50358H65.5977ZM62.6567 13.4034C61.1111 13.4034 59.8445 12.1583 59.8445 10.4839C59.8445 8.80949 61.1111 7.60734 62.6567 7.60734C64.095 7.60734 65.5547 8.72362 65.5547 10.4839C65.5547 12.2442 64.1808 13.4034 62.6567 13.4034Z" fill="white"/>
								<path d="M83.1432 5.50358L81.018 11.9651L78.8927 5.50358H76.6816L74.5564 11.9651L72.4312 5.50358H69.7478L73.4616 15.5286H75.5439L77.7765 8.68069L80.1164 15.5286H82.2201L85.8266 5.50358H83.1432Z" fill="white"/>
								<path d="M93.3314 5.28891C92.1293 5.28891 90.691 5.78265 90.0041 6.83453V5.50358H87.5139V20.4231H90.0041V14.1762C90.691 15.2496 92.0864 15.7218 93.3529 15.7218C95.9934 15.7218 98.2688 13.7254 98.2688 10.5054C98.2688 7.3068 95.9719 5.28891 93.3314 5.28891ZM92.945 13.4034C91.5068 13.4034 90.047 12.2871 90.047 10.5268C90.047 8.76655 91.4209 7.60734 92.945 7.60734C94.4907 7.60734 95.7787 8.85242 95.7787 10.5268C95.7787 12.2013 94.4907 13.4034 92.945 13.4034Z" fill="white"/>
							</svg>
						</span>
					</div>
				</div>
				<div class="content-wrapper">
					<h3 class="entry-title"><?php esc_html_e( 'Style Kits are now integrated into Elementor Theme Styles', 'ang' ); ?></h3>
					<div class="entry-content">
						<p class="intro">
							<?php echo __( 'With the introduction of Theme Styles in Elementor v2.9.0, 	Style Kits are now <strong>integrated into the Theme Styles panel</strong>, bringing you a consistent, native experience.', 'ang' ); ?>
						</p>
						<div class="video-wrapper">
							<?php echo $wp_embed->autoembed( 'https://www.youtube.com/watch?v=ItcKsNztJJU' ); //phpcs:ignore ?>
						</div>
						<p><?php esc_html_e( 'See what’s different in this quick video above.', 'ang' ); ?></p>
					</div>
				</div>
				<div class="entry-footer">
					<h3><?php esc_html_e( 'Need help?', 'ang' ); ?></h3>
					<nav>
						<a href="<?php echo esc_url( 'https://docs.analogwp.com' ); ?>" target="_blank"><?php esc_html_e( 'Go to docs', 'ang' ); ?></a>&nbsp;&#124;&nbsp;
						<a href="<?php echo esc_url( 'https://analogwp.com/support' ); ?>" target="_blank"><?php esc_html_e( 'Send a support ticket', 'ang' ); ?></a>
					</nav>
				</div>
			</div>
		</div>
	<?php
}
