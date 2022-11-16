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

	add_submenu_page(
		null,
		__( 'Instances', 'ang' ),
		__( 'Instances List', 'ang' ),
		'manage_options',
		'ang-instance-list',
		'Analog\Elementor\Kit\ang_instance_list'
	);

	add_submenu_page(
		null,
		__( 'Welcome to Style Kits', 'ang' ),
		__( 'Welcome to Style Kits', 'ang' ),
		'manage_options',
		'analog_onboarding',
		__NAMESPACE__ . '\theme_style_kit_onboarding'
	);
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

/**
 * Admin page contents for Theme Style Kit migration screen.
 *
 * @since 1.9.6
 * @return void
 */
function theme_style_kit_onboarding() {
	wp_enqueue_style(
		'analog-onboarding-screen',
		ANG_PLUGIN_URL . 'assets/css/onboarding-screen.css',
		array(),
		filemtime( ANG_PLUGIN_DIR . 'assets/css/onboarding-screen.css' )
	);


	$steps = array(
		array(
			'id'          => 'install-elementor',
			'label'       => __( 'Install and Activate Elementor', 'ang' ),
			'description' => __( 'This will install and activate Elementor from the WordPress repository', 'ang' ),
			'checked'     => true,
		),
		array(
			'id'          => 'enable-el-experiment',
			'label'       => __( 'Enable Elementor container experiment', 'ang' ),
			'description' => __( 'Style Kits 2.0 works with Elementor containers. We will enable this experiment in Elementor', 'ang' ),
			'checked'     => true,
		),
		array(
			'id'          => 'disable-el-defaults',
			'label'       => __( 'Disable Elementor default colors and fonts', 'ang' ),
			'description' => __( 'For Global Styles to work properly, Elementor default fonts and colors need to be disabled', 'ang' ),
			'checked'     => true,
		),
		array(
			'id'          => 'install-hello-theme',
			'label'       => __( 'Install and activate Hello Elementor Theme', 'ang' ),
			'description' => __( 'Style Kits works best with Elementor Hello theme. This will replace your currently active theme', 'ang' ),
			'checked'     => false,
		),
		array(
			'id'          => 'import-base-kit',
			'label'       => __( 'Import a starter theme style preset', 'ang' ),
			'description' => __( 'Use a basic Style Kit as your starting point. This will replace your existing global styles', 'ang' ),
			'checked'     => true,
		),
	);

	?>
	<div id="analog-welcome-screen" class="analog-welcome-screen">
		<form id="onboarding-modal" class="onboarding-modal">
			<div class="entry-header">
				<div class="logo">
						<span class="brand-icon">
							<svg width="41" height="41" viewBox="0 0 41 41" fill="none" xmlns="http://www.w3.org/2000/svg">
								<circle cx="20.5" cy="20.5" r="20.5" fill="#413EC5"/>
								<path fill-rule="evenodd" clip-rule="evenodd" d="M21.5261 10.1484C21.1412 9.48177 20.1789 9.48177 19.794 10.1484L9.73663 27.5684C9.35173 28.235 9.83285 29.0684 10.6027 29.0684H30.7174C31.4872 29.0684 31.9684 28.235 31.5835 27.5684L21.5261 10.1484ZM21.5261 17.8359C21.1412 17.1693 20.1789 17.1693 19.794 17.8359L16.3942 23.7246C16.0093 24.3913 16.4904 25.2246 17.2602 25.2246H24.0599C24.8297 25.2246 25.3108 24.3913 24.9259 23.7246L21.5261 17.8359Z" fill="white"/>
							</svg>
						</span>
						<span class="brand-title">Style Kits</span>
				</div>
				<nav>
					<a href="<?php echo esc_url( 'https://docs.analogwp.com' ); ?>" target="_blank"><?php esc_html_e( 'Docs', 'ang' ); ?></a>
				</nav>
			</div>
			<div class="content-wrapper">
				<p class="short-description"><?php esc_html_e( 'Setup Elementor properly for a seamless Style Kits Experience.', 'ang' ); ?>
					<a href="#">Learn more</a></p>
				<div class="steps-wrapper">
					<?php foreach ( $steps as $step ) : ?>
						<div class="step <?php echo esc_attr( 'step-' . $step['id'] ); ?>">
							<div class="switch">
								<div class="switch__field">
									<input id="<?php echo esc_attr( $step['id'] ); ?>" type="checkbox" <?php echo $step['checked'] ? esc_attr( 'checked' ) : ''; ?>>
									<label for="<?php echo esc_attr( $step['id'] ); ?>"></label>
								</div>
							</div>
							<div>
								<p class="switch-label"><?php echo esc_html( $step['label'] ); ?></p>
								<p class="switch-description"><?php echo esc_html( $step['description'] ); ?></p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="entry-footer">
				<div class="prev">
					<a href="#">Skip wizard</a>
				</div>
				<div class="next">
					<button id="start-onboarding" class="button btn-primary">Apply</button>
				</div>
			</div>
		</form>
	</div>
	<?php
}
