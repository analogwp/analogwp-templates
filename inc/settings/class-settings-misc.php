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
					'type' => 'sectionend',
					'id'   => 'ang_misc',
				),

				array(
					'type' => 'title',
					'id'   => 'sk_uninstall',
				),
				array(
					'title'         => __( 'Remove Data on Uninstall', 'ang' ),
					'desc'          => __( 'Check this option to remove plugin data on uninstall.', 'ang' ),
					'class'         => 'sk-uninstall',
					'id'            => 'remove_on_uninstall',
					'default'       => false,
					'type'          => 'checkbox',
					'checkboxgroup' => 'start',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'sk_uninstall',
				),

				array(
					'type' => 'title',
					'id'   => 'sk_uninstall_options',
				),
				array(
					'class'   => 'ang-uninstall-options',
					'desc'    => __( 'You can multi-select from the options below and upon plugin uninstall these will be respected.', 'ang' ),
					'id'      => 'uninstall_options',
					'default' => array(
						'plugin_settings'  => true,
					),
					'type'    => 'multi-checkbox',
					'options' => array(
						'plugin_settings'  => __( 'Plugin Settings', 'ang' ),
						'remove_kits'      => __( 'Remove Kits', 'ang' ),
					),
				),

				array(
					'type' => 'sectionend',
					'id'   => 'sk_uninstall_options',
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
