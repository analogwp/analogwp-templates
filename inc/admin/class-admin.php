<?php
/**
 * Class Analog\Admin.
 *
 * @package AnalogWP
 */

namespace Analog;

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
		add_filter( 'admin_footer_text', [ $this, 'footer_text' ] );
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
				'<a href="https://wordpress.org/support/plugin/analogwp-templates/reviews/?filter=5/#new-post" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
			);
		}

		return $text;
	}
}

new Admin();
