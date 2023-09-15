<?php
/**
 * Elemenotor Typography controls.
 *
 * @package Analog
 */

namespace Analog\Elementor;

use Analog\Options;
use Analog\Plugin;
use Elementor\Core\Base\Module;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Core\Kits\Controls\Repeater as Global_Style_Repeater;
use Elementor\Element_Base;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Settings\Manager;
use Analog\Utils;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

/**
 * Class Typography.
 *
 * @package Analog\Elementor
 */
class Typography extends Module {
	use Document;

	/**
	 * Tab to which add settings to.
	 *
	 * @since 1.8.0
	 *
	 * @var string
	 */
	private $settings_tab;

	/**
	 * Holds Style Kits.
	 *
	 * @since 1.4.0
	 * @var array
	 */
	protected $tokens;

	/**
	 * Holds Global Kit token data.
	 *
	 * @since 1.4.0
	 * @var mixed
	 */
	protected $global_token_data;

	/**
	 * Holds current page's Elementor settings.
	 *
	 * @since 1.4.0
	 * @var mixed
	 */
	protected $page_settings;

	/**
	 * Typography constructor.
	 */
	public function __construct() {
		$this->tokens       = Utils::get_kits();
		$this->settings_tab = Utils::get_kit_settings_tab();

		add_action( 'elementor/element/kit/section_buttons/after_section_end', array( $this, 'register_typography_sizes' ), 30, 2 );
		add_action( 'elementor/element/kit/section_buttons/after_section_end', array( $this, 'register_buttons' ), 40, 2 );
		add_action( 'elementor/element/after_section_end', array( $this, 'register_styling_settings' ), 20, 2 );
		add_action( 'elementor/element/kit/section_buttons/after_section_end', array( $this, 'register_tools' ), 270, 2 );

		// Legacy features ( Outer Section Padding & Column Gaps ) - deprecated to be removed.
		add_action( 'elementor/element/kit/section_buttons/after_section_end', array( $this, 'register_outer_section_padding' ), 280, 2 );
		add_action( 'elementor/element/kit/section_buttons/after_section_end', array( $this, 'register_columns_gap' ), 290, 2 );

		add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueue_preview_scripts' ) );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_editor_scripts' ), 999 );

		add_action( 'elementor/element/before_section_end', array( $this, 'update_padding_control_selector' ), 10, 2 );

		add_filter( 'display_post_states', array( $this, 'add_token_state' ), 10, 2 );

		add_action( 'elementor/element/section/section_layout/before_section_end', array( $this, 'tweak_section_widget' ) );
		add_action( 'elementor/element/section/section_advanced/before_section_end', array( $this, 'tweak_section_padding_control' ) );
		add_action( 'elementor/element/column/section_advanced/before_section_end', array( $this, 'tweak_column_element' ) );

		add_action( 'elementor/element/kit/section_settings-layout/before_section_end', array( $this, 'show_analog_container_spacing_hint' ), 10, 2 );
		add_action( 'elementor/element/kit/section_buttons/after_section_end', array( $this, 'register_container_spacing' ), 50, 2 );
		add_action( 'elementor/element/container/section_layout_container/before_section_end', array( $this, 'tweak_container_widget' ) );

		add_action( 'elementor/element/container/section_background/before_section_end', array( $this, 'tweak_container_widget_styles' ) );

		add_action( 'elementor/element/kit/section_typography/after_section_end', array( $this, 'tweak_typography_section' ), 999, 2 );

		add_action( 'elementor/element/kit/section_buttons/after_section_end', array( $this, 'register_global_fonts' ), 10, 2 );

		add_action( 'elementor/element/heading/section_title/after_section_end', array( $this, 'add_typo_helper_link' ), 999, 2 );
		add_action( 'elementor/element/button/section_button/after_section_end', array( $this, 'add_btn_sizes_helper_link' ), 999, 2 );

		add_action( 'elementor/element/kit/section_buttons/after_section_end', array( $this, 'register_shadows' ), 47, 2 );

		add_action( 'elementor/element/common/_section_border/before_section_end', array( $this, 'tweak_common_borders' ) );
		add_action( 'elementor/element/section/section_border/before_section_end', array( $this, 'tweak_section_column_borders' ) );
		add_action( 'elementor/element/column/section_border/before_section_end', array( $this, 'tweak_section_column_borders' ) );
		add_action( 'elementor/element/image/section_style_image/before_section_end', array( $this, 'tweak_image_borders' ) );

		add_action( 'elementor/element/container/section_border/before_section_end', array( $this, 'tweak_container_borders' ) );
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
				$control['selectors'] = array(
					'{{WRAPPER}} > .elementor-element-populated.elementor-element-populated.elementor-element-populated' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'(tablet) {{WRAPPER}} > .elementor-element-populated.elementor-element-populated.elementor-element-populated' => 'padding: {{padding_tablet.TOP}}{{padding_tablet.UNIT}} {{padding_tablet.RIGHT}}{{padding_tablet.UNIT}} {{padding_tablet.BOTTOM}}{{padding_tablet.UNIT}} {{padding_tablet.LEFT}}{{padding_tablet.UNIT}};',
					'(mobile) {{WRAPPER}} > .elementor-element-populated.elementor-element-populated.elementor-element-populated' => 'padding: {{padding_mobile.TOP}}{{padding_mobile.UNIT}} {{padding_mobile.RIGHT}}{{padding_mobile.UNIT}} {{padding_mobile.BOTTOM}}{{padding_mobile.UNIT}} {{padding_mobile.LEFT}}{{padding_mobile.UNIT}};',
				);

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
		if ( 'document_settings' !== $section_id ) {
			return;
		}

		$element->start_controls_section(
			'ang_headings_typography',
			array(
				'label' => __( 'Headings Typography', 'ang' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			)
		);

		$element->add_control(
			'ang_headings_typography_description',
			array(
				'raw'             => __( 'These settings apply to all Headings in your layout. You can still override individual values at each element.', 'ang' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		// TODO: Remove in v1.6.
		$selector = '{{WRAPPER}}';

		if ( method_exists( $element, 'get_main_id' ) ) {
			$main_id = $element->get_main_id();
			$type    = get_post_meta( $main_id, '_elementor_template_type', true );

			if ( 'popup' === $type ) {
				$selector = '.elementor-' . $main_id;
			}
		}

		for ( $i = 1; $i < 7; $i++ ) {
			$element->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'ang_heading_' . $i,
					/* translators: %s: Heading 1-6 type */
					'label'    => sprintf( __( 'Heading %s', 'ang' ), $i ),
					'selector' => "{$selector} h{$i}, {$selector} .elementor-widget-heading h{$i}.elementor-heading-title",
					'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				)
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
		if ( 'document_settings' !== $section_id ) {
			return;
		}

		$element->start_controls_section(
			'ang_body_and_paragraph_typography',
			array(
				'label' => __( 'Body Typography', 'ang' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			)
		);

		$element->add_control(
			'ang_recently_imported',
			array(
				'label'     => __( 'Recently Imported', 'ang' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => 'no',
				'selectors' => array(
					'{{WRAPPER}} .elementor-heading-title' => 'line-height: inherit;',
					'{{WRAPPER}} .dialog-message'          => 'font-size: inherit;',
				),
			)
		);

		$element->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'ang_body',
				'label'    => __( 'Body Typography', 'ang' ),
				'selector' => '{{WRAPPER}}',
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
			)
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
		$element->start_controls_section(
			'ang_typography_sizes',
			array(
				'label' => __( 'Typographic Sizes', 'ang' ),
				'tab'   => $this->settings_tab,
			)
		);

		/**
		 * Allowed controls for Heading/Text Sizes.
		 *
		 * @since 1.6.2
		 */
		$size_controls = apply_filters( 'analog_typographic_sizes_controls', array( 'font_family', 'font_weight', 'text_transform', 'text_decoration', 'font_style', 'letter_spacing' ) );

		$element->start_controls_tabs( 'ang_typgraphic_tabs' );

		$element->start_controls_tab(
			'ang_typographic_tab_heading',
			array(
				'label' => __( 'Heading Sizes', 'ang' ),
			)
		);

		$element->add_control(
			'ang_typography_sizes_description',
			array(
				'raw'             => __( 'Edit the available sizes for the Heading Element.', 'ang' ) . sprintf( ' <a href="%1$s" target="_blank">%2$s</a>', 'https://analogwp.com/docs/typographic-sizes/', __( 'Learn more.', 'ang' ) ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$settings = array(
			array( 'xxl', __( 'XXL', 'ang' ), 59 ),
			array( 'xl', __( 'XL', 'ang' ), 39 ),
			array( 'large', __( 'Large', 'ang' ), 29 ),
			array( 'medium', __( 'Medium', 'ang' ), 19 ),
			array( 'small', __( 'Small', 'ang' ), 15 ),
		);

		foreach ( $settings as $setting ) {
			$selectors = array(
				"{{WRAPPER}} h1.elementor-heading-title.elementor-size-{$setting[0]}",
				"{{WRAPPER}} h2.elementor-heading-title.elementor-size-{$setting[0]}",
				"{{WRAPPER}} h3.elementor-heading-title.elementor-size-{$setting[0]}",
				"{{WRAPPER}} h4.elementor-heading-title.elementor-size-{$setting[0]}",
				"{{WRAPPER}} h5.elementor-heading-title.elementor-size-{$setting[0]}",
				"{{WRAPPER}} h6.elementor-heading-title.elementor-size-{$setting[0]}",
			);
			$selectors = implode( ',', $selectors );

			$element->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'ang_size_' . $setting[0],
					'label'    => __( 'Heading', 'ang' ) . ' ' . $setting[1],
					'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'selector' => $selectors,
					'exclude'  => $size_controls,
				)
			);
		}

		$element->end_controls_tab();

		$element->start_controls_tab(
			'ang_typographic_tab_text',
			array(
				'label' => __( 'Text Sizes', 'ang' ),
			)
		);

		$element->add_control(
			'ang_text_sizes_description',
			array(
				'raw'             => __( 'Edit the available sizes for the p, span, and div tags of the Heading Element.', 'ang' ) . sprintf( ' <a href="%1$s" target="_blank">%2$s</a>', 'https://analogwp.com/docs/typographic-sizes/', __( 'Learn more.', 'ang' ) ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$settings = array(
			array( 'xxl', __( 'XXL', 'ang' ), 59 ),
			array( 'xl', __( 'XL', 'ang' ), 39 ),
			array( 'large', __( 'Large', 'ang' ), 29 ),
			array( 'medium', __( 'Medium', 'ang' ), 19 ),
			array( 'small', __( 'Small', 'ang' ), 15 ),
		);

		foreach ( $settings as $setting ) {
			$element->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'ang_text_size_' . $setting[0],
					'label'    => __( 'Text', 'ang' ) . ' ' . $setting[1],
					'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'selector' => "{{WRAPPER}} .elementor-widget-heading .elementor-heading-title.elementor-size-{$setting[0]}:not(h1):not(h2):not(h3):not(h4):not(h5):not(h6)",
					'exclude'  => $size_controls,
				)
			);
		}

		$element->end_controls_tab();

		$element->end_controls_tabs();

		$element->end_controls_section();
	}

	/**
	 * Register Container spacing controls.
	 *
	 * @param Controls_Stack $element Controls object.
	 * @param string         $section_id Section ID.
	 */
	public function register_container_spacing( Controls_Stack $element, $section_id ) {
		if ( ! Utils::is_elementor_container() ) { // Return early if Flexbox container is not active.
			return;
		}

		$element->start_controls_section(
			'ang_container_spacing',
			array(
				'label' => __( 'Container Spacing', 'ang' ),
				'tab'   => $this->settings_tab,
			)
		);

		$element->add_control(
			'ang_container_default_padding_hint',
			array(
				'raw'             => sprintf(
					'%1$s <a href="#" onClick="%2$s">%3$s</a>',
					__( 'The default container padding is set in Elementor Theme Styles > Layout Settings > ', 'ang' ),
					"analog.redirectToSection( 'settings-layout', 'section_settings-layout', 'global', true )",
					__( 'Container padding', 'ang' ),
				),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$element->add_control(
			'ang_container_padding_description',
			array(
				'raw'             => sprintf(
					'%1$s <a href="https://analogwp.com/docs/container-spacing/" target="_blank">%2$s</a>',
					__( 'Create additional spacing presets.', 'ang' ),
					__( 'Read more', 'ang' ),
				),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'separator'       => 'before',
			)
		);

		$no_padding_styles = array(
			'{{WRAPPER}} .elementor-repeater-item-ang_container_no_padding.elementor-element' => '--padding-block-start: 0px; --padding-inline-end: 0px; --padding-block-end: 0px; --padding-inline-start: 0px;',
		);

		$padding_preset_styles = array(
			'{{WRAPPER}} {{CURRENT_ITEM}}.elementor-element' => '--padding-block-start: {{TOP}}{{UNIT}}; --padding-inline-end: {{RIGHT}}{{UNIT}}; --padding-block-end: {{BOTTOM}}{{UNIT}}; --padding-inline-start: {{LEFT}}{{UNIT}}',
		);

		// Backwards compatibility with v3.15.3 and lower.
		if ( Utils::is_elementor_pre( '3.16.0' ) ) {
			$no_padding_styles = array(
				'{{WRAPPER}} .elementor-repeater-item-ang_container_no_padding.elementor-element' => '--padding-top: 0px; --padding-right: 0px; --padding-bottom: 0px; --padding-left: 0px;',
			);

			$padding_preset_styles = array(
				'{{WRAPPER}} {{CURRENT_ITEM}}.elementor-element' => '--padding-top: {{TOP}}{{UNIT}}; --padding-right: {{RIGHT}}{{UNIT}}; --padding-bottom: {{BOTTOM}}{{UNIT}}; --padding-left: {{LEFT}}{{UNIT}}',
			);
		}

		// Hack for adding no padding styles at container presets.
		$element->add_control(
			'ang_container_no_padding_hidden',
			array(
				'label'     => __( 'No Padding Styles', 'ang' ),
				'type'      => Controls_Manager::HIDDEN,
				'default'   => 'no',
				'selectors' => $no_padding_styles,
			)
		);

		$element->start_controls_tabs(
			'ang_container_spacing_tabs',
			array(
				'separator' => 'before',
			)
		);

		$element->start_controls_tab(
			'ang_tab_container_spacing_primary',
			array(
				'label' => __( '1-8', 'ang' ),
			)
		);

		$padding_defaults = array(
			array(
				'_id'            => 'ang_container_padding_1',
				'title'          => __( 'XXL - Hero Section', 'ang' ),
				'padding'        => array(
					'unit'     => 'px',
					'top'      => '80',
					'right'    => '24',
					'bottom'   => '80',
					'left'     => '24',
					'isLinked' => false,
				),
				'padding_tablet' => array(
					'unit'     => 'px',
					'top'      => '72',
					'right'    => '24',
					'bottom'   => '72',
					'left'     => '24',
					'isLinked' => false,
				),
				'padding_mobile' => array(
					'unit'     => 'px',
					'top'      => '64',
					'right'    => '24',
					'bottom'   => '64',
					'left'     => '24',
					'isLinked' => false,
				),
			),
			array(
				'_id'            => 'ang_container_padding_2',
				'title'          => __( 'XL - Primary Section', 'ang' ),
				'padding'        => array(
					'unit'     => 'px',
					'top'      => '64',
					'right'    => '24',
					'bottom'   => '64',
					'left'     => '24',
					'isLinked' => false,
				),
				'padding_tablet' => array(
					'unit'     => 'px',
					'top'      => '56',
					'right'    => '24',
					'bottom'   => '56',
					'left'     => '24',
					'isLinked' => false,
				),
				'padding_mobile' => array(
					'unit'     => 'px',
					'top'      => '40',
					'right'    => '24',
					'bottom'   => '40',
					'left'     => '24',
					'isLinked' => false,
				),
			),
			array(
				'_id'            => 'ang_container_padding_3',
				'title'          => __( 'Large - Box', 'ang' ),
				'padding'        => array(
					'unit'     => 'px',
					'top'      => '40',
					'right'    => '40',
					'bottom'   => '40',
					'left'     => '40',
					'isLinked' => true,
				),
				'padding_tablet' => array(
					'unit'     => 'px',
					'top'      => '32',
					'right'    => '32',
					'bottom'   => '32',
					'left'     => '32',
					'isLinked' => true,
				),
			),
			array(
				'_id'     => 'ang_container_padding_4',
				'title'   => __( 'Medium - Box', 'ang' ),
				'padding' => array(
					'unit'     => 'px',
					'top'      => '24',
					'right'    => '24',
					'bottom'   => '24',
					'left'     => '24',
					'isLinked' => true,
				),
			),
			array(
				'_id'     => 'ang_container_padding_5',
				'title'   => __( 'Small - Box', 'ang' ),
				'padding' => array(
					'unit'     => 'px',
					'top'      => '16',
					'right'    => '16',
					'bottom'   => '16',
					'left'     => '16',
					'isLinked' => true,
				),
			),
			array(
				'_id'   => 'ang_container_padding_6',
				'title' => __( 'Padding 6', 'ang' ),
			),
			array(
				'_id'   => 'ang_container_padding_7',
				'title' => __( 'Padding 7', 'ang' ),
			),
			array(
				'_id'   => 'ang_container_padding_8',
				'title' => __( 'Padding 8', 'ang' ),
			),
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			array(
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'required'    => true,
			)
		);

		// Padding Value.
		$repeater->add_responsive_control(
			'padding',
			array(
				'type'        => Controls_Manager::DIMENSIONS,
				'label_block' => true,
				'dynamic'     => array(),
				'default'     => array(
					'unit' => 'px',
				),
				'size_units'  => array( 'px', 'em', '%', 'rem', 'vw', 'custom' ),
				'selectors'   => $padding_preset_styles,
				'global'      => array(
					'active' => false,
				),
			)
		);

		$element->add_control(
			'ang_container_padding',
			array(
				'type'         => Global_Style_Repeater::CONTROL_TYPE,
				'fields'       => $repeater->get_controls(),
				'default'      => $padding_defaults,
				'item_actions' => array(
					'add'       => false,
					'remove'    => false,
					'sort'      => false,
					'duplicate' => false,
				),
			)
		);

		$element->end_controls_tab();

		do_action( 'analog_container_spacing_tabs_end', $element, $repeater );

		$element->end_controls_tabs();

		do_action( 'analog_container_spacing_section_end', $element, $repeater );

		$element->add_control(
			'ang_container_padding_reset',
			array(
				'label' => __( 'Reset labels and values to default', 'ang' ),
				'type'  => 'button',
				'text'  => __( 'Reset', 'ang' ),
				'event' => 'analog:resetContainerPadding',
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Show hint for Style Kit Container spacing presets.
	 *
	 * @param Controls_Stack $element Controls object.
	 * @param string         $section_id Section ID.
	 */
	public function show_analog_container_spacing_hint( Controls_Stack $element, $section_id ) {
		$element->start_injection(
			array(
				'of' => 'container_padding',
				'at' => 'after',
			)
		);

		$element->add_control(
			'analog_container_padding_hint',
			array(
				'raw'             => sprintf(
					'%1$s <a href="#" onClick="%2$s">%3$s</a>',
					__( 'Create additional spacing presets in ', 'ang' ),
					"analog.redirectToSection( 'theme-style-kits', 'ang_container_spacing', 'global', true );",
					__( 'Style Kits', 'ang' ),
				),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'separator'       => 'before',
			)
		);

		$element->add_control(
			'analog_container_padding_hint_separator',
			array(
				'type'  => Controls_Manager::DIVIDER,
				'style' => 'thick',
			)
		);

		$element->end_injection();
	}

	/**
	 * Register Outer Section padding controls.
	 *
	 * @param Controls_Stack $element Controls object.
	 * @param string         $section_id Section ID.
	 */
	public function register_outer_section_padding( Controls_Stack $element, $section_id ) {
		$gaps = array(
			'initial'  => __( 'Default', 'ang' ),
			'default'  => __( 'Normal', 'ang' ),
			'narrow'   => __( 'Small', 'ang' ),
			'extended' => __( 'Medium', 'ang' ),
			'wide'     => __( 'Large', 'ang' ),
			'wider'    => __( 'Extra Large', 'ang' ),
		);

		$element->start_controls_section(
			'ang_section_padding',
			array(
				'label' => __( 'Outer Section Padding', 'ang' ),
				'tab'   => $this->settings_tab,
			)
		);

		$element->add_control(
			'ang_section_padding_description',
			array(
				'raw'             => __( 'Add padding to the outer sections of your layouts by using these controls.', 'ang' ) . sprintf( ' <a href="%1$s" target="_blank">%2$s</a>', 'https://analogwp.com/docs/outer-section-padding/', __( 'Learn more.', 'ang' ) ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		foreach ( $gaps as $key => $label ) {
			$element->add_responsive_control(
				'ang_section_padding_' . $key,
				array(
					'label'      => $label,
					'type'       => Controls_Manager::DIMENSIONS,
					'default'    => array(
						'unit' => 'em',
					),
					'size_units' => array( 'px', 'em', '%', 'rem', 'vw', 'custom' ),
					'selectors'  => array(
						"{{WRAPPER}} .ang-section-padding-{$key}.elementor-top-section" =>
						'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					),
				)
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
		$gaps = array(
			'default'  => __( 'Default Padding', 'ang' ),
			'narrow'   => __( 'Narrow Padding', 'ang' ),
			'extended' => __( 'Extended Padding', 'ang' ),
			'wide'     => __( 'Wide Padding', 'ang' ),
			'wider'    => __( 'Wider Padding', 'ang' ),
		);

		$element->start_controls_section(
			'ang_column_gaps',
			array(
				'label' => __( 'Column Gaps', 'ang' ),
				'tab'   => $this->settings_tab,
			)
		);

		$element->add_control(
			'ang_column_gaps_description',
			array(
				'raw'             => __( 'Column Gap presets add padding to the columns of a section.', 'ang' ) . sprintf( ' <a href="%1$s" target="_blank">%2$s</a>', 'https://analogwp.com/docs/column-gaps/', __( 'Learn more.', 'ang' ) ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$elementor_row   = '';
		$optimized_dom   = get_option( 'elementor_experiment-e_dom_optimization' );
		$is_optimize_dom = \Elementor\Core\Experiments\Manager::STATE_ACTIVE === $optimized_dom;

		if ( 'default' === $optimized_dom ) {
			$experiments     = new \Elementor\Core\Experiments\Manager();
			$is_optimize_dom = $experiments->is_feature_active( 'e_dom_optimization' );
		}

		if ( ! $is_optimize_dom ) { // Add row class if DOM optimization is not active.
			$elementor_row = ' > .elementor-row ';
		}

		foreach ( $gaps as $key => $label ) {
			$element->add_responsive_control(
				'ang_column_gap_' . $key,
				array(
					'label'      => $label,
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%', 'rem', 'vw', 'custom' ),
					'selectors'  => array(
						"{{WRAPPER}} .elementor-column-gap-{$key} {$elementor_row} > .elementor-column > .elementor-element-populated"
						=> 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					),
				)
			);
		}

		$element->add_responsive_control(
			'ang_widget_spacing',
			array(
				'label'       => __( 'Space Between Widgets', 'ang' ),
				'description' => __( 'Sets the default space between widgets, overrides the default value set in Elementor > Style > Space Between Widgets.', 'ang' ),
				'type'        => Controls_Manager::NUMBER,
				'selectors'   => array(
					'{{WRAPPER}} .elementor-widget:not(:last-child)' => 'margin-bottom: {{VALUE}}px',
				),
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Register Buttons controls.
	 *
	 * @param Controls_Stack $element Controls object.
	 * @param string         $section_id Section ID.
	 * @since 1.3
	 */
	public function register_buttons( Controls_Stack $element, $section_id ) {
		$element->start_controls_section(
			'ang_buttons',
			array(
				'label' => __( 'Button Sizes', 'ang' ),
				'tab'   => $this->settings_tab,
			)
		);

		$element->add_control(
			'ang_buttons_description',
			array(
				'raw'             => __( 'Define the default styles for every button size.', 'ang' ) . sprintf( ' <a href="%1$s" target="_blank">%2$s</a>', 'https://analogwp.com/docs/button-sizes/', __( 'Learn more.', 'ang' ) ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$sizes = array(
			'xs' => __( 'XS', 'ang' ),
			'sm' => __( 'S', 'ang' ),
			'md' => __( 'M', 'ang' ),
			'lg' => __( 'L', 'ang' ),
			'xl' => __( 'XL', 'ang' ),
		);

		$element->start_controls_tabs( 'ang_button_sizes' );

		foreach ( $sizes as $size => $label ) {
			$element->start_controls_tab( 'ang_button_' . $size, array( 'label' => $label ) );

			$element->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'ang_button_' . $size,
					'label'    => __( 'Typography', 'ang' ),
					'selector' => "{{WRAPPER}} .elementor-button.elementor-size-{$size}",
				)
			);

			$element->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				array(
					'name'     => 'ang_button_text_shadow_' . $size,
					'selector' => "{{WRAPPER}} a.elementor-button.elementor-size-{$size}, {{WRAPPER}} .elementor-button.elementor-size-{$size}",
				)
			);

			$element->add_control(
				'ang_normal_state_' . $size,
				array(
					'type'      => Controls_Manager::HEADING,
					'label'     => __( 'Normal Styling', 'ang' ),
					'separator' => 'before',
				)
			);

			$element->add_control(
				'ang_button_text_color_' . $size,
				array(
					'label'     => __( 'Text Color', 'ang' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						"{{WRAPPER}} a.elementor-button.elementor-size-{$size}, {{WRAPPER}} .elementor-button.elementor-size-{$size}" => 'color: {{VALUE}};',
					),
				)
			);

			$element->add_control(
				'ang_button_background_color_' . $size,
				array(
					'label'     => __( 'Background Color', 'ang' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						"{{WRAPPER}} a.elementor-button.elementor-size-{$size}, {{WRAPPER}} .elementor-button.elementor-size-{$size}" => 'background-color: {{VALUE}};',
					),
				)
			);

			$element->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'ang_button_box_shadow_' . $size,
					'selector' => "{{WRAPPER}} .elementor-button.elementor-size-{$size}",
				)
			);

			$element->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'ang_button_border_' . $size,
					'selector' => "{{WRAPPER}} .elementor-button.elementor-size-{$size}",
				)
			);

			$element->add_control(
				'ang_button_border_radius_' . $size,
				array(
					'label'      => __( 'Border Radius', 'ang' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%', 'rem', 'custom' ),
					'selectors'  => array(
						"{{WRAPPER}} a.elementor-button.elementor-size-{$size}, {{WRAPPER}} .elementor-button.elementor-size-{$size}" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$element->add_control(
				'ang_hover_state_' . $size,
				array(
					'type'      => Controls_Manager::HEADING,
					'label'     => __( 'Hover Styling', 'ang' ),
					'separator' => 'before',
				)
			);

			$element->add_control(
				'ang_button_text_hover_color_' . $size,
				array(
					'label'     => __( 'Text Color', 'ang' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						"{{WRAPPER}} a.elementor-button.elementor-size-{$size}:hover, {{WRAPPER}} .elementor-button.elementor-size-{$size}:hover, {{WRAPPER}} a.elementor-button.elementor-size-{$size}:focus, {{WRAPPER}} .elementor-button.elementor-size-{$size}:focus" => 'color: {{VALUE}};',
					),
				)
			);

			$element->add_control(
				'ang_button_background_hover_color_' . $size,
				array(
					'label'     => __( 'Background Color', 'ang' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						"{{WRAPPER}} a.elementor-button.elementor-size-{$size}:hover, {{WRAPPER}} .elementor-button.elementor-size-{$size}:hover, {{WRAPPER}} a.elementor-button.elementor-size-{$size}:focus, {{WRAPPER}} .elementor-button.elementor-size-{$size}:focus" => 'background-color: {{VALUE}};',
					),
				)
			);

			$element->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'ang_button_box_shadow_hover_' . $size,
					'selector' => "{{WRAPPER}} .elementor-button.elementor-size-{$size}:hover, {{WRAPPER}} .elementor-button.elementor-size-{$size}:focus",
				)
			);

			$element->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'ang_button_border_hover_' . $size,
					'selector' => "{{WRAPPER}} .elementor-button.elementor-size-{$size}:hover, {{WRAPPER}} .elementor-button.elementor-size-{$size}:focus",
				)
			);

			$element->add_control(
				'ang_button_border_radius_hover_' . $size,
				array(
					'label'      => __( 'Border Radius', 'ang' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%', 'rem', 'custom' ),
					'selectors'  => array(
						"{{WRAPPER}} a.elementor-button.elementor-size-{$size}:hover, {{WRAPPER}} .elementor-button.elementor-size-{$size}:hover" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						"{{WRAPPER}} a.elementor-button.elementor-size-{$size}:focus, {{WRAPPER}} .elementor-button.elementor-size-{$size}:focus" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$element->add_responsive_control(
				'ang_button_padding_' . $size,
				array(
					'label'      => __( 'Padding', 'ang' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%', 'rem', 'vw', 'custom' ),
					'selectors'  => array(
						"{{WRAPPER}} a.elementor-button.elementor-size-{$size}, {{WRAPPER}} .elementor-button.elementor-size-{$size}" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'separator'  => 'before',
				)
			);

			$element->end_controls_tab();
		}

		$element->end_controls_tabs();

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
		if ( 'document_settings' !== $section_id ) {
			return;
		}

		$id = get_the_ID();
		if ( $id ) {
			$document = Plugin::elementor()->documents->get_doc_or_auto_save( $id );
			$config   = is_object( $document ) ? $document::get_editor_panel_config() : array();

			if ( isset( $config['support_kit'] ) && ! $config['support_kit'] ) {
				return;
			}
		}

		$element->start_controls_section(
			'ang_style_settings',
			array(
				'label' => __( 'Style Kits', 'ang' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			)
		);

		$element->add_control(
			'description_ang_global_stylekit',
			array(
				'raw'             => sprintf(
									/* translators: %s: Link to Style Kits */
					'<p>%1$s <a href="https://analogwp.com/docs/overriding-global-style-kit/" target="_blank">%2$s</a></p>',
					__( 'Select a different Style Kit to be applied on this page. The page will reload after your selection.', 'ang' ),
					__( 'Learn more', 'ang' )
				),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$element->add_control(
			'ang_action_tokens',
			array(
				'label'          => __( 'Select Style Kit', 'ang' ) . $this->get_tooltip( __( 'This will override your site\'s Global Style Kit for this page.', 'ang' ) ),
				'type'           => Controls_Manager::SELECT2,
				'select2options' => array(
					'allowClear' => false,
				),
				'options'        => $this->tokens,
				'default'        => get_option( 'elementor_active_kit' ),
			)
		);

		$element->add_control(
			'ang_updated_token',
			array(
				'label'   => __( 'Page Style Kit', 'ang' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => '',
			)
		);

		$element->add_control(
			'description_ang_stylekit_docs',
			array(
				'raw'  => sprintf(
					/* translators: %s: Link to Style Kits */
					'<p class="ang-notice description"><a href="%1$s" target="_blank">%2$s</a></p>',
					admin_url( 'admin.php?page=style-kits' ),
					__( 'Set your Global Style Kit here', 'ang' ),
				),
				'type' => Controls_Manager::RAW_HTML,
			)
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
		$element->start_controls_section(
			'ang_tools',
			array(
				'label' => __( 'Manage Style Kit', 'ang' ),
				'tab'   => $this->settings_tab,
			)
		);

		$label = __( 'This will reset the Theme Style Kit and clean up any values.', 'ang' );
		$element->add_control(
			'ang_action_reset',
			array(
				'label' => __( 'Reset Theme Style Kit', 'ang' ) . $this->get_tooltip( $label ),
				'type'  => 'button',
				'text'  => __( 'Reset', 'ang' ),
				'event' => 'analog:resetKit',
			)
		);

		$label = __( 'Save the current styles as a different Theme Style Kit. You can then apply it on other pages, or globally.', 'ang' );
		$element->add_control(
			'ang_action_save_token',
			array(
				'label' => __( 'Clone Style Kit', 'ang' ) . $this->get_tooltip( $label ),
				'type'  => 'button',
				'text'  => __( 'Clone', 'ang' ),
				'event' => 'analog:saveKit',
			)
		);

		$element->add_control(
			'ang_action_export_css',
			array(
				'label' => __( 'Export Theme Style Kit CSS', 'ang' ),
				'type'  => 'button',
				'text'  => __( 'Export', 'ang' ),
				'event' => 'analog:exportCSS',
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Tweak default Section widget.
	 *
	 * @param Element_Base $element Element_Base Class.
	 */
	public function tweak_section_widget( Element_Base $element ) {
		$element->start_injection(
			array(
				'of' => 'height',
				'at' => 'before',
			)
		);

		$post_id = get_the_ID();
		$default = 'initial';

		if ( $post_id ) {
			$settings = get_post_meta( $post_id, '_elementor_page_settings', true );

			if ( isset( $settings['ang_action_tokens'] ) && '' !== $settings['ang_action_tokens'] ) {
				$kit_osp = Utils::get_kit_settings( $settings['ang_action_tokens'], 'ang_default_section_padding' );

				if ( $kit_osp && '' !== $kit_osp ) {
					$default = $kit_osp;
				}
			}
		}

		$element->add_control(
			'ang_outer_gap',
			array(
				'label'         => __( 'Outer Section Padding', 'ang' ),
				'description'   => sprintf( '<a href="#" class="ang-notice blue" onClick="%1$s">%2$s</a>', "analog.redirectToPanel( 'ang_section_padding' )", 'Edit in Style Kits' ),
				'type'          => Controls_Manager::SELECT,
				'hide_in_inner' => true,
				'default'       => $default,
				'options'       => array(
					'initial'  => __( 'Default', 'ang' ),
					'no'       => __( 'No Padding', 'ang' ),
					'default'  => __( 'Normal', 'ang' ),
					'narrow'   => __( 'Small', 'ang' ),
					'extended' => __( 'Medium', 'ang' ),
					'wide'     => __( 'Large', 'ang' ),
					'wider'    => __( 'Extra Large', 'ang' ),
				),
				'prefix_class'  => 'ang-section-padding-',
			)
		);

		$element->end_injection();
	}

	/**
	 * Tweak default Column Element to have higher specificity than Column Gaps in TS.
	 *
	 * @since 1.6.6
	 *
	 * @param Element_Base $element Element_Base Class.
	 */
	public function tweak_column_element( Element_Base $element ) {
		$element->update_responsive_control(
			'padding',
			array(
				'selectors' => array(
					'{{WRAPPER}} > .elementor-element-populated.elementor-element-populated.elementor-element-populated' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
	}

	/**
	 * Tweak default Section Element's padding control to have higher specificity than OSP in TS.
	 *
	 * @since 1.6.8
	 *
	 * @param Element_Base $element Element_Base Class.
	 */
	public function tweak_section_padding_control( Element_Base $element ) {
		$element->update_responsive_control(
			'padding',
			array(
				'selectors' => array(
					'{{WRAPPER}}.elementor-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
	}

	/**
	 * Tweak default Container widget.
	 *
	 * @param Element_Base $element Element_Base Class.
	 */
	public function tweak_container_widget( Element_Base $element ) {

		if ( ! Utils::is_elementor_container() ) { // Return early if Flexbox container is not active.
			return;
		}

		$element->start_injection(
			array(
				'of' => 'content_width',
				'at' => 'after',
			)
		);

		// Register default options array.
		$options = array(
			'default_padding'          => __( 'Default', 'ang' ),
			'ang_container_no_padding' => __( 'No Padding', 'ang' ),
		);

		/**
		 * Get current kit settings.
		 */
		$kit = Utils::get_document_kit( get_the_ID() );

		if ( $kit ) {
			$controls = array(
				'ang_container_padding',
				'ang_container_padding_secondary',
				'ang_container_padding_tertiary',
				'ang_custom_container_padding',
			);

			// Use raw settings that doesn't have default values.
			$kit_raw_settings = $kit->get_data( 'settings' );

			foreach ( $controls as $control ) {
				// Get SK Container padding preset labels.
				if ( isset( $kit_raw_settings[ $control ] ) ) {
					$padding_items = $kit_raw_settings[ $control ];
				} else {
					// Get default items, but without empty defaults.
					$control       = $kit->get_controls( $control );
					$padding_items = $control['default'] ?? array();
				}

				if ( ! empty( $padding_items ) ) {
					foreach ( $padding_items as $padding ) {
						if ( isset( $padding['padding'] ) || isset( $padding['padding_tablet'] ) || isset( $padding['padding_mobile'] ) ) {
							$options[ $padding['_id'] ] = $padding['title'];
						}
					}
				}
			}
		}

		$element->add_control(
			'ang_container_spacing_size',
			array(
				'label'              => __( 'Spacing Preset', 'ang' ),
				'description'        => sprintf( '<a href="#" class="ang-notice blue" onClick="%1$s">%2$s</a>', "analog.redirectToPanel( 'ang_container_spacing' )", __( 'Edit in Style Kits', 'ang' ) ),
				'type'               => Controls_Manager::SELECT,
				'hide_in_inner'      => false,
				'default'            => 'default_padding',
				'options'            => $options,
				'prefix_class'       => 'elementor-repeater-item-',
				'frontend_available' => true,
			)
		);

		$element->end_injection();
	}

	/**
	 * Tweak Container widget for SK BG classes preset.
	 *
	 * @param Element_Base $element Element_Base Class.
	 */
	public function tweak_container_widget_styles( Element_Base $element ) {
		if ( ! Utils::is_elementor_container() ) { // Return early if Flexbox container is not active.
			return;
		}

		$element->start_injection(
			array(
				'of' => 'background_background',
				'at' => 'after',
			)
		);

		$bg_presets = array(
			'none'     => __( 'None', 'ang' ),
			'light-bg' => __( 'Light Background', 'ang' ),
			'dark-bg'  => __( 'Dark background', 'ang' ),
		);

		if ( Utils::has_pro() ) {
			$bg_presets['accent-bg'] = __( 'Accent Background', 'ang' );
		}

		$element->add_control(
			'ang_container_bg_preset',
			array(
				'label'         => __( 'Background presets', 'ang' ),
				'description'   => sprintf( '<a href="#" class="ang-notice blue" onClick="%1$s">%2$s</a>', 'analog.openThemeStyles()', 'Edit in Style Kits' ),
				'type'          => Controls_Manager::SELECT,
				'hide_in_inner' => false,
				'default'       => 'none',
				'options'       => $bg_presets,
				'prefix_class'  => 'sk-',
				'condition'     => array(
					'background_background' => array( 'classic' ),
				),
			)
		);

		$element->end_injection();
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
			array(
				'ang_heading_1',
				'ang_heading_2',
				'ang_heading_3',
				'ang_heading_4',
				'ang_heading_5',
				'ang_heading_6',
				'ang_default_heading',
				'ang_body',
				'ang_paragraph',
			)
		);

		$font_families = array();

		foreach ( $keys as $key ) {
			$font_families[] = $page_settings_model->get_settings( $key . '_font_family' );
		}

		// Remove duplicate and null values.
		$font_families = \array_unique( \array_filter( $font_families ) );

		if ( count( $font_families ) ) {
			wp_enqueue_style(
				'ang_typography_fonts',
				'https://fonts.googleapis.com/css?family=' . implode( ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic|', $font_families ),
				array(),
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
			array(
				'jquery',
				'editor',
			),
			filemtime( ANG_PLUGIN_DIR . "inc/elementor/js/ang-typography{$script_suffix}.js" ),
			true
		);
	}

	/**
	 * Get default value for specific control.
	 *
	 * @param string $key Setting ID.
	 * @param bool   $is_array Whether provided key includes set of array.
	 *
	 * @deprecated 1.6.0
	 *
	 * @return array|string
	 */
	public function get_default_value( $key, $is_array = false ) {
		$recently_imported = $this->page_settings;

		if ( isset( $recently_imported['ang_recently_imported'] ) && 'yes' === $recently_imported['ang_recently_imported'] ) {
			return ( $is_array ) ? array() : '';
		}

		$values = $this->global_token_data;

		if ( count( $values ) && isset( $values[ $key ] ) && '' !== $values[ $key ] ) {
			return $values[ $key ];
		}

		return ( $is_array ) ? array() : '';
	}

	/**
	 * Get default values for Typography group control.
	 *
	 * @param string $key Setting ID.
	 *
	 * @deprecated 1.6.0
	 *
	 * @return array
	 */
	public function get_default_typography_values( $key ) {
		$recently_imported = $this->page_settings;

		if ( isset( $recently_imported['ang_recently_imported'] ) && 'yes' === $recently_imported['ang_recently_imported'] ) {
			return array();
		}

		if ( empty( $this->global_token_data ) ) {
			return array();
		}

		return array(
			'typography'            => array(
				'default' => $this->get_default_value( $key . '_typography' ),
			),
			'font_size'             => array(
				'default' => $this->get_default_value( $key . '_font_size', true ),
			),
			'font_size_tablet'      => array(
				'default' => $this->get_default_value( $key . '_font_size_tablet', true ),
			),
			'font_size_mobile'      => array(
				'default' => $this->get_default_value( $key . '_font_size_mobile', true ),
			),
			'line_height'           => array(
				'default' => $this->get_default_value( $key . '_line_height', true ),
			),
			'line_height_mobile'    => array(
				'default' => $this->get_default_value( $key . '_line_height_mobile', true ),
			),
			'line_height_tablet'    => array(
				'default' => $this->get_default_value( $key . '_line_height_tablet', true ),
			),
			'letter_spacing'        => array(
				'default' => $this->get_default_value( $key . '_letter_spacing', true ),
			),
			'letter_spacing_mobile' => array(
				'default' => $this->get_default_value( $key . '_letter_spacing_mobile', true ),
			),
			'letter_spacing_tablet' => array(
				'default' => $this->get_default_value( $key . '_letter_spacing_tablet', true ),
			),
			'font_family'           => array(
				'default' => $this->get_default_value( $key . '_font_family' ),
			),
			'font_weight'           => array(
				'default' => $this->get_default_value( $key . '_font_weight' ),
			),
			'text_transform'        => array(
				'default' => $this->get_default_value( $key . '_text_transform' ),
			),
			'font_style'            => array(
				'default' => $this->get_default_value( $key . '_font_style' ),
			),
			'text_decoration'       => array(
				'default' => $this->get_default_value( $key . '_text_decoration' ),
			),
		);
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
		$global_token = (int) Utils::get_global_kit_id();
		if ( $global_token && $post->ID === $global_token ) {
			$post_states[] = '<span style="color:#32b644;">&#9679; Global Style Kit</span>';
		}

		return $post_states;
	}

	/**
	 * Tweak default Section widget.
	 *
	 * @since 1.6.0
	 * @param Element_Base $element Class.
	 */
	public function tweak_typography_section( $element ) {
		$element->start_injection(
			array(
				'of' => 'h1_heading',
				'at' => 'before',
			)
		);

		$default_fonts = Plugin::elementor()->kits_manager->get_current_settings( 'default_generic_fonts' );

		if ( $default_fonts ) {
			$default_fonts = ', ' . $default_fonts;
		}

		$element->add_control(
			'ang_heading_color_heading',
			array(
				'label'     => __( 'Headings', 'ang' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$element->add_control(
			'ang_color_heading',
			array(
				'label'     => __( 'Headings Color', 'ang' ),
				'type'      => Controls_Manager::COLOR,
				'variable'  => 'ang_color_heading',
				'selectors' => array(
					'{{WRAPPER}}' => '--ang_color_heading: {{VALUE}};',
					'{{WRAPPER}} h1, {{WRAPPER}} h2, {{WRAPPER}} h3, {{WRAPPER}} h4, {{WRAPPER}} h5, {{WRAPPER}} h6' => 'color: {{VALUE}}',
				),
			)
		);

		$element->add_control(
			'ang_default_heading_font_family',
			array(
				'label'     => __( 'Headings Font', 'ang' ),
				'type'      => Controls_Manager::FONT,
				'selectors' => array(
					'{{WRAPPER}} h1, {{WRAPPER}} h2, {{WRAPPER}} h3, {{WRAPPER}} h4, {{WRAPPER}} h5, {{WRAPPER}} h6' => 'font-family: "{{VALUE}}"' . $default_fonts . ';',
				),
			)
		);

		$element->add_control(
			'ang_description_default_heading',
			array(
				'raw'             => __( 'You can set individual heading font and colors below.', 'ang' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$element->end_injection();
	}

	/**
	 * Register Style Kits Global Font controls.
	 *
	 * @param Controls_Stack $element Controls object.
	 * @param string         $section_id Section ID.
	 */
	public function register_global_fonts( Controls_Stack $element, $section_id ) {
		$element->start_controls_section(
			'ang_global_fonts_section',
			array(
				'label' => esc_html__( 'Style Kit Fonts', 'ang' ),
				'tab'   => 'global-typography',
			)
		);

		$element->add_control(
			'ang_global_fonts_description',
			array(
				'raw'             => sprintf(
					'%1$s <a href="https://analogwp.com/docs/style-kit-global-fonts/" target="_blank">%2$s</a>',
					__( 'The Style Kit\'s typographic styles.', 'ang' ),
					__( 'Read more', 'ang' ),
				),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$element->start_controls_tabs(
			'ang_global_fonts_section_tabs',
			array(
				'separator' => 'before',
			)
		);

		$element->start_controls_tab(
			'ang_tab_global_fonts_primary',
			array( 'label' => __( '1-16', 'ang' ) )
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			array(
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'required'    => true,
			)
		);

		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'typography',
				'label'          => '',
				'global'         => array(
					'active' => false,
				),
				'fields_options' => array(
					'font_family'     => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--e-global-typography-{{external._id.VALUE}}-font-family: "{{VALUE}}"',
						),
					),
					'font_size'       => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--e-global-typography-{{external._id.VALUE}}-font-size: {{SIZE}}{{UNIT}}',
						),
					),
					'font_weight'     => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--e-global-typography-{{external._id.VALUE}}-font-weight: {{VALUE}}',
						),
					),
					'text_transform'  => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--e-global-typography-{{external._id.VALUE}}-text-transform: {{VALUE}}',
						),
					),
					'font_style'      => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--e-global-typography-{{external._id.VALUE}}-font-style: {{VALUE}}',
						),
					),
					'text_decoration' => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--e-global-typography-{{external._id.VALUE}}-text-decoration: {{VALUE}}',
						),
					),
					'line_height'     => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--e-global-typography-{{external._id.VALUE}}-line-height: {{SIZE}}{{UNIT}}',
						),
					),
					'letter_spacing'  => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--e-global-typography-{{external._id.VALUE}}-letter-spacing: {{SIZE}}{{UNIT}}',
						),
					),
					'word_spacing'    => array(
						'selectors' => array(
							'{{SELECTOR}}' => '--e-global-typography-{{external._id.VALUE}}-word-spacing: {{SIZE}}{{UNIT}}',
						),
					),
				),
			)
		);

		$title_typography = array(
			array(
				'_id'                   => 'sk_type_1',
				'title'                 => esc_html__( 'Display title', 'ang' ),
				'typography_typography' => 'custom',
				'typography_font_size'  => array(
					'size' => 5,
					'unit' => 'em',
				),
			),
			array(
				'_id'                   => 'sk_type_2',
				'title'                 => esc_html__( 'Title 1', 'ang' ),
				'typography_typography' => 'custom',
				'typography_font_size'  => array(
					'size' => 4,
					'unit' => 'em',
				),
			),
			array(
				'_id'                   => 'sk_type_3',
				'title'                 => esc_html__( 'Title 2', 'ang' ),
				'typography_typography' => 'custom',
				'typography_font_size'  => array(
					'size' => 3,
					'unit' => 'em',
				),
			),
			array(
				'_id'                   => 'sk_type_4',
				'title'                 => esc_html__( 'Title 3', 'ang' ),
				'typography_typography' => 'custom',
				'typography_font_size'  => array(
					'size' => 2,
					'unit' => 'em',
				),
			),
			array(
				'_id'                   => 'sk_type_5',
				'title'                 => esc_html__( 'Title 4', 'ang' ),
				'typography_typography' => 'custom',
				'typography_font_size'  => array(
					'size' => 1.5,
					'unit' => 'em',
				),
			),
			array(
				'_id'                   => 'sk_type_6',
				'title'                 => esc_html__( 'Title 5', 'ang' ),
				'typography_typography' => 'custom',
				'typography_font_size'  => array(
					'size' => 1.2,
					'unit' => 'em',
				),
			),
			array(
				'_id'                   => 'sk_type_7',
				'title'                 => esc_html__( 'Title 6', 'ang' ),
				'typography_typography' => 'custom',
				'typography_font_size'  => array(
					'size' => 1,
					'unit' => 'em',
				),
			),
			array(
				'_id'                   => 'sk_type_8',
				'title'                 => esc_html__( 'Overline / Subheader', 'ang' ),
				'typography_typography' => 'custom',
				'typography_font_size'  => array(
					'size' => 0.8,
					'unit' => 'em',
				),
			),
		);

		$element->add_control(
			'ang_global_title_fonts',
			array(
				'type'         => Global_Style_Repeater::CONTROL_TYPE,
				'fields'       => $repeater->get_controls(),
				'default'      => $title_typography,
				'item_actions' => array(
					'add'    => false,
					'remove' => false,
					'sort'   => false,
				),
			)
		);

		$text_typography = array(
			array(
				'_id'                   => 'sk_type_9',
				'title'                 => esc_html__( 'Display text', 'ang' ),
				'typography_typography' => 'custom',
				'typography_font_size'  => array(
					'size' => 2,
					'unit' => 'em',
				),
			),
			array(
				'_id'                   => 'sk_type_10',
				'title'                 => esc_html__( 'Large text', 'ang' ),
				'typography_typography' => 'custom',
				'typography_font_size'  => array(
					'size' => 1.5,
					'unit' => 'em',
				),
			),
			array(
				'_id'                   => 'sk_type_11',
				'title'                 => esc_html__( 'Normal text', 'ang' ),
				'typography_typography' => 'custom',
				'typography_font_size'  => array(
					'size' => 1,
					'unit' => 'em',
				),
			),
			array(
				'_id'                   => 'sk_type_12',
				'title'                 => esc_html__( 'Small text', 'ang' ),
				'typography_typography' => 'custom',
				'typography_font_size'  => array(
					'size' => 0.95,
					'unit' => 'em',
				),
			),
			array(
				'_id'                   => 'sk_type_13',
				'title'                 => esc_html__( 'Caption', 'ang' ),
				'typography_typography' => 'custom',
				'typography_font_size'  => array(
					'size' => 0.8,
					'unit' => 'em',
				),
			),
			array(
				'_id'                   => 'sk_type_14',
				'title'                 => esc_html__( 'Button text', 'ang' ),
				'typography_typography' => 'custom',
				'typography_font_size'  => array(
					'size' => 1,
					'unit' => 'em',
				),
			),
			array(
				'_id'                   => 'sk_type_15',
				'title'                 => esc_html__( 'Form label', 'ang' ),
				'typography_typography' => 'custom',
				'typography_font_size'  => array(
					'size' => 1,
					'unit' => 'em',
				),
			),
			array(
				'_id'                   => 'sk_type_16',
				'title'                 => esc_html__( 'Font Style 16', 'ang' ),
				'typography_typography' => 'custom',
			),
		);

		$element->add_control(
			'ang_global_text_fonts',
			array(
				'type'         => Global_Style_Repeater::CONTROL_TYPE,
				'fields'       => $repeater->get_controls(),
				'default'      => $text_typography,
				'item_actions' => array(
					'add'    => false,
					'remove' => false,
					'sort'   => false,
				),
				'separator'    => 'before',
			)
		);

		$element->end_controls_tab();

		do_action( 'analog_global_fonts_tab_end', $element, $repeater );

		$element->end_controls_tabs();

		$element->add_control(
			'ang_global_reset_fonts',
			array(
				'label' => __( 'Reset labels & fonts', 'ang' ),
				'type'  => 'button',
				'text'  => __( 'Reset', 'ang' ),
				'event' => 'analog:resetGlobalFonts',
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Register Global Shadow controls.
	 *
	 * @param Controls_Stack $element Controls object.
	 * @param string         $section_id Section ID.
	 */
	public function register_shadows( Controls_Stack $element, $section_id ) {
		$element->start_controls_section(
			'ang_shadows',
			array(
				'label' => __( 'Shadows', 'ang' ),
				'tab'   => Utils::get_kit_settings_tab(),
			)
		);

		$element->add_control(
			'ang_shadows_description',
			array(
				'raw'             => sprintf(
					'%1$s <a href="https://analogwp.com/docs/global-shadows/" target="_blank">%2$s</a>',
					__( 'Add global shadow presets by using these controls.', 'ang' ),
					__( 'Learn more', 'ang' ),
				),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$element->start_controls_tabs( 'ang_shadows_tabs' );

		$element->start_controls_tab(
			'ang_tab_shadows_primary',
			array(
				'label' => __( '1-8', 'ang' ),
			)
		);

		$shadow_defaults = array(
			array(
				'_id'   => 'shadow_1',
				'title' => __( 'Shadow 1', 'ang' ),
			),
			array(
				'_id'                    => 'shadow_2',
				'title'                  => __( 'Shadow 2', 'ang' ),
				'shadow_box_shadow_type' => 'yes',
				'shadow_box_shadow'      => array(
					'horizontal' => 0,
					'vertical'   => 4,
					'blur'       => 16,
					'spread'     => 0,
					'color'      => 'rgba(0,0,0,0.15)',
				),
			),
			array(
				'_id'                    => 'shadow_3',
				'title'                  => __( 'Shadow 3', 'ang' ),
				'shadow_box_shadow_type' => 'yes',
				'shadow_box_shadow'      => array(
					'horizontal' => 0,
					'vertical'   => 20,
					'blur'       => 20,
					'spread'     => 0,
					'color'      => 'rgba(0,0,0,0.15)',
				),
			),
			array(
				'_id'                    => 'shadow_4',
				'title'                  => __( 'Shadow 4', 'ang' ),
				'shadow_box_shadow_type' => 'yes',
				'shadow_box_shadow'      => array(
					'horizontal' => 0,
					'vertical'   => 30,
					'blur'       => 55,
					'spread'     => 0,
					'color'      => 'rgba(0,0,0,0.15)',
				),
			),
			array(
				'_id'                    => 'shadow_5',
				'title'                  => __( 'Shadow 5', 'ang' ),
				'shadow_box_shadow_type' => 'yes',
				'shadow_box_shadow'      => array(
					'horizontal' => 0,
					'vertical'   => 80,
					'blur'       => 80,
					'spread'     => 0,
					'color'      => 'rgba(0,0,0,0.15)',
				),
			),
			array(
				'_id'   => 'shadow_6',
				'title' => __( 'Shadow 6', 'ang' ),
			),
			array(
				'_id'   => 'shadow_7',
				'title' => __( 'Shadow 7', 'ang' ),
			),
			array(
				'_id'   => 'shadow_8',
				'title' => __( 'Shadow 8', 'ang' ),
			),
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			array(
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'required'    => true,
			)
		);

		$repeater->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'shadow',
				'label'    => '',
				'global'   => array(
					'active' => false,
				),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.elementor-element > .elementor-widget-container, {{WRAPPER}} {{CURRENT_ITEM}}_hover.elementor-element:hover > .elementor-widget-container, {{WRAPPER}} {{CURRENT_ITEM}}.elementor-element .elementor-element-populated, {{WRAPPER}} {{CURRENT_ITEM}}_hover.elementor-element:hover .elementor-element-populated, {{WRAPPER}} {{CURRENT_ITEM}}.e-container, {{WRAPPER}} {{CURRENT_ITEM}}_hover.e-container:hover, {{WRAPPER}} {{CURRENT_ITEM}}_external.elementor-element > .elementor-widget-container, {{WRAPPER}} {{CURRENT_ITEM}}.e-con, {{WRAPPER}} {{CURRENT_ITEM}}_hover.e-con:hover',
			)
		);

		$element->add_control(
			'ang_box_shadows',
			array(
				'type'         => Global_Style_Repeater::CONTROL_TYPE,
				'fields'       => $repeater->get_controls(),
				'default'      => $shadow_defaults,
				'item_actions' => array(
					'add'       => false,
					'remove'    => false,
					'sort'      => false,
					'duplicate' => false,
				),
				'separator'    => 'after',
			)
		);

		$element->end_controls_tab();

		do_action( 'analog_box_shadows_tab_end', $element, $repeater );

		$element->end_controls_tabs();

		$element->add_control(
			'ang_box_shadows_reset',
			array(
				'label' => __( 'Reset to default', 'ang' ),
				'type'  => 'button',
				'text'  => __( 'Reset', 'ang' ),
				'event' => 'analog:resetBoxShadows',
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Gets set Box Shadow presets.
	 */
	public function get_kit_shadow_presets() {
		// Register default options array.
		$options = array(
			'none' => __( 'None', 'ang' ),
		);

		/**
		 * Get current kit settings.
		 */
		$kit = Utils::get_document_kit( get_the_ID() );

		if ( $kit ) {
			$controls = array(
				'ang_box_shadows',
				'ang_box_shadows_secondary',
				'ang_box_shadows_tertiary',
			);

			// Use raw settings that doesn't have default values.
			$kit_raw_settings = $kit->get_data( 'settings' );

			foreach ( $controls as $control ) {
				// Get SK Container padding preset labels.
				if ( isset( $kit_raw_settings[ $control ] ) ) {
					$shadow_items = $kit_raw_settings[ $control ];
				} else {
					// Get default items, but without empty defaults.
					$control      = $kit->get_controls( $control );
					$shadow_items = $control['default'] ?? array();
				}

				if ( ! empty( $shadow_items ) ) {
					foreach ( $shadow_items as $shadow ) {
						if ( isset( $shadow['shadow_box_shadow_type'] ) && 'yes' === $shadow['shadow_box_shadow_type'] ) {
							$options[ $shadow['_id'] ] = $shadow['title'];
						}
					}
				}
			}
		}

		return $options;
	}

	/**
	 * Tweak Common widgets for Box Shadow presets.
	 *
	 * @param Element_Base $element Element_Base Class.
	 */
	public function tweak_common_borders( Element_Base $element ) {

		// Get presets options array.
		$options = $this->get_kit_shadow_presets();

		/**
		 * Common widgets.
		 */
		$element->start_injection(
			array(
				'tab' => '_tab_border_normal',
				'of'  => '_box_shadow_box_shadow_type',
				'at'  => 'before',
			)
		);

		$element->add_control(
			'ang_box_shadow_preset',
			array(
				'label'         => __( 'Box Shadow Preset', 'ang' ),
				'type'          => Controls_Manager::SELECT,
				'hide_in_inner' => true,
				'default'       => 'none',
				'options'       => $options,
				'prefix_class'  => 'elementor-repeater-item-',
			)
		);

		$element->add_control(
			'ang_box_shadow_helper_description',
			array(
				'raw'             => sprintf(
					'<a href="#" class="ang-notice blue" onClick="%1$s">%2$s</a>',
					'analog.redirectToPanel( \'ang_shadows\' )',
					__( 'Edit in Style Kits', 'ang' )
				),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$element->end_injection();

		$element->start_injection(
			array(
				'tab' => '_tab_border_hover',
				'of'  => '_box_shadow_hover_box_shadow_type',
				'at'  => 'before',
			)
		);

		$hover_options = array();

		foreach ( $options as $key => $value ) {
			$hover_options[ $key . '_hover' ] = $value;
		}

		$element->add_control(
			'ang_box_shadow_hover_preset',
			array(
				'label'         => __( 'Box Shadow Preset', 'ang' ),
				'type'          => Controls_Manager::SELECT,
				'hide_in_inner' => true,
				'default'       => 'none_hover',
				'options'       => $hover_options,
				'prefix_class'  => 'elementor-repeater-item-',
			)
		);

		$element->add_control(
			'ang_box_shadow_hover_helper_description',
			array(
				'raw'             => sprintf(
					'<a href="#" class="ang-notice blue" onClick="%1$s">%2$s</a>',
					'analog.redirectToPanel( \'ang_shadows\' )',
					__( 'Edit in Style Kits', 'ang' )
				),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$element->end_injection();
	}

	/**
	 * Tweak Image widget for Box Shadow presets.
	 *
	 * @param Element_Base $element Element_Base Class.
	 */
	public function tweak_image_borders( Element_Base $element ) {
		// Get presets options array.
		$options = $this->get_kit_shadow_presets();

		$updated_options = array();

		foreach ( $options as $key => $value ) {
			$updated_options[ $key . '_external' ] = $value;
		}

		$element->start_injection(
			array(
				'of' => 'image_box_shadow_box_shadow_type',
				'at' => 'before',
			)
		);

		$element->add_control(
			'ang_image_box_shadow_preset',
			array(
				'label'         => __( 'Box Shadow Preset', 'ang' ),
				'type'          => Controls_Manager::SELECT,
				'hide_in_inner' => true,
				'default'       => 'none_external',
				'options'       => $updated_options,
				'prefix_class'  => 'external_elementor-repeater-item-',
			)
		);

		$element->end_injection();
	}

	/**
	 * Tweak Section & Column widgets for Box Shadow presets.
	 *
	 * @param Element_Base $element Element_Base Class.
	 */
	public function tweak_section_column_borders( Element_Base $element ) {

		// Get presets options array.
		$options = $this->get_kit_shadow_presets();

		/**
		 * Column & Section widgets.
		 */
		$element->start_injection(
			array(
				'tab' => 'tab_border_normal',
				'of'  => 'box_shadow_box_shadow_type',
				'at'  => 'before',
			)
		);

		$element->add_control(
			'ang_sc_box_shadow_preset',
			array(
				'label'         => __( 'Box Shadow Preset', 'ang' ),
				'type'          => Controls_Manager::SELECT,
				'hide_in_inner' => true,
				'default'       => 'none',
				'options'       => $options,
				'prefix_class'  => 'elementor-repeater-item-',
			)
		);

		$element->add_control(
			'ang_sc_box_shadow_helper_description',
			array(
				'raw'             => sprintf(
					'<a href="#" class="ang-notice blue" onClick="%1$s">%2$s</a>',
					'analog.redirectToPanel( \'ang_shadows\' )',
					__( 'Edit in Style Kits', 'ang' )
				),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$element->end_injection();

		$element->start_injection(
			array(
				'tab' => 'tab_border_hover',
				'of'  => 'box_shadow_hover_box_shadow_type',
				'at'  => 'before',
			)
		);

		$hover_options = array();

		foreach ( $options as $key => $value ) {
			$hover_options[ $key . '_hover' ] = $value;
		}

		$element->add_control(
			'ang_sc_box_shadow_hover_preset',
			array(
				'label'         => __( 'Box Shadow Preset', 'ang' ),
				'type'          => Controls_Manager::SELECT,
				'hide_in_inner' => true,
				'default'       => 'none_hover',
				'options'       => $hover_options,
				'prefix_class'  => 'elementor-repeater-item-',
			)
		);

		$element->add_control(
			'ang_sc_box_shadow_hover_helper_description',
			array(
				'raw'             => sprintf(
					'<a href="#" class="ang-notice blue" onClick="%1$s">%2$s</a>',
					'analog.redirectToPanel( \'ang_shadows\' )',
					__( 'Edit in Style Kits', 'ang' )
				),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$element->end_injection();
	}

	/**
	 * Tweak Container widget for Box Shadow presets.
	 *
	 * @param Element_Base $element Element_Base Class.
	 */
	public function tweak_container_borders( Element_Base $element ) {
		// Get presets options array.
		$options = $this->get_kit_shadow_presets();

		/**
		 * Container.
		 */
		$element->start_injection(
			array(
				'tab' => 'tab_border',
				'of'  => 'box_shadow_box_shadow_type',
				'at'  => 'before',
			)
		);

		$element->add_control(
			'ang_container_box_shadow_preset',
			array(
				'label'         => __( 'Box Shadow Preset', 'ang' ),
				'type'          => Controls_Manager::SELECT,
				'hide_in_inner' => true,
				'default'       => 'none',
				'options'       => $options,
				'prefix_class'  => 'elementor-repeater-item-',
			)
		);

		$element->add_control(
			'ang_container_box_shadow_helper_description',
			array(
				'raw'             => sprintf(
					'<a href="#" class="ang-notice blue" onClick="%1$s">%2$s</a>',
					'analog.redirectToPanel( \'ang_shadows\' )',
					__( 'Edit in Style Kits', 'ang' )
				),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$element->end_injection();

		$element->start_injection(
			array(
				'tab' => 'tab_border_hover',
				'of'  => 'box_shadow_hover_box_shadow_type',
				'at'  => 'before',
			)
		);

		$hover_options = array();

		foreach ( $options as $key => $value ) {
			$hover_options[ $key . '_hover' ] = $value;
		}

		$element->add_control(
			'ang_container_box_shadow_hover_preset',
			array(
				'label'         => __( 'Box Shadow Preset', 'ang' ),
				'type'          => Controls_Manager::SELECT,
				'hide_in_inner' => true,
				'default'       => 'none_hover',
				'options'       => $hover_options,
				'prefix_class'  => 'elementor-repeater-item-',
			)
		);

		$element->end_injection();
	}

	/**
	 * Tweak Heading widget for typographic helper link
	 *
	 * @param Element_Base $element Element_Base Class.
	 */
	public function add_typo_helper_link( Element_Base $element ) {
		$element->start_injection(
			array(
				'of' => 'size',
				'at' => 'after',
			)
		);

		$element->add_control(
			'ang_typography_helper_description',
			array(
				'raw'             => sprintf(
					'<a href="#" class="ang-notice blue" onClick="%1$s">%2$s</a>',
					'analog.redirectToPanel( \'ang_typography_sizes\' )',
					__( 'Edit in Style Kits', 'ang' )
				),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$element->end_injection();
	}

	/**
	 * Tweak Button widget for Button sizes helper link
	 *
	 * @param Element_Base $element Element_Base Class.
	 */
	public function add_btn_sizes_helper_link( Element_Base $element ) {
		$element->start_injection(
			array(
				'of' => 'size',
				'at' => 'after',
			)
		);

		$element->add_control(
			'ang_btn_sizes_helper_description',
			array(
				'raw'             => sprintf(
					'<a href="#" class="ang-notice blue" onClick="%1$s">%2$s</a>',
					'analog.redirectToPanel( \'ang_buttons\' )',
					__( 'Edit in Style Kits', 'ang' )
				),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$element->end_injection();
	}
}

new Typography();
