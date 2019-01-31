<?php
/**
 * APIs.
 *
 * @package AnalogWP
 */

namespace Analog\API;

use \Analog\Base;
use \Analog\API\Remote;
use \Analog\Options;

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
			'/import/elementor/direct' => [
				\WP_REST_Server::CREATABLE => 'handle_direct_import',
			],
			'/templates'        => [
				\WP_REST_Server::READABLE => 'templates_list',
			],
			'/mark_favorite/'        => [
				\WP_REST_Server::CREATABLE => 'mark_as_favorite',
			],
			'/settings/'        => [
				\WP_REST_Server::READABLE => 'get_settings',
			],
			'/settings/'        => [
				\WP_REST_Server::CREATABLE => 'update_setting',
			],
		];

		foreach ( $endpoints as $endpoint => $details ) {
			foreach ( $details as $method => $callback ) {
				register_rest_route(
					'agwp/v1', $endpoint, [
						'methods'             => $method,
						'callback'            => [ $this, $callback ],
						'permission_callback' => [ $this, 'rest_permission_check' ],
						'args'                => [],
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

	public function handle_import( \WP_REST_Request $request ) {
		$template_id = $request->get_param( 'template_id' );
		$editor_id   = $request->get_param( 'editor_post_id' );

		if ( ! $template_id ) {
			return new \WP_REST_Response( [ 'error' => 'Invalid Template ID.' ], 500 );
		}

		$obj  = new \Elementor\TemplateLibrary\Analog_Importer();
		$data = $obj->get_data([
			'template_id'    => $template_id,
			'editor_post_id' => $editor_id,
		]);

		return new \WP_REST_Response( json_encode( maybe_unserialize( $data ) ), 200 );
	}

	public function templates_list( $request ) {
		$force_update = $request->get_param( 'force_update' );

		if ( $force_update ) {
			return Remote::get_instance()->get_templates_info( true );
		}

		return Remote::get_instance()->get_templates_info();
	}

	public function mark_as_favorite( \WP_REST_Request $request ) {
		$template_id         = $request->get_param( 'template_id' );
		$favorite            = $request->get_param( 'favorite' );
		$favorites_templates = get_user_meta( get_current_user_id(), \Analog\Analog_Templates::$user_meta_prefix, true );

		if ( ! $favorites_templates ) {
			$favorites_templates = [];
		}

		if ( $favorite ) {
			$favorites_templates[ $template_id ] = $favorite;
		} elseif ( isset( $favorites_templates[ $template_id ] ) ) {
			unset( $favorites_templates[ $template_id ] );
		}

		$data                  = [];
		$data['template_id']   = $template_id;
		$data['action']        = $favorite;
		$data['update_status'] = update_user_meta( get_current_user_id(), \Analog\Analog_Templates::$user_meta_prefix, $favorites_templates );
		$data['favorites']     = get_user_meta( get_current_user_id(), \Analog\Analog_Templates::$user_meta_prefix, true );

		return new \WP_REST_Response( $data, 200 );
	}

	private function create_page( $template, $with_page = false ) {
		if ( ! $template ) {
			return new \WP_Error( 'import_error', 'Invalid Template ID.' );
		}

		$args = [
			'post_type'    => $with_page ? 'page' : 'elementor_library',
			'post_status'  => $with_page ? 'draft' : 'publish',
			'post_title'   => $with_page ? $with_page : 'AnalogWP: ' . $template['title'],
			'post_content' => '',
		];

		$new_post_id = wp_insert_post( $args );
		update_post_meta( $new_post_id, '_elementor_data', $template['content'] );
		update_post_meta( $new_post_id, '_elementor_template_type', $template['type'] );
		update_post_meta( $new_post_id, '_elementor_edit_mode', 'builder' );

		if ( $new_post_id && ! is_wp_error( $new_post_id ) ) {
			update_post_meta( $new_post_id, '_ang_import_type', 'library' );
			update_post_meta( $new_post_id, '_ang_template_id', $template['id'] );
			update_post_meta( $new_post_id, '_wp_page_template', ! empty( $template['page_template'] ) ? $template['page_template'] : 'elementor_canvas' );

			if ( ! $with_page ) {
				wp_set_object_terms( $new_post_id, ! empty( $template['elementor_library_type'] ) ? $template['elementor_library_type'] : 'page', 'elementor_library_type' );
			}

			return $new_post_id;
		}

		return new \WP_Error( 'import_error', 'Unable to create page.' );
	}

	public function handle_direct_import( \WP_REST_Request $request ) {
		$template  = $request->get_param( 'template' );
		$with_page = $request->get_param( 'with_page' );

		if ( $template['is_pro'] ) {
			// TODO: Validate license here.
			return new \WP_Error( 'import_error', 'Invalid license provided.' );
		}

		// Initiate template import.
		$obj = new \Elementor\TemplateLibrary\Analog_Importer();

		$data = $obj->get_data([
			'template_id'    => $template['id'],
			'editor_post_id' => false,
		]);

		if ( ! is_array( $data ) ) {
			return new \WP_Error( 'import_error', 'Error fetching template content.', $data );
		}

		// Attach template content to template array for later use.
		$template['content'] = $data['content'];

		// Finally create the page.
		$page = $this->create_page( $template, $with_page );

		$data = [
			'page' => $page,
		];

		return new \WP_REST_Response( $data, 200 );
	}

	public function get_settings( \WP_REST_Request $request ) {
		$options = Options::get_instance()->get();

		return new \WP_REST_Response( $options, 200 );
	}

	public function update_setting( \WP_REST_Request $request ) {
		$key   = $request->get_param( 'key' );
		$value = $request->get_param( 'value' );

		if ( ! $key || ! $value ) {
			return new \WP_Error( 'settings_error', 'No options key provided.' );
		}

		$options = Options::get_instance()->set( $key, $value );

		return new \WP_REST_Response( $options, 200 );
	}
}

new \Analog\API\Local();
