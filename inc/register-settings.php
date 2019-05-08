<?php
/**
 * Register admin screen.
 *
 * @package AnalogWP
 */

namespace Analog\settings;

/**
 * Register plugin menu.
 *
 * @return void
 */
function register_menu() {
	add_menu_page(
		esc_html__( 'Analog Templates', 'ang' ),
		esc_html__( 'AnalogWP', 'ang' ),
		'manage_options',
		'analogwp_templates',
		'Analog\settings\settings_page',
		ANG_PLUGIN_URL . 'assets/img/triangle.svg',
		'58.6'
	);

	// Display Tokens submenu only when there is atleast one post.
	$tokens = get_posts( 'post_type=ang_tokens&post_status=publish' );

	if ( $tokens ) {
		add_submenu_page(
			'analogwp_templates',
			__( 'Library', 'ang' ),
			__( 'Library', 'ang' ),
			'manage_options',
			'analogwp_templates'
		);

		$menu_title = __( 'Style Kits', 'ang' );
		add_submenu_page(
			'analogwp_templates',
			$menu_title,
			$menu_title,
			'manage_options',
			'edit.php?post_type=ang_tokens'
		);
	}

}

add_action( 'admin_menu', 'Analog\settings\register_menu' );

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
 * Register plugin settings.
 *
 * @return void
 */
function register_settings() {
	register_setting(
		'ang',
		'ang_import_count',
		[
			'type'              => 'number',
			'description'       => esc_html__( 'Imported Count', 'ang' ),
			'sanitize_callback' => 'absint',
			'show_in_rest'      => true,
			'default'           => 0,
		]
	);

	register_setting(
		'ang',
		'ang_imported_templates',
		[
			'type'         => 'string',
			'description'  => esc_html__( 'Imported templates', 'ang' ),
			'show_in_rest' => true,
			'default'      => '',
		]
	);
}
