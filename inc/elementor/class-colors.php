<?php

namespace Analog\Elementor;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Core\Base\Module;

class Colors extends Module {
	public function __construct() {
		$this->add_dynamic_actions();
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

	public function register_colors( Element_Base $element, $section_id ) {
		$element->add_control(
			'ang_color',
			[
				'label'        => __( 'Global Color', 'elementor-pro' ),
				'description'  => __( 'Applied one of global colors defined in Page Styles tab.' ),
				'type'         => Controls_Manager::SELECT2,
				'prefix_class' => 'ang-color-',
				'options'      => [
					'1' => 'Color 1',
					'2' => 'Color 2',
					'3' => 'Color 3',
					'4' => 'Color 4',
				],

			]
		);
	}
}

new Colors();
