<?php

namespace Analog\Elementor;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Core\Settings\Manager;
use Elementor\Element_Base;
use Elementor\Core\Base\Module;

class Colors extends Module {
	public function __construct() {
		$this->add_dynamic_actions();

		add_action( 'elementor/element/after_section_end', [ $this, 'register_color_settings' ], 10, 2 );
	}

	public function add_dynamic_actions() {
		/**
		 * Elements data.
		 *
		 * Format:
		 * Element Name => Section Name
		 */
		$elements = [
			'icon'    => 'section_style_icon',
			'heading' => 'section_title_style',
		];

		foreach ( $elements as $element => $section ) {
			add_action( "elementor/element/{$element}/{$section}/after_section_start", [ $this, 'register_colors' ], 10, 2 );
		}
	}

	public function get_name() {
		return 'ang-colors';
	}

	public function register_color_settings( Controls_Stack $element, $section_id ) {
		if ( 'section_page_style' !== $section_id ) {
			return;
		}

		$element->start_controls_section(
			'ang_colors',
			[
				'label' => _x( 'Global Colors', 'Section Title', 'ang' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		for ( $i = 1; $i <= 8; $i++ ) {
			$element->add_control(
				'ang_color_toggle' . $i,
				[
					/* translators: %s: Color Index. */
					'label'        => sprintf( __( 'Color %s', 'ang' ), $i ),
					'type'         => Controls_Manager::POPOVER_TOGGLE,
					'return_value' => 'yes',
				]
			);

			$element->start_popover();

			$element->add_control(
				'ang_color_label_' . $i,
				[
					'label'   => __( 'Label', 'ang' ),
					'default' => 'Color ' . $i,
					'type'    => Controls_Manager::TEXT,
				]
			);

			$element->add_control(
				'ang-color-' . $i,
				[
					'label'     => __( 'Color', 'ang' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						".ang-color-{$i}, .ang-color-{$i} *" => 'color: {{VALUE}}',
					],
				]
			);

			$element->end_popover();
		}

		$element->end_controls_section();
	}

	public function register_colors( Element_Base $element, $section_id ) {
		$post_id = get_the_ID();

		// Get the page settings manager.
		$page_settings_manager = Manager::get_settings_managers( 'page' );
		$page_settings_model   = $page_settings_manager->get_model( $post_id );

		$element->add_control(
			'ang_color',
			[
				'label'        => __( 'Global Color', 'elementor-pro' ),
				'description'  => __( 'Applied one of global colors defined in Page Styles tab.' ),
				'type'         => Controls_Manager::SELECT2,
				'prefix_class' => 'ang-color-',
				'options'      => [
					'1' => $page_settings_model->get_settings( 'ang_color_label_1' ),
					'2' => $page_settings_model->get_settings( 'ang_color_label_2' ),
					'3' => $page_settings_model->get_settings( 'ang_color_label_3' ),
					'4' => $page_settings_model->get_settings( 'ang_color_label_4' ),
					'5' => $page_settings_model->get_settings( 'ang_color_label_5' ),
					'6' => $page_settings_model->get_settings( 'ang_color_label_6' ),
					'7' => $page_settings_model->get_settings( 'ang_color_label_7' ),
					'8' => $page_settings_model->get_settings( 'ang_color_label_8' ),
				],
			]
		);
	}
}

new Colors();
