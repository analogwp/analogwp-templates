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
		$this->label = __( 'General', 'analogwp-templates' );

		parent::__construct();
	}

	/**
	 * Get sections.
	 *
	 * @return array
	 */
	public function get_sections() {
		$sections = array(
			''            => __( 'General', 'analogwp-templates' ),
			'starter-kit' => __( 'Starter Kit', 'analogwp-templates' ),
		);
		return apply_filters( 'ang_get_sections_' . $this->id, $sections ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	}

	/**
	 * Get settings array.
	 *
	 * @param string $current_section Current section ID.
	 *
	 * @return array
	 */
	public function get_settings( $current_section = '' ) {
		global $ang_current_section;

		if ( '' === $current_section && ! empty( $ang_current_section ) ) {
			$current_section = $ang_current_section;
		}

		$settings = array();

		if ( '' === $current_section ) {
			$default_import_method = array();

			if ( ! Utils::is_elementor_container() ) {
				$default_import_method = array(
					'id'    => 'use_global_sk',
					'title' => esc_html_x( 'Template import method', 'settings title', 'analogwp-templates' ),
					'desc'  => sprintf(
					/* translators: %s: Global Style Kit Documentation link */
						__( 'Always import templates using the Global Style Kit. %s', 'analogwp-templates' ),
						'<a href="https://analogwp.com/docs/default-template-import-method/" target="_blank">' . __( 'Read more', 'analogwp-templates' ) . '</a>'
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
					'title' => esc_html_x( 'Global Style Kit', 'settings title', 'analogwp-templates' ),
					'desc'  => sprintf(
					/* translators: %s: Local Style Kits page link */
						__( 'This option is now in %s page.', 'analogwp-templates' ),
						'<a href="' . esc_url( admin_url( 'admin.php?page=style-kits' ) ) . '">' . __( 'Local Style Kits', 'analogwp-templates' ) . '</a>'
					),
					'id'    => 'global_kit_helper',
					'type'  => 'deprecated-notice',
				),
				$default_import_method,
				array(
					'id'      => 'allow_svg_uploads',
					'title'   => esc_html_x( 'Enable SVG Uploads', 'settings title', 'analogwp-templates' ),
					'desc'    => sprintf(
					/* translators: %s: Global Style Kit Documentation link */
						__( 'Helps importing SVGs in templates. %s', 'analogwp-templates' ),
						'<a href="https://analogwp.com/docs/enable-svg-imports-in-patterns" target="_blank">' . __( 'Read more', 'analogwp-templates' ) . '</a>'
					),
					'type'    => 'checkbox',
					'default' => true,
				),
				array(
					'id'      => 'hide_legacy_features',
					'title'   => esc_html_x( 'Hide legacy features', 'settings title', 'analogwp-templates' ),
					'desc'    => sprintf(
					/* translators: %s: Legacy features Documentation link */
						__( 'Hide legacy features from the Style Kit panel. %s', 'analogwp-templates' ),
						'<a href="https://analogwp.com/docs/what-are-legacy-features/" target="_blank">' . __( 'Read more', 'analogwp-templates' ) . '</a>'
					),
					'type'    => 'checkbox',
					'default' => true,
				),
				array(
					'id'    => 'onboarding_link',
					'title' => esc_html_x( 'Setup', 'settings title', 'analogwp-templates' ),
					'desc'  => __( 'Trigger the setup wizard manually', 'analogwp-templates' ),
					'to'    => admin_url( 'admin.php?page=analog_onboarding' ),
					'type'  => 'button',
					'class' => 'ang-button button-secondary',
					'value' => __( 'Restart wizard', 'analogwp-templates' ),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'ang_color_palette',
				),
			);
			$settings = apply_filters( 'ang_' . $this->id . '_settings', $settings ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		} elseif ( 'starter-kit' === $current_section ) {
			$response = Remote::get_instance()->get_starterkits_info();

			if ( $_GET && isset( $_GET['refresh'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$response = Remote::get_instance()->get_starterkits_info( true );
			}

			$settings = array(
				array(
					'id'                => 'ang-starter-kits',
					'title'             => __( 'Download Starter Kit', 'analogwp-templates' ),
					'desc'              => sprintf(
						'%1$s <a href="https://analogwp.com/docs/pulse-starter-kit/" target="_blank">%2$s</a>',
						__( 'Download a site kit zip that you can import into your website.', 'analogwp-templates' ),
						__( 'Learn more', 'analogwp-templates' ),
					),
					'type'              => 'starter-kits',
					'download_btn_text' => __( 'Download ZIP', 'analogwp-templates' ),
					'demo_btn_text'     => __( 'View Demo', 'analogwp-templates' ),
					'kits'              => $response['starterkits'] ?? array(),
				),
			);

			$settings = apply_filters( 'ang_' . $this->id . '_settings', $settings ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		}

		return apply_filters( 'ang_get_settings_' . $this->id, $settings ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	}

	/**
	 * Save settings.
	 */
	public function save() {
		global $ang_current_section;

		$settings = $this->get_settings( $ang_current_section );

		Admin_Settings::save_fields( $settings );
		if ( $ang_current_section ) {
			do_action( 'ang_update_options_' . $this->id . '_' . $ang_current_section ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		}
	}
}

return new General();
