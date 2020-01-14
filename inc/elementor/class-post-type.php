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
		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * CPT labels.
	 *
	 * @return array
	 */
	public function labels() {
		return array(
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
		);
	}

	/**
	 * Register post type.
	 *
	 * @return void
	 */
	public function register() {
		$args = array(
			'labels'            => $this->labels(),
			'hierarchical'      => false,
			'show_ui'           => apply_filters( 'analog_tokens_visibility', true ),
			'show_in_menu'      => false,
			'show_in_nav_menus' => false,
			'show_in_admin_bar' => false,
			'show_admin_column' => false,
			'rewrite'           => false,
			'public'            => false,
			'supports'          => array(
				'title',
				'author',
				'thumbnail',
				'custom-fields',
			),
			'capabilities'      => array(
				'create_posts' => 'do_not_allow',
			),
			'map_meta_cap'      => true,
		);

		$args = apply_filters( 'analog/elementor/cpt/tokens/args', $args, $this->labels() );

		register_post_type( self::CPT, $args );
	}
}

new Post_Type();
