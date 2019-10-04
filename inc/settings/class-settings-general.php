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
				array(
					'title' => __( 'Elementor Settings', 'ang' ),
					'type'  => 'title',
					'id'    => 'ang_color_palette',
				),
				array(
					'title'   => __( 'Sync Color Palettes and Style Kit colors by default', 'ang' ),
					'desc'    => __( 'The Elementor color palette will be populated with the Style Kit’s global colors', 'ang' ),
					'id'      => 'ang_sync_colors_option',
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'ang_color_palette',
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

return new Settings_General();
