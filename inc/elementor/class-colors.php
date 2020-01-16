<?php
/**
 * Class Analog\Elementor\Colors.
 *
 * @package AnalogWP
 */

namespace Analog\Elementor;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Core\Settings\Manager;
use Elementor\Core\Base\Module;
use Elementor\Element_Base;

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
		add_action( 'elementor/element/after_section_end', array( $this, 'register_color_settings' ), 170, 2 );
		add_action( 'elementor/element/divider/section_divider_style/before_section_end', array( $this, 'tweak_divider_style' ) );
		add_action( 'elementor/element/icon-box/section_style_content/before_section_end', array( $this, 'tweak_icon_box' ) );
		add_action( 'elementor/element/image-box/section_style_content/before_section_end', array( $this, 'tweak_image_box' ) );
		add_action( 'elementor/element/heading/section_title_style/before_section_end', array( $this, 'tweak_heading' ) );
		add_action( 'elementor/element/nav-menu/section_style_main-menu/before_section_end', array( $this, 'tweak_nav_menu' ) );
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
		$default_color         = $page_settings_model->get_settings( 'ang_color_accent_primary' );

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
	 * Register Analog Color controls.
	 *
	 * @param Controls_Stack $element Elementor element.
	 * @param string         $section_id Section ID.
	 */
	public function register_color_settings( Controls_Stack $element, $section_id ) {
		if ( 'section_page_style' !== $section_id ) {
			return;
		}

		$element->start_controls_section(
			'ang_colors',
			array(
				'label' => _x( 'Main Colors', 'Section Title', 'ang' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$element->add_control(
			'ang_colors_description',
			array(
				/* translators: %1$s: Link to documentation, %2$s: Link text. */
				'raw'             => __( 'Set the colors for Typography, accents and more.', 'ang' ) . sprintf( ' <a href="%1$s" target="_blank">%2$s</a>', 'https://docs.analogwp.com/article/574-working-with-colours', __( 'Learn more.', 'ang' ) ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$selectors = array(
			'{{WRAPPER}}'                                 => '--ang_color_accent_primary: {{VALUE}}',
			'{{WRAPPER}} .sk-accent-1'                    => 'color: {{VALUE}}',
			'{{WRAPPER}} .elementor-icon-box-icon .elementor-icon, {{WRAPPER}} .elementor-icon-list-icon' => 'color: {{VALUE}}',
			'{{WRAPPER}} .elementor-icon-list-icon'       => 'color: {{VALUE}}',
			'{{WRAPPER}} .elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}}; color: #fff;',
			'{{WRAPPER}} .elementor-view-framed .elementor-icon, {{WRAPPER}} .elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
			'{{WRAPPER}} .elementor-progress-bar'         => 'background-color: {{VALUE}}',
			'{{WRAPPER}} .sk-primary-accent'              => 'color: {{VALUE}}',

			'{{WRAPPER}} .sk-primary-accent.sk-primary-accent h1,
			{{WRAPPER}} .sk-primary-accent.sk-primary-accent h2,
			{{WRAPPER}} .sk-primary-accent.sk-primary-accent h3,
			{{WRAPPER}} .sk-primary-accent.sk-primary-accent h4,
			{{WRAPPER}} .sk-primary-accent.sk-primary-accent h5,
			{{WRAPPER}} .sk-primary-accent.sk-primary-accent h6' => 'color: {{VALUE}}',

			'{{WRAPPER}} .comment-form input#submit'      => 'background-color: {{VALUE}};',
			'.theme-hello-elementor .comment-form input#submit' => 'color: #fff; border: none;',

			'{{WRAPPER}} *:not(.menu-item):not(.elementor-tab-title):not(.elementor-image-box-title):not(.elementor-icon-box-title):not(.elementor-icon-box-icon):not(.elementor-post__title):not(.elementor-heading-title) > a:not(:hover):not(:active):not(.elementor-item-active):not([role="button"]):not(.button):not(.elementor-button):not(.elementor-post__read-more):not(.elementor-post-info__terms-list-item):not([role="link"]),
			{{WRAPPER}} a:not([class]),
			{{WRAPPER}} .elementor-tab-title.elementor-active,
			{{WRAPPER}} .elementor-post-info__terms-list-item,
			{{WRAPPER}} .elementor-post__title,
			{{WRAPPER}} .elementor-post__title a,
			{{WRAPPER}} .elementor-heading-title a,
			{{WRAPPER}} .elementor-post__read-more,
			{{WRAPPER}} .elementor-image-box-title a,
			{{WRAPPER}} .elementor-icon-box-icon a,
			{{WRAPPER}} .elementor-icon-box-title a'      => 'color: {{VALUE}};',

			'{{WRAPPER}} .elementor-tab-title a'          => 'color: inherit;',

			'{{WRAPPER}} .sk-primary-bg:not(.elementor-column)' => 'background-color: {{VALUE}}',

			'{{WRAPPER}} .elementor-nav-menu--main .elementor-nav-menu a:not(.elementor-sub-item)' => 'color: {{VALUE}};',
			'{{WRAPPER}} .elementor-nav-menu--main .elementor-nav-menu .elementor-sub-item:not(:hover) a' => 'color: {{VALUE}};',
			'{{WRAPPER}} .elementor-nav-menu--dropdown .elementor-item:hover' => 'background-color: {{VALUE}};',
			'{{WRAPPER}} .elementor-nav-menu--dropdown .elementor-item.elementor-item-active' => 'background-color: {{VALUE}};',
			'{{WRAPPER}} .elementor-nav-menu--dropdown a' => 'color: {{VALUE}};',
			'{{WRAPPER}} .elementor-nav-menu--dropdown .elementor-item.highlighted' => 'background-color: {{VALUE}};',

			'{{WRAPPER}} .elementor-nav-menu--main:not(.e--pointer-framed) .elementor-item:before,
			{{WRAPPER}} .elementor-nav-menu--main:not(.e--pointer-framed) .elementor-item:after' => 'background-color: {{VALUE}}',
			'{{WRAPPER}} .e--pointer-framed .elementor-item:before,
			{{WRAPPER}} .e--pointer-framed .elementor-item:after' => 'border-color: {{VALUE}}',
			'{{WRAPPER}} .elementor-sub-item:hover'       => 'background-color: {{VALUE}}; color: #fff;',
			'{{WRAPPER}} .sk-primary-bg.elementor-column > .elementor-element-populated' => 'background-color: {{VALUE}};',
		);

		$element->add_control(
			'ang_color_accent_primary',
			array(
				'label'     => __( 'Primary Accent', 'ang' ),
				'type'      => Controls_Manager::COLOR,
				'variable'  => 'ang_color_accent_primary',
				'selectors' => $selectors,
			)
		);

		$element->add_control(
			'ang_color_accent_primary_desc',
			array(
				'type'    => Controls_Manager::RAW_HTML,
				'raw'     => __( 'The primary accent color applies on Links.', 'ang' ),
				'classes' => 'elementor-descriptor',
			)
		);

		$element->add_control(
			'ang_color_accent_secondary',
			array(
				'label'     => __( 'Secondary Accent', 'ang' ),
				'type'      => Controls_Manager::COLOR,
				'variable'  => 'ang_color_accent_secondary',
				'selectors' => array(
					'{{WRAPPER}}'                      => '--ang_color_accent_secondary: {{VALUE}};',
					'{{WRAPPER}} .elementor-button, {{WRAPPER}} .button, {{WRAPPER}} button, {{WRAPPER}} .sk-accent-2' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .sk-secondary-accent' => 'color: {{VALUE}}',

					'{{WRAPPER}} .sk-secondary-accent.sk-secondary-accent h1,
					{{WRAPPER}} .sk-secondary-accent.sk-secondary-accent h2,
					{{WRAPPER}} .sk-secondary-accent.sk-secondary-accent h3,
					{{WRAPPER}} .sk-secondary-accent.sk-secondary-accent h4,
					{{WRAPPER}} .sk-secondary-accent.sk-secondary-accent h5,
					{{WRAPPER}} .sk-secondary-accent.sk-secondary-accent h6' => 'color: {{VALUE}}',

					'{{WRAPPER}} .sk-secondary-bg:not(.elementor-column)' => 'background-color: {{VALUE}}',

					'{{WRAPPER}} .sk-secondary-bg.elementor-column > .elementor-element-populated' => 'background-color: {{VALUE}};',
				),
			)
		);

		$element->add_control(
			'ang_color_accent_secondary_desc',
			array(
				'type'    => Controls_Manager::RAW_HTML,
				'raw'     => __( 'The default Button color. You can also set button colors in the Buttons tab.', 'ang' ),
				'classes' => 'elementor-descriptor',
			)
		);

		$element->add_control(
			'ang_color_text',
			array(
				'label'     => __( 'Text Color', 'ang' ),
				'type'      => Controls_Manager::COLOR,
				'variable'  => 'ang_color_text',
				'selectors' => array(
					'{{WRAPPER}}' => '--ang_color_text: {{VALUE}}; color: {{VALUE}};',
				),
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

		$element->end_controls_section();
	}
}

new Colors();
