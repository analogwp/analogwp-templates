<?php
/**
 * Helper functions for Page Settings.
 *
 * @package Analog
 * @since 1.3.8
 */

namespace Analog\Settings;

use Analog\Options;
use Elementor\Core\Kits\Manager;

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @param string|array $var Data to sanitize.
 * @return string|array
 */
function ang_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( __NAMESPACE__ . '\ang_clean', $var );
	}

	return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
}

/**
 * Output admin fields.
 *
 * Loops though the Analog options array and outputs each field.
 *
 * @param array $options Opens array to output.
 */
function ang_admin_fields( $options ) {

	if ( ! class_exists( 'Admin_Settings', false ) ) {
		include dirname( __FILE__ ) . '/class-admin-settings.php';
	}

	Admin_Settings::output_fields( $options );
}

/**
 * Update all settings which are passed.
 *
 * @param array $options Option fields to save.
 * @param array $data Passed data.
 */
function ang_update_options( $options, $data = null ) {

	if ( ! class_exists( 'Admin_Settings', false ) ) {
		include dirname( __FILE__ ) . '/class-admin-settings.php';
	}

	Admin_Settings::save_fields( $options, $data );
}

/**
 * Get a setting from the settings API.
 *
 * @param mixed $option_name Option name to save.
 * @param mixed $default Default value to save.
 * @return string
 */
function ang_settings_get_option( $option_name, $default = '' ) {

	if ( ! class_exists( 'Admin_Settings', false ) ) {
		include dirname( __FILE__ ) . '/class-admin-settings.php';
	}

	return Admin_Settings::get_option( $option_name, $default );
}


/**
 * Update Elementor Kit Option with respect to GSK.
 *
 * @return void
 */
function ang_update_elementor_kit() {
	if ( empty( $_POST ) ) { // phpcs:ignore
		return;
	}

	$data              = $_POST; // phpcs:ignore
	$key               = 'global_kit';
	$elementor_kit_key = Manager::OPTION_ACTIVE;
	$kit_id            = Options::get_instance()->get( 'global_kit' );

	$raw_value     = isset( $data[ $key ] ) ? wp_unslash( $data[ $key ] ) : $kit_id;
	$elementor_kit = \get_option( $elementor_kit_key );

	if ( $raw_value !== $kit_id || $raw_value !== $elementor_kit ) {
		if ( empty( $raw_value ) || '-1' === $raw_value ) {
			\update_option( $elementor_kit_key, Options::get_instance()->get( 'default_kit' ) );
		}

		\update_option( $elementor_kit_key, $raw_value );
	}
}
add_action( 'ang_update_option', __NAMESPACE__ . '\ang_update_elementor_kit' );
