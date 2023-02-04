<?php
/**
 * Class Analog\Elementor\Colors.
 *
 * @package AnalogWP
 */

namespace Analog\Elementor;

defined( 'ABSPATH' ) || exit;

use Analog\Utils;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Core\Kits\Controls\Repeater as Global_Style_Repeater;
use Elementor\Core\Settings\Manager;
use Elementor\Core\Base\Module;
use Elementor\Element_Base;
use Elementor\Repeater;
use Analog\Options;

/**
 * Class Colors.
 *
 * @package Analog\Elementor
 */
class Colors extends Module {
	use Document;

	/**
	 * Colors constructor.
	 */
	public function __construct() {

		// Legacy feature ( Accent Colors ) - deprecated to be removed.
		add_action( 'elementor/element/kit/section_buttons/after_section_end', array( $this, 'register_color_settings' ), 300, 2 );

		add_action( 'elementor/element/divider/section_divider_style/before_section_end', array( $this, 'tweak_divider_style' ) );
		add_action( 'elementor/element/icon-box/section_style_content/before_section_end', array( $this, 'tweak_icon_box' ) );
		add_action( 'elementor/element/image-box/section_style_content/before_section_end', array( $this, 'tweak_image_box' ) );
		add_action( 'elementor/element/heading/section_title_style/before_section_end', array( $this, 'tweak_heading' ) );
		add_action( 'elementor/element/nav-menu/section_style_main-menu/before_section_end', array( $this, 'tweak_nav_menu' ) );
		add_action( 'elementor/element/kit/section_buttons/after_section_end', array( $this, 'tweak_theme_style_button' ), 20, 2 );
		add_action( 'elementor/element/kit/section_typography/after_section_end', array( $this, 'tweak_theme_style_typography' ), 20, 2 );

		add_action( 'elementor/element/kit/section_buttons/after_section_end', array( $this, 'register_global_colors' ), 10, 2 );

	}

	/**
	 * Get module name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'ang-colors';
	}

	/**
	 * Tweak default Divider color to be SKs Primary accent color.
	 *
	 * @since 1.3.9
	 * @param Element_Base $element Element base.
	 */
	public function tweak_divider_style( Element_Base $element ) {
		$page_settings_manager = Manager::get_settings_managers( 'page' );
		$page_settings_model   = $page_settings_manager->get_model( get_the_ID() );

		$default_color = null;

		$kit_id = $page_settings_model->get_settings( 'ang_action_tokens' );
		if ( '' !== $kit_id ) {
			$kit_model     = $page_settings_manager->get_model( $kit_id );
			$default_color = $kit_model->get_settings( 'ang_color_accent_primary' );
		}

		if ( $default_color ) {
			$element->update_control(
				'color',
				array(
					'default' => $default_color,
				)
			);
		}
	}

	/**
	 * Tweak Elementor Icon Box widget.
	 *
	 * @since 1.3.10
	 * @param Element_Base $element Element base.
	 */
	public function tweak_icon_Box( Element_Base $element ) {
		$element->update_control(
			'title_color',
			array(
				'selectors' => array(
					'{{WRAPPER}} .elementor-icon-box-content .elementor-icon-box-title, {{WRAPPER}} .elementor-icon-box-content .elementor-icon-box-title a' => 'color: {{VALUE}};',
				),
			)
		);
	}

	/**
	 * Tweak Elementor Image Box widget.
	 *
	 * @since 1.3.10
	 * @param Element_Base $element Element base.
	 */
	public function tweak_image_Box( Element_Base $element ) {
		$element->update_control(
			'title_color',
			array(
				'selectors' => array(
					'{{WRAPPER}} .elementor-image-box-content .elementor-image-box-title, {{WRAPPER}} .elementor-image-box-content .elementor-image-box-title a' => 'color: {{VALUE}};',
				),
			)
		);
	}

	/**
	 * Tweak Elementor Heading widget.
	 *
	 * @since 1.3.10
	 * @param Element_Base $element Element base.
	 */
	public function tweak_heading( Element_Base $element ) {
		$element->update_control(
			'title_color',
			array(
				'selectors' => array(
					'{{WRAPPER}}.elementor-widget-heading .elementor-heading-title, {{WRAPPER}}.elementor-widget-heading .elementor-heading-title.elementor-heading-title a' => 'color: {{VALUE}};',
				),
			)
		);
	}

