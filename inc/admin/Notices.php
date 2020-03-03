<?php
/**
 * Class Analog\Admin\Notices.
 *
 * @package AnalogWP
 */

namespace Analog\Admin;

/**
 * Class managing admin Notices.
 *
 * @package Analog\Admin
 * @since 1.5.0
 * @access private
 * @ignore
 */
final class Notices {
	/**
	 * Registers functionality through WordPress hooks.
	 *
	 * @since 1.5.0
	 */
	public function register() {
		$callback = function() {
			global $hook_suffix;

			if ( empty( $hook_suffix ) ) {
				return;
			}

			$this->render_notices( $hook_suffix );
		};

		add_action( 'admin_notices', $callback );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Renders admin notices.
	 *
	 * @since 1.5.0
	 *
	 * @param string $hook_suffix The current admin screen hook suffix.
	 */
	private function render_notices( $hook_suffix ) {
		$notices = $this->get_notices();
		if ( empty( $notices ) ) {
			return;
		}

		/**
		 * Notice object.
		 *
		 * @var Notice $notice Notice object.
		 */
		foreach ( $notices as $notice ) {
			if ( ! $notice->is_active( $hook_suffix ) ) {
				continue;
			}

			$notice->render();
		}
	}

	/**
	 * Gets available admin notices.
	 *
	 * @since 1.5.0
	 *
	 * @return array List of Notice instances.
	 */
	private function get_notices() {
		/**
		 * Filters the list of available admin notices.
		 *
		 * @since 1.5.0
		 *
		 * @param array $notices List of Notice instances.
		 */
		$notices = apply_filters( 'analog_admin_notices', array() );

		return array_filter(
			$notices,
			static function( $notice ) {
				return $notice instanceof Notice;
			}
		);
	}

	/**
	 * Enqueue admin scripts.
	 *
	 * Registers all the admin scripts and enqueues them.
	 * Fired by `admin_enqueue_scripts` action.
	 *
	 * @since 1.5.0
	 * @access public
	 */
	public function enqueue_scripts() {
		wp_register_script(
			'analog-admin',
			ANG_PLUGIN_URL . '/assets/js/admin.js',
			array( 'jquery' ),
			ANG_VERSION,
			true
		);

		wp_localize_script(
			'analog-admin',
			'AnalogAdmin',
			array(
				'nonce' => wp_create_nonce( Notice::$nonce_action ),
			)
		);

		wp_enqueue_script( 'analog-admin' );
	}

}
