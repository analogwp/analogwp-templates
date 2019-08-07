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
		add_action( 'elementor/element/after_section_end', [ $this, 'register_color_settings' ], 10, 2 );
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

		$element->add_control(
			'ang_color_accent_primary',
			[
				'label'       => __( 'Primary Accent', 'ang' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'Applies to all links by default', 'ang' ),
				'selectors'   => [
					'{{WRAPPER}} a:not(.button):not(.elementor-button)' => 'color: {{VALUE}}',
				],
			]
		);

		$element->add_control(
			'ang_color_accent_secondary',
			[
				'label'       => __( 'Secondary Accent', 'ang' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'Adds a background color to all buttons on page.', 'ang' ),
				'selectors'   => [
					'{{WRAPPER}} .elementor-button, {{WRAPPER}} .button, {{WRAPPER}} button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$element->add_control(
			'ang_color_text_headings_dark',
			[
				'label'       => __( 'Text and Headings (Dark)', 'ang' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'Applies on the text and titles over light bg', 'ang' ),
				'selectors'   => [
					'{{WRAPPER}},{{WRAPPER}} h1, {{WRAPPER}} h2, {{WRAPPER}} h3, {{WRAPPER}} h4, {{WRAPPER}} h5, {{WRAPPER}} h6' => 'color: {{VALUE}}',
					':root' => '--ang_color_text_headings_dark: {{VALUE}}',
				],
			]
		);

		$element->add_control(
			'ang_color_text_headings_light',
			[
				'label'       => __( 'Text and Headings (Light)', 'ang' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'Applies on the text and titles over dark bg', 'ang' ),
				'selectors'   => [
					':root' => '--ang_color_text_headings_light: {{VALUE}}',
				],
			]
		);

		$element->add_control(
			'ang_color_background_dark',
			[
				'label'     => __( 'Dark Background', 'ang' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .dark-bg' => 'background-color: {{VALUE}}; color: var(--ang_color_text_headings_light)',
				],
			]
		);

		$element->add_control(
			'ang_color_background_light',
			[
				'label'     => __( 'Light Background', 'ang' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .light-bg' => 'background-color: {{VALUE}}; color: var(--ang_color_text_headings_dark)',
				],
			]
		);

		$element->end_controls_section();
	}
}

new Colors();
