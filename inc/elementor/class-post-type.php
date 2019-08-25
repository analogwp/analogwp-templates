<?php
/**
 * Register Post Types for Elementor.
 *
 * @package Analog
 */

namespace Analog\Elementor;

/**
 * Post_Type class.
 *
 * @since 1.2
 */
class Post_Type {
	const CPT = 'ang_tokens';

	/**
	 * Constructor class.
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'register' ] );
		add_filter( 'display_post_states', [ $this, 'post_state' ], 20, 2 );
	}

	/**
	 * Add Style Kit post state on Kits CPT page.
	 *
	 * @param array  $post_states An array of post display states.
	 * @param object $post The current post object.
	 *
	 * @return array A filtered array of post display states.
	 * @since 1.3.4
	 * @access public
	 */
	public function post_state( $post_states, $post ) {
		if ( 'ang_tokens' === get_post_type( $post ) && 'remote' === get_post_meta( $post->ID, '_import_type', true ) ) {
			$post_states['kit_type'] = '<em style="background:#3152FF;border-radius:50%;width:20px;height:20px;display:inline-flex;align-items:center;justify-content:center;"><img width="10" src="' . ANG_PLUGIN_URL . 'assets/img/triangle.svg"></em>';
		}
		return $post_states;
	}

	/**
	 * CPT labels.
	 *
	 * @return array
	 */
	public function labels() {
		return [
			'name'               => __( 'Style Kits', 'ang' ),
			'singular_name'      => __( 'Style Kit', 'ang' ),
			'menu_name'          => _x( 'Style Kits', 'admin menu', 'ang' ),
			'name_admin_bar'     => _x( 'Style Kit', 'add new on admin bar', 'ang' ),
			'add_new'            => _x( 'Add New', 'book', 'ang' ),
			'add_new_item'       => __( 'Add New Style Kit', 'ang' ),
			'new_item'           => __( 'New Style Kit', 'ang' ),
			'edit_item'          => __( 'Edit Style Kit', 'ang' ),
			'view_item'          => __( 'View Style Kit', 'ang' ),
			'all_items'          => __( 'Style Kits', 'ang' ),
			'search_items'       => __( 'Search Style Kits', 'ang' ),
			'parent_item_colon'  => __( 'Parent Style Kits:', 'ang' ),
			'not_found'          => __( 'No Style Kit found.', 'ang' ),
			'not_found_in_trash' => __( 'No Style Kit found in Trash.', 'ang' ),
		];
	}

	/**
	 * Register post type.
	 *
	 * @return void
	 */
	public function register() {
		$args = [
			'labels'            => $this->labels(),
			'hierarchical'      => false,
			'show_ui'           => apply_filters( 'analog_tokens_visibility', true ),
			'show_in_menu'      => false,
			'show_in_nav_menus' => false,
			'show_in_admin_bar' => false,
			'show_admin_column' => false,
			'rewrite'           => false,
			'public'            => false,
			'supports'          => [
				'title',
				'author',
				'thumbnail',
				'custom-fields',
			],
			'capabilities'      => [
				'create_posts' => 'do_not_allow',
			],
			'map_meta_cap'      => true,
		];

		$args = apply_filters( 'analog/elementor/cpt/tokens/args', $args, $this->labels() );

		register_post_type( self::CPT, $args );
	}
}

new Post_Type();
