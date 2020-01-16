<?php
/**
 * Class Analog\Elementor\Sections\BackgroundColorClasses.
 *
 * @package Analog
 */

namespace Analog\Elementor\Sections;

defined( 'ABSPATH' ) || exit;

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
		add_action( 'elementor/element/after_section_end', array( $this, 'register_section' ), 170, 2 );
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
		if ( 'section_page_style' !== $section_id ) {
			return;
		}

		$element->start_controls_section(
			'ang_background_color_classes',
			array(
				'label' => _x( 'Background Colors', 'Section Title', 'ang' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
				'raw'     => __( 'Add the class <strong>sk-light-bg</strong> to a section or column to apply these colors.', 'ang' ),
				'classes' => 'elementor-descriptor',
			)
		);

		$element->add_control(
			'ang_background_light_background',
			array(
				'label'     => __( 'Background Color', 'ang' ),
				'type'      => Controls_Manager::COLOR,
				'variable'  => 'ang_background_light_background',
				'selectors' => array(
					'{{WRAPPER}}' => '--ang_background_light_background: {{VALUE}};',
					'{{WRAPPER}} .sk-light-bg:not(.elementor-column)' => 'background-color: {{VALUE}}; color: var(--ang_color_text_light)',
					'{{WRAPPER}} .sk-dark-bg .elementor-counter-title, {{WRAPPER}} .sk-dark-bg .elementor-counter-number-wrapper' => 'color: currentColor',
					'{{WRAPPER}} .sk-light-bg.elementor-column > .elementor-element-populated' => 'background-color: {{VALUE}}; color: var(--ang_color_text_light)',
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
		$element->add_control(
			'ang_background_light_heading',
			array(
				'label'     => __( 'Headings Color', 'ang' ),
				'type'      => Controls_Manager::COLOR,
				'variable'  => 'ang_background_light_heading',
				'selectors' => array(
					'{{WRAPPER}}'                 => '--ang_background_light_heading: {{VALUE}};',
					'{{WRAPPER}} .sk-light-bg h1,' .
					'{{WRAPPER}} .sk-light-bg h2,' .
					'{{WRAPPER}} .sk-light-bg h3,' .
					'{{WRAPPER}} .sk-light-bg h4,' .
					'{{WRAPPER}} .sk-light-bg h5,' .
					'{{WRAPPER}} .sk-light-bg h6' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sk-dark-bg .sk-light-bg h1,' .
					'{{WRAPPER}} .sk-dark-bg .sk-light-bg h2,' .
					'{{WRAPPER}} .sk-dark-bg .sk-light-bg h3,' .
					'{{WRAPPER}} .sk-dark-bg .sk-light-bg h4,' .
					'{{WRAPPER}} .sk-dark-bg .sk-light-bg h5,' .
					'{{WRAPPER}} .sk-dark-bg .sk-light-bg h6' => 'color: {{VALUE}};',
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
				'raw'     => __( 'Add the class <strong>sk-dark-bg</strong> to a section or column to apply these colors.', 'ang' ),
				'classes' => 'elementor-descriptor',
			)
		);

		$element->add_control(
			'ang_background_dark_background',
			array(
				'label'     => __( 'Background Color', 'ang' ),
				'type'      => Controls_Manager::COLOR,
				'variable'  => 'ang_background_dark_background',
				'selectors' => array(
					'{{WRAPPER}}' => '--ang_background_dark_background: {{VALUE}};',
					'{{WRAPPER}} .sk-dark-bg:not(.elementor-column)' => 'background-color: {{VALUE}}; color: var(--ang_color_text_dark)',
					'{{WRAPPER}} .sk-light-bg .elementor-counter-title, {{WRAPPER}} .sk-light-bg .elementor-counter-number-wrapper' => 'color: currentColor;',
					'{{WRAPPER}} .sk-dark-bg.elementor-column > .elementor-element-populated' => 'background-color: {{VALUE}}; color: var(--ang_color_text_dark)',
				),
			)
		);
		$element->add_control(
			'ang_background_dark_text',
			array(
				'label'     => __( 'Text Color', 'ang' ),
				'type'      => Controls_Manager::COLOR,
				'variable'  => 'ang_color_text_dark',
				'selectors' => array(
					'{{WRAPPER}} .sk-dark-bg'   => 'color: {{VALUE}};',
					'{{WRAPPER}}, {{WRAPPER}} .sk-text-dark' => '--ang_color_text_dark: {{VALUE}}',
					'{{WRAPPER}} .sk-text-dark' => 'color: {{VALUE}}',
					'{{WRAPPER}} .sk-text-dark .elementor-heading-title' => 'color: {{VALUE}}',
				),
			)
		);
		$element->add_control(
			'ang_background_dark_heading',
			array(
				'label'     => __( 'Headings Color', 'ang' ),
				'type'      => Controls_Manager::COLOR,
				'variable'  => 'ang_background_dark_heading',
				'selectors' => array(
					'{{WRAPPER}}'                => '--ang_background_dark_heading: {{VALUE}};',
					'{{WRAPPER}} .sk-dark-bg h1,' .
					'{{WRAPPER}} .sk-dark-bg h2,' .
					'{{WRAPPER}} .sk-dark-bg h3,' .
					'{{WRAPPER}} .sk-dark-bg h4,' .
					'{{WRAPPER}} .sk-dark-bg h5,' .
					'{{WRAPPER}} .sk-dark-bg h6' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sk-light-bg .sk-dark-bg h1,' .
					'{{WRAPPER}} .sk-light-bg .sk-dark-bg h2,' .
					'{{WRAPPER}} .sk-light-bg .sk-dark-bg h3,' .
					'{{WRAPPER}} .sk-light-bg .sk-dark-bg h4,' .
					'{{WRAPPER}} .sk-light-bg .sk-dark-bg h5,' .
					'{{WRAPPER}} .sk-light-bg .sk-dark-bg h6' => 'color: {{VALUE}};',
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
