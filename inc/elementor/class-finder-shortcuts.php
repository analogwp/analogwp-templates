<?php
/**
 * Elementor Finder shortcuts for Analog.
 *
 * @package AnalogWP
 */

namespace Analog;

use Elementor\Core\Common\Modules\Finder\Base_Category;

/**
 * Finder_Shortcuts class.
 */
class Finder_Shortcuts extends Base_Category {
	/**
	 * Get title.
	 *
	 * @access public
	 * @return string
	 */
	public function get_title() {
		return __( 'AnalogWP Shortcuts', 'ang' );
	}

	/**
	 * Get category items.
	 *
	 * @access public
	 * @param array $options Old options.
	 * @return array
	 */
	public function get_category_items( array $options = [] ) {
		$items = [
			'library'    => [
				'title'    => __( 'Templates Library', 'ang' ),
				'url'      => admin_url( 'admin.php?page=analogwp_templates' ),
				'icon'     => 'library-download',
				'keywords' => [ 'analog', 'library', 'settings' ],
			],
			'settings'   => [
				'title'    => __( 'Settings', 'ang' ),
				'url'      => admin_url( 'admin.php?page=analogwp_templates#settings' ),
				'icon'     => 'settings',
				'keywords' => [ 'analog', 'settings' ],
			],
			'style-kits' => [
				'title'    => __( 'Style Kits', 'ang' ),
				'url'      => admin_url( 'edit.php?post_type=ang_tokens' ),
				'icon'     => 'settings',
				'keywords' => [ 'analog', 'style', 'kits' ],
			],
		];

		return $items;
	}
}
