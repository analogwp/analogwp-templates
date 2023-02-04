<?php
/**
 * Uninstall AnalogWP.
 *
 * @package AnalogWP
 */

// Exit if accessed directly.
defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

$options = get_option( 'ang_options' );

if ( is_array( $options ) && isset( $options['remove_on_uninstall'] ) && true === $options['remove_on_uninstall'] ) {
	delete_option( 'ang_options' );
	delete_option( '_ang_import_history' );
	delete_option( 'style_kits_previous_db_version' );
	delete_option( 'style_kits_db_version' );
	delete_option( 'analog_onboarding' );
	delete_option( 'ran_onboarding' );
}

delete_option( '_ang_installed_time' );

delete_transient( 'ang_license_message' );
delete_transient( 'analogwp_template_info' );

wp_clear_scheduled_hook( 'analog/tracker/send_event' );

if ( class_exists( '\Elementor\Plugin' ) ) {
	\Elementor\Plugin::$instance->files_manager->clear_cache();
}
