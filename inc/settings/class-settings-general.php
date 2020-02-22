<?php
/**
 * Analog General Settings
 *
 * @package Analog/Admin
 * @since 1.3.8
 */

namespace Analog\Settings;

use Analog\Options;
use Analog\Utils;

defined( 'ABSPATH' ) || exit;

/**
 * General.
 */
class General extends Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'general';
		$this->label = __( 'General', 'ang' );

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
	 * @param string $current_section Current section ID.
	 *
	 * @return array
	 */
	public function get_settings( $current_section = '' ) {
		global $current_section;

		$sections = $this->get_sections();
		$settings = array();

		if ( '' === $current_section ) {
			$settings = apply_filters(
				'ang_general_settings',
				array(
					array(
						'title' => __( 'Elementor Settings', 'ang' ),
						'type'  => 'title',
						'id'    => 'ang_color_palette',
					),
					array(
						'title'   => esc_html_x( 'Global Style Kit', 'settings title', 'ang' ),
						'desc'    => sprintf(
							/* translators: %s: Style Kit Documentation link */
							__( 'Choosing a Style Kit will make it global and apply site-wide. Learn more about %s.', 'ang' ),
							'<a href="https://docs.analogwp.com/article/554-what-are-style-kits" target="_blank">' . __( 'Style kits', 'ang' ) . '</a>'
						),
						'id'      => 'global_kit',
						'default' => get_option( 'elementor_active_kit' ),
						'type'    => 'select',
						'options' => Utils::get_kits( false ),
					),
					array(
						'type' => 'sectionend',
						'id'   => 'ang_color_palette',
					),
				)
			);
		}

		return apply_filters( 'ang_get_settings_' . $this->id, $settings );
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

return new General();
