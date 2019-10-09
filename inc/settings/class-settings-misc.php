<?php
/**
 * Analog Miscelleneous Settings
 *
 * @package Analog/Admin
 */

namespace Analog\Settings;

use Analog\Utils;

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
	 * Processes array to be used in setting fields.
	 *
	 * @return array
	 */
	public function get_rollback_versions() {
		$keys = Utils::get_rollback_versions();
		$data = [];
		foreach ( $keys as $key => $value ) {
			$data[ $value ] = $value;
		}

		return $data;
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
					'title' => __( 'Usage Data Tracking', 'ang' ),
					'type'  => 'title',
					'id'    => 'ang_usage_tracking',
				),
				array(
					'desc'          => __( 'Opt-in to our anonymous plugin data collection and to updates. We guarantee no sensitive data is collected.', 'ang' ),
					'id'            => 'ang_data_collection',
					'default'       => false,
					'type'          => 'checkbox',
					'checkboxgroup' => 'start',
					'desc_tip'      => '<a class="ang-link" href="https://docs.analogwp.com/article/547-what-data-is-tracked-by-the-plugin" target="_blank">' . __( 'More Info', 'ang' ) . '<span class="dashicons dashicons-external"></span></a>',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'ang_usage_tracking',
				),
				array(
					'title' => __( 'Rollback Version', 'ang' ),
					'type'  => 'title',
					'id'    => 'ang_plugin_rollback_version',
					'desc'  => __( 'If you are having issues with current version of Style Kits for Elementor, you can rollback to a previous stable version.', 'ang' ),
				),
				array(
					'id'       => 'ang_rollback_version_select_option',
					'default'  => '1.3.6',
					'type'     => 'select',
					'class'    => 'ang-enhanced-select',
					'desc_tip' => true,
					'options'  => $this->get_rollback_versions(),
				),
				array(
					'id'    => 'ang_rollback_version_button',
					'type'  => 'button',
					'class' => 'ang-rollback-version-button ang-button',
					'value' => __( 'Reinstall this version', 'ang' ),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'ang_plugin_rollback',
				),
				array(
					'title' => __( 'Template Settings', 'ang' ),
					'type'  => 'title',
					'id'    => 'ang_temp_settings',
				),
				array(
					'desc'          => __( 'Remove Styling from typographic elements', 'ang' ),
					'id'            => 'ang_remove_typography',
					'default'       => false,
					'type'          => 'checkbox',
					'checkboxgroup' => 'start',
					'desc_tip'      => __( 'This setting will remove any values that have been manually added in the templates. Existing templates are not affected.', 'ang' ) . '<br><a class="ang-link" href="https://docs.analogwp.com/article/544-remove-styling-from-typographic-elements" target="_blank">' . __( 'More Info', 'ang' ) . '<span class="dashicons dashicons-external"></span></a>',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'ang_temp_settings',
				),
				array(
					'title' => __( 'Remove Data on Uninstall', 'ang' ),
					'type'  => 'title',
					'id'    => 'ang_remove_data_uninstall',
				),
				array(
					'desc'          => __( 'Check this box to remove all data stored by Style Kit for Elementor plugin, including license info, user settings, import history etc. Any imported or manually saved Style Kits are not removed.', 'ang' ),
					'id'            => 'remove_on_uninstall',
					'default'       => false,
					'type'          => 'checkbox',
					'checkboxgroup' => 'start',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'ang_remove_data_uninstall',
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
