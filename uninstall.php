<?php
/**
 * Uninstall AnalogWP.
 *
 * @package AnalogWP
 */

// Exit if accessed directly.
defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

global $wpdb;

if ( true === wp_validate_boolean( wp_parse_args( get_option( 'ang_options', array() ), array( 'remove_on_uninstall' => false ) )['remove_on_uninstall'] ) ) {
	delete_option( 'ang_options' );
	delete_option( '_ang_import_history' );
	delete_option( 'style_kits_previous_db_version' );
	delete_option( 'style_kits_db_version' );
	delete_option( 'analog_onboarding' );
	delete_option( 'ran_onboarding' );
	delete_option( 'ang_queue_flush_rewrite_rules' );

	// Defaults added for each setting, see Analog\Settings\create_options().
	delete_option( 'allow_svg_uploads' );
	delete_option( 'hide_legacy_features' );
	delete_option( 'container_library_experiment' );
	delete_option( 'also_inline_global_kit' );
	delete_option( 'ang_data_collection' );
	delete_option( 'remove_on_uninstall' );

	$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $wpdb->esc_like( 'ang_hide_' ) . '%' ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

	delete_metadata( 'user', 0, 'analog_library_favorites', '', true );
	delete_metadata( 'user', 0, 'analog_block_favorites', '', true );
	delete_metadata( 'user', 0, 'analog_admin_notices', '', true );
}

delete_option( '_ang_installed_time' );

delete_transient( 'ang_license_message' );
delete_transient( 'analogwp_template_info' );
delete_transient( 'analogwp_template_info_v3' );
delete_transient( 'analogwp_starterkits_info' );
delete_transient( 'analogwp_google_fonts' );
delete_transient( 'analogwp_google_fonts_retry' );

foreach ( array( '_transient_ang_rollback_versions_', '_transient_timeout_ang_rollback_versions_' ) as $ang_rollback_prefix ) {
	$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $wpdb->esc_like( $ang_rollback_prefix ) . '%' ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
}

wp_clear_scheduled_hook( 'analog/tracker/send_event' );

if ( class_exists( '\Elementor\Plugin' ) ) {
	\Elementor\Plugin::$instance->files_manager->clear_cache();
}
