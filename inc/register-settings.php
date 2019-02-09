<?php
/**
 * Register admin screen.
 *
 * @package AnalogWP
 */

namespace Analog\settings;

function register_menu() {
	add_menu_page(
		esc_html__( 'AnalogWP Templates', 'ang' ),
		esc_html__( 'AnalogWP', 'ang' ),
		'manage_options',
		'analogwp_templates',
		'Analog\settings\settings_page',
		ANG_PLUGIN_URL . 'assets/img/triangle.svg',
		'58.6'
	);
}

add_action( 'admin_menu', 'Analog\settings\register_menu' );

function settings_page() {
	do_action( 'ang_loaded' );
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
			'type'              => 'string',
			'description'       => esc_html__( 'Imported Count', 'ang' ),
			'sanitize_callback' => 'absint',
			'show_in_rest'      => true,
			'default'           => '',
		]
	);

	register_setting(
		'ang',
		'ang_imported_templates',
		[
			'type'         => 'string',
			'description'  => esc_html__( 'Imported templates', 'ang' ),
			'show_in_rest' => true,
			'default'      => [],
		]
	);
}

add_action( 'init', __NAMESPACE__ . '\register_settings' );
