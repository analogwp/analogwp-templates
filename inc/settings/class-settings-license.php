<?php
/**
 * Analog License Settings
 *
 * @package Analog/Admin
 */

namespace Analog\settings;

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'Settings_License', false ) ) {
	return new Settings_License();
}

/**
 * Admin_Settings_License.
 */
class Settings_License extends Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'license';
		$this->label = __( 'License', 'ang' );

		parent::__construct();
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {

		$settings = apply_filters(
			'ang_license_settings',
			array(
				array(
					'title' => __( 'Pro license', 'ang' ),
					'type'  => 'title',
					'id'    => 'ang_license_activation_title',
					'desc'  => __( 'If you own an AnalogPro License, then please enter your license key here.', 'ang' ),
				),
				array(
					'title'    => __( 'Input your license key', 'ang' ),
					'desc'  => __( 'If you do not have a license key, you can get one from AnalogWP', 'ang' ),
					'id'       => 'ang_license_key_option',
					'default'  => '',
					'type'     => 'text',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'ang_license',
				),
			)
		);

		return apply_filters( 'ang_get_settings_' . $this->id, $settings );
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

return new Settings_License();
