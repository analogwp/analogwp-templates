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
use Analog\Options;
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
		add_action( 'elementor/element/after_section_end', [ $this, 'register_styling_settings' ], 10, 2 );

		add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_preview_scripts' ] );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ], 999 );

		add_action( 'wp_ajax_ang_make_token_global', [ $this, 'make_token_global' ] );
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
					'h1, h2, h3, h4, h5, h6' => 'font-family: "{{VALUE}}"' . $default_fonts . ';',
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
					'selector'       => "body h{$i}, body .elementor-widget-heading h{$i}.elementor-heading-title",
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
				'label' => __( 'Body and Paragraph Typography', 'ang' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'ang_body',
				'label'          => __( 'Body Typography', 'ang' ),
				'selector'       => 'body',
				'scheme'         => Scheme_Typography::TYPOGRAPHY_3,
				'fields_options' => $this->get_default_typography_values( 'ang_body' ),
			]
		);

		$element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'ang_paragraph',
				'label'          => __( 'Paragraph (primary text)', 'ang' ),
				'selector'       => 'body p',
				'scheme'         => Scheme_Typography::TYPOGRAPHY_4,
				'fields_options' => $this->get_default_typography_values( 'ang_paragraph' ),
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
					'label'           => $setting[1],
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
						"body .elementor-widget-heading h1.elementor-heading-title.elementor-size-{$setting[0]}," .
						"body .elementor-widget-heading h2.elementor-heading-title.elementor-size-{$setting[0]}," .
						"body .elementor-widget-heading h3.elementor-heading-title.elementor-size-{$setting[0]}," .
						"body .elementor-widget-heading h4.elementor-heading-title.elementor-size-{$setting[0]}," .
						"body .elementor-widget-heading h5.elementor-heading-title.elementor-size-{$setting[0]}," .
						"body .elementor-widget-heading h6.elementor-heading-title.elementor-size-{$setting[0]}"
						=> 'font-size: {{SIZE}}{{UNIT}}',
					],
				]
			);
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
			[ 'small', __( 'Small', 'ang' ), 15 ],
			[ 'medium', __( 'Medium', 'ang' ), 19 ],
			[ 'large', __( 'Large', 'ang' ), 29 ],
			[ 'xl', __( 'XL', 'ang' ), 39 ],
			[ 'xxl', __( 'XXL', 'ang' ), 59 ],
		];

		foreach ( $settings as $setting ) {
			$element->add_responsive_control(
				'ang_text_size_' . $setting[0],
				[
					'label'           => $setting[1],
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
						"body .elementor-widget-heading .elementor-heading-title.elementor-size-{$setting[0]}:not(h1):not(h2):not(h3):not(h4):not(h5):not(h6)"
						=> 'font-size: {{SIZE}}{{UNIT}}',
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
					'label'           => $label,
					'type'            => Controls_Manager::DIMENSIONS,
					'desktop_default' => $this->get_default_value( 'ang_column_gap_' . $key, true ),
					'tablet_default'  => $this->get_default_value( 'ang_column_gap_' . $key . '_tablet', true ),
					'mobile_default'  => $this->get_default_value( 'ang_column_gap_' . $key . '_mobile', true ),
					'size_units'      => [ 'px', 'em', '%' ],
					'selectors'       => [
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
			'ang_action_tokens',
			[
				'label'   => __( 'Style Kit', 'ang' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => Utils::get_tokens(),
			]
		);

		$element->add_control(
			'ang_make_token_global',
			[
				'label'       => __( 'Make this Style Kit global', 'ang' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => __( 'Applies to all pages of your WordPress site', 'ang' ),
				'condition'   => [
					'ang_action_tokens!' => '',
				],
			]
		);

		$element->add_control(
			'ang_action_save_token',
			[
				'label'        => __( 'Save styles as Style Kit', 'ang' ),
				'type'         => 'ang_action',
				'action'       => 'save_token',
				'action_label' => __( 'Save Style Kit', 'ang' ),
				'description'  => __( 'A Style Kit is a collection of the page styling settings.', 'ang' ),
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
	 * Ajax action for making a token global.
	 *
	 * @return void
	 */
	public function make_token_global() {
		$id   = (int) $_POST['id'];
		$post = get_post( $id );

		if ( empty( $post ) ) {
			wp_send_json_error(
				[
					'message' => __( 'Unable to find the post', 'ang' ),
					'id'      => $id,
				]
			);
		}

		$tokens_data = get_post_meta( $id, '_tokens_data', true );

		$tokens = [
			'id'   => $id,
			'data' => $tokens_data,
		];

		if ( 'set' === $_POST['perform'] ) {
			Options::get_instance()->set( 'global_token', $tokens );
		} else {
			Options::get_instance()->set( 'global_token', '' );
		}

		wp_send_json_success(
			[
				/* translators: %s: Post title. */
				'message' => sprintf( __( '&ldquo;%s&rdquo; has been set as a global Style Kit.', 'ang' ), $post->post_title ),
			]
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
		$global_token = Options::get_instance()->get( 'global_token' );
		if ( $global_token && ! empty( $global_token ) ) {
			$values = json_decode( $global_token['data'], true );

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
		$global_token = Options::get_instance()->get( 'global_token' );

		if ( empty( $global_token ) ) {
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
}

new Typography();
