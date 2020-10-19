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
		$routines = array(
			'1.7.0' => 'upgrade_1_7',
			'1.7.2' => 'upgrade_1_7_2',
		);

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

	/**
	 * Remove old Style Kits.
	 *
	 * @return void
	 */
	protected function upgrade_1_7() {
		$old_style_kits = get_posts(
			array(
				'fields'           => 'ids',
				'suppress_filters' => false,
				'post_type'        => 'ang_tokens',
				'posts_per_page'   => - 1,
			)
		);

		if ( count( $old_style_kits ) ) {
			foreach ( $old_style_kits as $post_id ) {
				wp_delete_post( (int) $post_id, true );
			}
		}
	}

	/**
	 * - Delete Kits list transient.
	 *
	 * @return void
	 */
	protected function upgrade_1_7_2() {
		delete_transient( 'analog_get_kits' );
	}
}
