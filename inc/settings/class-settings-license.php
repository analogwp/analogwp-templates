<?php
/**
 * Analog License Settings
 *
 * @package Analog/Admin
 */

namespace Analog\Settings;

use Analog\Options;
use Analog\LicenseManager;

defined( 'ABSPATH' ) || exit;

/**
 * License.
 */
class License extends Settings_Page {

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

		$license                = trim( Options::get_instance()->get( 'ang_license_key' ) );
		$status                 = Options::get_instance()->get( 'ang_license_key_status' );
		$lm_instance            = new LicenseManager();
		$strings                = $lm_instance->get_strings();
		$license_status_message = $lm_instance->check_license();

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
					'desc'    => '<p>' . __( 'If you do not have a license key, you can get one from ', 'ang' ) . '<a href="https://analogwp.com" target="_blank" class="ang-link">AnalogWP <span class="dashicons dashicons-external"></span></a></p>',
					'id'      => 'ang_license_key',
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

return new License();
