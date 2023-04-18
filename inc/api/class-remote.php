<?php
/**
 * APIs.
 *
 * @package AnalogWP
 */

namespace Analog\API;

use \Analog\Base;
use Analog\Options;
use Analog\Utils;

defined( 'ABSPATH' ) || exit;

/**
 * Handle Remote API requests.
 *
 * @package Analog\API
 */
class Remote extends Base {
	const STORE_URL = 'https://analogwp.com/';

	/**
	 * API template URL.
	 * Holds the URL for getting a single template data.
	 *
	 * @var string API template URL.
	 */
	private static $template_url = self::STORE_URL . 'wp-json/analogwp/v1/templates/%d';

	/**
	 * Style kits API endpoint.
	 *
	 * @since 1.3.4
	 * @var string API endpoint for style kits.
	 */
	private static $kits_endpoint = self::STORE_URL . 'wp-json/analogwp/v2/stylekits/';

	/**
	 * Blocks API endpoint.
	 *
	 * @since 1.4.0
	 * @var string API endpoint for style kits.
	 */
	private static $blocks_endpoint = self::STORE_URL . 'wp-json/analogwp/v1/blocks/';

	/**
	 * Patterns API endpoint.
	 *
	 * @var string
	 */
	private static $patterns_endpoint = self::STORE_URL . 'wp-json/analogwp/v1/patterns/';

	/**
	 * Starter Kits API endpoint.
	 *
	 * @var string
	 */
	private static $starterkits_endpoint = self::STORE_URL . 'wp-json/analogwp/v1/starterkits/';

	/**
	 * Starter Kits transient key.
	 *
	 * @var array
	 */
	public static $starterkits_transient_key = 'analogwp_starterkits_info';

