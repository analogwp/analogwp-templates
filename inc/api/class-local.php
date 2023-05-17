<?php
/**
 * APIs.
 *
 * @package AnalogWP
 */

namespace Analog\API;

use Analog\Plugin;
use Analog\Base;
use Analog\Classes\Import_Image;
use Analog\Elementor\Kit\Manager;
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
		add_action( 'rest_api_init', array( $this, 'register_endpoints' ) );
	}

	/**
	 * Register API endpoints.
	 *
	 * @return void
	 */
	public function register_endpoints() {
		$endpoints = array(
			'/import/elementor'        => array(
				WP_REST_Server::CREATABLE => 'handle_import',
			),
			'/import/elementor/direct' => array(
				WP_REST_Server::CREATABLE => 'handle_direct_import',
			),
			'/templates'               => array(
				WP_REST_Server::READABLE => 'templates_list',
			),
			'/mark_favorite/'          => array(
				WP_REST_Server::CREATABLE => 'mark_as_favorite',
			),
			'/get/settings/'           => array(
				WP_REST_Server::READABLE => 'get_settings',
			),
			'/update/settings/'        => array(
				WP_REST_Server::CREATABLE => 'update_setting',
			),
			'/tokens'                  => array(
				WP_REST_Server::READABLE => 'get_tokens',
			),
			'/tokens/save'             => array(
				WP_REST_Server::CREATABLE => 'save_tokens',
			),
			'/tokens/get'              => array(
				WP_REST_Server::CREATABLE => 'get_token',
			),
			'/import/kit'              => array(
				WP_REST_Server::CREATABLE => 'handle_kit_import',
			),
			'/blocks/insert'           => array(
				WP_REST_Server::CREATABLE => 'get_blocks_content',
			),
		);

		foreach ( $endpoints as $endpoint => $details ) {
			foreach ( $details as $method => $callback ) {
				register_rest_route(
					'agwp/v1',
					$endpoint,
					array(
						'methods'             => $method,
						'callback'            => array( $this, $callback ),
						'permission_callback' => array( $this, 'rest_permission_check' ),
						'args'                => array(),
					)
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
			return new WP_REST_Response( array( 'error' => 'Invalid Template ID.' ), 500 );
		}

		if ( $is_pro && ! Utils::has_valid_license() ) {
			return new WP_Error( 'license_error', __( 'Invalid or expired license provided.', 'ang' ) );
		}

		\update_post_meta( $editor_id, '_ang_import_type', 'elementor' );
		\update_post_meta( $editor_id, '_ang_template_id', $template_id );

		// Add import history.
		Utils::add_import_log( $template_id, $editor_id, 'elementor' );

		$obj  = new Analog_Importer();
		$data = $obj->get_data(
			array(
				'template_id'    => $template_id,
				'editor_post_id' => $editor_id,
				'license'        => Options::get_instance()->get( 'ang_license_key' ),
				'method'         => 'elementor',
				'site_id'        => $site_id,
			)
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
	 * Mark a template or block as favorite.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response
	 */
	public function mark_as_favorite( WP_REST_Request $request ) {
		$type = Plugin::$user_meta_prefix;
		if ( ! empty( $request->get_param( 'type' ) ) && 'block' === $request->get_param( 'type' ) ) {
			$type = Plugin::$user_meta_block_prefix;
		}
		$id        = $request->get_param( 'id' );
		$favorite  = $request->get_param( 'favorite' );
		$favorites = get_user_meta( get_current_user_id(), $type, true );

		if ( ! $favorites ) {
			$favorites = array();
		}

		if ( $favorite ) {
			$favorites[ $id ] = $favorite;
		} elseif ( isset( $favorites[ $id ] ) ) {
			unset( $favorites[ $id ] );
		}

		$data                  = array();
		$data['id']            = $id;
		$data['action']        = $favorite;
		$data['update_status'] = update_user_meta( get_current_user_id(), $type, $favorites );
		$data['favorites']     = get_user_meta( get_current_user_id(), $type, true );

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

		$args = array(
			'post_type'    => $with_page ? 'page' : 'elementor_library',
			'post_status'  => $with_page ? 'draft' : 'publish',
			'post_title'   => $with_page ? $with_page : 'AnalogWP: ' . $template['title'],
			'post_content' => '',
		);

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
		$args = array(
			'post_title'   => 'AnalogWP: ' . $block['title'],
			'post_type'    => 'elementor_library',
			'post_status'  => 'publish',
			'post_content' => '',
		);

		$type = Utils::is_container() ? 'container' : 'section';

		$post_id = wp_insert_post( $args );

		if ( $post_id && ! is_wp_error( $post_id ) ) {
			\update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $data['content'] ) ) );
			\update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
			\update_post_meta( $post_id, '_elementor_template_type', $type );
			\update_post_meta( $post_id, '_wp_page_template', 'default' );

			\update_post_meta( $post_id, '_ang_import_type', $method );
			\update_post_meta(
				$post_id,
				'_ang_template_id',
				array(
					'site_id' => $block['siteID'],
					'id'      => $block['id'],
				)
			);

			\wp_set_object_terms( $post_id, $type, 'elementor_library_type' );
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

		$method = $with_page ? 'page' : 'library';

		if ( isset( $template['is_pro'] ) && $template['is_pro'] && ! Utils::has_valid_license() ) {
			return new WP_Error( 'license_error', __( 'Invalid or expired license provided.', 'ang' ) );
		}

		// Initiate template import.
		$obj = new Analog_Importer();

		$data = $obj->get_data(
			array(
				'template_id'    => $template['id'],
				'editor_post_id' => false,
				'license'        => Options::get_instance()->get( 'ang_license_key' ),
				'method'         => $method,
				'site_id'        => $site_id,
			)
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

		$data = array(
			'page' => $page,
		);

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
			return new WP_Error( 'settings_error', __( 'No options key provided.', 'ang' ) );
		}

		Options::get_instance()->set( $key, $value );

		return new WP_REST_Response(
			array( 'message' => __( 'Setting updated.', 'ang' ) ),
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
			array(
				'post_type'      => 'ang_tokens',
				'posts_per_page' => - 1,
			)
		);

		if ( ! $query->have_posts() ) {
			return array();
		}

		$tokens = array();

		while ( $query->have_posts() ) {
			$query->the_post();
			$post_id = get_the_ID();

			$tokens[] = array(
				'id'    => $post_id,
				'title' => get_the_title(),
			);
		}

		wp_reset_postdata();

		return new WP_REST_Response(
			array(
				'tokens' => $tokens,
			),
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
		$settings   = $request->get_param( 'settings' );

		if ( ! isset( $belongs_to, $title, $settings ) ) {
			return new WP_Error( 'kit_params_error', __( 'Invalid param(s).', 'ang' ) );
		}

		if ( ! $title ) {
			return new WP_Error( 'kit_title_error', __( 'Please provide a title.', 'ang' ) );
		}

		$elementor_controls = \get_post_meta( $belongs_to, '_elementor_controls_usage', true );

		$tokens      = json_decode( $settings, true );
		$kit_manager = new Manager();

		$post_id = $kit_manager->create_kit(
			$title,
			array(
				'_elementor_data'           => $kit_manager->get_kit_content(),
				'_elementor_page_settings'  => $tokens,
				'_duplicate_of'             => $belongs_to,
				'_is_analog_user_kit'       => true,
				'_elementor_controls_usage' => $elementor_controls,
			)
		);

		if ( is_wp_error( $post_id ) ) {
			return new WP_Error( 'tokens_error', __( 'Unable to create a Kit', 'ang' ) );
		}

		return new WP_REST_Response(
			array(
				'id'      => $post_id,
				'message' => __( 'The new Theme Style Kit has been saved and applied on this page.', 'ang' ),
			),
			200
		);
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
			array(
				'data' => $tokens_data,
			),
			200
		);
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

		$kit_manager = new Manager();
		$data        = $kit_manager->import_kit( $kit );

		return new WP_REST_Response( $data, 200 );
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
			if ( isset( $kit['is_pro'] ) && $kit['is_pro'] && ! Utils::has_valid_license() ) {
				return new WP_Error( 'kit_import_error', __( 'Invalid license provided.', 'ang' ) );
			}

			$kit_manager = new Manager();
			$import      = $kit_manager->import_kit( $kit );

			if ( ! is_wp_error( $import ) ) {
				$post_id = $import['id'];
			}
		} else {
			$installed_kits = array_flip( Utils::get_kits( false ) );

			if ( isset( $installed_kits[ $kit ] ) ) {
				$post_id = $installed_kits[ $kit ];
			}
		}

		if ( ! $post_id ) {
			return new WP_Error( 'invalid_token_data', __( 'Invalid token data returned', 'ang' ) );
		}

		return array( 'ang_action_tokens' => $post_id );
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

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		return new WP_REST_Response( $data, 200 );
	}

	/**
	 * Process block import functionaliities.
	 *  1. Imports the remote template.
	 *  2. Then with retrieved content, creates a page.
	 *
	 * @uses \Analog\API\Remote::get_instance()->get_block_content()
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
		$license = Options::get_instance()->get( 'ang_license_key' );

		if ( isset( $block['is_pro'] ) && $block['is_pro'] && ! Utils::has_valid_license() ) {
			return new WP_Error( 'block_import_error', __( 'Invalid license provided.', 'ang' ) );
		}

		$raw_data = Remote::get_instance()->get_block_content( $block['id'], $license, $method, $block['siteID'] );
		$importer = new Analog_Importer();

		$data = $importer->get_data(
			array(
				'editor_post_id' => false,
			),
			'display',
			$raw_data
		);

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		if ( 'library' === $method ) {
			$page_id = $this->create_section( $block, $data, $method );

			$payload = array( 'id' => $page_id );
		} else {
			$payload = array( 'data' => $data );
		}

		return $payload;
	}
}

new Local();
