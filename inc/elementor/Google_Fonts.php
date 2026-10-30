<?php
/**
 * @package Analog
 */

namespace Analog\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Google_Fonts.
 *
 * @since 1.6.0
 */
class Google_Fonts {
	const TRANSIENT = 'analogwp_google_fonts';

	const RETRY_TRANSIENT = 'analogwp_google_fonts_retry';

	/**
	 * Registers functionality through WordPress hooks.
	 *
	 * @since 1.8.0
	 */
	public function register() {
		add_action( 'init', array( $this, 'fetch_google_fonts' ) );
		add_filter( 'elementor/fonts/additional_fonts', array( $this, 'add_fonts_to_elementor' ) );
	}

	/**
	 * Google fonts are updated automatically every 24 hours on referenced link in repository.
	 * This function refreshes the fonts list everyday to bring all fonts.
	 *
	 * @since 1.8.0
	 */
	public function fetch_google_fonts() {
		if ( get_transient( self::TRANSIENT ) || get_transient( self::RETRY_TRANSIENT ) ) {
			return;
		}

		// This runs on every request, so a failing fetch shouldn't be attempted again for a while.
		set_transient( self::RETRY_TRANSIENT, 1, HOUR_IN_SECONDS );

		$url = 'https://raw.githubusercontent.com/analogwp/google-fonts/main/fonts.json';

		$request = wp_remote_get( $url, array( 'timeout' => 10 ) );

		if ( is_wp_error( $request ) || 200 !== wp_remote_retrieve_response_code( $request ) ) {
			return;
		}

		$body = wp_remote_retrieve_body( $request );

		if ( is_array( json_decode( $body, true ) ) ) {
			set_transient( self::TRANSIENT, $body, DAY_IN_SECONDS );
		}
	}

	/**
	 * Iterate through our Google fonts library and add it to Elementor.
	 *
	 * @param array $additional_fonts Google fonts list.
	 *
	 * @since 1.8.0
	 * @return array|mixed
	 */
	public function add_fonts_to_elementor( $additional_fonts ) {
		$fonts = get_transient( self::TRANSIENT );

		if ( $fonts ) {
			$fonts = json_decode( $fonts, true );
			if ( is_array( $fonts ) && count( $fonts ) ) {
				$formatted_fonts = array();

				foreach ( $fonts as $font ) {
					$formatted_fonts[ $font ] = 'googlefonts';
				}

				$additional_fonts = array_merge( $additional_fonts, $formatted_fonts );
			}
		}

		return $additional_fonts;
	}
}
