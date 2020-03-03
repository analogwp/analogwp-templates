<?php
/**
 * Elementor Settings for Analog.
 *
 * @package Analog
 */

namespace Analog\Elementor;

defined( 'ABSPATH' ) || exit;

use Elementor\Settings;

/**
 * Analog Settings.
 *
 * @deprecated
 * @since 1.2.0
 */
class Analog_Settings {
	const ANG_GLOBAL_KIT_OPTION_NAME = 'ang_global_kit_deprecated';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'elementor/admin/after_create_settings/' . Settings::PAGE_ID, array( $this, 'register_admin_fields' ), 100 );
	}

	/**
	 * Register Settings fields.
	 *
	 * @param Settings $settings Settings object.
	 * @return void
	 */
	public function register_admin_fields( Settings $settings ) {
		$settings->add_section(
			Settings::TAB_STYLE,
			'analogwp',
			array(
				'callback' => function() {
					echo '<hr><h2>' . esc_html__( 'Style Kits for Elementor Settings', 'ang' ) . '</h2>';
				},
				'fields'   => array(
					self::ANG_GLOBAL_KIT_OPTION_NAME => array(
						'label'      => __( 'Global Style Kit', 'ang' ),
						'field_args' => array(
							'type' => 'raw_html',
							'html' => sprintf(
								/* translators: %s: Style Kit Documentation link */
								__( 'This setting has been moved to %s.', 'ang' ),
								'<a href="' . admin_url( 'admin.php?page=ang-settings&tab=general#global_kit' ) . '">' . __( 'Style Kit settings', 'ang' ) . '</a>'
							),
						),
					),
				),
			)
		);
	}

	/**
	 * Sanitize function for Global Style Kit.
	 *
	 * @param string|mixed $input Option value.
	 * @return string|mixed Sanitized Option value.
	 */
	public function sanitize_global_kit( $input ) {
		return $input;
	}
}

new Analog_Settings();
