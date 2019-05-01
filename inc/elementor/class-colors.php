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
			"elementor/element/{$element}/{$section}/after_section_start",
			function( Element_Base $element ) use ( $data ) {
				$section = $data['section'];
				$args    = $data['args'];
				$key     = "{$element->get_id()}_{$section}_color";

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

		for ( $i = 1; $i <= 8; $i++ ) {
			$element->add_control(
				'ang_color_toggle' . $i,
				[
					/* translators: %s: Color Index. */
					'label'        => sprintf( __( 'Color %s', 'ang' ), $i ),
					'type'         => Controls_Manager::POPOVER_TOGGLE,
					'return_value' => 'yes',
				]
			);

			$element->start_popover();

			$element->add_control(
				'ang_color_label_' . $i,
				[
					'label'   => __( 'Label', 'ang' ),
					'default' => 'Color ' . $i,
					'type'    => Controls_Manager::TEXT,
				]
			);

			$element->add_control(
				'ang_color_' . $i,
				[
					'label' => __( 'Color', 'ang' ),
					'type'  => Controls_Manager::COLOR,
				]
			);

			$element->end_popover();
		}

		$element->end_controls_section();
	}

	public function get_elements_data() {
		$elements = [
			'icon'          => [
				[
					'section' => 'section_style_icon',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-icon',
					],
				],
			],
			'heading'       => [
				[
					'section' => 'section_title_style',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-heading-title',
					],
				],
			],
			'alert'         => [
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
			'inner-section' => [
				[
					'section' => 'section_typo',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-inner-section',
					],
				],
			],
			'text-editor'   => [
				[
					'section' => 'section_style',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-text-editor',
					],
				],
			],
			'button'        => [
				[
					'section' => 'section_style',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-button',
					],
				],
			],
			'posts'         => [
				[
					'section' => 'classic_section_design_content',
					'args'    => [
						'selector' => '{{WRAPPER}} .elementor-post__title a, {{WRAPPER}} .elementor-post__meta-data, {{WRAPPER}} .elementor-post__excerpt p, {{WRAPPER}} .elementor-post__read-more',
					],
				],
			],
			'portfolio' => [
				[
					'section' => 'section_design_overlay',
					'args' => [
						'selector' => '{{WRAPPER}} .elementor-portfolio-item__title',
					],
				],
			],
			'slides' => [
				[
					'section' => 'section_style_title',
					'args' => [
						'selector' => '{{WRAPPER}} .elementor-slide-heading',
					],
				],
				[
					'section' => 'section_style_description',
					'args' => [
						'selector' => '{{WRAPPER}} .elementor-slide-description',
					],
				],
				[
					'section' => 'section_style_button',
					'args' => [
						'selector' => '{{WRAPPER}} .elementor-slide-button',
					],
				],
				[
					'section' => 'section_style_navigation',
					'args' => [
						'selector' => '{{WRAPPER}} .elementor-slides-wrapper .slick-slider .slick-next:before, {{WRAPPER}} .elementor-slides-wrapper .slick-slider .slick-prev:before, {{WRAPPER}} .elementor-slides-wrapper .elementor-slides .slick-dots li button:before',
					],
				],
			],
			'form' => [
				[
					'section' => 'section_form_style',
					'args' => [
						'selector' => '{{WRAPPER}} .elementor-field-subgroup label, {{WRAPPER}} .elementor-field-group > label',
					],
				],
				[
					'section' => 'section_field_style',
					'args' => [
						'selector' => '{{WRAPPER}} .elementor-field-group .elementor-field',
					],
				],
				[
					'section' => 'section_button_style',
					'args' => [
						'selector' => '{{WRAPPER}} .elementor-button',
					],
				],
				[
					'section' => 'section_messages_style',
					'args' => [
						'selector' => '{{WRAPPER}} .elementor-message.elementor-message-success, {{WRAPPER}} .elementor-message.elementor-message-danger, {{WRAPPER}} .elementor-message.elementor-help-inline',
					],
				],
			],
			'login' => [
				[
					'section' => 'section_style_labels',
					'args' => [
						'selector' => '{{WRAPPER}} .elementor-form-fields-wrapper label',
					],
				],
				[
					'section' => 'section_field_style',
					'args' => [
						'selector' => '{{WRAPPER}} .elementor-field-group .elementor-field',
					],
				],
				[
					'section' => 'section_button_style',
					'args' => [
						'selector' => '{{WRAPPER}} .elementor-button',
					],
				],
			],
		];

		return $elements;
	}
}

new Colors();
