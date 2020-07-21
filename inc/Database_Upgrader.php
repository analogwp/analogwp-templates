<?php
/**
 * Class Database_Upgrader.
 *
 * @package Analog
 */

namespace Analog;

/**
 * Class Database_Upgrader
 */
class Database_Upgrader {

	/**
	 * The slug of database option.
	 *
	 * @var string
	 */
	const OPTION = 'style_kits_db_version';

	/**
	 * The slug of database option.
	 *
	 * @var string
	 */
	const PREVIOUS_OPTION = 'style_kits_previous_db_version';

	/**
	 * Hooked into admin_init and walks through an array of upgrade methods.
	 *
	 * @return void
	 */
	public function init() {
		$routines = array();

		$version = get_option( self::OPTION, '0.0.0' );

		if ( version_compare( ANG_VERSION, $version, '=' ) ) {
			return;
		}

		array_walk( $routines, array( $this, 'run_upgrade_routine' ), $version );
		$this->finish_up( $version );
	}

	/**
	 * Runs the upgrade routine.
	 *
	 * @param string $routine The method to call.
	 * @param string $version The new version.
	 * @param string $current_version The current set version.
	 *
	 * @return void
	 */
	protected function run_upgrade_routine( $routine, $version, $current_version ) {
		if ( version_compare( $current_version, $version, '<' ) ) {
			$this->$routine( $current_version );
		}
	}

	/**
	 * Runs the needed cleanup after an update, setting the DB version to latest version, flushing caches etc.
	 *
	 * @param string $previous_version The previous version.
	 *
	 * @return void
	 */
	protected function finish_up( $previous_version ) {
		update_option( self::PREVIOUS_OPTION, $previous_version );
		update_option( self::OPTION, ANG_VERSION );
	}
}