	/**
	 * Tweak Elementor Nav Menu widget.
	 *
	 * @since 1.3.10
	 * @param Element_Base $element Element base.
	 */
	public function tweak_nav_menu( Element_Base $element ) {
		$element->update_control(
			'color_menu_item',
			array(
				'selectors' => array(
					'{{WRAPPER}} .elementor-nav-menu--main .elementor-item.elementor-item' => 'color: {{VALUE}}',
				),
			)
		);
	}

	/**
	 * Tweak default theme style button bg color - increases class priority.
	 *
	 * @since 1.8.0
	 * @param Controls_Stack $element Elementor element.
	 * @param string         $section_id Section ID.
	 */
	public function tweak_theme_style_button( Controls_Stack $element, $section_id ) {
		$button_selectors = array(
			'{{WRAPPER}} button',
			'{{WRAPPER}} input[type="button"]',
			'{{WRAPPER}} input[type="submit"]',
			'{{WRAPPER}} .elementor-button.elementor-button',
		);

		$button_selector = implode( ',', $button_selectors );

		$element->update_control(
			'button_background_color',
			array(
				'selectors' => array(
					$button_selector => 'background-color: {{VALUE}};',
				),
			)
		);
	}

	/**
	 * Tweak default theme style typography.
	 *
	 * @since 1.8.0
	 * @param Controls_Stack $element Elementor element.
	 * @param string         $section_id Section ID.
	 */
	public function tweak_theme_style_typography( Controls_Stack $element, $section_id ) {
		$link_selectors = array(
			'{{WRAPPER}} .elementor-widget-container *:not(.menu-item):not(.elementor-tab-title):not(.elementor-image-box-title):not(.elementor-icon-box-title):not(.elementor-icon-box-icon):not(.elementor-post__title):not(.elementor-heading-title) > a:not(:hover):not(:active):not(.elementor-item-active):not([role="button"]):not(.button):not(.elementor-button):not(.elementor-post__read-more):not(.elementor-post-info__terms-list-item):not([role="link"])',
			'{{WRAPPER}} .elementor-widget-container a:not([class])',
		);

		$link_hover_selectors = array(
			'{{WRAPPER}} .elementor-widget-container a:hover:not([class])',
		);

		$link_selectors       = implode( ',', $link_selectors );
		$link_hover_selectors = implode( ',', $link_hover_selectors );

		$element->update_control(
			'link_normal_color',
			array(
				'selectors' => array(
					$link_selectors => 'color: {{VALUE}};',
				),
			)
		);

		$element->update_control(
			'link_hover_color',
			array(
				'selectors' => array(
					$link_hover_selectors => 'color: {{VALUE}};',
				),
			)
		);
	}


