<?php
/**
 * Elemenotor Typography controls.
 *
 * @package Analog
 */

namespace Analog\Elementor;

use Analog\Plugin;
use Elementor\Core\Base\Module;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Element_Base;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Settings\Manager;
use Analog\Utils;

if ( version_compare( ELEMENTOR_VERSION, '2.8.0', '<' ) ) {
	class_alias( 'Elementor\Scheme_Typography', 'Analog\Elementor\Scheme_Typography' );
} else {
	class_alias( 'Elementor\Core\Schemes\Typography', 'Analog\Elementor\Scheme_Typography' );
}

defined( 'ABSPATH' ) || exit;

/**
 * Class Typography.
 *
 * @package Analog\Elementor
 */
class Typography extends Module {
	use Document;

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
		$this->tokens = Utils::get_kits();

		add_action( 'elementor/element/kit/section_typography/after_section_end', array( $this, 'register_typography_sizes' ), 20, 2 );

		// Color section is hooked at 170 Priority.
		add_action( 'elementor/element/kit/section_buttons/after_section_end', array( $this, 'register_buttons' ), 20, 2 );
		add_action( 'elementor/element/kit/section_images/after_section_end', array( $this, 'register_outer_section_padding' ), 20, 2 );
		add_action( 'elementor/element/kit/section_images/after_section_end', array( $this, 'register_columns_gap' ), 40, 2 );
		add_action( 'elementor/element/after_section_end', array( $this, 'register_styling_settings' ), 20, 2 );
		add_action( 'elementor/element/kit/section_images/after_section_end', array( $this, 'register_tools' ), 999, 2 );