	/**
	 * Common API call args.
	 *
	 * @var array
	 */
	public static $api_call_args = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'ang_loaded_templates', array( $this, 'set_templates_info' ) );

		self::$api_call_args = array(
			'plugin_version' => ANG_VERSION,
			'url'            => home_url(),
		);
	}

	/**
	 * Transient key for library data, changes if container experiment is active.
	 *
	 * @return string
	 */
	public static function transient_key() {
		$key = 'analogwp_template_info';

		if ( Utils::is_container() ) {
			$key .= '_v3';
		}

		return $key;
	}

	/**
	 * Returns API endpoint for remote library, changes if container experiment is active.
	 *
	 * @return string
	 */
	public static function api_endpoint() {
		$endpoint = self::STORE_URL . 'wp-json/analogwp/v2/info/';

		if ( Utils::is_container() ) {
			$endpoint = self::STORE_URL . 'wp-json/analogwp/v3/info/';
		}

		return $endpoint;
	}

	/**
	 * Retrieve template library and save as a transient.
	 *
	 * @param boolean $force_update Force new info from remote API.
	 * @return void
	 */
	public static function set_templates_info( $force_update = false ) {
		$transient = get_transient( self::transient_key() );

		if ( ! $transient || $force_update ) {
			$info = self::request_remote_templates_info( $force_update );
			set_transient( self::transient_key(), $info, DAY_IN_SECONDS );
		}
	}

	/**
	 * Get template info.
	 *
	 * @param boolean $force_update Force new info from remote API.
	 *
	 * @return array
	 */
	public function get_templates_info( $force_update = false ) {
		if ( ! get_transient( self::transient_key() ) || $force_update ) {
			self::set_templates_info( true );
		}
		return get_transient( self::transient_key() );
	}

	/**
	 * Fetch remote template library info.
	 *
	 * @param boolean $force_update Force update.
	 * @return array $response AnalogWP Templates library.
	 */
	public static function request_remote_templates_info( $force_update ) {
		global $wp_version;

		$body_args = apply_filters( 'analog/api/get_templates/body_args', self::$api_call_args ); // @codingStandardsIgnoreLine

		if ( $force_update ) {
			$body_args['force_update'] = $force_update;
		}

		$request = wp_remote_get(
			self::api_endpoint(),
			array(
				'timeout'    => $force_update ? 25 : 10,
				'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url(),
				'body'       => $body_args,
				'sslverify'  => false,
			)
		);

		return json_decode( wp_remote_retrieve_body( $request ), true );
	}

	/**
	 * Get Block content.
	 *
	 * @param int      $block_id    Block ID.
	 * @param string   $license     Customer license.
	 * @param string   $method      Whether being imported from Elementor, or library.
	 * @param int|bool $site_id     Site ID to fetch Remote template from.
	 * @return mixed|\WP_Error
	 */
	public function get_block_content( $block_id, $license, $method, $site_id ) {
		$url = self::$blocks_endpoint . $block_id;

		if ( Utils::is_container() ) {
			$url = self::$patterns_endpoint . $block_id;
		}

		$body_args = apply_filters( 'analog/api/get_block_content/body_args', self::$api_call_args ); // @codingStandardsIgnoreLine
		$body_args = array_merge(
			$body_args,
			array(
				'license' => $license,
				'url'     => home_url(),
				'method'  => $method,
				'site_id' => $site_id,
			)
		);

		$response = wp_remote_get(
			$url,
			array(
				'timeout'   => 40,
				'sslverify' => false,
				'body'      => $body_args,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response_code = (int) wp_remote_retrieve_response_code( $response );
		if ( 200 !== $response_code ) {
			$error = json_decode( wp_remote_retrieve_body( $response ), true );

			return new \WP_Error( $error['code'], $error['message'] );
		}

		$block_content = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( isset( $block_content['error'] ) ) {
			return new \WP_Error( 'response_error', $block_content['error'] );
		}

		if ( empty( $block_content['content'] ) ) {
			return new \WP_Error( 'block_data_error', 'An invalid data was returned.' );
		}

		return $block_content;
	}

	/**
	 * Get Style Kit tokens data from remote server.
	 *
	 * @since 1.3.4
	 * @param array $kit Style Kit details.
	 *
	 * @return array|mixed|object
	 */
	public function get_stylekit_data( $kit ) {
		$url = self::$kits_endpoint . $kit['id'];

		global $wp_version;
		$body_args = apply_filters( 'analog/api/get_stylekits_data/body_args', self::$api_call_args ); // @codingStandardsIgnoreLine
		$body_args = array_merge(
			$body_args,
			array(
				'license' => Options::get_instance()->get( 'ang_license_key' ),
				'url'     => home_url(),
				'site_id' => $kit['site_id'],
			)
		);

		$request = wp_remote_get(
			$url,
			array(
				'timeout'    => 10,
				'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url(),
				'body'       => $body_args,
				'sslverify'  => false,
			)
		);

		if ( is_wp_error( $request ) ) {
			return new \WP_Error( 'kit_remote_error', $request->get_error_messages() );
		}

		return json_decode( wp_remote_retrieve_body( $request ), true );
	}

	/**
	 * Get a single template content.
	 *
	 * @param int      $template_id Template ID.
	 * @param string   $license Customer license.
	 * @param string   $method Whether being imported from Elementor, library, or page.
	 * @param int|bool $site_id Site ID to fetch Remote template from.
	 * @return mixed|\WP_Error
	 */
	public function get_template_content( $template_id, $license, $method = 'elementor', $site_id = false ) {
		$url = sprintf( self::$template_url, $template_id );

		$body_args = apply_filters( 'analog/api/get_template_content/body_args', self::$api_call_args ); // @codingStandardsIgnoreLine
		$body_args = array_merge(
			$body_args,
			array(
				'license' => $license,
				'url'     => home_url(),
				'method'  => $method,
				'site_id' => $site_id,
			)
		);

		$response = wp_remote_get(
			$url,
			array(
				'timeout'   => 40,
				'body'      => $body_args,
				'sslverify' => false,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response_code = (int) wp_remote_retrieve_response_code( $response );
		if ( 200 !== $response_code ) {
			$error = json_decode( wp_remote_retrieve_body( $response ), true );

			return new \WP_Error( $error['code'], $error['message'] );
		}

		$template_content = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( isset( $template_content['error'] ) ) {
			return new \WP_Error( 'response_error', $template_content['error'] );
		}

		if ( empty( $template_content['data'] ) && empty( $template_content['content'] ) ) {
			return new \WP_Error( 'template_data_error', 'An invalid data was returned.' );
		}

		return $template_content;
	}

	/**
	 * Retrieve starter kits and save as a transient.
	 *
	 * @param boolean $force_update Force new info from remote API.
	 * @return void
	 */
	public static function set_starterkits_info( $force_update = false ) {
		$transient = get_transient( self::$starterkits_transient_key );

		if ( ! $transient || $force_update ) {
			$info = self::request_remote_starterkits_info( $force_update );
			set_transient( self::$starterkits_transient_key, $info, DAY_IN_SECONDS );
		}
	}

	/**
	 * Get Starter kits info.
	 *
	 * @param boolean $force_update Force new info from remote API.
	 *
	 * @return array
	 */
	public function get_starterkits_info( $force_update = false ) {
		if ( ! get_transient( self::$starterkits_transient_key ) || $force_update ) {
			self::set_starterkits_info( true );
		}
		return get_transient( self::$starterkits_transient_key );
	}

	/**
	 * Fetch starter kits info.
	 *
	 * @param boolean $force_update Force update.
	 * @return array $response AnalogWP Starter Kits.
	 */
	public static function request_remote_starterkits_info( $force_update ) {
		global $wp_version;

		$body_args = apply_filters( 'analog/api/get_starterkits/body_args', self::$api_call_args ); // @codingStandardsIgnoreLine

		if ( $force_update ) {
			$body_args['force_update'] = $force_update;
		}

		$request = wp_remote_get(
			self::$starterkits_endpoint,
			array(
				'timeout'    => $force_update ? 25 : 10,
				'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url(),
				'body'       => $body_args,
				'sslverify'  => false,
			)
		);

		return json_decode( wp_remote_retrieve_body( $request ), true );
	}
}

new Remote();
