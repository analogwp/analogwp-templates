<?php
/**
 * Analog General Settings
 *
 * @package Analog/Admin
 * @since 1.3.8
 */

namespace Analog\Settings;

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
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {
		$tokens_dropdown = array( '-1' => __( '— Select a Style Kit —', 'ang' ) ) + Utils::get_tokens( false );

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
					'default' => ( '' !== get_option( 'elementor_ang_global_kit' ) ) ? get_option( 'elementor_ang_global_kit' ) : '-1',
					'type'    => 'select',
					'options' => $tokens_dropdown,
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

return new General();