		add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueue_preview_scripts' ) );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_editor_scripts' ), 999 );

		add_action( 'elementor/element/before_section_end', array( $this, 'update_padding_control_selector' ), 10, 2 );

		add_filter( 'display_post_states', array( $this, 'add_token_state' ), 10, 2 );

		add_action( 'elementor/element/section/section_layout/before_section_end', array( $this, 'tweak_section_widget' ) );

		add_action( 'elementor/element/kit/section_typography/after_section_end', array( $this, 'tweak_typography_section' ), 999, 2 );
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
					'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
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
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
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
				'tab'   => Controls_Manager::TAB_STYLE,
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
				'raw'             => __( 'Edit the available sizes for the Heading Element.', 'ang' ) . sprintf( ' <a href="%1$s" target="_blank">%2$s</a>', 'https://docs.analogwp.com/article/575-text-and-heading-sizes', __( 'Learn more.', 'ang' ) ),
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
					'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
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
				'raw'             => __( 'Edit the available sizes for the p, span, and div tags of the Heading Element.', 'ang' ) . sprintf( ' <a href="%1$s" target="_blank">%2$s</a>', 'https://docs.analogwp.com/article/575-text-and-heading-sizes', __( 'Learn more.', 'ang' ) ),
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
					'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
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
	 * Register Outer Section padding controls.
	 *
	 * @param Controls_Stack $element Controls object.
	 * @param string         $section_id Section ID.
	 */
	public function register_outer_section_padding( Controls_Stack $element, $section_id ) {
		$gaps = array(
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
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		/**
		 * Add default Outer section padding control.
		 *
		 * @since 1.6.2
		 */
		$element->add_control(
			'ang_default_section_padding',
			array(
				'label'   => __( 'Set a Default Padding', 'ang' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'no',
				'options' => array(
					'no'       => __( 'No Padding', 'ang' ),
					'default'  => __( 'Normal', 'ang' ),
					'narrow'   => __( 'Small', 'ang' ),
					'extended' => __( 'Medium', 'ang' ),
					'wide'     => __( 'Large', 'ang' ),
					'wider'    => __( 'Extra Large', 'ang' ),
				),
			)
		);

		$element->add_control(
			'ang_section_padding_description',
			array(
				'raw'             => __( 'Add padding to the outer sections of your layouts by using these controls.', 'ang' ) . sprintf( ' <a href="%1$s" target="_blank">%2$s</a>', 'https://docs.analogwp.com/article/587-outer-section-padding', __( 'Learn more.', 'ang' ) ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'separator'       => 'before',
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
					'size_units' => array( 'px', 'em', '%' ),
					'selectors'  => array(
						"{{WRAPPER}} .ang-section-padding-{$key}" =>
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
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$element->add_control(
			'ang_column_gaps_description',
			array(
				'raw'             => __( 'Column Gap presets add padding to the columns of a section.', 'ang' ) . sprintf( ' <a href="%1$s" target="_blank">%2$s</a>', 'https://docs.analogwp.com/article/588-working-with-column-gaps', __( 'Learn more.', 'ang' ) ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		foreach ( $gaps as $key => $label ) {
			$element->add_responsive_control(
				'ang_column_gap_' . $key,
				array(
					'label'      => $label,
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%' ),
					'selectors'  => array(
						"{{WRAPPER}} .elementor-column-gap-{$key} > .elementor-row > .elementor-column > .elementor-element-populated"
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
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$element->add_control(
			'ang_buttons_description',
			array(
				'raw'             => __( 'Define the default styles for every button size.', 'ang' ) . sprintf( ' <a href="%1$s" target="_blank">%2$s</a>', 'https://docs.analogwp.com/article/586-button-styles-and-sizes', __( 'Learn more.', 'ang' ) ),
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
					'size_units' => array( 'px', '%' ),
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
					'size_units' => array( 'px', '%' ),
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
					'size_units' => array( 'px', 'em', '%' ),
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
			$config   = $document::get_editor_panel_config();

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

		/**
		 * Important:
		 *
		 * Setting Kit ID to "string" here on purpose. Elementor's condition arg expects the matching option to be a
		 * string, where our option returns an integer.
		 */
		$global_token = Utils::get_global_kit_id();

		if ( ! $global_token ) {
			$global_token = -1;
		}

		$element->add_control(
			'description_ang_global_stylekit',
			array(
				'raw'             => __( 'You are editing the Global Style Kit.', 'ang' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'ang-notice',
				'condition'       => array(
					'ang_action_tokens' => (string) $global_token,
				),
			)
		);

		$label = __( 'A Style Kit is a saved configuration of Theme Styles, that you can optionally apply on any page. This will override the Global theme Styles for this page.', 'ang' );
		$element->add_control(
			'ang_action_tokens',
			array(
				'label'          => __( 'Page Style Kit', 'ang' ) . $this->get_tooltip( $label ),
				'type'           => Controls_Manager::SELECT2,
				'select2options' => array(
					'allowClear' => false,
				),
				'options'        => $this->tokens,
				'default'        => get_option( 'elementor_active_kit' ),
			)
		);

		$element->add_control(
			'ang_edit_kit',
			array(
				'type'       => Controls_Manager::BUTTON,
				'label'      => __( 'Edit Theme Style Kit', 'ang' ),
				'show_label' => false,
				'text'       => __( 'Edit Theme Style Kit', 'ang' ),
				'separator'  => 'after',
				'event'      => 'analog:editKit',
			)
		);

		$element->add_control(
			'description_ang_stylekit_docs',
			array(
				'raw'  => sprintf(
					/* translators: %s: Link to Style Kits */
					__( 'You can set a Global Style Kit <a href="%s" target="_blank">here</a>.', 'ang' ),
					admin_url( 'admin.php?page=ang-settings&tab=general#global_kit' )
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
				'label' => __( 'Theme Style Kit', 'ang' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
				'label' => __( 'Save Theme Style Kit as', 'ang' ) . $this->get_tooltip( $label ),
				'type'  => 'button',
				'text'  => __( 'Save as&hellip;', 'ang' ),
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
		$default = 'no';

		if ( $post_id ) {
			$settings = get_post_meta( $post_id, '_elementor_page_settings', true );

			if ( isset( $settings['ang_action_tokens'] ) && '' !== $settings['ang_action_tokens'] ) {
				$default = Utils::get_kit_settings( $settings['ang_action_tokens'], 'ang_default_section_padding' );
			}
		}

		$element->add_control(
			'ang_outer_gap',
			array(
				'label'         => __( 'Outer Section Padding', 'ang' ),
				'description'   => __( 'A Style Kits control that adds padding to your outer sections. You can edit the values', 'ang' ) . sprintf( '<a href="#" onClick="%1$s">%2$s</a>', "analog.redirectToPanel( 'ang_section_padding' )", ' here.' ),
				'type'          => Controls_Manager::SELECT,
				'hide_in_inner' => true,
				'default'       => $default,
				'options'       => array(
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
	 * @param $element Element_Base Class.
	 */
	public function tweak_typography_section( $element ) {
		$element->start_injection(
			array(
				'of' => 'h1_heading',
				'at' => 'before',
			)
		);

		$default_fonts = Manager::get_settings_managers( 'general' )->get_model()->get_settings( 'elementor_default_generic_fonts' );

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
}

new Typography();
