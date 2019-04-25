<?php
/**
 * Run upgrade functions.
 *
 * @package AnalogWP
 * @since 1.2
 */

namespace Analog\Upgrade;

use Analog\Options;
use Analog\Install_Stylekits as StyleKits;

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
