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
				'description' => __( 'Sets the primary brand color, applies on Links.', 'ang' ),
				'classes'     => 'ang-description-wide',
				'selectors'   => [
					'{{WRAPPER}} a:not(.button):not(.elementor-button), {{WRAPPER}} .sk-accent-1' => 'color: {{VALUE}}',
				],
			]
		);

		$element->add_control(
			'ang_color_accent_secondary',
			[
				'label'       => __( 'Secondary Accent', 'ang' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'Sets the default color for Buttons. You can also apply button colors in the Global buttons tab.', 'ang' ),
				'classes'     => 'ang-description-wide',
				'selectors'   => [
					'{{WRAPPER}} .elementor-button, {{WRAPPER}} .button, {{WRAPPER}} button, {{WRAPPER}} .sk-accent-2' => 'background-color: {{VALUE}}',
				],
			]
		);

		$element->add_control(
			'ang_color_text_light',
			[
				'label'       => __( 'Text over light background', 'ang' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'Applies on the text and titles over a section or column with light bg', 'ang' ),
				'classes'     => 'ang-description-wide',
				'selectors'   => [
					'{{WRAPPER}},{{WRAPPER}} h1, {{WRAPPER}} h2, {{WRAPPER}} h3, {{WRAPPER}} h4, {{WRAPPER}} h5, {{WRAPPER}} h6' => 'color: {{VALUE}}',
					':root, {{WRAPPER}} .sk-text-light' => '--ang_color_text_light: {{VALUE}}',
				],
			]
		);

		$element->add_control(
			'ang_color_text_dark',
			[
				'label'       => __( 'Text over dark background', 'ang' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'Applies on the text and titles over a section or column with dark bg', 'ang' ),
				'classes'     => 'ang-description-wide',
				'selectors'   => [
					':root, {{WRAPPER}} .sk-text-dark' => '--ang_color_text_dark: {{VALUE}}',
				],
			]
		);

		$element->add_control(
			'ang_color_background_light',
			[
				'label'       => __( 'Light Background', 'ang' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'Apply the class <strong>sk-light-bg</strong> to a section or column to apply this color as a background. Text will inherit the <strong>Text over Light bg</strong> color.', 'ang' ),
				'classes'     => 'ang-description-wide',
				'selectors'   => [
					'{{WRAPPER}} .sk-light-bg' => 'background-color: {{VALUE}}; color: var(--ang_color_text_light)',
				],
			]
		);

		$element->add_control(
			'ang_color_background_dark',
			[
				'label'       => __( 'Dark Background', 'ang' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'Apply the class <strong>sk-dark-bg</strong> to a section or column to apply this color as a background. Text will inherit the <strong>Text over Dark bg</strong> color.', 'ang' ),
				'classes'     => 'ang-description-wide',
				'selectors'   => [
					'{{WRAPPER}} .sk-dark-bg'   => 'background-color: {{VALUE}}; color: var(--ang_color_text_dark)',
					'{{WRAPPER}} .sk-dark-bg h1,
					{{WRAPPER}} .sk-dark-bg h2,
					{{WRAPPER}} .sk-dark-bg h3,
					{{WRAPPER}} .sk-dark-bg h4,
					{{WRAPPER}} .sk-dark-bg h5,
					{{WRAPPER}} .sk-dark-bg h6' => 'color: var(--ang_color_text_dark)',
				],
			]
		);

		$element->end_controls_section();
	}
}

new Colors();
