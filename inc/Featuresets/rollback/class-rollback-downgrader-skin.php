<?php
/**
 * Rollback Downgrader Skin.
 *
 * @package Analog\Featuresets
 */

namespace Analog\Featuresets\Rollback;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use WP_Upgrader_Skin;

/**
 * Class Rollback_Downgrader_Skin
 *
 * Custom upgrader skin for rollback process.
 *
 * @since 0.56.0
 */
class Rollback_Downgrader_Skin extends WP_Upgrader_Skin {

	/**
	 * Header output.
	 */
	public function header() {
		// Optional: custom header HTML.
		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'Rolling back plugin…', 'ang' ) . '</h1>';
		echo '<div class="analog-style-kits-rollback-log">';
	}

	/**
	 * Footer output.
	 */
	public function footer() {
		// Optional: custom footer HTML.
		echo '</div>'; // .analog-style-kits-rollback-log
		echo '</div>'; // .wrap
	}

	/**
	 * Error output.
	 *
	 * @param mixed $errors Errors.
	 */
	public function error( $errors ) {
		// Called for immediate errors in steps before "after()".
		echo '<div class="notice notice-error">';
		if ( is_wp_error( $errors ) ) {
			foreach ( $errors->get_error_messages() as $message ) {
				echo '<p>' . esc_html( $message ) . '</p>';
			}
		} elseif ( ! empty( $errors ) ) {
			echo '<p>' . esc_html( $errors ) . '</p>';
		}
		echo '</div>';
	}

	/**
	 * After upgrade actions.
	 */
	public function after() {
		// At this point, $this->result is set by WP_Upgrader::run().
		// You can also look at $this->upgrader->result if you want.

		wp_clean_plugins_cache( true );

		if ( is_wp_error( $this->result ) || ! $this->result ) {
			echo '<div class="notice notice-error">';
			echo '<p>' . esc_html__( 'Rollback failed during installation.', 'ang' ) . '</p>';
			// If it's WP_Error, show its messages too.
			if ( is_wp_error( $this->result ) ) {
				foreach ( $this->result->get_error_messages() as $message ) {
					echo '<p>' . esc_html( $message ) . '</p>';
				}
			}
			echo '</div>';
		} else {
			// Success block.
			echo '<div class="notice notice-success is-dismissible">';
			echo '<p>' . esc_html__( 'Rollback complete. The plugin files have been restored.', 'ang' ) . '</p>';
			echo '</div>';
		}

		echo '<p><a class="button button-primary" href="' . esc_url( admin_url( 'plugins.php' ) ) . '">';
		echo esc_html__( 'Go to Plugins page', 'ang' );
		echo '</a></p>';
	}
}
