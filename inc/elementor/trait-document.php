<?php
/**
 * Elementor document trait.
 *
 * @since 1.3.9
 * @package Analog
 */

namespace Analog\Elementor;

use Elementor\Plugin;

trait Document {
	/**
	 * Holds the Post ID.
	 *
	 * @var false|int Post ID.
	 */
	private static $post_id;

	/**
	 * Document constructor.
	 */
	public function __construct() {
		if ( ! self::$post_id ) {
			self::$post_id = get_the_ID();
		}
	}

	/**
	 * Get Elementor document type.
	 *
	 * @return mixed
	 */
	public function get_document_type() {
		$document = Plugin::$instance->documents->get_doc_or_auto_save( get_the_ID() );
		if ( $document ) {
			$config = $document->get_config();

			return $config['type'];
		}
	}
}
