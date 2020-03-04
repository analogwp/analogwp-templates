<?php
/**
 * Elementor document trait.
 *
 * @since 1.3.9
 * @package Analog
 */

namespace Analog\Elementor;

use Analog\Plugin;
use Elementor\Core\Settings\Manager;

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
	 * Get Post ID.
	 *
	 * @since 1.3.11
	 * @return false|int
	 */
	public function get_post_id() {
		if ( Plugin::elementor()->editor->is_edit_mode() ) {
			$post_id = Plugin::elementor()->editor->get_post_id();
		} else {
			$post_id = get_the_ID();
		}

		return $post_id;
	}

	/**
	 * Get Elementor document type.
	 *
	 * @return mixed
	 */
	public function get_document_type() {
		$document = Plugin::elementor()->documents->get_doc_or_auto_save( $this->get_post_id() );
		if ( $document ) {
			$config = $document->get_config();

			return $config['type'];
		}

		return false;
	}

	/**
	 * Get a specific Elementor page setting.
	 *
	 * @param string $id Setting ID.
	 *
	 * @return mixed
	 */
	public function get_page_setting( $id ) {
		$page_settings_manager = Manager::get_settings_managers( 'page' );
		$page_settings_model   = $page_settings_manager->get_model( $this->get_post_id() );

		return $page_settings_model->get_settings( $id );
	}
}
