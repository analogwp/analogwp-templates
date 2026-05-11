<?php
/**
 * Analog Experiments Settings.
 *
 * @package Analog/Admin
 * @since 1.9.0
 */

namespace Analog\Settings;

defined( 'ABSPATH' ) || exit;

/**
 * Experiments Control.
 */
class Experiments extends Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'experiments';
		$this->label = __( 'Experiments', 'analogwp-templates' );
		parent::__construct();
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {

		$options = array(
			'default'  => __( 'Default', 'analogwp-templates' ),
			'active'   => __( 'Active', 'analogwp-templates' ),
			'inactive' => __( 'Inactive', 'analogwp-templates' ),
		);

		$settings = apply_filters(
			'ang_experiments_settings', // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			array(
				array(
					'title' => __( 'Style Kits Experiments', 'analogwp-templates' ),
					'desc'  => sprintf(
						/* translators: %s: Style Kits Experiments Documentation link */
						__( 'Below you can activate experimental features for Style Kits and Style Kits Pro. We suggest you to turn on backups while using these experiments. %s about how this works.', 'analogwp-templates' ),
						'<a href="https://analogwp.com/docs/style-kits-experiments/" target="_blank">' . __( 'Learn more', 'analogwp-templates' ) . '</a>'
					),
					'type'  => 'title',
					'id'    => 'ang_experiments',
				),
				array(
					'title'   => __( 'Container-based Library', 'analogwp-templates' ),
					'desc'    => __( 'Get access to the container-based library of Patterns. You need to have the Containers Feature activated in Elementor, to test the new library.', 'analogwp-templates' ),
					'id'      => 'container_library_experiment',
					'default' => 'default',
					'type'    => 'select',
					'options' => $options,
				),
				array(
					'type' => 'sectionend',
					'id'   => 'ang_beta',
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

return new Experiments();
