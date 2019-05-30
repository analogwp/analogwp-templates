<?php
/**
 * Elementor core integration.
 *
 * @package AnalogWP
 */

namespace Analog;

/**
 * Intializes scripts/styles needed for AnalogWP modal on Elementor editing page.
 */
class Elementor {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );
		add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_editor_scripts' ] );

		add_action(
			'elementor/finder/categories/init',
			function ( $categories_manager ) {
				include_once ANG_PLUGIN_DIR . 'inc/elementor/class-finder-shortcuts.php';

				$categories_manager->add_category( 'ang-shortcuts', new Finder_Shortcuts() );
			}
		);

		add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );
	}

	/**
	 * Register custom Elementor control.
	 */
	public function register_controls() {
		require_once ANG_PLUGIN_DIR . 'inc/elementor/class-ang-action.php';

		$controls_manager = \Elementor\Plugin::$instance->controls_manager;
		$controls_manager->register_control( 'ang_action', new \Analog\Elementor\ANG_Action() );
	}

	/**
	 * Load styles and scripts for Elementor modal.
	 *
	 * @return void
	 */
	public function enqueue_editor_scripts() {
		do_action( 'ang_loaded_templates' );

		wp_enqueue_script( 'analogwp-elementor-modal', ANG_PLUGIN_URL . 'assets/js/elementor-modal.js', [ 'jquery' ], filemtime( ANG_PLUGIN_DIR . 'assets/js/elementor-modal.js' ), false );
		wp_enqueue_style( 'analogwp-elementor-modal', ANG_PLUGIN_URL . 'assets/css/elementor-modal.css', [ 'dashicons' ], filemtime( ANG_PLUGIN_DIR . 'assets/css/elementor-modal.css' ) );

		$script_suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? 'development' : 'production';

		wp_deregister_script( 'react' );
		wp_deregister_script( 'react-dom' );

		wp_enqueue_script(
			'react',
			"https://cdn.jsdelivr.net/npm/react@16.8.4/umd/react.{$script_suffix}.min.js",
			[],
			'16.8.4',
			true
		);
		wp_enqueue_script(
			'react-dom',
			"https://cdn.jsdelivr.net/npm/react-dom@16.8.4/umd/react-dom.{$script_suffix}.min.js",
			[ 'react' ],
			'16.8.4',
			true
		);

		wp_enqueue_script(
			'analogwp-app',
			ANG_PLUGIN_URL . 'assets/js/app.js',
			[
				'react',
				'react-dom',
				'wp-components',
				'wp-i18n',
				'wp-html-entities',
			],
			filemtime( ANG_PLUGIN_DIR . 'assets/js/app.js' ),
			true
		);
		wp_set_script_translations( 'analogwp-app', 'ang' );

		wp_enqueue_style( 'wp-components' );

		wp_enqueue_style( 'analog-google-fonts', 'https://fonts.googleapis.com/css?family=Poppins:400,500,600,700', [], '20190128' );

		$favorites = get_user_meta( get_current_user_id(), Analog_Templates::$user_meta_prefix, true );

		if ( ! $favorites ) {
			$favorites = [];
		}

		$current_user = wp_get_current_user();

		wp_localize_script(
			'analogwp-app',
			'AGWP',
			[
				'ajaxurl'          => admin_url( 'admin-ajax.php' ),
				'is_settings_page' => false,
				'favorites'        => $favorites,
				'isPro'            => false,
				'version'          => ANG_VERSION,
				'pluginURL'        => ANG_PLUGIN_URL,
				'license'          => [
					'status'  => Options::get_instance()->get( 'ang_license_key_status' ),
					'message' => get_transient( 'ang_license_message' ),
				],
				'user'             => [
					'email' => $current_user->user_email,
				],
				'stylekit_queue'   => Utils::get_stylekit_queue() ? array_values( Utils::get_stylekit_queue() ) : [],
			]
		);
	}
}

new Elementor();
