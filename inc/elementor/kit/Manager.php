<?php
/**
 * Extend on Elementor Kit.
 *
 * @package Analog
 */

namespace Analog\Elementor\Kit;

use Elementor\Plugin;
use Elementor\Core\Files\CSS\Post as Post_CSS;

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

	const OPTION_CUSTOM_KIT = '_ang_custom_kit';

	/**
	 * Manager constructor.
	 */
	public function __construct() {
		add_action( 'elementor/frontend/after_enqueue_global', array( $this, 'frontend_before_enqueue_styles' ), 20 );
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'preview_enqueue_styles' ), 0 );
		add_filter( 'body_class', array( $this, 'should_remove_global_kit_class' ) );
	}

	/**
	 * Get current Post object.
	 *
	 * @return \Elementor\Core\Base\Document|false
	 */
	public function get_current_post() {
		return Plugin::$instance->documents->get( get_the_ID() );
	}

	/**
	 * Deterrmine if current post is using a custom Kit or not.
	 *
	 * @return bool
	 */
	public function is_using_custom_kit() {
		$kit = $this->get_current_post()->get_meta( self::OPTION_CUSTOM_KIT );

		return ( '' !== $kit ) ? true : false;
	}

	/**
	 * Remove Global Kit CSS added by Elementor.
	 *
	 * @return void
	 */
	public function remove_global_kit_css() {
		$kit_id = get_option( self::OPTION_ACTIVE );

		wp_dequeue_style( 'elementor-post-' . $kit_id );
	}

	/**
	 * Remove Kit class added by Elementor, if user has custom kit.
	 *
	 * Fired by `body_class` filter.
	 *
	 * @param array $classes Body classes.
	 * @return mixed Modified classes.
	 */
	public function should_remove_global_kit_class( $classes ) {
		if ( $this->is_using_custom_kit() ) {
			$class = 'elementor-kit-' . get_option( self::OPTION_ACTIVE );
			unset( $classes[ $class ] );
		}

		return $classes;
	}

	/**
	 *
	 * Fired by `elementor/frontend/after_enqueue_global` action.
	 *
	 * @return void
	 */
	public function frontend_before_enqueue_styles() {
		if ( ! $this->is_using_custom_kit() ) {
			return;
		}

		$custom_kit = $this->get_current_post()->get_meta( self::OPTION_CUSTOM_KIT );

		$this->remove_global_kit_css();
		$css = Post_CSS::create( $custom_kit );
		$css->enqueue();
		Plugin::$instance->frontend->add_body_class( 'elementor-kit-' . $custom_kit );
	}

	/**
	 * Enqueue Elementor preview styles.
	 *
	 * Fired by `elementor/preview/enqueue_styles` action.
	 *
	 * @return void
	 */
	public function preview_enqueue_styles() {
		if ( ! $this->is_using_custom_kit() ) {
			return;
		}

		Plugin::$instance->frontend->print_fonts_links();

		$this->frontend_before_enqueue_styles();
	}
}

new Manager();
