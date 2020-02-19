<?php
/**
 * Extend on Elementor Kit.
 *
 * @package Analog
 */

namespace Analog\Elementor\Kit;

use Analog\Admin\Notice;
use Analog\Options;
use Analog\Utils;
use Elementor\Plugin;
use Elementor\Core\Files\CSS\Post as Post_CSS;
use Elementor\TemplateLibrary\Source_Local;

/**
 * Class Manager.
 *
 * @since n.e.x.t
 * @package Analog\Elementor\Kit
 */
class Manager {
	/**
	 * Elementor key storing active kit ID.
	 */
	const OPTION_ACTIVE = 'elementor_active_kit';

	const OPTION_CUSTOM_KIT = '_elementor_page_settings';

	/**
	 * Manager constructor.
	 */
	public function __construct() {
		add_action( 'elementor/frontend/after_enqueue_global', array( $this, 'frontend_before_enqueue_styles' ), 999 );
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'preview_enqueue_styles' ), 999 );
		add_filter( 'body_class', array( $this, 'should_remove_global_kit_class' ), 999 );
		add_action( 'delete_post', array( $this, 'restore_default_kit' ) );

		add_filter(
			'analog_admin_notices',
			function( $notices ) {
				$notices[] = $this->get_kit_edit_notice();

				return $notices;
			}
		);
	}

	/**
	 * Restore Elementor default if a custom Kit is deleted, if it was global.
	 *
	 * @param int $post_id Post ID being deleted.
	 *
	 * @since n.e.x.t
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
	 * Get current Post object.
	 *
	 * @since n.e.x.t
	 * @return \Elementor\Core\Base\Document|false
	 */
	public function get_current_post() {
		return Plugin::$instance->documents->get( get_the_ID() );
	}

	/**
	 * Deterrmine if current post is using a custom Kit or not.
	 *
	 * @since n.e.x.t
	 * @return bool
	 */
	public function is_using_custom_kit() {
		if ( ! get_the_ID() ) {
			return false;
		}

		$kit = $this->get_current_post()->get_meta( self::OPTION_CUSTOM_KIT );

		if ( isset( $kit['ang_action_tokens'] ) && '' !== $kit['ang_action_tokens'] ) {
			return true;
		}

		return false;
	}

	/**
	 * Remove Global Kit CSS added by Elementor.
	 *
	 * @since n.e.x.t
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
	 * @since n.e.x.t
	 * @return mixed Modified classes.
	 */
	public function should_remove_global_kit_class( $classes ) {
		if ( $this->is_using_custom_kit() ) {
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
	 * @since n.e.x.t
	 * @return void
	 */
	public function frontend_before_enqueue_styles() {
		if ( ! $this->is_using_custom_kit() ) {
			return;
		}

		$custom_kit = $this->get_current_post()->get_meta( self::OPTION_CUSTOM_KIT );
		$custom_kit = $custom_kit['ang_action_tokens'];

		$post_status = get_post_status( $custom_kit );
		if ( 'publish' !== $post_status ) {
			return;
		}

		if ( Plugin::$instance->preview->is_preview_mode() ) {
			$this->generate_kit_css();
		} else {
			$this->remove_global_kit_css();
		}

		$css = Post_CSS::create( $custom_kit );
		$css->enqueue();

		Plugin::$instance->frontend->add_body_class( 'elementor-kit-' . $custom_kit );
	}

	/**
	 * Generate CSS stylesheets for all Kits.
	 *
	 * @since n.e.x.t
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
	 * @since n.e.x.t
	 * @return void
	 */
	public function preview_enqueue_styles() {
		if ( ! $this->is_using_custom_kit() ) {
			return;
		}

		Plugin::$instance->frontend->print_fonts_links();

		$this->frontend_before_enqueue_styles();
	}

	/**
	 * Create an Elementor Kit.
	 *
	 * @param string $title Kit title.
	 * @param array  $meta Kit meta data. Optional.
	 *
	 * @access private
	 * @since n.e.x.t
	 * @return string
	 */
	public function create_kit( $title, $meta = array() ) {
		$kit = Plugin::$instance->documents->create(
			'kit',
			array(
				'post_type'   => Source_Local::CPT,
				'post_title'  => $title,
				'post_status' => 'publish',
			),
			$meta
		);

		return $kit->get_id();
	}

	/**
	 * Display Kit edit notice, kits can't directly be edited.
	 *
	 * @since n.e.x.t
	 * @return Notice
	 */
	public function get_kit_edit_notice() {
		return new Notice(
			'kit_migration',
			array(
				'content'         => __( 'As of now, Elementor doesn’t allow to edit Kits directly. You can only edit the kit Styles through the Theme Style panel.', 'ang' ),
				'type'            => Notice::TYPE_WARNING,
				'active_callback' => static function() {
					$screen = get_current_screen();

					return ! ( 'style-kits_page_style-kits' !== $screen->id );
				},
				'dismissible'     => false,
			)
		);
	}

	/**
	 * Get Kit content
	 *
	 * @since n.e.x.t
	 * @return false|string
	 */
	public function get_kit_content() {
		$file = ANG_PLUGIN_DIR . 'inc/elementor/kit/kit-content.json';

		ob_start();
		include $file;

		return ob_get_clean();
	}
}

new Manager();