	/**
	 * Register Analog Color controls.
	 *
	 * @param Controls_Stack $element Elementor element.
	 * @param string         $section_id Section ID.
	 */
	public function register_color_settings( Controls_Stack $element, $section_id ) {
		$element->start_controls_section(
			'ang_colors',
			array(
				'label' => _x( 'Accent Colors', 'Section Title', 'ang' ),
				'tab'   => Utils::get_kit_settings_tab(),
			)
		);

		$element->add_control(
			'ang_colors_description',
			array(
				/* translators: %1$s: Link to documentation, %2$s: Link text. */
				'raw'             => __( 'Set the accent colors of your layout.', 'ang' ) . sprintf( ' <a href="%1$s" target="_blank">%2$s</a>', 'https://analogwp.com/docs/style-kit-global-colors/', __( 'Learn more.', 'ang' ) ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$primary_accent_color_selectors = array(
			'{{WRAPPER}} .sk-accent-1',
			'{{WRAPPER}} .elementor-view-default .elementor-icon-box-icon .elementor-icon',
			'{{WRAPPER}} .elementor-view-framed .elementor-icon-box-icon .elementor-icon',
			'{{WRAPPER}} .elementor-icon-list-icon',
			'{{WRAPPER}} .elementor-view-framed .elementor-icon',
			'{{WRAPPER}} .elementor-view-default .elementor-icon',
			'{{WRAPPER}} .sk-primary-accent',
			'{{WRAPPER}} .sk-primary-accent.sk-primary-accent h1',
			'{{WRAPPER}} .sk-primary-accent.sk-primary-accent h2',
			'{{WRAPPER}} .sk-primary-accent.sk-primary-accent h3',
			'{{WRAPPER}} .sk-primary-accent.sk-primary-accent h4',
			'{{WRAPPER}} .sk-primary-accent.sk-primary-accent h5',
			'{{WRAPPER}} .sk-primary-accent.sk-primary-accent h6',
			'{{WRAPPER}} *:not(.menu-item):not(.elementor-tab-title):not(.elementor-image-box-title):not(.elementor-icon-box-title):not(.elementor-icon-box-icon):not(.elementor-post__title):not(.elementor-heading-title) > a:not(:hover):not(:active):not(.elementor-item-active):not([role="button"]):not(.button):not(.elementor-button):not(.elementor-post__read-more):not(.elementor-post-info__terms-list-item):not([role="link"])',
			'{{WRAPPER}} a:not([class])',
			'{{WRAPPER}} .elementor-tab-title.elementor-active',
			'{{WRAPPER}} .elementor-post-info__terms-list-item',
			'{{WRAPPER}} .elementor-post__title',
			'{{WRAPPER}} .elementor-post__title a',
			'{{WRAPPER}} .elementor-heading-title a',
			'{{WRAPPER}} .elementor-post__read-more',
			'{{WRAPPER}} .elementor-image-box-title a',
			'{{WRAPPER}} .elementor-icon-box-icon a',
			'{{WRAPPER}} .elementor-icon-box-title a',
			'{{WRAPPER}} .elementor-nav-menu--main .elementor-nav-menu a:not(.elementor-sub-item)',
			'{{WRAPPER}} .elementor-nav-menu--main .elementor-nav-menu .elementor-sub-item:not(:hover) a',
			'{{WRAPPER}} .elementor-nav-menu--dropdown a',
		);

		$primary_accent_background_selectors = array(
			'{{WRAPPER}} .elementor-view-stacked .elementor-icon',
			'{{WRAPPER}} .elementor-progress-bar',
			'{{WRAPPER}} .comment-form input#submit',
			'{{WRAPPER}} .sk-primary-bg:not(.elementor-column)',
			'{{WRAPPER}} .elementor-nav-menu--dropdown .elementor-item:hover',
			'{{WRAPPER}} .elementor-nav-menu--dropdown .elementor-item.elementor-item-active',
			'{{WRAPPER}} .elementor-nav-menu--dropdown .elementor-item.highlighted',
			'{{WRAPPER}} .elementor-nav-menu--main:not(.e--pointer-framed) .elementor-item:before',
			'{{WRAPPER}} .elementor-nav-menu--main:not(.e--pointer-framed) .elementor-item:after',
			'{{WRAPPER}} .elementor-sub-item:hover',
			'{{WRAPPER}} .sk-primary-bg.elementor-column > .elementor-element-populated',
		);

		$primary_accent_color_selectors      = implode( ',', $primary_accent_color_selectors );
		$primary_accent_background_selectors = implode( ',', $primary_accent_background_selectors );

		$selectors = array(
			'{{WRAPPER}}'                           => '--ang_color_accent_primary: {{VALUE}};',
			'{{WRAPPER}} .elementor-view-framed .elementor-icon, {{WRAPPER}} .elementor-view-default .elementor-icon' => 'border-color: {{VALUE}};',
			'.theme-hello-elementor .comment-form input#submit' => 'color: #fff; border: none;',
			'{{WRAPPER}} .elementor-tab-title a'    => 'color: inherit;',
			'{{WRAPPER}} .e--pointer-framed .elementor-item:before,{{WRAPPER}} .e--pointer-framed .elementor-item:after' => 'border-color: {{VALUE}};',
			'{{WRAPPER}} .elementor-sub-item:hover' => 'color: #fff;',
			'{{WRAPPER}} .dialog-message'           => 'font-size:inherit;line-height:inherit;',

			$primary_accent_color_selectors         => 'color: {{VALUE}};',
			$primary_accent_background_selectors    => 'background-color: {{VALUE}};',
		);

		$tooltip = __( 'The primary accent color applies on links, icons, and other elements. You can also define the text link color in the Typography panel.', 'ang' );
		$element->add_control(
			'ang_color_accent_primary',
			array(
				'label'     => __( 'Primary Accent', 'ang' ) . $this->get_tooltip( $tooltip ),
				'type'      => Controls_Manager::COLOR,
				'variable'  => 'ang_color_accent_primary',
				'selectors' => $selectors,
			)
		);

		$accent_secondary_selectors = array(
			'{{WRAPPER}} .sk-secondary-accent',
			'{{WRAPPER}} .sk-secondary-accent.sk-secondary-accent h1',
			'{{WRAPPER}} .sk-secondary-accent.sk-secondary-accent h2',
			'{{WRAPPER}} .sk-secondary-accent.sk-secondary-accent h3',
			'{{WRAPPER}} .sk-secondary-accent.sk-secondary-accent h4',
			'{{WRAPPER}} .sk-secondary-accent.sk-secondary-accent h5',
			'{{WRAPPER}} .sk-secondary-accent.sk-secondary-accent h6',
		);

		$accent_secondary_selectors = implode( ',', $accent_secondary_selectors );

		$tooltip = __( 'The default button color. You can also define button colors under the Buttons panel, and individually for each button size under Buttons Sizes panel.', 'ang' );
		$element->add_control(
			'ang_color_accent_secondary',
			array(
				'label'     => __( 'Secondary Accent', 'ang' ) . $this->get_tooltip( $tooltip ),
				'type'      => Controls_Manager::COLOR,
				'variable'  => 'ang_color_accent_secondary',
				'selectors' => array(
					'{{WRAPPER}}'               => '--ang_color_accent_secondary: {{VALUE}};',
					'{{WRAPPER}} .elementor-button, {{WRAPPER}} .button, {{WRAPPER}} button, {{WRAPPER}} .sk-accent-2' => 'background-color: {{VALUE}}',
					$accent_secondary_selectors => 'color: {{VALUE}}',
					'{{WRAPPER}} .sk-secondary-bg:not(.elementor-column)' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .sk-secondary-bg.elementor-column > .elementor-element-populated' => 'background-color: {{VALUE}};',
				),
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Get label tooltip.
	 *
	 * @since 1.6.4
	 *
	 * @param string $text Tooltip text.
	 *
	 * @return string
	 */
	protected function get_tooltip( $text ) {
		return ' <span class="hint--top-right hint--medium" aria-label="' . $text . '"><i class="fa fa-info-circle"></i></span>';
	}

	/**
	 * Register Style Kits Global Color controls.
	 *
	 * @param Controls_Stack $element Controls object.
	 * @param string         $section_id Section ID.
	 */
	public function register_global_colors( Controls_Stack $element, $section_id ) {

		$element->start_controls_section(
			'ang_global_colors_section',
			array(
				'label' => esc_html__( 'Style Kit Colors', 'ang' ),
				'tab'   => 'global-colors',
			)
		);

		$element->add_control(
			'ang_global_colors_description',
			array(
				'raw'             => sprintf(
					'%1$s <a href="https://analogwp.com/docs/style-kit-global-colors/" target="_blank">%2$s</a>',
					__( 'The Style Kit\'s color palette.', 'ang' ),
					__( 'Read more', 'ang' ),
				),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$element->start_controls_tabs(
			'ang_global_colors_section_tabs',
			array(
				'separator' => 'before',
			)
		);

		$element->start_controls_tab(
			'ang_tab_global_colors_primary',
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

		// Color Value.
		$repeater->add_control(
			'color',
			array(
				'type'        => Controls_Manager::COLOR,
				'label_block' => true,
				'dynamic'     => array(),
				'selectors'   => array(
					'{{WRAPPER}}' => '--e-global-color-{{_id.VALUE}}: {{VALUE}}',
				),
				'global'      => array(
					'active' => false,
				),
			)
		);

		// Background Colors.
		$default_surface_colors = array(
			array(
				'_id'   => 'sk_color_1',
				'title' => esc_html__( 'Site background', 'ang' ),
				'color' => '#FFFFFF',
			),
			array(
				'_id'   => 'sk_color_2',
				'title' => esc_html__( 'Light background', 'ang' ),
				'color' => '#F4F4F4',
			),
			array(
				'_id'   => 'sk_color_3',
				'title' => esc_html__( 'Dark background', 'ang' ),
				'color' => '#171720',
			),
			array(
				'_id'   => 'sk_color_4',
				'title' => esc_html__( 'Background 4', 'ang' ),
				'color' => '',
			),
		);

		$element->add_control(
			'ang_global_background_colors',
			array(
				'type'         => Global_Style_Repeater::CONTROL_TYPE,
				'fields'       => $repeater->get_controls(),
				'default'      => $default_surface_colors,
				'item_actions' => array(
					'add'    => false,
					'remove' => false,
					'sort'   => false,

				),
				'separator'    => 'after',
			)
		);

		// Accents Colors.
		$default_accent_colors = array(
			array(
				'_id'   => 'sk_color_5',
				'title' => esc_html__( 'Accent 1', 'ang' ),
				'color' => '#413EC5',
			),
			array(
				'_id'   => 'sk_color_6',
				'title' => esc_html__( 'Accent 2', 'ang' ),
				'color' => '',
			),
			array(
				'_id'   => 'sk_color_7',
				'title' => esc_html__( 'Accent 3', 'ang' ),
				'color' => '',
			),
			array(
				'_id'   => 'sk_color_8',
				'title' => esc_html__( 'Accent 4', 'ang' ),
				'color' => '',
			),
		);

		$element->add_control(
			'ang_global_accent_colors',
			array(
				'type'         => Global_Style_Repeater::CONTROL_TYPE,
				'fields'       => $repeater->get_controls(),
				'default'      => $default_accent_colors,
				'item_actions' => array(
					'add'    => false,
					'remove' => false,
					'sort'   => false,
				),
				'separator'    => 'after',
			)
		);

		// Text Colors.
		$default_type_colors = array(
			array(
				'_id'   => 'sk_color_9',
				'title' => esc_html__( 'Titles', 'ang' ),
				'color' => '#1B1B1D',
			),
			array(
				'_id'   => 'sk_color_10',
				'title' => esc_html__( 'Normal text', 'ang' ),
				'color' => '#1B1B1D',
			),
			array(
				'_id'   => 'sk_color_11',
				'title' => esc_html__( 'Secondary text', 'ang' ),
				'color' => '#707071',
			),
			array(
				'_id'   => 'sk_color_12',
				'title' => esc_html__( 'Inverted text', 'ang' ),
				'color' => '#FFFFFF',
			),
		);

		$element->add_control(
			'ang_global_text_colors',
			array(
				'type'         => Global_Style_Repeater::CONTROL_TYPE,
				'fields'       => $repeater->get_controls(),
				'default'      => $default_type_colors,
				'item_actions' => array(
					'add'    => false,
					'remove' => false,
					'sort'   => false,
				),
				'separator'    => 'before',
			)
		);

		// Extra Colors.
		$default_other_colors = array(
			array(
				'_id'   => 'sk_color_13',
				'title' => esc_html__( 'Border', 'ang' ),
				'color' => '#0000001A',
			),
			array(
				'_id'   => 'sk_color_14',
				'title' => esc_html__( 'Color Style 14', 'ang' ),
				'color' => '',
			),
			array(
				'_id'   => 'sk_color_15',
				'title' => esc_html__( 'Color Style 15', 'ang' ),
				'color' => '',
			),
			array(
				'_id'   => 'sk_color_16',
				'title' => esc_html__( 'Color Style 16', 'ang' ),
				'color' => '',
			),
		);

		$element->add_control(
			'ang_global_extra_colors',
			array(
				'type'         => Global_Style_Repeater::CONTROL_TYPE,
				'fields'       => $repeater->get_controls(),
				'default'      => $default_other_colors,
				'item_actions' => array(
					'add'    => false,
					'remove' => false,
					'sort'   => false,
				),
				'separator'    => '',
			)
		);

		$element->end_controls_tab();

		do_action( 'analog_global_colors_tab_end', $element, $repeater );

		$element->end_controls_tabs();

		$element->add_control(
			'ang_global_reset_colors',
			array(
				'label' => __( 'Reset labels & colors', 'ang' ),
				'type'  => 'button',
				'text'  => __( 'Reset', 'ang' ),
				'event' => 'analog:resetGlobalColors',
			)
		);

		$element->end_controls_section();
	}
}

new Colors();
