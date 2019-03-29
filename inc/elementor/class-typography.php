<?php
/**
 * Elemenotor Typography controls.
 *
 * @package Analog
 */

namespace Analog\Elementor;

use Elementor\Core\Base\Module;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Core\Settings\Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Class Typography.
 *
 * @package Analog\Elementor
 */
class Typography extends Module {
	/**
	 * Typography constructor.
	 */
	public function __construct() {
		add_action( 'elementor/element/after_section_end', [ $this, 'register_body_and_paragraph_typography' ], 10, 2 );
		add_action( 'elementor/element/after_section_end', [ $this, 'register_heading_typography' ], 10, 2 );
		add_action( 'elementor/element/after_section_end', [ $this, 'register_typography_sizes' ], 10, 2 );
		add_action( 'elementor/element/after_section_end', [ $this, 'register_columns_gap' ], 10, 2 );
		add_action( 'elementor/element/after_section_end', [ $this, 'register_styling_settings' ], 10, 2 );

		add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_preview_scripts' ] );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ], 999 );
	}

	/**
	 * Get public name for control.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'agwp-controls';
	}

	/**
	 * Register Heading typography controls.
	 *
	 * @param Controls_Stack $element Controls object.
	 * @param string         $section_id Section ID.
	 */
	public function register_heading_typography( Controls_Stack $element, $section_id ) {
		if ( 'section_page_style' !== $section_id ) {
			return;
		}

		$element->start_controls_section(
			'ang_headings_typography',
			[
				'label' => __( 'Headings Typography', 'ang' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$element->add_control(
			'ang_headings_typography_description',
			[
				'raw'             => __( 'These settings apply to all Headings in your layout. You can still override individual values at each element.', 'ang' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			]
		);

		$default_fonts = Manager::get_settings_managers( 'general' )->get_model()->get_settings( 'elementor_default_generic_fonts' );

		if ( $default_fonts ) {
			$default_fonts = ', ' . $default_fonts;
		}

		$element->add_control(
			'ang_default_heading_font_family',
			[
				'label'     => __( 'Default Headings Font', 'ang' ),
				'type'      => Controls_Manager::FONT,
				'default'   => '',
				'selectors' => [
					'h1, h2, h3, h4, h5, h6' => 'font-family: "{{VALUE}}"' . $default_fonts . ';',
				],
			]
		);

		for ( $i = 1; $i < 7; $i++ ) {
			$element->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'ang_heading_' . $i,
					/* translators: %s: Heading 1-6 type */
					'label'    => sprintf( __( 'Heading %s', 'ang' ), $i ),
					'selector' => "body h{$i}, body .elementor-widget-heading h{$i}.elementor-heading-title",
					'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
				]
			);
		}

		$element->end_controls_section();
	}

	/**
	 * Register Body and Paragraph typography controls.
	 *
	 * @param Controls_Stack $element Controls object.
	 * @param string         $section_id Section ID.
	 */
	public function register_body_and_paragraph_typography( Controls_Stack $element, $section_id ) {
		if ( 'section_page_style' !== $section_id ) {
			return;
		}

		$element->start_controls_section(
			'ang_body_and_paragraph_typography',
			[
				'label' => __( 'Body and Paragraph Typography', 'ang' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'ang_body',
				'label'    => __( 'Body Typography', 'ang' ),
				'selector' => 'body',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'ang_paragraph',
				'label'    => __( 'Paragraph (primary text)', 'ang' ),
				'selector' => 'body p',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
			]
		);

		$element->end_controls_section();
	}

	/**
	 * Register typography sizes controls.
	 *
	 * @param Controls_Stack $element Controls object.
	 * @param string         $section_id Section ID.
	 */
	public function register_typography_sizes( Controls_Stack $element, $section_id ) {
		if ( 'section_page_style' !== $section_id ) {
			return;
		}

		$element->start_controls_section(
			'ang_typography_sizes',
			[
				'label' => __( 'Sizes', 'ang' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$settings = [
			[ 'small', __( 'Small', 'ang' ), 15 ],
			[ 'medium', __( 'Medium', 'ang' ), 19 ],
			[ 'large', __( 'Large', 'ang' ), 29 ],
			[ 'xl', __( 'XL', 'ang' ), 39 ],
			[ 'xxl', __( 'XXL', 'ang' ), 59 ],
		];

		foreach ( $settings as $setting ) {
			$element->add_responsive_control(
				'ang_size_' . $setting[0],
				[
					'label'      => $setting[1],
					'type'       => Controls_Manager::SLIDER,
					'desktop_default' => [
						'unit' => 'em',
					],
					'tablet_default' => [
						'unit' => 'em',
					],
					'mobile_default' => [
						'unit' => 'em',
					],
					'size_units' => [ 'px', 'em', 'rem', 'vw' ],
					'range'      => [
						'px' => [
							'min' => 1,
							'max' => 200,
						],
						'vw' => [
							'min'  => 0.1,
							'max'  => 10,
							'step' => 0.1,
						],
					],
					'responsive' => true,
					'selectors'  => [
						"body .elementor-widget-heading .elementor-heading-title.elementor-size-{$setting[0]}" => 'font-size: {{SIZE}}{{UNIT}}',
					],
				]
			);
		}

		$element->end_controls_section();
	}

	/**
	 * Register Columns gaps controls.
	 *
	 * @param Controls_Stack $element Controls object.
	 * @param string         $section_id Section ID.
	 */
	public function register_columns_gap( Controls_Stack $element, $section_id ) {
		if ( 'section_page_style' !== $section_id ) {
			return;
		}

		$gaps = [
			'default'  => __( 'Default Padding', 'ang' ),
			'narrow'   => __( 'Narrow Padding', 'ang' ),
			'extended' => __( 'Extended Padding', 'ang' ),
			'wide'     => __( 'Wide Padding', 'ang' ),
			'wider'    => __( 'Wider Padding', 'ang' ),
		];

		$element->start_controls_section(
			'ang_column_gaps',
			[
				'label' => __( 'Column Gaps', 'ang' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$element->add_control(
			'ang_column_gaps_description',
			[
				'raw'             => __( 'Set the default values of the column gaps. Based on Elementor&apos;s default sizes.', 'ang' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			]
		);

		foreach ( $gaps as $key => $label ) {
			$element->add_responsive_control(
				'ang_column_gap_' . $key,
				[
					'label'      => $label,
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						"body .elementor-column-gap-{$key} > .elementor-row > .elementor-column > .elementor-element-populated"
						=> 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					],
				]
			);
		}

		$element->end_controls_section();
	}

	public function register_styling_settings( Controls_Stack $element, $section_id ) {
		if ( 'section_page_style' !== $section_id ) {
			return;
		}

		$element->start_controls_section(
			'ang_style_settings',
			[
				'label' => __( 'Styling Settings', 'ang' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		$element->add_control(
			'ang_action_reset',
			[
				'label'        => __( 'Reset all styling', 'ang' ),
				'type'         => 'ang_action',
				'action'       => 'reset_css',
				'action_label' => __( 'Reset all', 'ang' ),
				'description'  => __( 'Resets only the CSS that is added at the Style panel.', 'ang' ),
			]
		);

		$element->add_control(
			'ang_action_export_css',
			[
				'label'        => __( 'Export the custom CSS', 'ang' ),
				'type'         => 'ang_action',
				'action'       => 'export_css',
				'action_label' => __( 'Export CSS', 'ang' ),
			]
		);

		$element->end_controls_section();
	}

	/**
	 * Enqueue Google fonts.
	 *
	 * @return void
	 */
	public function enqueue_preview_scripts() {
		$post_id = get_the_ID();

		// Get the page settings manager.
		$page_settings_manager = Manager::get_settings_managers( 'page' );
		$page_settings_model   = $page_settings_manager->get_model( $post_id );

		$keys = apply_filters(
			'analog/elementor/typography/keys',
			[
				'ang_heading_1',
				'ang_heading_2',
				'ang_heading_3',
				'ang_heading_4',
				'ang_heading_5',
				'ang_heading_6',
				'ang_default_heading',
				'ang_body',
				'ang_paragraph',
			]
		);

		$font_families = [];

		foreach ( $keys as $key ) {
			$font_families[] = $page_settings_model->get_settings( $key . '_font_family' );
		}

		// Remove duplicate and null values.
		$font_families = \array_unique( \array_filter( $font_families ) );

		if ( count( $font_families ) ) {
			wp_enqueue_style(
				'ang_typography_fonts',
				'https://fonts.googleapis.com/css?family=' . implode( '|', $font_families ),
				[],
				get_the_modified_time( 'U', $post_id )
			);
		}
	}

	/**
	 * Enqueue preview script.
	 *
	 * @return void
	 */
	public function enqueue_editor_scripts() {
		wp_enqueue_script(
			'ang_typography_script',
			ANG_PLUGIN_URL . 'inc/elementor/js/ang-typography.js',
			[
				'jquery',
				'editor',
			],
			ANG_VERSION,
			true
		);
	}
}

new Typography();
