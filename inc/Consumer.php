<?php

namespace Analog;

use Analog\Admin\Notice;

/**
 * Class Consumer.
 *
 * Classname changed from User to Consumer due to a conflict with Elementor/User class usage.
 *
 * @since 1.5.0
 * @package Analog
 */
final class Consumer {
	const ADMIN_NOTICES_KEY = 'analog_admin_notices';

	/**
	 * Init.
	 *
	 * @since 1.5.0
	 * @access public
	 */
	public function register() {
		add_action( 'wp_ajax_analog_set_admin_notice_viewed', array( __CLASS__, 'ajax_set_admin_notice_viewed' ) );
		add_action( 'admin_post_analog_set_admin_notice_viewed', array( __CLASS__, 'ajax_set_admin_notice_viewed' ) );
	}

	/**
	 * Set admin notice as viewed.
	 *
	 * Flag the user admin notice as viewed using an authenticated ajax request.
	 *
	 * Fired by `wp_ajax_elementor_set_admin_notice_viewed` action.
	 *
	 * @since 1.5.0
	 * @access public
	 * @static
	 */
	public static function ajax_set_admin_notice_viewed() {
		check_ajax_referer( Notice::$nonce_action, 'nonce' );

		$notices = self::get_user_notices();
		if ( empty( $notices ) ) {
			$notices = array();
		}

		if ( ! isset( $_POST['key'] ) ) {
			wp_send_json_error();
		}

		$notices[ sanitize_key( $_POST['key'] ) ] = 'true';
		update_user_meta( get_current_user_id(), self::ADMIN_NOTICES_KEY, $notices );

		wp_send_json_success();
	}

	/**
	 * Is user notice viewed.
	 *
	 * Whether the notice was viewed by the user.
	 *
	 * @since 1.5.0
	 * @access public
	 * @static
	 *
	 * @param int $notice_id The notice ID.
	 *
	 * @return bool Whether the notice was viewed by the user.
	 */
	public static function is_user_notice_viewed( $notice_id ) {
		$notices = self::get_user_notices();

		return ! ( empty( $notices ) || empty( $notices[ $notice_id ] ) );
	}

	/**
	 * Get user notices.
	 *
	 * Retrieve the list of notices for the current user.
	 *
	 * @since 1.5.0
	 * @access private
	 * @static
	 *
	 * @return array A list of user notices.
	 */
	private static function get_user_notices() {
		return get_user_meta( get_current_user_id(), self::ADMIN_NOTICES_KEY, true );
	}
}
