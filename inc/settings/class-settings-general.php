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
					'title' => __( 'Color Palette', 'ang' ),
					'type'  => 'title',
					'id'    => 'color_palette',
				),
				array(
					'title'   => __( 'Sync Elementor Color Palette and Style Kit colors', 'ang' ),
					'desc'    => __( 'If this is checked, the Elementor color picker will be populated with the Style Kit’s global colors', 'ang' ),
					'id'      => 'sync_el_color_palette_style_kits',
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'color_palette',
				),
				array(
					'title' => __( 'Template Import method', 'ang' ),
					'type'  => 'title',
					'id'    => 'tmp_import_method',
				),
				array(
					'title'           => __( 'Template Import method', 'ang' ),
					'id'              => 'temp_import_method',
					'default'         => 'manual-import',
					'type'            => 'radio',
					'options'         => array(
						'manual-import'           => __( 'Let me choose the import method while importing', 'ang' ),
						'apply-default-style-kit' => __( 'Always apply the template’s default Style Kit', 'ang' ),
						'apply-current-style-kit' => __( 'Always apply the Style Kit you are working with to the imported templates', 'ang' ),
					),
					'autoload'        => false,
					'desc_tip'        => true,
					'show_if_checked' => 'option',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'temp_import',
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
