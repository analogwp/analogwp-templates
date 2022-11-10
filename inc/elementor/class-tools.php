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
use Elementor\Rollback;
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
		add_action( 'admin_post_ang_rollback', array( $this, 'post_ang_rollback' ) );
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
	 * Rollback AnalogWP version.
	 *
	 * @return void
	 * @since 1.2.3
	 */
	public function post_ang_rollback() {
		check_admin_referer( 'ang_rollback' );

		if ( defined( 'STYLEKIT_DEBUG' ) || ! current_user_can( 'update_plugins' ) ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to rollback Style Kits plugin for this site.', 'ang' ) );
		}

		$rollback_versions = Utils::get_rollback_versions();

		$version = filter_input( INPUT_GET, 'version', FILTER_SANITIZE_STRING );

		if ( ! $version || ! in_array( $version, $rollback_versions, true ) ) {
			wp_die( esc_html__( 'Error occurred, the version selected is invalid. Try selecting different version.', 'ang' ) );
		}

		?>
		<style>
			.wrap h1 {
				position: relative;
				padding-top: 140px !important;
			}

			.wrap h1:before {
				content: '';
				position: absolute;
				width: 300px;
				height: 65px;
				color: #fff;
				top: 40px;
				background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 116 24' fill='white' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M65.3219 12.0481C65.3219 15.7023 62.3543 18.6647 58.6935 18.6647C55.0328 18.6647 52.0652 15.7023 52.0652 12.0481C52.0652 8.39391 55.0328 5.43158 58.6935 5.43158C62.3543 5.43158 65.3219 8.39391 65.3219 12.0481Z' %3E%3C/path%3E%3Cpath d='M9.59184 6.29053V7.70526C8.75667 6.51789 7.16224 6.01263 5.7956 6.01263C2.7586 6.01263 0 8.36211 0 12.1516C0 15.9411 2.7586 18.2905 5.7956 18.2905C7.11163 18.2905 8.75667 17.76 9.59184 16.5979V18.0632H12.9072V6.29053H9.59184ZM6.4283 15.2084C4.75796 15.2084 3.366 13.8695 3.366 12.1516C3.366 10.4084 4.75796 9.12 6.4283 9.12C7.97211 9.12 9.49061 10.3326 9.49061 12.1516C9.49061 13.9453 8.04803 15.2084 6.4283 15.2084Z' %3E%3C/path%3E%3Cpath d='M23.113 5.98737C21.9488 5.98737 20.076 6.66947 19.5698 8.26105V6.29053H16.2544V18.0632H19.5698V12.0253C19.5698 9.87789 21.0377 9.24632 22.2272 9.24632C23.3661 9.24632 24.4796 10.08 24.4796 11.9495V18.0632H27.795V11.5958C27.8203 8.05895 26.2006 5.98737 23.113 5.98737Z' %3E%3C/path%3E%3Cpath d='M39.8679 6.29053V7.70526C39.0327 6.51789 37.4383 6.01263 36.0716 6.01263C33.0346 6.01263 30.276 8.36211 30.276 12.1516C30.276 15.9411 33.0346 18.2905 36.0716 18.2905C37.3876 18.2905 39.0327 17.76 39.8679 16.5979V18.0632H43.1832V6.29053H39.8679ZM36.7043 15.2084C35.034 15.2084 33.642 13.8695 33.642 12.1516C33.642 10.4084 35.034 9.12 36.7043 9.12C38.2481 9.12 39.7666 10.3326 39.7666 12.1516C39.7666 13.9453 38.3241 15.2084 36.7043 15.2084Z' %3E%3C/path%3E%3Cpath d='M46.5305 18.0632H49.8458V0H46.5305V18.0632Z' %3E%3C/path%3E%3Cpath d='M58.7973 18.2905C62.1633 18.2905 65.1496 15.8653 65.1496 12.1516C65.1496 8.41263 62.1633 5.98737 58.7973 5.98737C55.4313 5.98737 52.4449 8.41263 52.4449 12.1516C52.4449 15.8653 55.4313 18.2905 58.7973 18.2905ZM58.7973 15.2084C57.1522 15.2084 55.8109 13.9705 55.8109 12.1516C55.8109 10.3074 57.1522 9.06947 58.7973 9.06947C60.4423 9.06947 61.7836 10.3074 61.7836 12.1516C61.7836 13.9705 60.4423 15.2084 58.7973 15.2084Z' %3E%3C/path%3E%3Cpath d='M76.644 6.29053V7.68C75.7835 6.54316 74.189 6.01263 72.8477 6.01263C69.8107 6.01263 67.0521 8.36211 67.0521 12.1516C67.0521 15.9411 69.8107 18.2905 72.8477 18.2905C74.1637 18.2905 75.7835 17.76 76.644 16.6232V16.8C76.644 19.8821 75.3026 21.0442 73.1767 21.0442C71.9113 21.0442 70.6965 20.2863 70.1903 19.2253L67.4317 20.4126C68.4441 22.5853 70.6459 24 73.1767 24C77.3526 24 79.9593 21.6 79.9593 16.4463V6.29053H76.644ZM73.4804 15.2084C71.8101 15.2084 70.4181 13.8695 70.4181 12.1516C70.4181 10.4084 71.8101 9.12 73.4804 9.12C75.0242 9.12 76.5427 10.3326 76.5427 12.1516C76.5427 13.9453 75.1001 15.2084 73.4804 15.2084Z' %3E%3C/path%3E%3Cpath d='M97.6574 6.29053L95.4303 13.4653L93.1779 6.29053H90.3939L88.1415 13.4653L85.9144 6.29053H82.3206L86.623 18.0632H89.4575L91.8112 10.4084L94.2408 18.0632H97.0753L101.251 6.29053H97.6574Z' %3E%3C/path%3E%3Cpath d='M110.204 5.98737C108.863 5.98737 107.243 6.51789 106.408 7.70526V6.29053H103.093V23.8484H106.408V16.5979C107.243 17.7853 108.863 18.2905 110.204 18.2905C113.241 18.2905 116 15.9411 116 12.1516C116 8.36211 113.241 5.98737 110.204 5.98737ZM109.572 15.1832C108.028 15.1832 106.509 13.9705 106.509 12.1516C106.509 10.3579 107.952 9.09474 109.572 9.09474C111.242 9.09474 112.609 10.4337 112.609 12.1516C112.609 13.8947 111.242 15.1832 109.572 15.1832Z' %3E%3C/path%3E%3C/svg%3E");
				background-repeat: no-repeat;
				transform: translate(50%);
			}

			.wrap img {
				display: none;
			}
		</style>
		<?php

		$plugin_slug = 'analogwp-templates';
		$plugin_name = 'analogwp-templates/analogwp-templates.php';

		$rollback = new Rollback(
			array(
				'version'     => $version,
				'plugin_name' => $plugin_name,
				'plugin_slug' => $plugin_slug,
				'package_url' => sprintf( 'https://downloads.wordpress.org/plugin/%s.%s.zip', $plugin_slug, $version ),
			)
		);

		$rollback->run();

		wp_die(
			'',
			esc_html__( 'Rollback to Previous Version', 'ang' ),
			array(
				'response' => 200,
			)
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
