<?php
/**
 * Analog User Roles Settings
 *
 * @package Analog/Admin
 */

namespace Analog\settings;

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'Settings_User_Roles', false ) ) {
	return new Settings_User_Roles();
}

/**
 * Admin_Settings_User_Roles.
 */
class Settings_User_Roles extends Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'uroles';
		$this->label = __( 'User Roles', 'ang' );

		parent::__construct();
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {

		$settings = apply_filters(
			'ang_user_roles_settings',
			array(
				array(
					'title' => __( 'User Roles Management', 'ang' ),
					'type'  => 'title',
					'id'    => 'user_roles_manage',
					'desc'  => __( 'Disable Style Kits  functionality for specific user roles', 'ang' ),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'user_roles_manage',
				),
				array(
					'title' => __( 'Template Gallery', 'ang' ),
					'type'  => 'title',
					'id'    => 'temp_gallery_manage',
					'desc'  => __( 'Disabled user roles will not be able to import Style Kits templates', 'ang' ),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'temp_gallery',
				),
				array(
					'title' => __( 'Style Kits Panels (Editor)', 'ang' ),
					'type'  => 'title',
					'id'    => 'style_kits_panels_manage',
					'desc'  => __( 'Disabled user roles will not be able to import Style Kits templates', 'ang' ),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'style_kits_panels',
				),
				array(
					'title' => __( 'WordPress dashboard panel', 'ang' ),
					'type'  => 'title',
					'id'    => 'wp_dashboard_panel_manage',
					'desc'  => __( 'Disabled user roles will not be able to import Style Kits templates', 'ang' ),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'wp_dashboard_panel',
				),

				array(
					'title' => __( 'Blocks', 'ang' ),
					'type'  => 'title',
					'id'    => 'blocks_manage',
					'desc'  => __( 'Disabled user roles will not be able to import Style Kits templates', 'ang' ),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'blocks',
				),
			)
		);

		return apply_filters( 'ang_get_settings_' . $this->id, $settings );
	}

	/**
	 * Output the settings.
	 */
	public function output() {
		$settings = $this->get_settings();

		Admin_Settings::output_fields( $settings );
	}

	/**
	 * Save settings.
	 */
	public function save() {
		$settings = $this->get_settings();

		Admin_Settings::save_fields( $settings );
	}
}

return new Settings_User_Roles();
