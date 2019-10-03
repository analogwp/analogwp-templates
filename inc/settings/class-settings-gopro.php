<?php
/**
 * Analog Go Pro Promotional Tab.
 *
 * @package Analog/Admin
 */

namespace Analog\settings;

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'Settings_GoPro', false ) ) {
	return new Settings_GoPro();
}

/**
 * Admin_Settings_GoPro.
 */
class Settings_GoPro extends Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'gopro';
		$this->label = __( 'Go Pro', 'ang' );
		parent::__construct();

		add_action( 'ang_settings_tabs_' . $this->id, [ $this, 'get_pro' ] );
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {
		$settings = apply_filters(
			'ang_gopro_settings',
			array(
				array(
				'title' => __( 'Access an interconnected library of Template Kits, blocks and additional design control with Style kits Pro.', 'ang' ),
				'type'  => 'title',
				'id'    => 'ang_gopro_title',
				),
				array(
				'to'    => '',
				'id'    => 'ang_gopro_button',
				'type'  => 'button',
				'class' => 'ang-gopro-button ang-button',
				'value' => __( 'Explore Style Kits Pro', 'ang' ),
				),
				array(
				'type' => 'sectionend',
				'id'   => 'ang_gopro_title',
				),
			)
		);

		return apply_filters( 'ang_get_settings_' . $this->id, $settings );
	}

	/**
	 * Get Pro Tab Data.
	 */
	public function get_pro() {
		echo 'hello';
		include dirname( __FILE__ ) . '/views/html-admin-settings-gopro.php';
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

return new Settings_GoPro();
