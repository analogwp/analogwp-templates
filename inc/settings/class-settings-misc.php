<?php
/**
 * Analog Miscelleneous Settings
 *
 * @package Analog/Admin
 * @since 1.3.8
 */

namespace Analog\Settings;

defined( 'ABSPATH' ) || exit;

/**
 * Misc.
 */
class Misc extends Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'misc';
		$this->label = __( 'Misc', 'ang' );

		parent::__construct();
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {
		$settings = apply_filters(
			'ang_misc_settings',
			array(
				array(
					'type' => 'title',
					'id'   => 'ang_misc',
				),
				array(
					'title'         => __( 'Usage Data Tracking', 'ang' ),
					'desc'          => __( 'Opt-in to our anonymous plugin data collection and to updates', 'ang' ),
					'id'            => 'ang_data_collection',
					'default'       => false,
					'type'          => 'checkbox',
					'checkboxgroup' => 'start',
					'desc_tip'      => __( 'We guarantee no sensitive data is collected. ', 'ang' ) . '<a class="ang-link" href="https://docs.analogwp.com/article/547-what-data-is-tracked-by-the-plugin" target="_blank">' . __( 'More Info', 'ang' ) . '</a>',
				),
				array(
					'title'         => __( 'Template Settings', 'ang' ),
					'desc'          => __( 'Remove Styling from typographic elements', 'ang' ),
					'id'            => 'ang_remove_typography',
					'default'       => false,
					'type'          => 'checkbox',
					'checkboxgroup' => 'start',
					'desc_tip'      => __( 'This setting will remove any values that have been manually added in the templates. Existing templates are not affected. ', 'ang' ) . '<a class="ang-link" href="https://docs.analogwp.com/article/544-remove-styling-from-typographic-elements" target="_blank">' . __( 'More Info', 'ang' ) . '</a>',
				),
				array(
					'title'         => __( 'Remove Data on Uninstall', 'ang' ),
					'desc'          => __( 'Check this box to remove all data stored by Style Kit for Elementor plugin, including license info, user settings, import history etc. Any imported or manually saved Style Kits are not removed.', 'ang' ),
					'id'            => 'remove_on_uninstall',
					'default'       => false,
					'type'          => 'checkbox',
					'checkboxgroup' => 'start',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'ang_misc',
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

return new Misc();
