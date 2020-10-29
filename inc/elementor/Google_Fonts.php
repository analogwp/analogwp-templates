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
	 * Last updated on: 2020/10/29
	 *
	 * Total 17 Fonts.
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
				'Commissioner' => 'googlefonts',
				'Epilogue' => 'googlefonts',
				'Grandstander' => 'googlefonts',
				'Kufam' => 'googlefonts',
				'Kumbh Sans' => 'googlefonts',
				'Mulish' => 'googlefonts',
				'Piazzolla' => 'googlefonts',
				'Recursive' => 'googlefonts',
				'Red Rose' => 'googlefonts',
				'Rowdies' => 'googlefonts',
				'Sansita Swashed' => 'googlefonts',
				'Sora' => 'googlefonts',
				'Syne' => 'googlefonts',
				'Syne Mono' => 'googlefonts',
				'Syne Tactile' => 'googlefonts',
				'Trispace' => 'googlefonts',
				'Varta' => 'googlefonts',
			)
		);
	}
}
