<?php

namespace Analog\Elementor;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Core\Settings\Manager;
use Elementor\Element_Base;
use Elementor\Core\Base\Module;

class Colors extends Module {
	public function __construct() {
		$this->add_dynamic_actions();

		add_action( 'elementor/element/after_section_end', [ $this, 'register_color_settings' ], 10, 2 );
	}

	public function add_dynamic_actions() {
		$elements = $this->get_elements_data();

		foreach ( $elements as $el_key => $element ) {
			foreach ( $element as $section ) {
				$this->register_control( $el_key, $section['section'], $section );
			}
		}
	}

	/**
	 * Get Page setting by key.
	 *
	 * @param string $key Setting ID.
	 * @return mixed
	 */
	public static function get_page_setting( $key ) {
		$post_id = get_the_ID();

		// Get the page settings manager.
		$settings_manager    = Manager::get_settings_managers( 'page' );
		$page_settings_model = $settings_manager->get_model( $post_id );

		return $page_settings_model->get_settings( $key );
	}

	/**
	 * Register a control section.
	 *
	 * @param string $element Element name.
	 * @param string $section Section name.
	 * @param array  $data Control arguments.
	 *
	 * @return void
	 */
	protected function register_control( $element, $section, $data ) {
		add_action(
			"elementor/element/{$element}/{$section}/before_section_end",
			function( Element_Base $element ) use ( $data ) {
				$section = $data['section'];
				$args    = $data['args'];

				if ( isset( $data['injection'] ) ) {
					$section .= '_' . $data['injection'];
				}

				$key = "{$element->get_id()}_{$section}_color";

				if ( isset( $data['injection'] ) ) {
					$element->start_injection(
						[
							'of' => $data['injection'],
							'at' => 'before',
						]
					);
				}

				$element->add_control(
					$key,
					[
						'label'                => __( 'Global Color', 'elementor-pro' ),
						'description'          => __( 'Applied one of global colors defined in Page Styles tab.' ),
						'type'                 => Controls_Manager::SELECT2,
						'options'              => [
							'1' => self::get_page_setting( 'ang_color_label_1' ),
							'2' => self::get_page_setting( 'ang_color_label_2' ),
							'3' => self::get_page_setting( 'ang_color_label_3' ),
							'4' => self::get_page_setting( 'ang_color_label_4' ),
							'5' => self::get_page_setting( 'ang_color_label_5' ),
							'6' => self::get_page_setting( 'ang_color_label_6' ),
							'7' => self::get_page_setting( 'ang_color_label_7' ),
							'8' => self::get_page_setting( 'ang_color_label_8' ),
						],
						'selectors'            => [
							$args['selector'] => 'color: {{VALUE}}',
						],
						'selectors_dictionary' => [
							'1' => self::get_page_setting( 'ang_color_1' ),
							'2' => self::get_page_setting( 'ang_color_2' ),
							'3' => self::get_page_setting( 'ang_color_3' ),
							'4' => self::get_page_setting( 'ang_color_4' ),
							'5' => self::get_page_setting( 'ang_color_5' ),
							'6' => self::get_page_setting( 'ang_color_6' ),
							'7' => self::get_page_setting( 'ang_color_7' ),
							'8' => self::get_page_setting( 'ang_color_8' ),
						],
					]
				);

				if ( isset( $data['injection'] ) ) {
					$element->end_injection();
				}
			},
			10
		);
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
			'ang_color_accent_primary',
			[
				'label'       => __( 'Primary Accent', 'ang' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'Applies to all links by default', 'ang' ),
				'selectors'   => [
					'{{WRAPPER}} a:not(.button):not(.elementor-button)' => 'color: {{VALUE}}',
				],
			]
		);

