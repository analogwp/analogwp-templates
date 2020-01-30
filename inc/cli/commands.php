<?php

namespace Analog\CLI;

use Analog\Core\Util\Migration;
use Analog\Elementor\Kit\Manager;
use Analog\Utils;
use Elementor\TemplateLibrary\Source_Local;
use WP_CLI;

WP_CLI::add_command( 'analog style_kits migrate', __NAMESPACE__ . '\migrate' );
WP_CLI::add_command( 'analog style_kits list', __NAMESPACE__ . '\style_kits_list' );
WP_CLI::add_command( 'analog kit list', __NAMESPACE__ . '\kits_list' );
WP_CLI::add_command( 'analog library refresh', __NAMESPACE__ . '\refresh_library' );

function migrate() {
	$migration = new Migration();
	$migration->convert_all_sk_to_kits();

	WP_CLI::success( 'All Style Kits have been migrated. 🎉' );
}

function style_kits_list() {
	$posts = \get_posts(
		array(
			'post_type'      => 'ang_tokens',
			'posts_per_page' => -1,
		)
	);

	WP_CLI\Utils\format_items( 'table', $posts, array( 'ID', 'post_title' ) );
}

function kits_list() {
	$posts = \get_posts(
		array(
			'post_type'      => Source_Local::CPT,
			'post_status'    => array( 'publish', 'draft' ),
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'DESC',
			'meta_query'     => array( // @codingStandardsIgnoreLine
				array(
					'key'   => \Elementor\Core\Base\Document::TYPE_META_KEY,
					'value' => 'kit',
				),
			),
		)
	);

	WP_CLI\Utils\format_items( 'table', $posts, array( 'ID', 'post_title' ) );
}

function refresh_library() {
	delete_transient( 'analogwp_template_info' );

	WP_CLI::success( 'Refresh Style Kit remote library.' );
}
