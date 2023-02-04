<?php
/**
 * Class Analog\Elementor\Sections\BackgroundColorClasses.
 *
 * @package Analog
 */

namespace Analog\Elementor\Sections;

defined( 'ABSPATH' ) || exit;

use Analog\Utils;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Core\Base\Module;

/**
 * Class BackgroundColorClasses.
 *
 * Add "Background Color Classes" section.
 *
 * @since 1.5.0
 * @package Analog\Elementor\Sections
 */
final class BackgroundColorClasses extends Module {
	/**
	 * BackgroundColorClasses constructor.
	 */
	public function __construct() {
		add_action( 'elementor/element/kit/section_buttons/after_section_end', array( $this, 'register_section' ), 10, 2 );
	}

	/**
	 * Get module name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'ang-background-color-classes';
	}

	/**
	 * Register Analog Color controls.
	 *
	 * @param Controls_Stack $element Elementor element.
	 * @param string         $section_id Section ID.
	 */
	public function register_section( Controls_Stack $element, $section_id ) {
		$element->start_controls_section(
			'ang_background_color_classes',
			array(
				'label' => _x( 'Background Color Classes', 'Section Title', 'ang' ),
				'tab'   => Utils::get_kit_settings_tab(),
			)
		);

		$element->start_controls_tabs( 'ang_tabs_background_color_classes' );

		$element->start_controls_tab(
			'ang_tab_background_light',
			array( 'label' => __( 'Light', 'ang' ) )
		);

		$element->add_control(
			'ang_tab_background_light_desc',
			array(
				'type'    => Controls_Manager::RAW_HTML,
				'raw'     => sprintf(
					'%1$s <a href="https://analogwp.com/docs/background-color-classes/" target="_blank">%2$s</a>',
					__( 'Add the class <strong>sk-light-bg</strong> to a section or column to apply these colors.', 'ang' ),
					__( 'Learn more', 'ang' ),
				),
				'classes' => 'elementor-descriptor',
			)
		);

		$element->add_control(
			'ang_background_light_background',
			array(
				'label'     => __( 'Background Color', 'ang' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#F4F4F4',
				'variable'  => 'ang_background_light_background',
				'selectors' => array(
					'{{WRAPPER}}' => '--ang_background_light_background: {{VALUE}};',
					'{{WRAPPER}} .sk-light-bg:not(.elementor-column)' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .sk-dark-bg .elementor-counter-title, {{WRAPPER}} .sk-dark-bg .elementor-counter-number-wrapper' => 'color: currentColor',
					'{{WRAPPER}} .sk-light-bg.elementor-column > .elementor-element-populated' => 'background-color: {{VALUE}};',
				),
			)
		);
		$element->add_control(
			'ang_background_light_text',
			array(
				'label'     => __( 'Text Color', 'ang' ),
				'type'      => Controls_Manager::COLOR,
				'variable'  => 'ang_color_text_light',
				'selectors' => array(
					'{{WRAPPER}} .sk-light-bg'   => 'color: {{VALUE}};',
					'{{WRAPPER}}, {{WRAPPER}} .sk-text-light' => '--ang_color_text_light: {{VALUE}}',
					'{{WRAPPER}} .sk-text-light' => 'color: {{VALUE}}',
					'{{WRAPPER}} .sk-text-light .elementor-heading-title' => 'color: {{VALUE}}',
				),
			)
		);

		$light_heading_selectors = array(
			'{{WRAPPER}} .sk-light-bg h1',
			'{{WRAPPER}} .sk-light-bg h1.elementor-heading-title',
			'{{WRAPPER}} .sk-light-bg h2',
			'{{WRAPPER}} .sk-light-bg h2.elementor-heading-title',
			'{{WRAPPER}} .sk-light-bg h3',
			'{{WRAPPER}} .sk-light-bg h3.elementor-heading-title',
			'{{WRAPPER}} .sk-light-bg h4',
			'{{WRAPPER}} .sk-light-bg h4.elementor-heading-title',
			'{{WRAPPER}} .sk-light-bg h5',
			'{{WRAPPER}} .sk-light-bg h5.elementor-heading-title',
			'{{WRAPPER}} .sk-light-bg h6',
			'{{WRAPPER}} .sk-light-bg h6.elementor-heading-title',
			'{{WRAPPER}} .sk-dark-bg .sk-light-bg h1',
			'{{WRAPPER}} .sk-dark-bg .sk-light-bg h1.elementor-heading-title',
			'{{WRAPPER}} .sk-dark-bg .sk-light-bg h2',
			'{{WRAPPER}} .sk-dark-bg .sk-light-bg h2.elementor-heading-title',
			'{{WRAPPER}} .sk-dark-bg .sk-light-bg h3',
			'{{WRAPPER}} .sk-dark-bg .sk-light-bg h3.elementor-heading-title',
			'{{WRAPPER}} .sk-dark-bg .sk-light-bg h4',
			'{{WRAPPER}} .sk-dark-bg .sk-light-bg h4.elementor-heading-title',
			'{{WRAPPER}} .sk-dark-bg .sk-light-bg h5',
			'{{WRAPPER}} .sk-dark-bg .sk-light-bg h5.elementor-heading-title',
			'{{WRAPPER}} .sk-dark-bg .sk-light-bg h6',
			'{{WRAPPER}} .sk-dark-bg .sk-light-bg h6.elementor-heading-title',
		);
		$light_heading_selectors = implode( ',', $light_heading_selectors );

		$element->add_control(
			'ang_background_light_heading',
			array(
				'label'     => __( 'Headings Color', 'ang' ),
				'type'      => Controls_Manager::COLOR,
				'variable'  => 'ang_background_light_heading',
				'selectors' => array(
					'{{WRAPPER}}'            => '--ang_background_light_heading: {{VALUE}};',
					$light_heading_selectors => 'color: {{VALUE}};',
				),
			)
		);

		$element->end_controls_tab();

		$element->start_controls_tab(
			'ang_tab_background_dark',
			array( 'label' => __( 'Dark', 'ang' ) )
		);

		$element->add_control(
			'ang_tab_background_dark_desc',
			array(
				'type'    => Controls_Manager::RAW_HTML,
				'raw'     => sprintf(
					'%1$s <a href="https://analogwp.com/docs/background-color-classes/" target="_blank">%2$s</a>',
					__( 'Add the class <strong>sk-dark-bg</strong> to a section or column to apply these colors.', 'ang' ),
					__( 'Learn more', 'ang' ),
				),
				'classes' => 'elementor-descriptor',
			)
		);

		$element->add_control(
			'ang_background_dark_background',
			array(
				'label'     => __( 'Background Color', 'ang' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#171720',
				'variable'  => 'ang_background_dark_background',
				'selectors' => array(
					'{{WRAPPER}}' => '--ang_background_dark_background: {{VALUE}};',
					'{{WRAPPER}} .sk-dark-bg:not(.elementor-column)' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .sk-light-bg .elementor-counter-title, {{WRAPPER}} .sk-light-bg .elementor-counter-number-wrapper' => 'color: currentColor;',
					'{{WRAPPER}} .sk-dark-bg.elementor-column > .elementor-element-populated' => 'background-color: {{VALUE}};',
				),
			)
		);
		$element->add_control(
			'ang_background_dark_text',
			array(
				'label'     => __( 'Text Color', 'ang' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'variable'  => 'ang_color_text_dark',
				'selectors' => array(
					'{{WRAPPER}} .sk-dark-bg'   => 'color: {{VALUE}};',
					'{{WRAPPER}}, {{WRAPPER}} .sk-text-dark' => '--ang_color_text_dark: {{VALUE}}',
					'{{WRAPPER}} .sk-text-dark' => 'color: {{VALUE}}',
					'{{WRAPPER}} .sk-text-dark .elementor-heading-title' => 'color: {{VALUE}}',
				),
			)
		);

		$dark_heading_selectors = array(
			'{{WRAPPER}} .sk-dark-bg h1',
			'{{WRAPPER}} .sk-dark-bg h1.elementor-heading-title',
			'{{WRAPPER}} .sk-dark-bg h2',
			'{{WRAPPER}} .sk-dark-bg h2.elementor-heading-title',
			'{{WRAPPER}} .sk-dark-bg h3',
			'{{WRAPPER}} .sk-dark-bg h3.elementor-heading-title',
			'{{WRAPPER}} .sk-dark-bg h4',
			'{{WRAPPER}} .sk-dark-bg h4.elementor-heading-title',
			'{{WRAPPER}} .sk-dark-bg h5',
			'{{WRAPPER}} .sk-dark-bg h5.elementor-heading-title',
			'{{WRAPPER}} .sk-dark-bg h6',
			'{{WRAPPER}} .sk-dark-bg h6.elementor-heading-title',
			'{{WRAPPER}} .sk-light-bg .sk-dark-bg h1',
			'{{WRAPPER}} .sk-light-bg .sk-dark-bg h1.elementor-heading-title',
			'{{WRAPPER}} .sk-light-bg .sk-dark-bg h2',
			'{{WRAPPER}} .sk-light-bg .sk-dark-bg h2.elementor-heading-title',
			'{{WRAPPER}} .sk-light-bg .sk-dark-bg h3',
			'{{WRAPPER}} .sk-light-bg .sk-dark-bg h3.elementor-heading-title',
			'{{WRAPPER}} .sk-light-bg .sk-dark-bg h4',
			'{{WRAPPER}} .sk-light-bg .sk-dark-bg h4.elementor-heading-title',
			'{{WRAPPER}} .sk-light-bg .sk-dark-bg h5',
			'{{WRAPPER}} .sk-light-bg .sk-dark-bg h5.elementor-heading-title',
			'{{WRAPPER}} .sk-light-bg .sk-dark-bg h6',
			'{{WRAPPER}} .sk-light-bg .sk-dark-bg h6.elementor-heading-title',
		);

		$dark_heading_selectors = implode( ',', $dark_heading_selectors );
		$element->add_control(
			'ang_background_dark_heading',
			array(
				'label'     => __( 'Headings Color', 'ang' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'variable'  => 'ang_background_dark_heading',
				'selectors' => array(
					'{{WRAPPER}}'           => '--ang_background_dark_heading: {{VALUE}};',
					$dark_heading_selectors => 'color: {{VALUE}};',
				),
			)
		);

		$element->end_controls_tab();

		do_action( 'analog_background_colors_tab_end', $element );

		$element->end_controls_tabs();

		$element->end_controls_section();
	}
}

new BackgroundColorClasses();
