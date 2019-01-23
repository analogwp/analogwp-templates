<?php
/**
 * APIs.
 *
 * @package AnalogWP
 */

namespace Analog\API;

use \Analog\Base;
use \Analog\API\Remote;

defined( 'ABSPATH' ) || exit;

class Local extends Base {
	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_endpoints' ] );
	}

	public function register_endpoints() {
		$endpoints = [
			'/import/elementor' => [
				\WP_REST_Server::CREATABLE => 'handle_import',
			],
			'/templates'        => [
				\WP_REST_Server::READABLE => 'templates_list',
			],
		];

		foreach ( $endpoints as $endpoint => $details ) {
			foreach ( $details as $method => $callback ) {
				register_rest_route(
					'agwp/v1', $endpoint, [
						'methods'  => $method,
						'callback' => [ $this, $callback ],
						// 'permission_callback' => [ $this, 'rest_permission_check' ],
						'args'     => [],
					]
				);
			}
		}
	}

	/**
	 * Check if a given request has access to update a setting
	 *
	 * @param object \WP_REST_Request $request Full data about the request.
	 * @return \WP_Error|bool
	 */
	public function rest_permission_check( $request ) {
		return current_user_can( 'edit_posts' );
	}

	public function handle_import() {
		return 'Hello world';
	}

	public function templates_list( $request ) {
		$force_update = $request->get_param( 'force_update' );

		if ( $force_update ) {
			return Remote::get_instance()->get_templates_info( true );
		}

		return Remote::get_instance()->get_templates_info();
	}

}

new \Analog\API\Local();
