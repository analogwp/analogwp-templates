<?php
/**
 * Class Analog\Admin.
 *
 * @package AnalogWP
 */

namespace Analog\Admin;

use Analog\Base;

/**
 * Class to handle Admin related funtionality.
 *
 * @since 1.4.0
 * @package Analog
 */
final class Admin extends Base {
	/**
	 * Admin constructor.
	 */
	public function __construct() {
		add_filter( 'admin_footer_text', array( $this, 'footer_text' ) );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
	}

	/**
	 * Update footer admin text for Analog screens.
	 *
	 * @access public
	 *
	 * @param string $text Original footer text.
	 *
	 * @return string Updated footer text.
	 */
	public function footer_text( $text ) {
		$current_screen   = get_current_screen();
		$is_analog_screen = 'analogwp_templates' === $current_screen->parent_base;

		if ( $is_analog_screen ) {
			$text = sprintf(
				/* translators: 1: Style Kits for Elementor, 2: Link to plugin review */
				__( 'Enjoyed %1$s? Please leave us a %2$s rating. We really appreciate your support!', 'ang' ),
				'<strong>' . __( 'Style Kits for Elementor', 'ang' ) . '</strong>',
				'<a href="https://analogwp.com/admin-review" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
			);
		}

		return $text;
	}

	/**
	 * Plugin row meta.
	 *
	 * Adds row meta links to the plugin list table.
	 *
	 * @param array  $plugin_meta An array of the plugin's metadata, including the version, author, author URI, and plugin URI.
	 * @param string $plugin_file Path to the plugin file, relative to the plugins directory.
	 *
	 * @return array An array of modified plugin row meta links.
	 */
	public function plugin_row_meta( $plugin_meta, $plugin_file ) {
		if ( ANG_PLUGIN_BASE === $plugin_file ) {
			$row_meta = array(
				'ang_docs'    => '<a href="https://analogwp.com/docs/" aria-label="' . esc_attr( __( 'View Documentation', 'ang' ) ) . '" target="_blank">' . __( 'Documentation', 'ang' ) . '</a>',
				'ang_support' => '<a href="https://analogwp.com/support/" aria-label="' . esc_attr( __( 'Get Support', 'ang' ) ) . '" target="_blank">' . __( 'Get Support', 'ang' ) . '</a>',
			);

			$plugin_meta = array_merge( $plugin_meta, $row_meta );
		}

		return $plugin_meta;
	}
}

new Admin();
