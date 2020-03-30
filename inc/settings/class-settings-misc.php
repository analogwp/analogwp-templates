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
					'title' => __( 'Remove Data on Uninstall', 'ang' ),
					'desc'  => __( 'You can multi-select from the options below and upon plugin uninstall these will be respected.', 'ang' ),
					'class' => 'ang-uninstall',
					'type'  => 'content',
					'id'    => 'ang-uninstall',
				),
				array(
					'type' => 'title',
					'id'   => 'ang_sk_uninstall',
				),
				array(
					'id'      => 'remove_on_uninstall',
					'default' => array(
						'remove_all_media' => true,
						'plugin_settings'  => true,
					),
					'type'    => 'multi-checkbox',
					'options' => array(
						'remove_all_media' => __( 'Remove all media files imported along Templates and Blocks', 'ang' ),
						'plugin_settings'  => __( 'Plugin Settings', 'ang' ),
						'remove_kits'      => __( 'Remove Kits', 'ang' ),
					),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'ang_sk_uninstall',
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
