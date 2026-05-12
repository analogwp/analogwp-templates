<?php
/**
 * Class Analog\Admin.
 *
 * @package AnalogWP
 */

namespace Analog\Admin;

use Analog\Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
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
				'ang_docs'    => '<a href="https://analogwp.com/docs/" aria-label="' . esc_attr( __( 'View Documentation', 'analogwp-templates' ) ) . '" target="_blank">' . __( 'Documentation', 'analogwp-templates' ) . '</a>',
				'ang_support' => '<a href="https://analogwp.com/support/" aria-label="' . esc_attr( __( 'Get Support', 'analogwp-templates' ) ) . '" target="_blank">' . __( 'Get Support', 'analogwp-templates' ) . '</a>',
			);

			$plugin_meta = array_merge( $plugin_meta, $row_meta );
		}

		return $plugin_meta;
	}
}

new Admin();
