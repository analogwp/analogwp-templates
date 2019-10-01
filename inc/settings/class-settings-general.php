<?php
/**
 * Analog General Settings
 *
 * @package Analog/Admin
 */

namespace Analog\settings;

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'Settings_General', false ) ) {
	return new Settings_General();
}

/**
 * Admin_Settings_General.
 */
class Settings_General extends Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'general';
		$this->label = __( 'General', 'ang' );

		parent::__construct();
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {

		$settings = apply_filters(
			'ang_general_settings',
			array(
			)
		);

		return apply_filters( 'ang_get_settings_' . $this->id, $settings );
	}

	/**
	 * Output a color picker input box.
	 *
	 * @param mixed  $name Name of input.
	 * @param string $id ID of input.
	 * @param mixed  $value Value of input.
	 * @param string $desc (default: '') Description for input.
	 */
	public function color_picker( $name, $id, $value, $desc = '' ) {
		echo '<div class="color_box">' . wp_kses( $desc ) . '
			<input name="' . esc_attr( $id ) . '" id="' . esc_attr( $id ) . '" type="text" value="' . esc_attr( $value ) . '" class="colorpick" /> <div id="colorPickerDiv_' . esc_attr( $id ) . '" class="colorpickdiv"></div>
		</div>';
	}

	/**
	 * Output the settings.
	 */
	public function output() {
		$settings = $this->get_settings();

		Admin_Settings::output_fields( $settings );
	}

	/**
	 * Save settings.
	 */
	public function save() {
		$settings = $this->get_settings();

		Admin_Settings::save_fields( $settings );
	}
}

return new Settings_General();
