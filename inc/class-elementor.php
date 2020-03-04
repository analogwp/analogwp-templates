<?php
/**
 * Elementor core integration.
 *
 * @package AnalogWP
 */

namespace Analog;

use Analog\Elementor\ANG_Action;
use Analog\Elementor\Google_Fonts;
use Elementor\Core\Common\Modules\Finder\Categories_Manager;
use Elementor\Core\DynamicTags\Manager;
use Analog\Elementor\Tags\Light_Background;
use Analog\Elementor\Tags\Dark_Background;

/**
 * Intializes scripts/styles needed for AnalogWP modal on Elementor editing page.
 */
class Elementor {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_editor_scripts' ) );
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueue_editor_scripts' ) );

		add_action(
			'elementor/finder/categories/init',
			static function ( Categories_Manager $categories_manager ) {
				include_once ANG_PLUGIN_DIR . 'inc/elementor/class-finder-shortcuts.php';

				$categories_manager->add_category( 'ang-shortcuts', new Finder_Shortcuts() );
			}
		);

		add_action( 'elementor/controls/controls_registered', array( $this, 'register_controls' ) );

		add_action(
			'elementor/dynamic_tags/register_tags',
			static function( Manager $dynamic_tags ) {

				$dynamic_tags->register_group(
					'ang_classes',
					array(
						'title' => __( 'AnalogWP Classes', 'ang' ),
					)
				);

				include_once ANG_PLUGIN_DIR . 'inc/elementor/tags/class-dark-background.php';
				include_once ANG_PLUGIN_DIR . 'inc/elementor/tags/class-light-background.php';

				$dynamic_tags->register_tag( Light_Background::class );
				$dynamic_tags->register_tag( Dark_Background::class );
			}
		);

		add_filter(
			'elementor/fonts/additional_fonts',
			static function( $additional_fonts ) {
				$fonts = Google_Fonts::get_google_fonts();

				if ( count( $fonts ) ) {
					$additional_fonts = array_merge( $additional_fonts, $fonts );
				}

				return $additional_fonts;
			}
		);
	}

	/**
	 * Register custom Elementor control.
	 */
	public function register_controls() {
		require_once ANG_PLUGIN_DIR . 'inc/elementor/class-ang-action.php';

		$controls_manager = Plugin::elementor()->controls_manager;
		$controls_manager->register_control( 'ang_action', new ANG_Action() );
	}

	/**
	 * Load styles and scripts for Elementor modal.
	 *
	 * @return void
	 */
	public function enqueue_editor_scripts() {
		do_action( 'ang_loaded_templates' );

		wp_enqueue_script( 'analogwp-elementor-modal', ANG_PLUGIN_URL . 'assets/js/elementor-modal.js', array( 'jquery' ), filemtime( ANG_PLUGIN_DIR . 'assets/js/elementor-modal.js' ), false );
		wp_enqueue_style( 'analogwp-elementor-modal', ANG_PLUGIN_URL . 'assets/css/elementor-modal.css', array( 'dashicons' ), filemtime( ANG_PLUGIN_DIR . 'assets/css/elementor-modal.css' ) );

		wp_enqueue_script(
			'analogwp-app',
			ANG_PLUGIN_URL . 'assets/js/app.js',
			array(
				'react',
				'react-dom',
				'jquery',
				'wp-components',
				'wp-hooks',
				'wp-i18n',
				'wp-api-fetch',
				'wp-html-entities',
			),
			filemtime( ANG_PLUGIN_DIR . 'assets/js/app.js' ),
			true
		);
		wp_set_script_translations( 'analogwp-app', 'ang' );

		wp_enqueue_style( 'wp-components' );

		wp_enqueue_style( 'analog-google-fonts', 'https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap', array(), '20190716' );

		$i10n = apply_filters( // phpcs:ignore
			'analog/app/strings',
			array(
				'is_settings_page' => false,
				'global_kit'       => get_option( 'elementor_active_kit' ),
				'stylekit_queue'   => Utils::get_stylekit_queue() ? array_values( Utils::get_stylekit_queue() ) : array(),
			)
		);

		wp_localize_script( 'analogwp-app', 'AGWP', $i10n );
	}
}

new Elementor();
