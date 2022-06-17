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
		$this->label = __( 'Experiments', 'ang' );
		parent::__construct();

	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {

		$options = array(
			'active'   => __( 'Active', 'ang' ),
			'inactive' => __( 'Inactive', 'ang' ),
		);

		$settings = apply_filters(
			'ang_experiments_settings',
			array(
				array(
					'title' => __( 'Style Kits Experiments', 'ang' ),
					'desc'  => sprintf(
						/* translators: %s: Style Kits Experiments Documentation link */
						__( 'Below you can activate experimental features for Style Kits and Style Kits Pro. We suggest that you don’t use these features on a production site. %s about how this works.', 'ang' ),
						'<a href="https://docs.analogwp.com/article/548-beta-features" target="_blank">' . __( 'Learn more', 'ang' ) . '</a>'
					),
					'type'  => 'title',
					'id'    => 'ang_experiments',
				),
				array(
					'title'   => __( 'Container Spacing', 'ang' ),
					'desc'    => sprintf(
						/* translators: %s: SK Container Spacing experiment documentation link */
						__( 'Manage the spacing of your containers, through a group of customisable spacing presets. You need to have the Containers experiment activated in Elementor, to test this feature. %s', 'ang' ),
						'<a href="https://docs.analogwp.com/article/655-container-presets" target="_blank">' . __( 'Learn more', 'ang' ) . '</a>'
					),
					'id'      => 'container_spacing_experiment',
					'default' => 'inactive',
					'type'    => 'select',
					'options' => $options,
				),
				array(
					'title'   => __( 'Container Background class presets ', 'ang' ),
					'desc'    => __( 'Apply sk-background classes to containers, using a dropdown. You need to have the Containers experiment activated in Elementor, to test this feature.', 'ang' ),
					'id'      => 'container_bg_classes_experiment',
					'default' => 'inactive',
					'type'    => 'select',
					'options' => $options,
				),
				array(
					'title'   => __( 'Global Colors', 'ang' ),
					'desc'    => __( 'Enable this experiment to try the new Style Kit Global colors', 'ang' ),
					'id'      => 'global_colors_experiment',
					'default' => 'inactive',
					'type'    => 'select',
					'options' => $options,
				),
				array(
					'title'   => __( 'Global Fonts', 'ang' ),
					'desc'    => __( 'Enable this experiment to try the new Style Kit Global fonts', 'ang' ),
					'id'      => 'global_fonts_experiment',
					'default' => 'inactive',
					'type'    => 'select',
					'options' => $options,
				),
				array(
					'type' => 'sectionend',
					'id'   => 'ang_beta',
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

return new Experiments();
