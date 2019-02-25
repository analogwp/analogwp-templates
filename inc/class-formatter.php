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
	 * @param array $fields Elementor import data.
	 * @return array
	 */
	public static function remove_typography_data( array $fields ) : array {
		array_walk(
			$fields,
			function ( &$field, $field_name ) {
				$elements = $field->elements;
				foreach ( $elements as $element ) {
					foreach ( $element->elements as $el ) {
						if ( 'widget' === $el->elType ) {
							$settings = $el->settings;
							foreach ( self::get_reset_keys() as $key ) {
								unset( $settings->{$key} );
							}
						} else {
							foreach ( $el->elements as $el1 ) {
								foreach ( $el1 as $el1_child ) {
									if ( \is_object( $el1_child ) || \is_array( $el1_child ) ) {
										foreach ( $el1_child as $el2_child ) {
											if ( isset( $el2_child->elType ) && 'widget' === $el2_child->elType ) {
												$settings = $el2_child->settings;
												foreach ( self::get_reset_keys() as $key ) {
													unset( $settings->{$key} );
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		);

		return $fields;
	}
}

new Formatter();
