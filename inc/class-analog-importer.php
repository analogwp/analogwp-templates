<?php
/**
 * Class for importing a template.
 *
 * @package AnalogWP
 */

namespace Elementor\TemplateLibrary;

use Analog\API\Remote;
use Analog\Formatter;
use Analog\Plugin;
use Elementor\TemplateLibrary\Classes\Images;
use Analog\Utils;

/**
 * Class Analog_Importer.
 *
 * @package Elementor\TemplateLibrary
 */
class Analog_Importer extends Source_Remote {
	/**
	 * Analog_Importer constructor.
	 */
	public function __construct() {
		if ( ! function_exists( 'wp_crop_image' ) ) {
			include ABSPATH . 'wp-admin/includes/image.php';
		}
	}

	/**
	 * Get template data.
	 *
	 * @inheritDoc
	 *
	 * @param array       $args    Custom template arguments.
	 * @param string      $context Optional. The context. Default is `display`.
	 * @param object|bool $data Template/block import data.
	 *
	 * @since 1.4.0 $data was added.
	 *
	 * @return array Remote Template data.
	 */
	public function get_data( array $args, $context = 'display', $data = false ) {
		if ( ! $data ) {
			$data = Remote::get_instance()->get_template_content( $args['template_id'], $args['license'], $args['method'], $args['site_id'] );
		}

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		Plugin::elementor()->editor->set_edit_mode( true );

		// Remove Typography options if opted in.
		if ( isset( $args['options']['remove_typography'] ) && true === $args['options']['remove_typography'] ) {
			require_once ANG_PLUGIN_DIR . 'inc/class-formatter.php';
			$data['content'] = Formatter::remove_typography_data_recursive( $data['content'] );
		}

		$data['content'] = $this->replace_elements_ids( $data['content'] );
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );

		$post_id  = $args['editor_post_id'];
		$document = Plugin::elementor()->documents->get( $post_id );
		if ( $document ) {
			$data['content'] = $document->get_elements_raw_data( $data['content'], true );
		}

		/**
		 * During json encode/decode between preview/demo, isInner is usually converted into string.
		 * This helper function converts it back to Boolean so Elementor doesn't changes this control
		 * into an "Inner Section".
		 *
		 * @since 1.3.8
		 */
		$data['content'] = Utils::convert_string_to_boolean( $data['content'] );

		return $data;
	}
}
