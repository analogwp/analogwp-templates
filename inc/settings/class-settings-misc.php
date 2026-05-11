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
		$this->label = __( 'Misc', 'analogwp-templates' );

		parent::__construct();
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {
		$settings = apply_filters(
			'ang_misc_settings', // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			array(
				array(
					'type' => 'title',
					'id'   => 'ang_misc',
				),
				array(
					'title'         => __( 'Show Global Kit Data', 'analogwp-templates' ),
					'desc'          => sprintf(
						"%s <a href='https://analogwp.com/docs/global-kit-data/'>%s</a>",
						__( 'Global Colors and Fonts that belong to the Global Style Kit will still be available when working with other Style Kits.', 'analogwp-templates' ),
						__( 'More Info', 'analogwp-templates' )
					),
					'id'            => 'also_inline_global_kit',
					'default'       => false,
					'type'          => 'checkbox',
					'checkboxgroup' => 'start',
				),
				array(
					'title'         => __( 'Usage Data Tracking', 'analogwp-templates' ),
					'desc'          => __( 'Opt-in to our anonymous plugin data collection and to updates', 'analogwp-templates' ),
					'id'            => 'ang_data_collection',
					'default'       => false,
					'type'          => 'checkbox',
					'checkboxgroup' => 'start',
					'desc_tip'      => __( 'We guarantee no sensitive data is collected. ', 'analogwp-templates' ) . '<a class="ang-link" href="https://analogwp.com/docs/what-usage-data-is-tracked-by-style-kits/" target="_blank">' . __( 'More Info', 'analogwp-templates' ) . '</a>',
				),
				array(
					'title'         => __( 'Remove Data on Uninstall', 'analogwp-templates' ),
					'desc'          => __( 'Check this box to remove all data stored by Style Kit for Elementor plugin, including license info, user settings, import history etc. Any imported or manually saved Style Kits are not removed.', 'analogwp-templates' ),
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

		return apply_filters( 'ang_get_settings_' . $this->id, $settings ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
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
