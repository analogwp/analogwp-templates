<?php
/**
 * Beta Testers.
 *
 * @package AnalogWP
 */

namespace Analog;

/**
 * Class BetaTesters

 * @package Analog
 * @since 1.4.0
 */
class Beta_Testers {
	/**
	 * Transient key.
	 *
	 * Holds the "Style Kit for Elementor" beta testers transient key.
	 *
	 * @access private
	 * @static
	 *
	 * @var string Transient key.
	 */
	private $transient_key;

	/**
	 * Beta_Testers constructor.
	 */
	public function __construct() {
		if ( true !== Options::get_instance()->get( 'beta_tester' ) ) {
			return;
		}

		$this->transient_key = md5( 'ang_beta_testers_response_key' );

		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_version' ) );
	}

	/**
	 * Get beta version.
	 *
	 * Retrieve beta version from wp.org plugin repository.
	 *
	 * @access private
	 *
	 * @return string|false Beta version or false.
	 */
	public function get_beta_version() {
		$beta_version = get_site_transient( $this->transient_key );

		if ( false === $beta_version ) {
			$beta_version = 'false';

			$response = wp_remote_get( 'https://plugins.svn.wordpress.org/analogwp-templates/trunk/readme.txt' );

			if ( ! is_wp_error( $response ) && ! empty( $response['body'] ) ) {
				preg_match( '/Beta tag: (.*)/i', $response['body'], $matches );
				if ( isset( $matches[1] ) ) {
					$beta_version = $matches[1];
				}
			}

			set_site_transient( $this->transient_key, $beta_version, 6 * HOUR_IN_SECONDS );
		}

		return $beta_version;
	}

	/**
	 * Check version.
	 *
	 * Checks whether a beta version exist, and retrieve the version data.
	 *
	 * Fired by `pre_set_site_transient_update_plugins` filter, before WordPress
	 * runs the plugin update checker.
	 *
	 * @access public
	 *
	 * @param array $transient Plugin version data.
	 *
	 * @return array Plugin version data.
	 */
	public function check_version( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		delete_site_transient( $this->transient_key );

		$plugin_slug  = basename( ANG_PLUGIN_FILE, '.php' );
		$beta_version = $this->get_beta_version();

		if ( 'false' !== $beta_version && version_compare( $beta_version, ANG_VERSION, '>' ) ) {
			$response              = new \stdClass();
			$response->plugin      = $plugin_slug;
			$response->slug        = $plugin_slug;
			$response->new_version = $beta_version;
			$response->url         = 'https://analogwp.com/';
			$response->package     = sprintf( 'https://downloads.wordpress.org/plugin/analogwp-templates.%s.zip', $beta_version );

			$transient->response[ ANG_PLUGIN_BASE ] = $response;
		}

		return $transient;
	}
}

new Beta_Testers();
