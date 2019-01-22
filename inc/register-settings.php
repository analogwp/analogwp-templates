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
		'dashicons-marker'
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
