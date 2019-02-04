<?php
/**
 * Class for importing a template.
 *
 * @package AnalogWP
 */

namespace Elementor\TemplateLibrary;

use \Analog\API\Remote;
use Elementor\TemplateLibrary\Source_Remote;
use Elementor\TemplateLibrary\Classes\Images;
use Elementor\Api;
use Elementor\Plugin;

class Analog_Importer extends Source_Remote {
	public function __construct() {
		if ( ! function_exists( 'wp_crop_image' ) ) {
			include ABSPATH . 'wp-admin/includes/image.php';
		}
	}
	public function get_data( array $args, $context = 'display' ) {
		$data = Remote::get_instance()->get_template_content( $args['template_id'], $args['license'] );

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		Plugin::$instance->editor->set_edit_mode( true );

		$data['content'] = $this->replace_elements_ids( $data['content'] );
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );

		$post_id  = $args['editor_post_id'];
		$document = Plugin::$instance->documents->get( $post_id );
		if ( $document ) {
			$data['content'] = $document->get_elements_raw_data( $data['content'], true );
		}

		return $data;
	}
}
