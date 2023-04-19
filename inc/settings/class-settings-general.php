<?php
/**
 * Analog General Settings
 *
 * @package Analog/Admin
 * @since 1.3.8
 */

namespace Analog\Settings;

use Analog\Utils;
use Analog\API\Remote;

defined( 'ABSPATH' ) || exit;

/**
 * General.
 */
class General extends Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'general';
		$this->label = __( 'General', 'ang' );

		parent::__construct();
	}

	/**
	 * Get sections.
	 *
	 * @return array
	 */
	public function get_sections() {
		$sections = array(
			''            => __( 'General', 'ang' ),
			'starter-kit' => __( 'Starter Kit', 'ang' ),
		);
		return apply_filters( 'ang_get_sections_' . $this->id, $sections );
	}

	/**
	 * Get settings array.
	 *
	 * @param string $current_section Current section ID.
	 *
	 * @return array
	 */
	public function get_settings( $current_section = '' ) {
		global $current_section;

		$settings = array();

		if ( '' === $current_section ) {
			$default_import_method = array();

			if ( ! Utils::is_elementor_container() ) {
				$default_import_method = array(
					'id'    => 'use_global_sk',
					'title' => esc_html_x( 'Template import method', 'settings title', 'ang' ),
					'desc'  => sprintf(
					/* translators: %s: Global Style Kit Documentation link */
						__( 'Always import templates using the Global Style Kit. %s', 'ang' ),
						'<a href="https://analogwp.com/docs/default-template-import-method/" target="_blank">' . __( 'Read more', 'ang' ) . '</a>'
					),
					'type'  => 'checkbox',
				);
			}

			$settings = array(
				array(
					'title' => '',
					'type'  => 'title',
					'id'    => 'ang_color_palette',
				),
				array(
					'title' => esc_html_x( 'Global Style Kit', 'settings title', 'ang' ),
					'desc'  => sprintf(
					/* translators: %s: Local Style Kits page link */
						__( 'This option is now in %s page.', 'ang' ),
						'<a href="' . esc_url( admin_url( 'admin.php?page=style-kits' ) ) . '">' . __( 'Local Style Kits', 'ang' ) . '</a>'
					),
					'id'    => 'global_kit_helper',
					'type'  => 'deprecated-notice',
				),
				$default_import_method,
				array(
					'id'      => 'allow_svg_uploads',
					'title'   => esc_html_x( 'Enable SVG Uploads', 'settings title', 'ang' ),
					'desc'    => sprintf(
					/* translators: %s: Global Style Kit Documentation link */
						__( 'Helps importing SVGs in templates. %s', 'ang' ),
						'<a href="https://analogwp.com/docs/enable-svg-imports-in-patterns" target="_blank">' . __( 'Read more', 'ang' ) . '</a>'
					),
					'type'    => 'checkbox',
					'default' => true,
				),
				array(
					'id'      => 'hide_legacy_features',
					'title'   => esc_html_x( 'Hide legacy features', 'settings title', 'ang' ),
					'desc'    => sprintf(
					/* translators: %s: Legacy features Documentation link */
						__( 'Hide legacy features from the Style Kit panel. %s', 'ang' ),
						'<a href="https://analogwp.com/docs/what-are-legacy-features/" target="_blank">' . __( 'Read more', 'ang' ) . '</a>'
					),
					'type'    => 'checkbox',
					'default' => true,
				),
				array(
					'id'    => 'onboarding_link',
					'title' => esc_html_x( 'Setup', 'settings title', 'ang' ),
					'desc'  => __( 'Trigger the setup wizard manually', 'ang' ),
					'to'    => admin_url( 'admin.php?page=analog_onboarding' ),
					'type'  => 'button',
					'class' => 'ang-button button-secondary',
					'value' => __( 'Restart wizard', 'ang' ),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'ang_color_palette',
				),
			);
			$settings = apply_filters( 'ang_' . $this->id . '_settings', $settings );
		} elseif ( 'starter-kit' === $current_section ) {
			$response = Remote::get_instance()->get_starterkits_info();

			if ( $_GET && isset( $_GET['refresh'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$response = Remote::get_instance()->get_starterkits_info( true );
			}

			$settings = array(
				array(
					'id'                => 'ang-starter-kits',
					'title'             => __( 'Download Starter Kit', 'ang' ),
					'desc'              => sprintf(
						'%1$s <a href="https://analogwp.com/docs/pulse-starter-kit/" target="_blank">%2$s</a>',
						__( 'Download a site kit zip that you can import into your website.', 'ang-pro' ),
						__( 'Learn more', 'ang-pro' ),
					),
					'type'              => 'starter-kits',
					'download_btn_text' => __( 'Download ZIP', 'ang' ),
					'demo_btn_text'     => __( 'View Demo', 'ang' ),
					'kits'              => $response['starterkits'] ?? array(),
				),
			);

			$settings = apply_filters( 'ang_' . $this->id . '_settings', $settings );
		}

		return apply_filters( 'ang_get_settings_' . $this->id, $settings );
	}

	/**
	 * Save settings.
	 */
	public function save() {
		global $current_section;

		$settings = $this->get_settings( $current_section );

		Admin_Settings::save_fields( $settings );
		if ( $current_section ) {
			do_action( 'ang_update_options_' . $this->id . '_' . $current_section );
		}
	}
}

return new General();