		$element->add_control(
			'ang_color_accent_secondary',
			[
				'label'       => __( 'Secondary Accent', 'ang' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'Adds a background color to all buttons on page.', 'ang' ),
				'selectors'   => [
					'{{WRAPPER}} .elementor-button, {{WRAPPER}} .button, {{WRAPPER}} button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$element->add_control(
			'ang_color_text_headings_dark',
			[
				'label'       => __( 'Text and Headings (Dark)', 'ang' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'Applies on the text and titles over light bg', 'ang' ),
				'selectors'   => [
					'{{WRAPPER}},{{WRAPPER}} h1, {{WRAPPER}} h2, {{WRAPPER}} h3, {{WRAPPER}} h4, {{WRAPPER}} h5, {{WRAPPER}} h6' => 'color: {{VALUE}}',
					':root' => '--ang_color_text_headings_dark: {{VALUE}}',
				],
			]
		);

		$element->add_control(
			'ang_color_text_headings_light',
			[
				'label'       => __( 'Text and Headings (Light)', 'ang' ),
				'type'        => Controls_Manager::COLOR,
				'description' => __( 'Applies on the text and titles over dark bg', 'ang' ),
				'selectors'   => [
					':root' => '--ang_color_text_headings_light: {{VALUE}}',
				],
			]
		);

		$element->add_control(
			'ang_color_background_dark',
			[
				'label'     => __( 'Dark Background', 'ang' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .dark-bg' => 'background-color: {{VALUE}}; color: var(--ang_color_text_headings_light)',
				],
			]
		);

		$element->add_control(
			'ang_color_background_light',
			[
				'label'     => __( 'Light Background', 'ang' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .light-bg' => 'background-color: {{VALUE}}; color: var(--ang_color_text_headings_dark)',
				],
			]
		);

		$element->end_controls_section();
	}

	public function get_elements_data() {
		$elements = [
			'icon'              => [
				[
					'section' => 'section_style_icon',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-icon',
					],
				],
			],
			'heading'           => [
				[
					'section' => 'section_title_style',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-heading-title',
					],
				],
			],
			'alert'             => [
				[
					'section' => 'section_title',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-alert-title',
					],
				],
				[
					'section' => 'section_description',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-alert-description',
					],
				],
			],
			'inner-section'     => [
				[
					'section' => 'section_typo',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-inner-section',
					],
				],
			],
			'text-editor'       => [
				[
					'section' => 'section_style',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-text-editor',
					],
				],
			],
			'button'            => [
				[
					'section'   => 'section_style',
					'args'      => [
						'selector' => '{{WRAPPER}} .elementor-button',
					],
					'injection' => 'background_color',
				],
				[
					'section'   => 'section_style',
					'args'      => [
						'selector' => '{{WRAPPER}} .elementor-button',
					],
					'injection' => 'button_text_color',
				],
			],
			'posts'             => [
				[
					'section'   => 'classic_section_design_content',
					'args'      => [
						'selector' => '{{WRAPPER}} .elementor-post__title a',
					],
					'injection' => 'title_color',
				],
				[
					'section'   => 'classic_section_design_content',
					'args'      => [
						'selector' => '{{WRAPPER}} .elementor-post__meta-data',
					],
					'injection' => 'meta_color',
				],
				[
					'section'   => 'classic_section_design_content',
					'args'      => [
						'selector' => '{{WRAPPER}} .elementor-post__excerpt p',
					],
					'injection' => 'excerpt_color',
				],
				[
					'section'   => 'classic_section_design_content',
					'args'      => [
						'selector' => '{{WRAPPER}} .elementor-post__excerpt p, {{WRAPPER}} .elementor-post__read-more',
					],
					'injection' => 'readmore_color',
				],
			],
			'portfolio'         => [
				[
					'section' => 'section_design_overlay',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-portfolio-item__title',
					],
				],
			],
			'slides'            => [
				[
					'section' => 'section_style_title',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-slide-heading',
					],
				],
				[
					'section' => 'section_style_description',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-slide-description',
					],
				],
				[
					'section' => 'section_style_button',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-slide-button',
					],
				],
				[
					'section'   => 'section_style_navigation',
					'args'      => [
						'selector' => '{{WRAPPER}} .elementor-slides-wrapper .slick-slider .slick-next:before, {{WRAPPER}} .elementor-slides-wrapper .slick-slider .slick-prev:before',
					],
					'injection' => 'arrows_color',
				],
				[
					'section'   => 'section_style_navigation',
					'args'      => [
						'selector' => '{{WRAPPER}} .elementor-slides-wrapper .elementor-slides .slick-dots li button:before',
					],
					'injection' => 'dots_color',
				],
			],
			'form'              => [
				[
					'section' => 'section_form_style',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-field-subgroup label, {{WRAPPER}} .elementor-field-group > label',
					],
				],
				[
					'section' => 'section_field_style',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-field-group .elementor-field',
					],
				],
				[
					'section' => 'section_button_style',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-button',
					],
				],
				[
					'section'   => 'section_messages_style',
					'args'      => [
						'selector' => '{{WRAPPER}} .elementor-message.elementor-message-success',
					],
					'injection' => 'success_color',
				],
				[
					'section'   => 'section_messages_style',
					'args'      => [
						'selector' => '{{WRAPPER}} .elementor-message.elementor-message-danger',
					],
					'injection' => 'danger_color',
				],
				[
					'section'   => 'section_messages_style',
					'args'      => [
						'selector' => '{{WRAPPER}} .elementor-message.elementor-help-inline',
					],
					'injection' => 'help_inline_color',
				],
			],
			'login'             => [
				[
					'section' => 'section_style_labels',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-form-fields-wrapper label',
					],
				],
				[
					'section' => 'section_field_style',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-field-group .elementor-field',
					],
				],
				[
					'section' => 'section_button_style',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-button',
					],
				],
			],
			'nav-menu'          => [
				[
					'section' => 'section_style_main-menu',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-nav-menu--main .elementor-item',
					],
				],
				[
					'section' => 'section_style_dropdown',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-nav-menu--dropdown a, {{WRAPPER}} .elementor-menu-toggle',
					],
				],
				[
					'section' => 'style_toggle',
					'args'    => [
						'selector' => '{{WRAPPER}} div.elementor-menu-toggle',
					],
				],
			],
			'animated-headline' => [
				[
					'section' => 'section_style_marker',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-headline-dynamic-wrapper path',
					],
				],
				[
					'section'   => 'section_style_text',
					'args'      => [
						'selector' => '{{WRAPPER}} .elementor-headline-plain-text',
					],
					'injection' => 'headline_color',
				],
				[
					'section'   => 'section_style_text',
					'args'      => [
						'selector' => '{{WRAPPER}} .elementor-headline-dynamic-text',
					],
					'injection' => 'animated_text_color',
				],
			],
			'price-list'        => [
				[
					'section'   => 'section_list_style',
					'args'      => [
						'selector' => '{{WRAPPER}} .elementor-price-list-header',
					],
					'injection' => 'title_price_color',
				],
				[
					'section'   => 'section_list_style',
					'args'      => [
						'selector' => '{{WRAPPER}} .elementor-price-list-description',
					],
					'injection' => 'description_color',
				],
			],
			'price-table'       => [
				[
					'section'   => 'section_header_style',
					'args'      => [
						'selector' => '{{WRAPPER}} .elementor-price-table__heading',
					],
					'injection' => 'title_color',
				],
				[
					'section'   => 'section_header_style',
					'args'      => [
						'selector' => '{{WRAPPER}} .elementor-price-table__subheading',
					],
					'injection' => 'subtitle_color',
				],
				[
					'section'   => 'section_pricing_element_style',
					'args'      => [
						'selector' => '{{WRAPPER}} .elementor-price-table__currency, {{WRAPPER}} .elementor-price-table__integer-part, {{WRAPPER}} .elementor-price-table__fractional-part',
					],
					'injection' => 'pricing_color',
				],
				[
					'section'   => 'section_pricing_element_style',
					'args'      => [
						'selector' => '{{WRAPPER}} .elementor-price-table__period',
					],
					'injection' => 'pricing_period_color',
				],
				[
					'section' => 'section_features_list_style',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-price-table__features-list',
					],
				],
				[
					'section'   => 'section_footer_style',
					'args'      => [
						'selector' => '{{WRAPPER}} .elementor-price-table__button',
					],
					'injection' => 'button_text_color',
				],
				[
					'section'   => 'section_footer_style',
					'args'      => [
						'selector' => '{{WRAPPER}} .elementor-price-table__additional_info',
					],
					'injection' => 'additional_text_color',
				],
				[
					'section' => 'section_ribbon_style',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-price-table__ribbon-inner',
					],
				],
			],
		];

		return $elements;
	}
}

new Colors();
