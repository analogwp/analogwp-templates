<?php
/**
 * AnalogWP Maintenance.
 *
 * @package Analog
 */

namespace Analog;

/**
 * Analog\Cron class..
 *
 * This class handles scheduled events
 *
 * @since 1.1
 */
class Cron {
	/**
	 * Get things going
	 *
	 * @since 1.1
	 * @see Cron::weekly_events()
	 */
	public function __construct() {
		add_filter( 'cron_schedules', [ $this, 'add_schedules' ] );
		add_action( 'init', [ $this, 'schedule_events' ] );
	}

	/**
	 * Registers new cron schedules
	 *
	 * @since 1.1
	 *
	 * @param array $schedules Old schedules.
	 * @return array
	 */
	public function add_schedules( $schedules = array() ) {
		// Adds once weekly to the existing schedules.
		$schedules['weekly'] = array(
			'interval' => 604800,
			'display'  => __( 'Once Weekly', 'ang' ),
		);

		return $schedules;
	}

	/**
	 * Schedules our events
	 *
	 * @since 1.1
	 * @return void
	 */
	public function schedule_events() {
		$this->weekly_events();
	}

	/**
	 * Schedule weekly events
	 *
	 * @access private
	 * @since 1.1
	 * @return void
	 */
	private function weekly_events() {
		if ( ! wp_next_scheduled( 'analog/tracker/send_event' ) ) {
			wp_schedule_event( current_time( 'timestamp', true ), 'weekly', 'analog/tracker/send_event' );
		}
	}
}


new Cron();
