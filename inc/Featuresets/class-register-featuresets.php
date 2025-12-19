<?php
/**
 * Register and initialize all Featuresets for AnalogWP.
 *
 * @package Analog\Featuresets
 */

namespace Analog\Featuresets;

defined( 'ABSPATH' ) || exit;

/**
 * Class Register_Featuresets
 */
class Register_Featuresets {
	/**
	 * Class instance.
	 *
	 * @var $instance
	 */
	protected static $instance;

	/**
	 * Get a class instance.
	 *
	 * @return Register_Featuresets
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init_featuresets();
	}

	/**
	 * Initialize all Featuresets.
	 */
	public function init_featuresets() {
		// Rollback.
		require_once __DIR__ . '/rollback/class-rollbacker.php';
		require_once __DIR__ . '/rollback/class-init.php';

		do_action( 'analog_style_kits_after_featuresets_initialize' );
	}
}
