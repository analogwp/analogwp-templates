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
	 * Last updated on: 2020/11/26
	 *
	 * Total 28 Fonts.
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
				'Big Shoulders Inline Display' => 'googlefonts',
				'Big Shoulders Inline Text' => 'googlefonts',
				'Big Shoulders Stencil Display' => 'googlefonts',
				'Big Shoulders Stencil Text' => 'googlefonts',
				'Castoro' => 'googlefonts',
				'Commissioner' => 'googlefonts',
				'Epilogue' => 'googlefonts',
				'Goldman' => 'googlefonts',
				'Grandstander' => 'googlefonts',
				'Kufam' => 'googlefonts',
				'Kumbh Sans' => 'googlefonts',
				'Libre Barcode EAN13 Text' => 'googlefonts',
				'Mulish' => 'googlefonts',
				'Nerko One' => 'googlefonts',
				'Piazzolla' => 'googlefonts',
				'Recursive' => 'googlefonts',
				'Red Rose' => 'googlefonts',
				'Rowdies' => 'googlefonts',
				'Sansita Swashed' => 'googlefonts',
				'Sora' => 'googlefonts',
				'Space Grotesk' => 'googlefonts',
				'Syne' => 'googlefonts',
				'Syne Mono' => 'googlefonts',
				'Syne Tactile' => 'googlefonts',
				'Texturina' => 'googlefonts',
				'Trispace' => 'googlefonts',
				'Varta' => 'googlefonts',
				'Xanh Mono' => 'googlefonts',
			)
		);
	}
}
