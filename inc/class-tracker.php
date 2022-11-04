<?php
/**
 * Track user data if opted in.
 *
 * @package Analog
 */

namespace Analog;

use Analog\Admin\Notice;
use Analog\Options;
use Analog\Utils;

defined( 'ABSPATH' ) || exit();

/**
 * User data tracker.
 *
 * @package Analog
 * @since   1.1
 */
class Tracker {
	/**
	 * API URL.
	 *
	 * Holds the URL of the Tracker API.
	 *
	 * @access private
	 * @var string API URL.
	 */
	private static $api_url = 'https://analogwp.com/wp-json/analogwp/v1/tracker';

	/**
	 * AnalogWP installation time.
	 *
	 * @var bool|int
	 */
	private static $installed_time = false;

	/**
	 * Tracker constructor.
	 */
	public function __construct() {
		self::$installed_time = self::get_installed_time();

		add_action( 'analog/tracker/send_event', array( __CLASS__, 'send_tracking_data' ) );

		add_filter(
			'analog_admin_notices',
			function( $notices ) {
				$notices[] = $this->get_rating_notification();
				return $notices;
			}
		);
	}

	/**
	 * Get installed time.
	 *
	 * Retrieve the time when AnalogWP was installed.
	 *
	 * @access private
	 * @static
	 *
	 * @return int Unix timestamp when AnalogWP was installed.
	 */
	private static function get_installed_time() {
		$installed_time = get_option( '_ang_installed_time' );
		if ( ! $installed_time ) {
			update_option( '_ang_installed_time', time() );
		}
		return $installed_time;
	}

	/**
	 * Send tracking data.
	 *
	 * Determines where to send data or not.
	 *
	 * @param bool $override Whether to override data.
	 */
	public static function send_tracking_data( $override = false ) {
		$allow_track = Options::get_instance()->get( 'ang_data_collection' );
		if ( ! $allow_track ) {
			return;
		}

		// Tracking Data.
		$data = array(
			'site_lang'      => get_bloginfo( 'language' ),
			'email'          => get_option( 'admin_email' ),
			'wp_version'     => get_bloginfo( 'version' ),
			'site_url'       => home_url(),
			'plugin_version' => ANG_VERSION,
			'usages'         => Utils::get_import_log(),
		);

		$data = apply_filters( 'analog/tracker/send_tracking_data_params', $data );

		wp_remote_post(
			self::$api_url,
			array(
				'timeout'   => 25,
				'blocking'  => false,
				'sslverify' => false,
				'body'      => array(
					'data' => wp_json_encode( $data ),
				),
			)
		);
	}

	/**
	 * Show rating notification for users.
	 *
	 * Displayed only:
	 * - If current user is admin users,
	 * - If the user has been using the plugin for more than 2 weeks.
	 *
	 * @since 1.5.0
	 *
	 * @return Notice
	 */
	public function get_rating_notification() {
		return new Notice(
			'rate_plugin',
			array(
				'content'         => sprintf(
					/* translators: %2$s Plugin Name %3%s Review text */
					__( 'Hey! You have been using %1$s for over 2 weeks, we hope you enjoy it! If so, please leave a positive %2$s.', 'ang' ),
					'<strong>' . __( 'Style Kits for Elementor', 'ang' ) . '</strong>',
					'<a href="https://analogwp.com/admin-review" target="_blank">' . __( 'review on WordPress.org', 'ang' ) . '</a>'
				),
				'type'            => Notice::TYPE_INFO,
				'active_callback' => static function() {
					if ( 0 === absint( self::$installed_time ) || ! self::$installed_time ) {
						return false;
					}

					return current_user_can( 'manage_options' ) && ( self::$installed_time < strtotime( '-2 week' ) );
				},
				'dismissible'     => true,
			)
		);
	}
}

new Tracker();
