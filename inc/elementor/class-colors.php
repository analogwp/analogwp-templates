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
		add_action( 'elementor/element/kit/section_body/after_section_end', array( $this, 'register_color_settings' ), 40, 2 );
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
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$element->add_control(
			'ang_colors_description',
			array(
				/* translators: %1$s: Link to documentation, %2$s: Link text. */
				'raw'             => __( 'Set the accent colors of your layout.', 'ang' ) . sprintf( ' <a href="%1$s" target="_blank">%2$s</a>', 'https://docs.analogwp.com/article/574-working-with-colours', __( 'Learn more.', 'ang' ) ),
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
			'{{WRAPPER}} .elementor-view-stacked .elementor-icon' => 'color: #fff;',
			'{{WRAPPER}} .elementor-view-framed .elementor-icon, {{WRAPPER}} .elementor-view-default .elementor-icon' => 'border-color: {{VALUE}};',
			'.theme-hello-elementor .comment-form input#submit' => 'color: #fff; border: none;',
			'{{WRAPPER}} .elementor-tab-title a'    => 'color: inherit;',
			'{{WRAPPER}} .e--pointer-framed .elementor-item:before,{{WRAPPER}} .e--pointer-framed .elementor-item:after' => 'border-color: {{VALUE}};',
			'{{WRAPPER}} .elementor-sub-item:hover' => 'color: #fff;',

			$primary_accent_color_selectors         => 'color: {{VALUE}};',
			$primary_accent_background_selectors    => 'background-color: {{VALUEE}};',
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
				'raw'     => sprintf(
					/* translators: %s: Typography Panel link/text. */
					__( 'The primary accent color applies on links, icons, and other elements. You can also define the text link color in the %s.', 'ang' ),
					'<a href="#" onClick="analog.switchKitSection( \'section_typography\' )">' . __( 'Typography panel', 'ang' ) . '</a>'
				),
				'classes' => 'elementor-descriptor',
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

		$element->add_control(
			'ang_color_accent_secondary',
			array(
				'label'     => __( 'Secondary Accent', 'ang' ),
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

		$element->add_control(
			'ang_color_accent_secondary_desc',
			array(
				'type'    => Controls_Manager::RAW_HTML,
				'raw'     => sprintf(
					/* translators: %1$s: Button Panel link/text. %2$s: Button sizes panel link/text. */
					__( 'The default button color. You can also define button colors under the %1$s, and individually for each button size under %2$s.', 'ang' ),
					'<a href="#" onClick="analog.switchKitSection( \'section_buttons\' )">' . __( 'Buttons panel', 'ang' ) . '</a>',
					'<a href="#" onClick="analog.switchKitSection( \'ang_buttons\' )">' . __( 'Buttons Sizes panel', 'ang' ) . '</a>'
				),
				'classes' => 'elementor-descriptor',
			)
		);

		$element->end_controls_section();
	}
}

new Colors();
