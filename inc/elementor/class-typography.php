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
		add_action( 'elementor/element/after_section_end', [ $this, 'register_heading_typography' ], 10, 2 );
		add_action( 'elementor/element/after_section_end', [ $this, 'register_body_and_paragraph_typography' ], 10, 2 );

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
			'headings_typography',
			[
				'label' => __( 'Headings Typography', 'ang' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$element->add_control(
			'headings_typography_description',
			[
				'raw'             => __( 'These settings apply to all Headings in your layout. You can still override individual values at each element.', 'ang' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			]
		);

		for ( $i = 1; $i < 7; $i++ ) {
			$element->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'ang_heading_' . $i,
					/* translators: %s: Heading 1-6 type */
					'label'    => sprintf( __( 'Heading %s', 'ang' ), $i ),
					'selector' => 'body h' . $i,
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
			'body_and_paragraph_typography',
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
				get_the_modified_time( 'U' )
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
