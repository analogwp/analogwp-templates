<?php
/**
 * APIs.
 *
 * @package AnalogWP
 */

namespace Analog\API;

use Analog\Analog_Templates;
use Analog\Base;
use Analog\Classes\Import_Image;
use Analog\Options;
use Analog\Utils;
use Elementor\TemplateLibrary\Analog_Importer;
use WP_Error;
use WP_Query;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

defined( 'ABSPATH' ) || exit;

/**
 * Local APIs.
 *
 * @package Analog\API
 */
class Local extends Base {
	/**
	 * Local constructor.
	 */
	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_endpoints' ] );
	}

	/**
	 * Register API endpoints.
	 *
	 * @return void
	 */
	public function register_endpoints() {
		$endpoints = [
			'/import/elementor'        => [
				WP_REST_Server::CREATABLE => 'handle_import',
			],
			'/import/elementor/direct' => [
				WP_REST_Server::CREATABLE => 'handle_direct_import',
			],
			'/templates'               => [
				WP_REST_Server::READABLE => 'templates_list',
			],
			'/mark_favorite/'          => [
				WP_REST_Server::CREATABLE => 'mark_as_favorite',
			],
			'/get/settings/'           => [
				WP_REST_Server::READABLE => 'get_settings',
			],
			'/update/settings/'        => [
				WP_REST_Server::CREATABLE => 'update_setting',
			],
			'/tokens'                  => [
				WP_REST_Server::READABLE => 'get_tokens',
			],
			'/tokens/save'             => [
				WP_REST_Server::CREATABLE => 'save_tokens',
			],
			'/tokens/get'              => [
				WP_REST_Server::CREATABLE => 'get_token',
			],
			'/tokens/update'           => [
				WP_REST_Server::CREATABLE => 'update_token',
			],
			'/import/kit'              => [
				WP_REST_Server::CREATABLE => 'handle_kit_import',
			],
			'/blocks/insert'           => [
				WP_REST_Server::CREATABLE => 'get_blocks_content',
			],
		];

		foreach ( $endpoints as $endpoint => $details ) {
			foreach ( $details as $method => $callback ) {
				register_rest_route(
					'agwp/v1',
					$endpoint,
					[
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
	 * @return WP_Error|bool
	 */
	public function rest_permission_check() {
		return current_user_can( 'edit_posts' );
	}

	/**
	 * Handle template import.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function handle_import( WP_REST_Request $request ) {
		$template_id = $request->get_param( 'template_id' );
		$editor_id   = $request->get_param( 'editor_post_id' );
		$is_pro      = (bool) $request->get_param( 'is_pro' );
		$site_id     = $request->get_param( 'site_id' );
		$kit_info    = $request->get_param( 'kit' );

		if ( ! $template_id ) {
			return new WP_REST_Response( [ 'error' => 'Invalid Template ID.' ], 500 );
		}

		$license = false;

		if ( $is_pro ) {
			// Fetch license only when necessary, throw error if not found.
			$license = Options::get_instance()->get( 'ang_license_key' );
			if ( empty( $license ) ) {
				return new WP_Error( 'import_error', 'Invalid license provided.' );
			}
		}

		\update_post_meta( $editor_id, '_ang_import_type', 'elementor' );
		\update_post_meta( $editor_id, '_ang_template_id', $template_id );

		// Add import history.
		Utils::add_import_log( $template_id, $editor_id, 'elementor' );

		$obj  = new Analog_Importer();
		$data = $obj->get_data(
			[
				'template_id'    => $template_id,
				'editor_post_id' => $editor_id,
				'license'        => $license,
				'method'         => 'elementor',
				'site_id'        => $site_id,
				'options'        => [
					'remove_typography' => Options::get_instance()->get( 'ang_remove_typography' ),
				],
			]
		);

		if ( $kit_info && isset( $kit_info['data'] ) ) {
			$tokens = $this->fetch_kit_content( $kit_info['data'] );

			$data['tokens'] = $tokens;
		}

		return new WP_REST_Response( wp_json_encode( maybe_unserialize( $data ) ), 200 );
	}

	/**
	 * Get all templates.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return array
	 */
	public function templates_list( WP_REST_Request $request ) {
		$force_update = $request->get_param( 'force_update' );

		if ( $force_update ) {
			return Remote::get_instance()->get_templates_info( true );
		}

		return Remote::get_instance()->get_templates_info();
	}

	/**
	 * Mark a template as favorite.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response
	 */
	public function mark_as_favorite( WP_REST_Request $request ) {
		$template_id         = $request->get_param( 'template_id' );
		$favorite            = $request->get_param( 'favorite' );
		$favorites_templates = get_user_meta( get_current_user_id(), Analog_Templates::$user_meta_prefix, true );

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
		$data['update_status'] = update_user_meta( get_current_user_id(), Analog_Templates::$user_meta_prefix, $favorites_templates );
		$data['favorites']     = get_user_meta( get_current_user_id(), Analog_Templates::$user_meta_prefix, true );

		return new WP_REST_Response( $data, 200 );
	}

	/**
	 * Create page during import if opted.
	 *
	 * @param array $template Template data object.
	 * @param bool  $with_page Whether to install in Elementor library or Page CPT.
	 *
	 * @return int|WP_Error
	 */
	private function create_page( $template, $with_page = false ) {
		if ( ! $template ) {
			return new WP_Error( 'import_error', 'Invalid Template ID.' );
		}

		$args = [
			'post_type'    => $with_page ? 'page' : 'elementor_library',
			'post_status'  => $with_page ? 'draft' : 'publish',
			'post_title'   => $with_page ? $with_page : 'AnalogWP: ' . $template['title'],
			'post_content' => '',
		];

		$new_post_id = wp_insert_post( $args );

		/**
		 * Small hack to later avoid loading default values in Elementor.
		 */
		if ( is_array( $template['tokens'] ) ) {
			$template['tokens']['ang_recently_imported'] = 'yes';
		}
		\update_post_meta( $new_post_id, '_elementor_data', $template['content'] );
		\update_post_meta( $new_post_id, '_elementor_page_settings', wp_slash( $template['tokens'] ) );
		\update_post_meta( $new_post_id, '_elementor_template_type', $template['type'] );
		\update_post_meta( $new_post_id, '_elementor_edit_mode', 'builder' );

		if ( $new_post_id && ! is_wp_error( $new_post_id ) ) {
			\update_post_meta( $new_post_id, '_ang_import_type', $with_page ? 'page' : 'library' );
			\update_post_meta( $new_post_id, '_ang_template_id', $template['id'] );
			\update_post_meta( $new_post_id, '_wp_page_template', ! empty( $template['page_template'] ) ? $template['page_template'] : 'elementor_canvas' );

			if ( ! $with_page ) {
				wp_set_object_terms( $new_post_id, ! empty( $template['elementor_library_type'] ) ? $template['elementor_library_type'] : 'page', 'elementor_library_type' );
			}

			return $new_post_id;
		}

		return new WP_Error( 'import_error', 'Unable to create page.' );
	}

	/**
	 * Creates a 'Section' for Elementor.
	 *
	 * @since 1.4.0
	 *
	 * @uses wp_insert_post()
	 *
	 * @param array  $block Block details.
	 * @param array  $data Block content.
	 * @param string $method Import method.
	 *
	 * @return int Post ID.
	 */
	private function create_section( array $block, $data, $method ) {
		$args = [
			'post_title'   => 'AnalogWP: ' . $block['title'],
			'post_type'    => 'elementor_library',
			'post_status'  => 'publish',
			'post_content' => '',
		];

		$post_id = wp_insert_post( $args );

		if ( $post_id && ! is_wp_error( $post_id ) ) {
			\update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $data['content'] ) ) );
			\update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
			\update_post_meta( $post_id, '_elementor_template_type', 'section' );
			\update_post_meta( $post_id, '_wp_page_template', 'default' );

			\update_post_meta( $post_id, '_ang_import_type', $method );
			\update_post_meta(
				$post_id,
				'_ang_template_id',
				[
					'site_id' => $block['siteID'],
					'id'      => $block['id'],
				]
			);

			\wp_set_object_terms( $post_id, 'section', 'elementor_library_type' );
		}

		return (int) $post_id;
	}

	/**
	 * Handle template imports from settings page.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @uses \Elementor\TemplateLibrary\Analog_Importer
	 * @uses Utils::convert_string_to_boolean()
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function handle_direct_import( WP_REST_Request $request ) {
		$template  = $request->get_param( 'template' );
		$with_page = $request->get_param( 'with_page' );
		$site_id   = $request->get_param( 'site_id' );
		$kit_info  = $request->get_param( 'kit' );

		$method  = $with_page ? 'page' : 'library';
		$license = false;

		if ( $template['is_pro'] ) {
			// Fetch license only when necessary, throw error if not found.
			$license = Options::get_instance()->get( 'ang_license_key' );
			if ( empty( $license ) ) {
				return new WP_Error( 'import_error', 'Invalid license provided.' );
			}
		}

		// Initiate template import.
		$obj = new Analog_Importer();

		$data = $obj->get_data(
			[
				'template_id'    => $template['id'],
				'editor_post_id' => false,
				'license'        => $license,
				'method'         => $method,
				'site_id'        => $site_id,
				'options'        => [
					'remove_typography' => Options::get_instance()->get( 'ang_remove_typography' ),
				],
			]
		);

		if ( ! is_array( $data ) ) {
			return new WP_Error( 'import_error', 'Error fetching template content.', $data );
		}

		// Attach template content to template array for later use.
		$template['content'] = wp_slash( wp_json_encode( $data['content'] ) );
		$template['tokens']  = $data['tokens'];

		if ( $kit_info ) {
			$kit_content = $this->fetch_kit_content( $kit_info['data'] );
			if ( ! is_wp_error( $kit_content ) ) {
				$template['tokens'] = $kit_content;
			}
		}

		// Finally create the page.
		$page = $this->create_page( $template, $with_page );

		// Add import history.
		Utils::add_import_log( $template['id'], $page, $method );

		$data = [
			'page' => $page,
		];

		return new WP_REST_Response( $data, 200 );
	}

	/**
	 * Get plugin settings.
	 *
	 * @return WP_REST_Response
	 */
	public function get_settings() {
		$options = Options::get_instance()->get();

		return new WP_REST_Response( $options, 200 );
	}

	/**
	 * Update plugin settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function update_setting( WP_REST_Request $request ) {
		$key   = $request->get_param( 'key' );
		$value = $request->get_param( 'value' );

		if ( ! $key ) {
			return new WP_Error( 'settings_error', 'No options key provided.' );
		}

		Options::get_instance()->set( $key, $value );

		return new WP_REST_Response(
			[ 'message' => __( 'Setting updated.', 'ang' ) ],
			200
		);
	}

	/**
	 * Get registered tokens.
	 *
	 * @return WP_REST_Response|array
	 * @since 1.2
	 */
	public function get_tokens() {
		$query = new WP_Query(
			[
				'post_type'      => 'ang_tokens',
				'posts_per_page' => - 1,
			]
		);

		if ( ! $query->have_posts() ) {
			return [];
		}

		$tokens = [];

		while ( $query->have_posts() ) {
			$query->the_post();
			$post_id = get_the_ID();

			$tokens[] = [
				'id'    => $post_id,
				'title' => get_the_title(),
			];
		}

		wp_reset_postdata();

		return new WP_REST_Response(
			[
				'tokens' => $tokens,
			],
			200
		);
	}

	/**
	 * Save tokens.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_Error|WP_REST_Response
	 * @since 1.2.0
	 */
	public function save_tokens( WP_REST_Request $request ) {
		$belongs_to = $request->get_param( 'id' );
		$title      = $request->get_param( 'title' );
		$tokens     = $request->get_param( 'tokens' );

		if ( ! $tokens ) {
			return new WP_Error( 'tokens_error', 'No tokens data found.' );
		}
		if ( ! $title ) {
			return new WP_Error( 'tokens_error', 'Please provide a title.' );
		}

		$args = [
			'post_type'   => 'ang_tokens',
			'post_title'  => $title,
			'post_status' => 'publish',
			'meta_input'  => [
				'belongs_to'   => $belongs_to,
				'_tokens_data' => $tokens,
			],
		];

		/**
		 * Save token arguments. Filters the arguments for wp_insert_post().
		 *
		 * @param string $args Arguments.
		 * @param string $title Post Title.
		 * @param string $tokens Tokens data.
		 * @param string $belongs_to Post/Page ID of the page tokens are being saved from.
		 */
		$args = apply_filters( 'analog/elementor/save/tokens/args', $args, $title, $tokens, $belongs_to );

		$post_id = wp_insert_post( $args );

		if ( is_wp_error( $post_id ) ) {
			return new WP_Error( 'tokens_error', __( 'Unable to create a post', 'ang' ) );
		} else {
			return new WP_REST_Response(
				[
					'id'      => $post_id,
					'message' => __( 'Token saved.', 'ang' ),
				],
				200
			);
		}
	}

	/**
	 * Get all templates.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function get_token( WP_REST_Request $request ) {
		$id = $request->get_param( 'id' );

		if ( ! $id ) {
			return new WP_Error( 'tokens_error', __( 'Please provide a valid post ID.', 'ang' ) );
		}

		if ( ! get_post( $id ) ) {
			return new WP_Error( 'tokens_error', __( 'Invalid Post ID', 'ang' ) );
		}

		$tokens_data = get_post_meta( $id, '_tokens_data', true );

		return new WP_REST_Response(
			[
				'data' => $tokens_data,
			],
			200
		);
	}

	/**
	 * Update a token.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function update_token( WP_REST_Request $request ) {
		$id         = $request->get_param( 'id' );
		$tokens     = $request->get_param( 'tokens' );
		$current_id = $request->get_param( 'current_id' );

		if ( ! $id ) {
			return new WP_Error( 'tokens_error', __( 'Please provide a valid post ID.', 'ang' ) );
		}

		$data = \update_post_meta( $id, '_tokens_data', wp_slash( $tokens ) );

		do_action( 'analog/token/update', $id, $data );

		// Update other posts using Style kit. Avoid updating Style kit itself when being edited.
		if ( $id !== $current_id ) {
			Utils::refresh_posts_using_stylekit( $tokens, $id, $current_id );
			Utils::clear_elementor_cache();
		}

		return new WP_REST_Response( $data, 200 );
	}

	/**
	 * Handle remote Style Kit import.
	 *
	 * @since 1.3.4
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error
	 */
	public function handle_kit_import( WP_REST_Request $request ) {
		$kit = $request->get_param( 'kit' );

		if ( ! $kit ) {
			return new WP_Error( 'kit_import_error', __( 'Invalid Style Kit ID.', 'ang' ) );
		}

		$data = $this->process_kit_import( $kit );

		return new WP_REST_Response( $data, 200 );
	}

	/**
	 * Process a Style Kit import.
	 *
	 * @since 1.3.8
	 *
	 * @param array $kit Array containing Style Kit info to import.
	 * @return WP_Error|array
	 */
	protected function process_kit_import( $kit ) {
		$remote_kit = Remote::get_instance()->get_stylekit_data( $kit['id'] );

		if ( is_wp_error( $remote_kit ) ) {
			return new WP_Error( 'kit_import_request_error', __( 'Error occured while requesting Style Kit data.', 'ang' ) );
		}

		$tokens_data = $remote_kit['data'];

		$post_args = [
			'post_type'   => 'ang_tokens',
			'post_title'  => $kit['title'],
			'post_status' => 'publish',
			'meta_input'  => [
				'_tokens_data' => $tokens_data,
				'_import_type' => 'remote',
			],
		];

		$post = wp_insert_post( apply_filters( 'analog/kits/remote/create', $post_args ) );

		if ( is_wp_error( $post ) ) {
			return new WP_Error( 'kit_post_error', $post->get_error_message() );
		} else {
			$attachment = Import_Image::get_instance()->import(
				[
					'id'  => wp_rand( 000, 999 ),
					'url' => $kit['image'],
				]
			);

			update_post_meta( $post, '_thumbnail_id', $attachment['id'] );

			return [
				'message' => __( 'Style Kit imported', 'ang' ),
				'id'      => $post,
			];
		}
	}

	/**
	 * Fetch a Style Kit's tokens.
	 *
	 * @since 1.3.8
	 *
	 * @param array|string $kit Kit Info.
	 * @return array|WP_Error Kit tokens or WP_Error object.
	 */
	protected function fetch_kit_content( $kit ) {
		$post_id = false;

		if ( is_array( $kit ) && isset( $kit['id'] ) ) {
			$import = $this->process_kit_import( $kit );

			if ( ! is_wp_error( $import ) ) {
				$post_id = $import['id'];
			}
		} else {
			$find    = get_page_by_title( $kit, OBJECT, 'ang_tokens' );
			$post_id = $find->ID;
		}

		$tokens = json_decode( get_post_meta( $post_id, '_tokens_data', true ), true );
		if ( is_array( $tokens ) ) {
			$tokens += [ 'ang_action_tokens' => $post_id ];
		}

		if ( ! $tokens ) {
			return new WP_Error( 'invalid_token_data', __( 'Invalid token data returned', 'ang' ) );
		} else {
			return $tokens;
		}
	}

	/**
	 * Handle remote "Blocks" import.
	 *
	 * @since 1.3.4
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response|WP_Error
	 */
	public function get_blocks_content( WP_REST_Request $request ) {
		$block  = $request->get_param( 'block' );
		$method = $request->get_param( 'method' );

		if ( ! $block ) {
			return new WP_Error( 'block_import_error', __( 'Invalid Block ID.', 'ang' ) );
		}

		$data = $this->process_block_import( $block, $method );

		return new WP_REST_Response( $data, 200 );
	}

	/**
	 * Process block import functionaliities.
	 *  1. Imports the remote template.
	 *  2. Then with retrieved content, creates a page.
	 *
	 * @uses \Analog\API\Remote::::get_instance()->get_block_content()
	 * @uses \Elementor\TemplateLibrary\Analog_Importer
	 *
	 * @since 1.4.0
	 *
	 * @param array  $block Block data.
	 * @param string $method Import method.
	 *
	 * @return array|WP_Error
	 */
	protected function process_block_import( $block, $method = 'library' ) {
		$license = false;

		if ( $block['is_pro'] ) {
			// Fetch license only when necessary, throw error if not found.
			$license = Options::get_instance()->get( 'ang_license_key' );
			if ( empty( $license ) ) {
				return new WP_Error( 'import_error', 'Invalid license provided.' );
			}
		}

		$raw_data = Remote::get_instance()->get_block_content( $block['id'], $license, $method, $block['siteID'] );
		$importer = new Analog_Importer();

		$data = $importer->get_data(
			[
				'editor_post_id' => false,
				'options'        => [
					'remove_typography' => Options::get_instance()->get( 'ang_remove_typography' ),
				],
			],
			'display',
			$raw_data
		);

		if ( 'library' === $method ) {
			$page_id = $this->create_section( $block, $data, $method );

			$payload = [ 'id' => $page_id ];
		} else {
			$payload = [ 'data' => $data ];
		}

		return $payload;
	}
}

new Local();
