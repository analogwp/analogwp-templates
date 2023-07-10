<?php
/**
 * Extend on Elementor Kit.
 *
 * @package Analog
 */

namespace Analog\Elementor\Kit;

use Analog\Admin\Notice;
use Analog\API\Remote;
use Analog\Options;
use Analog\Plugin;
use Analog\Utils;
use Elementor\Core\Files\CSS\Post as Post_CSS;
use Elementor\Core\Kits\Manager as KitManager;
use Elementor\TemplateLibrary\Source_Local;
use Elementor\User;
use WP_Error;

/**
 * Class Manager.
 *
 * @since 1.6.0
 * @package Analog\Elementor\Kit
 */
class Manager {
	/**
	 * Elementor key storing active kit ID.
	 */
	const OPTION_ACTIVE = 'elementor_active_kit';

	const OPTION_CUSTOM_KIT = '_elementor_page_settings';

	/**
	 * Holds Elementor kits list.
	 *
	 * @var array
	 */
	public $kits;

	/**
	 * Holds current document.
	 *
	 * @var mixed
	 */
	public $document;

	/**
	 * Manager constructor.
	 */
	public function __construct() {
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'frontend_before_enqueue_styles' ), 999 );
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'preview_enqueue_styles' ), 999 );
		add_filter( 'body_class', array( $this, 'should_remove_global_kit_class' ), 999 );
		add_action( 'delete_post', array( $this, 'restore_default_kit' ) );

		add_action(
			'wp_trash_post',
			function ( $post_id ) {
				$this->before_delete_kit( $post_id );
			}
		);

		add_action( 'wp_ajax_nopriv_ang_global_kit', array( $this, 'update_global_kit' ) );
		add_action( 'wp_ajax_ang_global_kit', array( $this, 'update_global_kit' ) );

		add_action( 'wp_ajax_ang_trash_kit', array( $this, 'trash_kit' ) );

		add_action( 'wp_ajax_analog_local_kits_import', array( $this, 'handle_template_import' ) );

		add_action( 'wp_ajax_stylekits_library_direct_actions', array( $this, 'handle_library_actions' ) );

		add_filter(
			'analog_admin_notices',
			function( $notices ) {
				if ( isset( $_GET['success'] ) ) {
					$notices[] = $this->get_kit_notification();
				}
				return $notices;
			}
		);

		if ( ! $this->kits ) {
			$this->kits = Utils::get_kits();
		}

	}

	/**
	 * Restore Elementor default if a custom Kit is deleted, if it was global.
	 *
	 * @param int $post_id Post ID being deleted.
	 *
	 * @since 1.6.0
	 * @return void
	 */
	public function restore_default_kit( $post_id ) {
		if ( Source_Local::CPT !== get_post_type( $post_id ) ) {
			return;
		}

		$global_kit = Options::get_instance()->get( 'global_kit' );

		if ( $global_kit && $post_id === (int) $global_kit ) {
			update_option( self::OPTION_ACTIVE, Options::get_instance()->get( 'default_kit' ) );
		}
	}


	/**
	 * Trash a kit.
	 */
	public function trash_kit() {
		$kit_id = (int) $_REQUEST['kit_id'];

		if ( ! $kit_id ) {
			wp_send_json_error();
		}

		if ( isset( $_REQUEST['ang_trash_kit_nonce'] ) && check_ajax_referer( 'ang_trash_kit', 'ang_trash_kit_nonce' ) ) {
			$kit = get_post( $kit_id );

			if ( ! $kit ) {
				wp_send_json_error();
			}

			$global_kit = Options::get_instance()->get( 'global_kit' );

			if ( $global_kit && $kit_id === (int) $global_kit ) {
				update_option( self::OPTION_ACTIVE, Options::get_instance()->get( 'default_kit' ) );
			}

			wp_trash_post( $kit_id );

			wp_safe_redirect( admin_url() . "admin.php?page=style-kits&trashed={$kit_id}" );
			exit();
		}
	}

	/**
	 * Throws a wp die error page.
	 *
	 * @since 2.0.3
	 * @retun void
	 */
	public function handle_error( $message ) {
		_default_wp_die_handler( $message, 'Style Kits Library' );
	}

	/**
	 * Import kit action from the local style kits library.
	 *
	 * @since 2.0.3
	 * @return void
	 */
	public function handle_template_import() {
		if ( ! User::is_current_user_can_edit_post_type( Source_Local::CPT ) ) {
			return;
		}

		if ( ! check_ajax_referer( 'analog_local_kits_import_nonce', '_nonce' ) ) {
			$this->handle_error( 'Access Denied' );
		}

		$result = Plugin::elementor()->templates_manager->direct_import_template();

		if ( is_wp_error( $result ) ) {
			$this->handle_error( $result->get_error_message() . '.' );
		}

		wp_safe_redirect( admin_url( 'admin.php?page=style-kits' ) );

		die;
	}

	/**
	 * Handle local kits library actions.
	 *
	 * @since 2.0.5
	 * @return void
	 */
	public function handle_library_actions() {
		if ( ! User::is_current_user_can_edit_post_type( Source_Local::CPT ) ) {
			return;
		}

		if ( ! check_ajax_referer( 'stylekits_ajax', '_nonce' ) ) {
			$this->handle_error( 'Access Denied' );
		}

		$action = Utils::get_super_global_value( $_REQUEST, 'library_action' ); // phpcs:ignore -- Nonce already verified.

		$result = $this->$action( $_REQUEST ); // phpcs:ignore -- Nonce already verified.

		if ( is_wp_error( $result ) ) {
			/** @var \WP_Error $result */
			$this->handle_error( $result->get_error_message() . '.' );
		}

		wp_safe_redirect( admin_url( 'admin.php?page=style-kits' ) );

		die;
	}

	/**
	 * Process a Style kit local import.
	 *
	 * @since 2.0.5
	 *
	 * @param array $args Kit arguments.
	 *
	 * @return mixed Whether the export succeeded or failed.
	 */
	public function import_local_kit( array $args ) {
		$file = Utils::get_super_global_value( $_FILES, 'file' );

		if ( empty( $file ) ) {
			return new \WP_Error( 'file_error', 'Please upload a file to import' );
		}

		return $this->process_uploaded_kit( $file['tmp_name'] );

	}

	/**
	 * Import template from a file.
	 *
	 * @since 2.0.5
	 * @access public
	 *
	 * @param string $path - The file path.
	 *
	 * @return \WP_Error|array An array of items on success, 'WP_Error' on failure.
	 */
	public function process_uploaded_kit( $path ) {
		// @todo: Maybe handle multi-uploads.
		// If the import file is a single JSON file.
		$data = json_decode( Utils::file_get_contents( $path ), true );

		if ( empty( $data ) ) {
			return new \WP_Error( 'file_error', 'Invalid File' );
		}

		$content = maybe_unserialize( $data['data'] );

		if ( ! is_array( $content ) ) {
			return new \WP_Error( 'file_error', 'Invalid Content In File' );
		}

		$kit_id = $this->direct_kit_import( $data );

		if ( is_wp_error( $kit_id ) ) {
			return $kit_id;
		}

		return get_post( $kit_id );
	}

	/**
	 * Export a kit.
	 *
	 * @since 2.0.5
	 *
	 * @param array $args Kit arguments.
	 *
	 * @return mixed Whether the export succeeded or failed.
	 */
	public function export_kit( array $args ) {
		$validate_args = $this->ensure_args( array( 'kit_id' ), $args );

		if ( is_wp_error( $validate_args ) ) {
			return $validate_args;
		}

		$file_data = $this->prepare_kit_export( $args['kit_id'] );

		if ( is_wp_error( $file_data ) ) {
			return $file_data;
		}

		header( 'Content-Type: application/octet-stream' );
		header( 'Content-Disposition: attachment; filename=' . $file_data['name'] );
		header( 'Expires: 0' );
		header( 'Cache-Control: must-revalidate' );
		header( 'Pragma: public' );
		header( 'Content-Length: ' . strlen( $file_data['content'] ) );

		// Clear buffering just in case.
		@ob_end_clean();

		flush();

		// Output file contents.
		// PHPCS - Export widget json
		echo $file_data['content']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		die;
	}

	/**
	 * Prepare kit to export.
	 *
	 * Retrieve the relevant template data and return them as an array.
	 *
	 * @since 2.0.5
	 * @access private
	 *
	 * @param int $kit_id The kit ID.
	 *
	 * @return \WP_Error|array Exported kit data.
	 */
	private function prepare_kit_export( $kit_id ) {
		$kit = get_post( $kit_id );

		if ( ! $kit ) {
			return new \WP_Error( 'stylekit_error', 'Style Kit source not found.' );
		}

		$kit_data = serialize( get_post_meta( $kit_id, '_elementor_page_settings', true ) );

		$export_data = array(
			'title' => $kit->post_title,
			'data' => $kit_data,
		);

		return array(
			'name'    => 'stylekits-' . $kit_id . '-' . gmdate( 'Y-m-d' ) . '.json',
			'content' => wp_json_encode( $export_data ),
		);
	}

	/**
	 * Send a confirm message before move a kit to trash, or if delete permanently not for trash.
	 *
	 * @param       $post_id
	 * @param false   $is_permanently_delete
	 */
	private function before_delete_kit( $post_id ) {
		$document = Plugin::elementor()->documents->get( $post_id );

		if (
			! $document ||
			! Plugin::elementor()->kits_manager->is_kit( $post_id ) ||
			! isset( $_GET['ang_action'] ) ||
			isset( $_GET['force_delete_kit'] ) ||  // phpcs:ignore -- nonce validation is not require here.
			( $document->is_trash() )
		) {
			return;
		}

		ob_start();
		require __DIR__ . '/views/trash-kit-confirmation.php';

		$confirmation_content = ob_get_clean();

		// PHPCS - the content does not contain user input value.
		wp_die( new \WP_Error( 'cant_delete_kit', $confirmation_content ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Get current Post object.
	 *
	 * @since 1.6.0
	 * @return \Elementor\Core\Base\Document|false
	 */
	public function get_current_post() {
		if ( ! $this->document ) {
			$this->document = Plugin::elementor()->documents->get_doc_for_frontend( get_the_ID() );
		}

		return $this->document;
	}

	/**
	 * Deterrmine if current post is using a custom Kit or not.
	 *
	 * @since 1.6.0
	 * @return bool
	 */
	public function is_using_custom_kit() {
		if ( ! get_the_ID() ) {
			return false;
		}

		$current_post = $this->get_current_post();

		if ( ! $current_post ) {
			return false;
		}

		$kit_id = $current_post->get_settings_for_display( 'ang_action_tokens' );

		if ( ! $kit_id || '' === $kit_id ) {
			return false;
		}

		// Return early if Global kit and current kit is same.
		$global_kit = Options::get_instance()->get( 'global_kit' );
		if ( ! $global_kit ) {

			$global_kit = ( new KitManager() )->get_active_id();

			Options::get_instance()->set( 'global_kit', $global_kit );
		}

		if ( (int) $global_kit === (int) $kit_id ) {
			return false;
		}

		// Return if current kit doesn't exists in Kits list.
		if ( ! array_key_exists( (int) $kit_id, $this->kits ) ) {
			return false;
		}

		if ( isset( $kit_id ) && '' !== $kit_id ) {
			return true;
		}

		return false;
	}

	/**
	 * Remove Global Kit CSS added by Elementor.
	 *
	 * @since 1.6.0
	 * @return void
	 */
	public function remove_global_kit_css() {
		$kit_id = get_option( self::OPTION_ACTIVE );

		if ( wp_style_is( 'elementor-post-' . $kit_id, 'enqueued' ) ) {
			wp_dequeue_style( 'elementor-post-' . $kit_id );
		}
	}

	/**
	 * Remove Kit class added by Elementor, if user has custom kit.
	 *
	 * Fired by `body_class` filter.
	 *
	 * @param array $classes Body classes.
	 *
	 * @since 1.6.0
	 * @return mixed Modified classes.
	 */
	public function should_remove_global_kit_class( $classes ) {
		if ( $this->is_using_custom_kit() ) {
			$classes = array_unique( $classes );

			$class = 'elementor-kit-' . get_option( self::OPTION_ACTIVE );
			$found = array_search( $class, $classes, true );
			if ( $found ) {
				unset( $classes[ $found ] );
			}
		}

		return $classes;
	}

	/**
	 * Enqueue front-end styles.
	 *
	 * Fired by `elementor/frontend/after_enqueue_global` action.
	 *
	 * @since 1.6.0
	 * @return void
	 */
	public function frontend_before_enqueue_styles() {
		if ( ! $this->is_using_custom_kit() ) {
			return;
		}

		$custom_kit = $this->get_current_post()->get_settings_for_display( 'ang_action_tokens' );

		if ( Options::get_instance()->get( 'global_kit' ) === $custom_kit ) {
			return;
		}

		$post_status = get_post_status( $custom_kit );
		if ( 'publish' !== $post_status ) {
			return;
		}

		if ( Plugin::elementor()->preview->is_preview_mode() ) {
			$this->generate_kit_css();
		} else {
			// TODO: 1.6.1 header/footer make use of this so its not safe to remove.
			  // $this->remove_global_kit_css();
		}

		$css = Post_CSS::create( $custom_kit );
		$css->enqueue();

		Plugin::elementor()->frontend->add_body_class( 'elementor-kit-' . $custom_kit );
	}

	/**
	 * Generate CSS stylesheets for all Kits.
	 *
	 * @since 1.6.0
	 * @return void
	 */
	public function generate_kit_css() {
		$kits = Utils::get_kits();

		foreach ( $kits as $id => $title ) {
			$css = Post_CSS::create( $id );
			$css->enqueue();
		}
	}

	/**
	 * Enqueue Elementor preview styles.
	 *
	 * Fired by `elementor/preview/enqueue_styles` action.
	 *
	 * @since 1.6.0
	 * @return void
	 */
	public function preview_enqueue_styles() {
		if ( ! $this->is_using_custom_kit() ) {
			return;
		}

		Plugin::elementor()->frontend->print_fonts_links();

		$this->frontend_before_enqueue_styles();
	}

	/**
	 * Create an Elementor Kit.
	 *
	 * @param string $title Kit title.
	 * @param array  $meta Kit meta data. Optional.
	 *
	 * @access private
	 * @since 1.6.0
	 * @return string
	 */
	public function create_kit( $title, $meta = array() ) {
		$kit = Plugin::elementor()->documents->create(
			'kit',
			array(
				'post_type'   => Source_Local::CPT,
				'post_title'  => $title,
				'post_status' => 'publish',
				'post_author' => get_current_user_id(),
			),
			$meta
		);

		return $kit->get_id();
	}

	/**
	 * Process a Style Kit remote import.
	 *
	 * @since 1.9.6
	 *
	 * @uses \Analog\Elementor\Kit\Manager
	 *
	 * @param array $kit Array containing Style Kit info to import.
	 * @return WP_Error|array
	 */
	public function import_kit( $kit ) {
		if ( isset( $kit['is_pro'] ) && $kit['is_pro'] && ! Utils::has_valid_license() ) {
			return new WP_Error( 'import_error', 'Invalid license provided.' );
		}

		$remote_kit = Remote::get_instance()->get_stylekit_data( $kit );

		$remote_kit['title'] = $kit['title'] ?? '';

		if ( isset( $remote_kit['message'], $remote_kit['code'] ) ) {
			return new WP_Error( $remote_kit['code'], $remote_kit['message'] );
		}

		if ( is_wp_error( $remote_kit ) ) {
			return new WP_Error( 'kit_import_request_error', __( 'Error occured while requesting Style Kit data.', 'ang' ) );
		}

		return $this->direct_kit_import( $remote_kit );
	}

	/**
	 * Import kit.
	 *
	 * @param array $kit Array containing Style Kit info to import.
	 *
	 * @return WP_Error|array
	 */
	public function direct_kit_import( $kit ) {
		$kit_settings = maybe_unserialize( $kit['data'] );

		$kit_id = $this->create_kit(
			$kit['title'],
			array(
				'_elementor_data'          => $this->get_kit_content(),
				'_elementor_page_settings' => $kit_settings,
				'_is_analog_kit'           => true,
			)
		);

		if ( is_wp_error( $kit_id ) ) {
			return new WP_Error( 'kit_post_error', $kit_id->get_error_message() );
		}

		return array(
			'message' => __( 'Style Kit imported', 'ang' ),
			'id'      => $kit_id,
		);
	}

	/**
	 * Get Kit content
	 *
	 * @since 1.6.0
	 * @return false|string
	 */
	public function get_kit_content() {
		$file = ANG_PLUGIN_DIR . 'inc/elementor/kit/kit-content.json';

		ob_start();
		include $file;

		return ob_get_clean();
	}

	/**
	 * Update global kit.
	 * Hack to prevent showing incorrect kit via standard ajax.
	 *
	 * @since 1.9.5
	 *
	 * @return void
	 */
	public function update_global_kit() {
		$kit_key = 'global_kit';

		if ( ! isset( $_REQUEST[ $kit_key ] ) ) {
			wp_send_json_error();
			return;
		}

		if ( isset( $_REQUEST['ang_global_kit_nonce'] ) && check_ajax_referer( 'ang_global_kit', 'ang_global_kit_nonce' ) ) {
			$kit_id = wp_unslash( $_REQUEST[ $kit_key ] );
			Options::get_instance()->set( $kit_key, $kit_id );
			Utils::set_elementor_active_kit( $kit_id );

			// Regenerate Elementor CSS.
			Utils::clear_elementor_cache();

			wp_send_json_success();
		}
	}

	/**
	 * Show kit update notification.
	 *
	 * @since 1.9.5
	 *
	 * @return Notice
	 */
	public function get_kit_notification() {
		return new Notice(
			'kit_notification',
			array(
				'content'         => sprintf(
					'%1$s&nbsp;<a href="%2$s" target="_blank">%3$s</a>',
					__( 'All good! The Style Kit has been set as Global.', 'ang' ),
					get_bloginfo( 'url' ),
					__( 'View site', 'ang' )
				),
				'type'            => Notice::TYPE_INFO,
				'active_callback' => static function () {
					$screen = get_current_screen();

					return ! ( 'style-kits_page_style-kits' !== $screen->id );
				},
			)
		);
	}

	/**
	 * Ensure arguments exist.
	 *
	 * Checks whether the required arguments exist in the specified arguments.
	 *
	 * @since 2.0.5
	 * @access private
	 *
	 * @param array $required_args  Required arguments to check whether they
	 *                              exist.
	 * @param array $specified_args The list of all the specified arguments to
	 *                              check against.
	 *
	 * @return \WP_Error|true True on success, 'WP_Error' otherwise.
	 */
	private function ensure_args( array $required_args, array $specified_args ) {
		$not_specified_args = array_diff( $required_args, array_keys( $specified_args ) );

		if ( $not_specified_args ) {
			return new \WP_Error( 'arguments_not_specified', sprintf( 'The required argument(s) "%s" not specified.', implode( ', ', $not_specified_args ) ) );
		}

		return true;
	}
}

new Manager();
