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
	?>
	<div id="analogwp-templates"></div>
	<?php
}
