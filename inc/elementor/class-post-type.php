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
	}

	/**
	 * CPT labels.
	 *
	 * @return array
	 */
	public function labels() {
		return [
			'name'               => __( 'Tokens', 'ang' ),
			'singular_name'      => __( 'Token', 'ang' ),
			'menu_name'          => _x( 'Tokens', 'admin menu', 'ang' ),
			'name_admin_bar'     => _x( 'Token', 'add new on admin bar', 'ang' ),
			'add_new'            => _x( 'Add New', 'book', 'ang' ),
			'add_new_item'       => __( 'Add New Token', 'ang' ),
			'new_item'           => __( 'New Token', 'ang' ),
			'edit_item'          => __( 'Edit Token', 'ang' ),
			'view_item'          => __( 'View Token', 'ang' ),
			'all_items'          => __( 'Tokens', 'ang' ),
			'search_items'       => __( 'Search Tokens', 'ang' ),
			'parent_item_colon'  => __( 'Parent Tokens:', 'ang' ),
			'not_found'          => __( 'No tokens found.', 'ang' ),
			'not_found_in_trash' => __( 'No tokens found in Trash.', 'ang' ),
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
			'show_in_menu'      => 'analogwp_templates',
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
		];

		$args = apply_filters( 'analog/elementor/cpt/tokens/args', $args, $this->labels() );

		register_post_type( self::CPT, $args );
	}
}

new Post_Type();
