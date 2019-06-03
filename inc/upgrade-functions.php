<?php
/**
 * Run upgrade functions.
 *
 * @package AnalogWP
 * @since 1.2
 */

namespace Analog\Upgrade;

use Analog\Utils;

defined( 'ABSPATH' ) || exit;

use Analog\Options;
use Analog\Install_Stylekits as StyleKits;

/**
 * Perform automatic upgrades when necessary.
 *
 * @return void
 */
function do_automatic_upgrades() {
	$did_upgrade       = false;
	$installed_version = Options::get_instance()->get( 'version' );

	if ( version_compare( $installed_version, ANG_VERSION, '<' ) ) {
		// Let us know that an upgrade has happened.
		$did_upgrade = true;
	}

	if ( version_compare( $installed_version, '1.2', '<' ) ) {
		Utils::clear_elementor_cache();
	}

	if ( version_compare( $installed_version, '1.2.1', '<' ) ) {
		Utils::clear_elementor_cache();
	}

	if ( version_compare( $installed_version, '1.3', '<' ) ) {
		Utils::clear_elementor_cache();
	}

	if ( $did_upgrade ) {
		// Bump version.
		Options::get_instance()->set( 'version', ANG_VERSION );
	}
}
add_action( 'admin_init', __NAMESPACE__ . '\do_automatic_upgrades' );

/**
 * Install Sample Stylekits.
 *
 * @return void
 */
function install_stylekits() {
	$stylekits_installed = Options::get_instance()->get( 'installed_stylekits' );

	if ( ! $stylekits_installed ) {
		require_once ANG_PLUGIN_DIR . 'inc/elementor/class-install-stylekits.php';

		$did_fail = StyleKits::get_instance()->perform_install();

		if ( ! $did_fail ) {
			Options::get_instance()->set( 'installed_stylekits', true );
		}
	}
}
add_action( 'admin_init', __NAMESPACE__ . '\install_stylekits' );
