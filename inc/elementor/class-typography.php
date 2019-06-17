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
use Analog\Utils;

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
		add_action( 'elementor/element/after_section_end', [ $this, 'register_text_sizes' ], 10, 2 );
		add_action( 'elementor/element/after_section_end', [ $this, 'register_columns_gap' ], 10, 2 );
		add_action( 'elementor/element/after_section_end', [ $this, 'register_styling_settings' ], -9999, 2 );
		add_action( 'elementor/element/after_section_end', [ $this, 'register_tools' ], 10, 2 );

		add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_preview_scripts' ] );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ], 999 );

		add_action( 'elementor/element/before_section_end', [ $this, 'update_padding_control_selector' ], 10, 2 );

		add_filter( 'display_post_states', [ $this, 'add_token_state' ], 10, 2 );
	}

	/**
	 * Update selector for padding, so it doesn't conflict with column gaps.
	 *
	 * @param Controls_Stack $control_stack Control Stack.
	 * @param array          $args Arguments.
	 */
	public function update_padding_control_selector( Controls_Stack $control_stack, $args ) {
		$control = $control_stack->get_controls( 'padding' );

		// Exit early if $control_stack dont have the image_size control.
		if ( empty( $control ) || ! is_array( $control ) ) {
			return;
		}

		if ( 'section_advanced' === $control['section'] ) {
			if ( isset( $control['selectors']['{{WRAPPER}} > .elementor-element-populated'] ) ) {
				$control['selectors'] = [
					'{{WRAPPER}} > .elementor-element-populated.elementor-element-populated' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'(tablet) {{WRAPPER}} > .elementor-element-populated.elementor-element-populated' => 'padding: {{padding_tablet.TOP}}{{padding_tablet.UNIT}} {{padding_tablet.RIGHT}}{{padding_tablet.UNIT}} {{padding_tablet.BOTTOM}}{{padding_tablet.UNIT}} {{padding_tablet.LEFT}}{{padding_tablet.UNIT}};',
					'(mobile) {{WRAPPER}} > .elementor-element-populated.elementor-element-populated' => 'padding: {{padding_mobile.TOP}}{{padding_mobile.UNIT}} {{padding_mobile.RIGHT}}{{padding_mobile.UNIT}} {{padding_mobile.BOTTOM}}{{padding_mobile.UNIT}} {{padding_mobile.LEFT}}{{padding_mobile.UNIT}};',
				];

				$control_stack->update_control( 'padding', $control );
			}
		}
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
				'default'   => $this->get_default_value( 'ang_default_heading_font_family' ),
				'selectors' => [
					'{{WRAPPER}} h1, {{WRAPPER}} h2, {{WRAPPER}} h3, {{WRAPPER}} h4, {{WRAPPER}} h5, h6' => 'font-family: "{{VALUE}}"' . $default_fonts . ';',
				],
			]
		);

		for ( $i = 1; $i < 7; $i++ ) {
			$element->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'           => 'ang_heading_' . $i,
					/* translators: %s: Heading 1-6 type */
					'label'          => sprintf( __( 'Heading %s', 'ang' ), $i ),
					'selector'       => "{{WRAPPER}} h{$i}, {{WRAPPER}} .elementor-widget-heading h{$i}.elementor-heading-title, {{WRAPPER}} h{$i} a, {{WRAPPER}} .elementor-widget-heading h{$i}.elementor-heading-title a",
					'scheme'         => Scheme_Typography::TYPOGRAPHY_1,
					'fields_options' => $this->get_default_typography_values( 'ang_heading_' . $i ),
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
				'label' => __( 'Body Typography', 'ang' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$element->add_control(
			'ang_recently_imported',
			[
				'label'   => __( 'Recently Imported', 'ang' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'no',
			]
		);

		$element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'ang_body',
				'label'          => __( 'Body Typography', 'ang' ),
				'selector'       => '{{WRAPPER}}',
				'scheme'         => Scheme_Typography::TYPOGRAPHY_3,
				'fields_options' => $this->get_default_typography_values( 'ang_body' ),
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
				'label' => __( 'Heading Sizes', 'ang' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$settings = [
			[ 'xxl', __( 'XXL', 'ang' ), 59 ],
			[ 'xl', __( 'XL', 'ang' ), 39 ],
			[ 'large', __( 'Large', 'ang' ), 29 ],
			[ 'medium', __( 'Medium', 'ang' ), 19 ],
			[ 'small', __( 'Small', 'ang' ), 15 ],
		];

		foreach ( $settings as $setting ) {
			$element->add_control(
				'ang_toggle_heading_size_' . $setting[0],
				[
					'label'        => $setting[1],
					'type'         => Controls_Manager::POPOVER_TOGGLE,
					'return_value' => 'yes',
				]
			);

			$element->start_popover();

			$element->add_responsive_control(
				'ang_size_' . $setting[0],
				[
					'label'           => __( 'Font Size', 'ang' ),
					'type'            => Controls_Manager::SLIDER,
					'desktop_default' => $this->get_default_value( 'ang_size_' . $setting[0], true ),
					'tablet_default'  => $this->get_default_value( 'ang_size_' . $setting[0] . '_tablet', true ),
					'mobile_default'  => $this->get_default_value( 'ang_size_' . $setting[0] . '_mobile', true ),
					'size_units'      => [ 'px', 'em', 'rem', 'vw' ],
					'range'           => [
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
					'responsive'      => true,
					'selectors'       => [
						"{{WRAPPER}} .elementor-widget-heading h1.elementor-heading-title.elementor-size-{$setting[0]}," .
						"{{WRAPPER}} .elementor-widget-heading h2.elementor-heading-title.elementor-size-{$setting[0]}," .
						"{{WRAPPER}} .elementor-widget-heading h3.elementor-heading-title.elementor-size-{$setting[0]}," .
						"{{WRAPPER}} .elementor-widget-heading h4.elementor-heading-title.elementor-size-{$setting[0]}," .
						"{{WRAPPER}} .elementor-widget-heading h5.elementor-heading-title.elementor-size-{$setting[0]}," .
						"{{WRAPPER}} .elementor-widget-heading h6.elementor-heading-title.elementor-size-{$setting[0]}"
						=> 'font-size: {{SIZE}}{{UNIT}}',
					],
				]
			);

			$element->add_responsive_control(
				'ang_heading_size_lh_' . $setting[0],
				[
					'label'      => __( 'Line Height', 'ang' ),
					'type'       => Controls_Manager::SLIDER,
					'responsive' => true,
					'size_units' => [ 'px', 'em' ],
					'range'      => [
						'px' => [
							'min' => 1,
							'max' => 200,
						],
					],
					'selectors'  => [
						"{{WRAPPER}} .elementor-widget-heading h1.elementor-heading-title.elementor-size-{$setting[0]}," .
						"{{WRAPPER}} .elementor-widget-heading h2.elementor-heading-title.elementor-size-{$setting[0]}," .
						"{{WRAPPER}} .elementor-widget-heading h3.elementor-heading-title.elementor-size-{$setting[0]}," .
						"{{WRAPPER}} .elementor-widget-heading h4.elementor-heading-title.elementor-size-{$setting[0]}," .
						"{{WRAPPER}} .elementor-widget-heading h5.elementor-heading-title.elementor-size-{$setting[0]}," .
						"{{WRAPPER}} .elementor-widget-heading h6.elementor-heading-title.elementor-size-{$setting[0]}"
						=> 'line-height: {{SIZE}}{{UNIT}}',
					],
				]
			);

			$element->end_popover();
		}

		$element->end_controls_section();
	}

	/**
	 * Register text sizes controls.
	 *
	 * @param Controls_Stack $element Controls object.
	 * @param string         $section_id Section ID.
	 */
	public function register_text_sizes( Controls_Stack $element, $section_id ) {
		if ( 'section_page_style' !== $section_id ) {
			return;
		}

		$element->start_controls_section(
			'ang_text_sizes',
			[
				'label' => __( 'Text Sizes', 'ang' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$settings = [
			[ 'xxl', __( 'XXL', 'ang' ), 59 ],
			[ 'xl', __( 'XL', 'ang' ), 39 ],
			[ 'large', __( 'Large', 'ang' ), 29 ],
			[ 'medium', __( 'Medium', 'ang' ), 19 ],
			[ 'small', __( 'Small', 'ang' ), 15 ],
		];

		foreach ( $settings as $setting ) {
			$element->add_control(
				'ang_toggle_text_size' . $setting[0],
				[
					'label'        => $setting[1],
					'type'         => Controls_Manager::POPOVER_TOGGLE,
					'return_value' => 'yes',
				]
			);

			$element->start_popover();

			$element->add_responsive_control(
				'ang_text_size_' . $setting[0],
				[
					'label'           => __( 'Font Size', 'ang' ),
					'type'            => Controls_Manager::SLIDER,
					'desktop_default' => $this->get_default_value( 'ang_size_' . $setting[0], true ),
					'tablet_default'  => $this->get_default_value( 'ang_size_' . $setting[0] . '_tablet', true ),
					'mobile_default'  => $this->get_default_value( 'ang_size_' . $setting[0] . '_mobile', true ),
					'size_units'      => [ 'px', 'em', 'rem', 'vw' ],
					'range'           => [
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
					'responsive'      => true,
					'selectors'       => [
						"{{WRAPPER}} .elementor-widget-heading .elementor-heading-title.elementor-size-{$setting[0]}:not(h1):not(h2):not(h3):not(h4):not(h5):not(h6)"
						=> 'font-size: {{SIZE}}{{UNIT}}',
					],
				]
			);

			$element->add_responsive_control(
				'ang_text_size_lh_' . $setting[0],
				[
					'label'      => __( 'Line Height', 'ang' ),
					'type'       => Controls_Manager::SLIDER,
					'responsive' => true,
					'size_units' => [ 'px', 'em' ],
					'range'      => [
						'px' => [
							'min' => 1,
							'max' => 200,
						],
					],
					'selectors'  => [
						"{{WRAPPER}} .elementor-widget-heading .elementor-heading-title.elementor-size-{$setting[0]}:not(h1):not(h2):not(h3):not(h4):not(h5):not(h6)"
						=> 'line-height: {{SIZE}}{{UNIT}}',
					],
				]
			);

			$element->end_popover();
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
					'label'           => $label,
					'type'            => Controls_Manager::DIMENSIONS,
					'desktop_default' => $this->get_default_value( 'ang_column_gap_' . $key, true ),
					'tablet_default'  => $this->get_default_value( 'ang_column_gap_' . $key . '_tablet', true ),
					'mobile_default'  => $this->get_default_value( 'ang_column_gap_' . $key . '_mobile', true ),
					'size_units'      => [ 'px', 'em', '%' ],
					'selectors'       => [
						"{{WRAPPER}} .elementor-column-gap-{$key} > .elementor-row > .elementor-column > .elementor-element-populated"
						=> 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					],
				]
			);
		}

		$element->end_controls_section();
	}

	/**
	 * Register Style Kit section.
	 *
	 * @param Controls_Stack $element Controls object.
	 * @param string         $section_id Section ID.
	 *
	 * @return void
	 */
	public function register_styling_settings( Controls_Stack $element, $section_id ) {
		if ( 'section_page_style' !== $section_id ) {
			return;
		}

		$element->start_controls_section(
			'ang_style_settings',
			[
				'label' => __( 'Style Kits', 'ang' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$global_token = get_option( 'elementor_ang_global_kit' );

		if ( ! $global_token ) {
			$global_token = -1;
		}

		$element->add_control(
			'description_ang_global_stylekit',
			[
				'raw'             => __( '<strong>You are editing the style kit that has been set as global.</strong> You can optionally choose a different Style Kit for this page below.', 'ang' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'ang-notice',
				'condition'       => [
					'ang_action_tokens' => $global_token,
				],
			]
		);

		$label = __( 'A style kit is a collection of all the custom styles added at page styling settings. Your Style Kit is updated every time you click the Update Style Kit Button below.', 'ang' );
		$element->add_control(
			'ang_action_tokens',
			[
				'label'   => __( 'Page Style Kit', 'ang' ) . $this->get_tooltip( $label ),
				'type'    => Controls_Manager::SELECT2,
				'options' => Utils::get_tokens(),
				'default' => get_option( 'elementor_ang_global_kit' ),
			]
		);

		$element->add_control(
			'ang_action_update_token',
			[
				'label'        => __( 'Update Your Style Kit', 'ang' ),
				'type'         => 'ang_action',
				'action'       => 'update_token',
				'action_label' => __( 'Update Style Kit', 'ang' ),
				'condition'    => [
					'ang_action_tokens!' => '',
				],
			]
		);

		$label = __( 'Save all the styles as a Style Kit that you can apply on other pages or globally. Please note that only the custom styles added in the styles page are saved with the stylekit.', 'ang' );
		$element->add_control(
			'ang_action_save_token',
			[
				'label'        => __( 'Save Style Kit as...', 'ang' ) . $this->get_tooltip( $label ),
				'type'         => 'ang_action',
				'action'       => 'save_token',
				'action_label' => __( 'Save as...', 'ang' ),
			]
		);

		$element->add_control(
			'description_ang_stylekit_docs',
			[
				'raw'  => sprintf(
					/* translators: %s: Link to Style Kits */
					__( 'You can set a Global Style Kit <a href="%s" target="_blank">here</a>.', 'ang' ),
					admin_url( 'admin.php?page=elementor#tab-style' )
				),
				'type' => Controls_Manager::RAW_HTML,
			]
		);

		$element->end_controls_section();
	}

	/**
	 * Register Tools section.
	 *
	 * @param Controls_Stack $element Controls object.
	 * @param string         $section_id Section ID.
	 *
	 * @return void
	 */
	public function register_tools( Controls_Stack $element, $section_id ) {
		if ( 'section_page_style' !== $section_id ) {
			return;
		}

		$element->start_controls_section(
			'ang_tools',
			[
				'label' => __( 'Tools', 'ang' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$label = __( 'This will reset all the custom style values added in the Style tab, and detach this page from any Style kits', 'ang' );
		$element->add_control(
			'ang_action_reset',
			[
				'label'        => __( 'Reset all styling', 'ang' ) . $this->get_tooltip( $label ),
				'type'         => 'ang_action',
				'action'       => 'reset_css',
				'action_label' => __( 'Reset all', 'ang' ),
			]
		);

		$label = __( 'Export styles as custom CSS text.', 'ang' );
		$element->add_control(
			'ang_action_export_css',
			[
				'label'        => __( 'Export Custom CSS', 'ang' ) . $this->get_tooltip( $label ),
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
				'https://fonts.googleapis.com/css?family=' . implode( ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic|', $font_families ),
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
		$script_suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		wp_enqueue_script(
			'ang_typography_script',
			ANG_PLUGIN_URL . "inc/elementor/js/ang-typography{$script_suffix}.js",
			[
				'jquery',
				'editor',
			],
			ANG_VERSION,
			true
		);
	}

	/**
	 * Get default value for specific control.
	 *
	 * @param string $key Setting ID.
	 * @param bool   $is_array Whether provided key includes set of array.
	 *
	 * @return array|string
	 */
	public function get_default_value( $key, $is_array = false ) {
		$global_token = Utils::get_global_token_data();

		$recently_imported = get_post_meta( get_the_ID(), '_elementor_page_settings', true );
		if ( isset( $recently_imported['ang_recently_imported'] ) && 'yes' === $recently_imported['ang_recently_imported'] ) {
			return ( $is_array ) ? [] : '';
		}

		if ( $global_token && ! empty( $global_token ) ) {
			$values = json_decode( $global_token, true );

			if ( isset( $values[ $key ] ) && '' !== $values[ $key ] ) {
				return $values[ $key ];
			}
		}

		return ( $is_array ) ? [] : '';
	}

	/**
	 * Get default values for Typography group control.
	 *
	 * @param string $key Setting ID.
	 *
	 * @return array
	 */
	public function get_default_typography_values( $key ) {
		$global_token = Utils::get_global_token_data();

		$recently_imported = get_post_meta( get_the_ID(), '_elementor_page_settings', true );
		if ( isset( $recently_imported['ang_recently_imported'] ) && 'yes' === $recently_imported['ang_recently_imported'] ) {
			return [];
		}

		if ( empty( $global_token ) || 'yes' === $recently_imported ) {
			return [];
		}

		return [
			'typography'            => [
				'default' => $this->get_default_value( $key . '_typography' ),
			],
			'font_size'             => [
				'default' => $this->get_default_value( $key . '_font_size' ),
			],
			'font_size_tablet'      => [
				'default' => $this->get_default_value( $key . '_font_size_tablet' ),
			],
			'font_size_mobile'      => [
				'default' => $this->get_default_value( $key . '_font_size_mobile' ),
			],
			'line_height'           => [
				'default' => $this->get_default_value( $key . '_line_height' ),
			],
			'line_height_mobile'    => [
				'default' => $this->get_default_value( $key . '_line_height_mobile' ),
			],
			'line_height_tablet'    => [
				'default' => $this->get_default_value( $key . '_line_height_tablet' ),
			],
			'letter_spacing'        => [
				'default' => $this->get_default_value( $key . '_letter_spacing' ),
			],
			'letter_spacing_mobile' => [
				'default' => $this->get_default_value( $key . '_letter_spacing_mobile' ),
			],
			'letter_spacing_tablet' => [
				'default' => $this->get_default_value( $key . '_letter_spacing_tablet' ),
			],
			'font_family'           => [
				'default' => $this->get_default_value( $key . '_font_family' ),
			],
			'font_weight'           => [
				'default' => $this->get_default_value( $key . '_font_weight' ),
			],
			'text_transform'        => [
				'default' => $this->get_default_value( $key . '_text_transform' ),
			],
			'font_style'            => [
				'default' => $this->get_default_value( $key . '_font_style' ),
			],
			'text_decoration'       => [
				'default' => $this->get_default_value( $key . '_text_decoration' ),
			],
		];
	}

	/**
	 * Return text formatter for displaying tooltip.
	 *
	 * @param string $text Tooltip Text.
	 *
	 * @return string
	 */
	public function get_tooltip( $text ) {
		return ' <span class="hint--top-right hint--medium" aria-label="' . $text . '"><i class="fa fa-info-circle"></i></span>';
	}

	/**
	 * Add visual indicator for token CPT.
	 *
	 * @param array  $post_states Post states.
	 * @param object $post Post Object.
	 * @return array
	 */
	public function add_token_state( $post_states, $post ) {
		$global_token = (int) get_option( 'elementor_ang_global_kit' );
		if ( $global_token && $post->ID === $global_token ) {
			$post_states[] = '<span style="color:#32b644;">&#9679; Global Style Kit</span>';
		}

		return $post_states;
	}
}

new Typography();
