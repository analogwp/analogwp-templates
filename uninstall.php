<?php
/**
 * Uninstall AnalogWP.
 *
 * @package AnalogWP
 */

// Exit if accessed directly.
defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

$options = get_option( 'ang_options' );

if ( is_array( $options ) ) {
	if ( isset( $options['remove_on_uninstall'] ) && true === $options['remove_on_uninstall'] ) {
		delete_transient( 'ang_license_message' );
		delete_transient( 'analogwp_template_info' );
		delete_option( 'ang_options' );

		wp_clear_scheduled_hook( 'analog/tracker/send_event' );
	}
}

