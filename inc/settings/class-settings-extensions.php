<?php
/**
 * Analog Extensions Settings
 *
 * @package Analog/Admin
 * @since 1.3.8
 */

namespace Analog\Settings;

use Analog\Options;

defined( 'ABSPATH' ) || exit;

/**
 * Extensions.
 */
class Extensions extends Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'extensions';
		$this->label = __( 'Extensions', 'ang' );

		parent::__construct();
	}

	/**
	 * Get sections.
	 *
	 * @return array
	 */
	public function get_sections() {
		$sections = array(
			'' => __( 'General', 'ang' ),
		);

		return apply_filters( 'ang_get_sections_' . $this->id, $sections );
	}

	/**
	 * Get settings array.
	 *
	 * @param string $current_section Current section id.
	 * @return array
	 */
	public function get_settings( $current_section = '' ) {
		$settings = [];
		if ( '' === $current_section ) {
			$settings = apply_filters(
				'ang_general_extension_settings',
				array()
			);
		}

		return apply_filters( 'ang_get_settings_extensions', $settings, $current_section );
	}

	/**
	 * Output the settings.
	 */
	public function output() {
		global $current_section;

		$settings = $this->get_settings( $current_section );

		Admin_Settings::output_fields( $settings );
	}

	/**
	 * Save settings.
	 */
	public function save() {
		global $current_section;

		$settings = $this->get_settings( $current_section );

		Admin_Settings::save_fields( $settings );
		if ( $current_section ) {
			do_action( 'ang_update_options_' . $this->id . '_' . $current_section );
		}
	}
}

return new Extensions();
