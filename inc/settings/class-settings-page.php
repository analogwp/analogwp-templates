<?php
/**
 * Analog Settings Page/Tab
 *
 * @author      AnalogWP
 * @category    Admin
 * @package     Analog/Admin
 * @since       1.3.8
 */

namespace Analog\Settings;

defined( 'ABSPATH' ) || exit;

/**
 * Settings_Page.
 *
 * @since 1.3.8
 */
abstract class Settings_Page {

	/**
	 * Setting page id.
	 *
	 * @var string
	 */
	protected $id = '';

	/**
	 * Setting page label.
	 *
	 * @var string
	 */
	protected $label = '';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'ang_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'ang_sections_' . $this->id, array( $this, 'output_sections' ) );
		add_action( 'ang_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'ang_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get settings page ID.
	 *
	 * @since 3.0.0
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get settings page label.
	 *
	 * @since 3.0.0
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * Add this page to settings.
	 *
	 * @param array $pages List of existing pages.
	 *
	 * @return mixed
	 */
	public function add_settings_page( $pages ) {
		if ( 'extensions' === $this->id && ! has_filter( 'ang_get_settings_' . $this->id ) ) {
			return $pages;
		}

		$pages[ $this->id ] = $this->label;

		return $pages;
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {
		return apply_filters( 'ang_get_settings_' . $this->id, array() );
	}

	/**
	 * Get sections.
	 *
	 * @return array
	 */
	public function get_sections() {
		return apply_filters( 'ang_get_sections_' . $this->id, array() );
	}

	/**
	 * Output sections.
	 */
	public function output_sections() {
		global $current_section;

		$sections = $this->get_sections();

		if ( empty( $sections ) ) {
			return;
		}

		echo '<ul class="subsubsub">';

		$array_keys = array_keys( $sections );

		foreach ( $sections as $id => $label ) {
			echo '<li><a href="' . esc_url( admin_url( 'admin.php?page=ang-settings&tab=' . $this->id . '&section=' . sanitize_title( $id ) ) ) . '" class="' . ( $current_section === $id ? 'current' : '' ) . '">' . esc_html( $label ) . '</a> ' . ( end( $array_keys ) === $id ? '' : '|' ) . ' </li>';
		}

		echo '</ul><br class="clear" />';
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
		global $current_section;

		$settings = $this->get_settings();
		Admin_Settings::save_fields( $settings );

		if ( $current_section ) {
			do_action( 'ang_update_options_' . $this->id . '_' . $current_section );
		}
	}
}
