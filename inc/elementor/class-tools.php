<?php
/**
 * Analog Elementor Tools.
 *
 * @package AnalogWP
 */

namespace Analog\Elementor;

use Analog\Base;
use Analog\Plugin;
use Analog\Utils;
use Elementor\Core\Base\Document;
use Elementor\TemplateLibrary\Source_Local;
use Elementor\User;
use WP_Post;

/**
 * Analog Elementor Tools.
 *
 * @package Analog\Elementor
 * @since 1.2.1
 */
class Tools extends Base {
	const BULK_EXPORT_ACTION = 'analog_export_multiple_kits';

	const TEMP_FILES_DIR = 'elementor/tmp';

	/**
	 * Fetch documents.
	 *
	 * Holds the list of all documents fetched currently.
	 *
	 * @var array
	 */
	protected $documents;

	/**
	 * Tools constructor.
	 */
	public function __construct() {
		$this->add_actions();
	}

	/**
	 * Add all actions and filters.
	 */
	private function add_actions() {
		add_filter( 'display_post_states', array( $this, 'stylekit_post_state' ), 20, 2 );

		add_filter( 'post_row_actions', array( $this, 'filter_post_row_actions' ), 15, 2 );
		add_filter( 'page_row_actions', array( $this, 'filter_post_row_actions' ), 15, 2 );

		add_action( 'wp_ajax_ang_make_global', array( $this, 'post_global_stylekit' ) );

		add_action( 'heartbeat_received', array( $this, 'heartbeat_received' ), 10, 2 );
	}

	/**
	 * Handle WP_Error message.
	 *
	 * @access private
	 *
	 * @param string $message Error message.
	 */
	private function handle_wp_error( $message ) {
		_default_wp_die_handler( $message, 'Style Kits for Elementor' );
	}

	/**
	 * Checks if current screen is Style Kits CPT screen.
	 *
	 * @deprecated 1.6.0
	 *
	 * @return bool
	 */
	public static function is_tokens_screen() {
		global $current_screen;

		if ( ! $current_screen ) {
			return false;
		}

		return 'edit' === $current_screen->base && 'ang_tokens' === $current_screen->post_type;
	}

	/**
	 * Returns a link to make a Style Kit Global.
	 *
	 * @access private
	 * @return string
	 */
	private function get_stylekit_global_link() {
		return add_query_arg(
			array(
				'action'  => 'ang_make_global',
				'post_id' => get_the_ID(),
			),
			admin_url( 'admin-ajax.php' )
		);
	}

	/**
	 * Fetch a post.
	 *
	 * @since 1.6.1
	 * @param int|string $id Post ID.
	 *
	 * @return mixed
	 */
	protected function get_post( $id ) {
		if ( ! isset( $this->documents[ $id ] ) ) {
			$this->documents[ $id ] = get_post( $id );
		}

		return $this->documents[ $id ];
	}

	/**
	 * Add Style Kit post state.
	 *
	 * Adds a new "Style Kit: %s" post state to the post table.
	 *
	 * Fired by `display_post_states` filter.
	 *
	 * @param array   $post_states An array of post display states.
	 * @param WP_Post $post The current post object.
	 *
	 * @return array A filtered array of post display states.
	 * @since 1.2.3
	 * @access public
	 */
	public function stylekit_post_state( $post_states, $post ) {
		global $pagenow;

		$page = Plugin::elementor()->documents->get( $post->ID );

		// Bail if not a document.
		if ( ! $page instanceof Document ) {
			return $post_states;
		}

		$supported_pages = array( 'edit.php', 'admin-ajax.php' );

		if (
			User::is_current_user_can_edit( $post->ID ) &&
			$page->is_built_with_elementor() && in_array( $pagenow, $supported_pages, true )
		) {
			$settings   = get_post_meta( $post->ID, '_elementor_page_settings', true );
			$global_kit = (string) Utils::get_global_kit_id();

			if ( isset( $settings['ang_action_tokens'] ) && '' !== $settings['ang_action_tokens'] ) {
				$kit_id = (string) $settings['ang_action_tokens'];

				// Return early, if Page Kit and Global Kit are same.
				if ( $global_kit === $kit_id ) {
					return $post_states;
				}

				$kit = $this->get_post( $kit_id );

				if ( ! $kit || Source_Local::CPT !== $kit->post_type ) {
					return $post_states;
				}

				if ( '' !== $global_kit && 'publish' === $kit->post_status ) {
					/* translators: %s: Style kit title. */
					$post_states['style_kit'] = sprintf( __( 'Style Kit: %s <span style="color:#5C32B6;">&#9679;</span>', 'ang' ), $kit->post_title );
				}
			}
		}

		return $post_states;
	}

	/**
	 * Add custom post action.
	 *
	 * @param array  $actions Existing actions.
	 * @param object $post Post object.
	 *
	 * @return mixed
	 */
	public function filter_post_row_actions( $actions, $post ) {
		if ( User::is_current_user_can_edit( $post->ID ) && Plugin::elementor()->documents->get( $post->ID )->is_built_with_elementor() ) {
			$settings   = get_post_meta( $post->ID, '_elementor_page_settings', true );
			$global_kit = (string) Utils::get_global_kit_id();

			$display = true;

			if ( isset( $settings['ang_action_tokens'] ) ) {
				$kit_id = (string) $settings['ang_action_tokens'];

				if ( ! array_key_exists( (int) $kit_id, Utils::get_kits() ) ) {
					$display = false;
				}

				if ( $global_kit !== $kit_id && $display ) {
					$actions['apply_global_kit'] = sprintf(
						'<a href="%1$s">%2$s</a>',
						wp_nonce_url( $this->get_stylekit_global_link(), 'ang_make_global' ),
						__( 'Apply Global Style Kit', 'ang' )
					);
				}
			}
		}

		return $actions;
	}

	/**
	 * Ajax action for applying Global stylekit to specific post.
	 *
	 * @return void
	 * @since 1.2.3
	 */
	public function post_global_stylekit() {
		check_admin_referer( 'ang_make_global' );

		if ( ! isset( $_REQUEST['post_id'] ) ) {
			exit;
		}

		$post_id = $_REQUEST['post_id'];
		$token   = get_post_meta( Utils::get_global_kit_id(), '_tokens_data', true );
		$token   = json_decode( $token, ARRAY_A );

		$token['ang_action_tokens'] = (string) Utils::get_global_kit_id();

		update_post_meta( $post_id, '_elementor_page_settings', $token );

		Utils::clear_elementor_cache();

		wp_safe_redirect( wp_get_referer() );
		exit;
	}

	/**
	 * Send posts using Style Kit to heartbeat API for later use.
	 *
	 * @param array $response Heartbeat response.
	 * @param array $data Heartbeat data sent as `$_POST`.
	 *
	 * @since 1.3.12
	 * @return mixed
	 */
	public function heartbeat_received( $response, $data ) {
		if ( isset( $data['ang_sk_post']['kit_id'] ) ) {
			$kit_id  = (int) $data['ang_sk_post']['kit_id'];
			$post_id = (int) $data['ang_sk_post']['post_id'];
			$updated = $data['ang_sk_post']['updated'];

			$posts = Utils::posts_using_stylekit( $kit_id );
			$posts = array_values( array_diff( $posts, array( $post_id ) ) );

			$key = 'ang_sks_using_' . $kit_id;
			if ( 'false' !== $updated ) {
				set_transient( $key, $posts, 60 );
			}
			$cached = get_transient( $key );

			$response['sk_posts'] = $cached;
		}

		return $response;
	}
}

Tools::get_instance();
