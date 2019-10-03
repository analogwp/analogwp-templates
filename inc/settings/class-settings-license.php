<?php
/**
 * Analog License Settings
 *
 * @package Analog/Admin
 */

namespace Analog\settings;

use Analog\Options;
use Analog\LicenseManager;

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

		$license                = trim( Options::get_instance()->get( 'ang_license_key_option' ) );
		$status                 = Options::get_instance()->get( 'ang_license_key_status' );
		$lm_instance            = new LicenseManager();
		$strings                = $lm_instance->get_strings();
		$license_status_message = $lm_instance->get_license_message();

		$license_status_setting = array();
		if ( $license ) {
			$license_status_setting = array(
				'title' => __( 'License Status', 'ang' ),
				'desc'  => $license_status_message,
				'class' => 'ang-license-status',
				'type'  => 'content',
				'id'    => 'ang_license_status',
			);
		}

		$license_action_setting = array();
		if ( ! empty( $license ) ) {
			if ( 'valid' === $status ) {
				$license_action_setting = array(
					'title' => __( 'License Action', 'ang' ),
					'type'  => 'action',
					'class' => 'button-secondary',
					'id'    => 'ang-license_deactivate',
					'value' => $strings['deactivate-license'],
				);
			} else {
				$license_action_setting = array(
					'title' => __( 'License Action', 'ang' ),
					'type'  => 'action',
					'class' => 'button-secondary',
					'id'    => 'ang-license_activate',
					'value' => $strings['activate-license'],
				);
			}
		}


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
					'title'   => __( 'Input your license key', 'ang' ),
					'desc'    => '<p>' . __( 'If you do not have a license key, you can get one from ', 'ang' ) . '<a href="https://analogwp.com" target="_blank">AnalogWP</a></p>',
					'id'      => 'ang_license_key_option',
					'default' => '',
					'type'    => 'text',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'ang_license',
				),
				$license_status_setting,
				$license_action_setting,
				array(
					'type' => 'sectionend',
					'id'   => 'ang_license_status',
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
