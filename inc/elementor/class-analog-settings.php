<?php
/**
 * Elementor Settings for Analog.
 *
 * @package Analog
 */

namespace Analog\Elementor;

defined( 'ABSPATH' ) || exit;

use Elementor\Settings;
use Analog\Utils;

/**
 * Analog Settings.
 *
 * @since 1.2.0
 */
class Analog_Settings {
	const ANG_GLOBAL_KIT_OPTION_NAME = 'ang_global_kit';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'elementor/admin/after_create_settings/' . Settings::PAGE_ID, [ $this, 'register_admin_fields' ], 100 );

		add_action(
			'update_option_elementor_ang_global_kit',
			function() {
				Utils::add_notice(
					__( 'Global Stylekit Settings Saved. It\'s recommended to close any open elementor tabs in your browser, and re-open them, for the effect to apply.', 'ang' ),
					'info'
				);

				Utils::clear_elementor_cache();
			}
		);
	}

	/**
	 * Register Settings fields.
	 *
	 * @param Settings $settings Settings object.
	 * @return void
	 */
	public function register_admin_fields( Settings $settings ) {
		$tokens_dropdown = [ '' => __( '— Select a Style Kit —', 'ang' ) ] + Utils::get_tokens( false );

		$settings->add_section(
			Settings::TAB_STYLE,
			'analogwp',
			[
				'callback' => function() {
					echo '<hr><h2>' . esc_html__( 'AnalogWP Settings', 'ang' ) . '</h2>';
				},
				'fields'   => [
					self::ANG_GLOBAL_KIT_OPTION_NAME => [
						'label'        => __( 'Global Style Kit', 'ang' ),
						'field_args'   => [
							'type'    => 'select',
							'options' => $tokens_dropdown,
							'desc'    => sprintf(
								/* translators: %s: Style Kit Documentation link */
								__( 'Choosing a Style Kit will make it global and apply site-wide. Learn more about <a href="%s" target="_blank">Style kits</a>.', 'ang' ),
								'https://docs.analogwp.com/article/554-what-are-style-kits'
							),
						],
						'setting_args' => [
							'sanitize_callback' => [ $this, 'sanitize_global_kit' ],
						],
					],
				],
			]
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
