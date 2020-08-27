<?php
/**
 * @package Analog
 */

namespace Analog\Elementor;

/**
 * Class Google_Fonts.
 *
 * @since 1.6.0
 */
class Google_Fonts {
	/**
	 * Return an array of all available Google Fonts.
	 *
	 * Last updated on: 2020/08/27
	 *
	 * Total 7 Fonts.
	 *
	 * @since 1.6.0
	 *
	 * @return array    All Google Fonts.
	 */
	public static function get_google_fonts() {
		/**
		 * Allow developers to modify the allowed Google fonts.
		 *
		 * @param array $fonts The list of Google fonts with variants and subsets.
		 */
		return apply_filters(
			'analog_get_google_fonts',
			array(
				'Epilogue'  => 'googlefonts',
				'Mulish'    => 'googlefonts',
				'Recursive' => 'googlefonts',
				'Red Rose'  => 'googlefonts',
				'Rowdies'   => 'googlefonts',
				'Sora'      => 'googlefonts',
				'Varta'     => 'googlefonts',
			)
		);
	}
}
