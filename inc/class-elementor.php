<?php
/**
 * Elementor core integration.
 *
 * @package AnalogWP
 */

namespace Analog;

class Elementor {
	public function __construct() {
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );
		add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_editor_scripts' ] );
	}

	/**
	 * Load styles and scripts for Elementor modal.
	 *
	 * @return void
	 */
	public function enqueue_editor_scripts() {
		wp_enqueue_script( 'analogwp-elementor-modal', ANG_PLUGIN_URL . 'assets/js/elementor-modal.js', [ 'jquery' ], filemtime( ANG_PLUGIN_DIR . 'assets/js/elementor-modal.js' ) );
		wp_enqueue_style( 'analogwp-elementor-modal', ANG_PLUGIN_URL . 'assets/css/elementor-modal.css', [], filemtime( ANG_PLUGIN_DIR . 'assets/css/elementor-modal.css' ) );
	}
}

new \Analog\Elementor();
