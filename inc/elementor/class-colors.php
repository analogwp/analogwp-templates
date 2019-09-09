<?php

namespace Analog\Elementor;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Core\Settings\Manager;
use Elementor\Core\Base\Module;

class Colors extends Module {
	public function __construct() {
		add_action( 'elementor/element/after_section_end', [ $this, 'register_color_settings' ], 170, 2 );
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
			'ang_colors_description',
			[
				'raw'             => __( 'Set the colors for Typography, accents and more.', 'ang' ) . sprintf( ' <a href="%1$s" target="_blank">%2$s</a>', 'https://docs.analogwp.com/article/574-working-with-colours', __( 'Learn more.', 'ang' ) ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			]
		);

		$element->add_control(
			'ang_color_accent_primary',
			[
				'label'       => __( 'Primary Accent', 'ang' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'The primary accent color applies on Links.', 'ang' ),
				'classes'     => 'ang-description-wide',
				'selectors'   => [
					'{{WRAPPER}} a:not([role=button]), {{WRAPPER}} .sk-accent-1' => 'color: {{VALUE}}',
					'{{WRAPPER}} .elementor-icon-box-icon .elementor-icon, {{WRAPPER}} .elementor-icon-list-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .elementor-progress-bar' => 'background-color: {{VALUE}}',
				],
			]
		);

		$element->add_control(
			'ang_color_accent_secondary',
			[
				'label'       => __( 'Secondary Accent', 'ang' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'The default Button color. You can also set button colors in the Buttons tab.', 'ang' ),
				'classes'     => 'ang-description-wide',
				'selectors'   => [
					'{{WRAPPER}} .elementor-button, {{WRAPPER}} .button, {{WRAPPER}} button, {{WRAPPER}} .sk-accent-2' => 'background-color: {{VALUE}}',
				],
			]
		);

		$element->add_control(
			'ang_color_text_light',
			[
				'label'       => __( 'Text and Headings Color', 'ang' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'Applies on the text and headings in the layout.', 'ang' ),
				'classes'     => 'ang-description-wide',
				'selectors'   => [
					'{{WRAPPER}},{{WRAPPER}} h1, {{WRAPPER}} h2, {{WRAPPER}} h3, {{WRAPPER}} h4, {{WRAPPER}} h5, {{WRAPPER}} h6' => 'color: {{VALUE}}',
					':root, {{WRAPPER}} .sk-text-light' => '--ang_color_text_light: {{VALUE}}',
				],
			]
		);

		$element->add_control(
			'ang_color_background_light',
			[
				'label'       => __( 'Light Background', 'ang' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'Apply this color to sections or columns, using the <code>sk-light-bg</code>. The text inside will inherit the Text and titles color.', 'ang' ),
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
				'description' => __( 'Apply this color to sections or columns, using the <code>sk-dark-bg</code>. The text inside will inherit the <em>Text over Dark Background</em> color that can be set below.', 'ang' ),
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

		$element->add_control(
			'ang_color_text_dark',
			[
				'label'       => __( 'Text over dark background', 'ang' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'This color will apply on the text in a section or column with the Dark Background Color, as it has been set above.', 'ang' ),
				'classes'     => 'ang-description-wide',
				'selectors'   => [
					':root, {{WRAPPER}} .sk-text-dark' => '--ang_color_text_dark: {{VALUE}}',
				],
			]
		);

		$element->end_controls_section();
	}
}

new Colors();
