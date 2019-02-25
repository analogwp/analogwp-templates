<?php
/**
 * Format content during Elementor import.
 *
 * @package Analog
 */

namespace Analog;

/**
 * Elementor Formatter.
 */
class Formatter {

	/**
	 * Returns the list of keys to reset values of.
	 *
	 * @return array
	 */
	private static function get_reset_keys() {
		return [
			'typography_typography',
			'typography_font_family',

			'typography_font_size',
			'typography_font_size_mobile',
			'typography_font_size_tablet',

			'typography_font_style',
			'typography_font_weight',

			'typography_line_height',
			'typography_line_height_mobile',
			'typography_line_height_tablet',

			'typography_letter_spacing',
			'typography_letter_spacing_mobile',
			'typography_letter_spacing_tablet',

			'typography_text_decoration',
			'typography_text_transform',
		];
	}

	/**
	 * Strip off Typography specific data.
	 *
	 * @param mixed $haystack Elementor import data.
	 * @return mixed
	 */
	public static function remove_typography_data_recursive( $haystack ) {
		if ( is_array( $haystack ) ) {
			foreach ( self::get_reset_keys() as $key ) {
				unset( $haystack[ $key ] );
			}
			foreach ( $haystack as $k => $value ) {
				if ( is_array( $haystack ) ) {
					$haystack[ $k ] = self::remove_typography_data_recursive( $value );
				}
			}
		}
		return $haystack;
	}
}

new Formatter();
