<?php
/**
 * Run upgrade functions.
 *
 * @package AnalogWP
 * @since 1.2
 */

namespace Analog\Upgrade;

use Analog\Utils;

defined( 'ABSPATH' ) || exit;

use Analog\Options;
use Analog\Install_Stylekits as StyleKits;

/**
 * Perform automatic upgrades when necessary.
 *
 * @return void
 */
function do_automatic_upgrades() {
	$did_upgrade       = false;
	$installed_version = Options::get_instance()->get( 'version' );

	if ( version_compare( $installed_version, ANG_VERSION, '<' ) ) {
		// Let us know that an upgrade has happened.
		$did_upgrade = true;
	}

	if ( version_compare( $installed_version, '1.2', '<' ) ) {
		Utils::clear_elementor_cache();
	}

	if ( version_compare( $installed_version, '1.2.1', '<' ) ) {
		Utils::clear_elementor_cache();
	}

	if ( version_compare( $installed_version, '1.3', '<' ) ) {
		Utils::clear_elementor_cache();
	}

	if ( version_compare( $installed_version, '1.3.8', '<' ) ) {
		ang_v138_upgrades();
	}

	if ( version_compare( $installed_version, '1.3.10', '<' ) ) {
		Utils::clear_elementor_cache();
	}

	if ( version_compare( $installed_version, '1.3.12', '<' ) ) {
		Utils::clear_elementor_cache();
	}

	if ( version_compare( $installed_version, '1.3.13', '<' ) ) {
		Utils::clear_elementor_cache();
	}

	if ( version_compare( $installed_version, '1.3.14', '<' ) ) {
		Utils::clear_elementor_cache();
	}

	if ( version_compare( $installed_version, '1.3.15', '<' ) ) {
		version_1_3_15_upgrades();
	}

	if ( version_compare( $installed_version, '1.3.16', '<' ) ) {
		$kit = get_option( 'elementor_ang_global_kit' );
		Options::get_instance()->set( 'global_kit', $kit );

		Utils::clear_elementor_cache();
	}

	if ( version_compare( $installed_version, '1.3.17', '<' ) ) {
		Utils::clear_elementor_cache();
	}

	if ( version_compare( $installed_version, '1.4.0', '<' ) ) {
		$global_kit = Options::get_instance()->get( 'global_kit' );

		// Trigger a post save on global kit, so all associated posts can be updated.
		if ( $global_kit && '' !== $global_kit ) {
			wp_update_post(
				array(
					'ID'           => $global_kit,
					'post_content' => 'Updated.',
				)
			);
		}

		Utils::clear_elementor_cache();
	}

	if ( version_compare( $installed_version, '1.5.0', '<' ) ) {
		version_1_5_upgrades();
	}

	if ( version_compare( $installed_version, '1.5.1', '<' ) ) {
		version_1_5_1_upgrades();
	}

	if ( version_compare( $installed_version, '1.5.6', '<' ) ) {
		Options::get_instance()->set( 'ang_sync_colors', false );
	}

	if ( $did_upgrade ) {
		// Bump version.
		Options::get_instance()->set( 'version', ANG_VERSION );
	}
}
add_action( 'admin_init', __NAMESPACE__ . '\do_automatic_upgrades' );

/**
 * Install Sample Stylekits.
 *
 * @return void
 */
function install_stylekits() {
	$stylekits_installed = Options::get_instance()->get( 'installed_stylekits' );

	if ( ! $stylekits_installed ) {
		require_once ANG_PLUGIN_DIR . 'inc/elementor/class-install-stylekits.php';

		$did_fail = StyleKits::get_instance()->perform_install();

		if ( ! $did_fail ) {
			Options::get_instance()->set( 'installed_stylekits', true );
		}
	}
}

/**
 * Check if a string ends with certain characters.
 *
 * @param string $string Haystack, string to look into.
 * @param string $end_string Needlee, string to look for.
 *
 * @return bool
 */
function ends_with( $string, $end_string ) {
	$len = strlen( $end_string );
	if ( 0 === $len ) {
		return true;
	}

	return ( substr( $string, -$len ) === $end_string );
}

/**
 * Version 1.3.0 upgrades.
 *
 * @since 1.3.0
 * @return void
 */
function ang_v13_upgrades() {
	$keys = array(
		// Heading Text sizes.
		'ang_size_xxl'                      => 'ang_size_xxl_font_size',
		'ang_size_xxl_tablet'               => 'ang_size_xxl_font_size_tablet',
		'ang_size_xxl_mobile'               => 'ang_size_xxl_font_size_mobile',
		'ang_size_xl'                       => 'ang_size_xl_font_size',
		'ang_size_xl_tablet'                => 'ang_size_xl_font_size_tablet',
		'ang_size_xl_mobile'                => 'ang_size_xl_font_size_mobile',
		'ang_size_large'                    => 'ang_size_large_font_size',
		'ang_size_large_tablet'             => 'ang_size_large_font_size_tablet',
		'ang_size_large_mobile'             => 'ang_size_large_font_size_mobile',
		'ang_size_medium'                   => 'ang_size_medium_font_size',
		'ang_size_medium_tablet'            => 'ang_size_medium_font_size_tablet',
		'ang_size_medium_mobile'            => 'ang_size_medium_font_size_mobile',
		'ang_size_small'                    => 'ang_size_small_font_size',
		'ang_size_small_tablet'             => 'ang_size_small_font_size_tablet',
		'ang_size_small_mobile'             => 'ang_size_small_font_size_mobile',

		// Heading size line heights.
		'ang_heading_size_lh_xxl'           => 'ang_size_xxl_line_height',
		'ang_heading_size_lh_xxl_tablet'    => 'ang_size_xxl_line_height_tablet',
		'ang_heading_size_lh_xxl_mobile'    => 'ang_size_xxl_line_height_mobile',
		'ang_heading_size_lh_xl'            => 'ang_size_xl_line_height',
		'ang_heading_size_lh_xl_tablet'     => 'ang_size_xl_line_height_tablet',
		'ang_heading_size_lh_xl_mobile'     => 'ang_size_xl_line_height_mobile',
		'ang_heading_size_lh_large'         => 'ang_size_large_line_height',
		'ang_heading_size_lh_large_tablet'  => 'ang_size_large_line_height_tablet',
		'ang_heading_size_lh_large_mobile'  => 'ang_size_large_line_height_mobile',
		'ang_heading_size_lh_medium'        => 'ang_size_medium_line_height',
		'ang_heading_size_lh_medium_tablet' => 'ang_size_medium_line_height_tablet',
		'ang_heading_size_lh_medium_mobile' => 'ang_size_medium_line_height_mobile',
		'ang_heading_size_lh_small'         => 'ang_size_small_line_height',
		'ang_heading_size_lh_small_tablet'  => 'ang_size_small_line_height_tablet',
		'ang_heading_size_lh_small_mobile'  => 'ang_size_small_line_height_mobile',

		// Text sizes.
		'ang_text_size_xxl'                 => 'ang_text_size_xxl_font_size',
		'ang_text_size_xxl_tablet'          => 'ang_text_size_xxl_font_size_tablet',
		'ang_text_size_xxl_mobile'          => 'ang_text_size_xxl_font_size_mobile',
		'ang_text_size_xl'                  => 'ang_text_size_xl_font_size',
		'ang_text_size_xl_tablet'           => 'ang_text_size_xl_font_size_tablet',
		'ang_text_size_xl_mobile'           => 'ang_text_size_xl_font_size_mobile',
		'ang_text_size_large'               => 'ang_text_size_large_font_size',
		'ang_text_size_large_tablet'        => 'ang_text_size_large_font_size_tablet',
		'ang_text_size_large_mobile'        => 'ang_text_size_large_font_size_mobile',
		'ang_text_size_medium'              => 'ang_text_size_medium_font_size',
		'ang_text_size_medium_tablet'       => 'ang_text_size_medium_font_size_tablet',
		'ang_text_size_medium_mobile'       => 'ang_text_size_medium_font_size_mobile',
		'ang_text_size_small'               => 'ang_text_size_small_font_size',
		'ang_text_size_small_tablet'        => 'ang_text_size_small_font_size_tablet',
		'ang_text_size_small_mobile'        => 'ang_text_size_small_font_size_mobile',

		// Text size line heights.
		'ang_text_size_lh_xxl'              => 'ang_text_size_xxl_line_height',
		'ang_text_size_lh_xxl_tablet'       => 'ang_text_size_xxl_line_height_tablet',
		'ang_text_size_lh_xxl_mobile'       => 'ang_text_size_xxl_line_height_mobile',
		'ang_text_size_lh_xl'               => 'ang_text_size_xl_line_height',
		'ang_text_size_lh_xl_tablet'        => 'ang_text_size_xl_line_height_tablet',
		'ang_text_size_lh_xl_mobile'        => 'ang_text_size_xl_line_height_mobile',
		'ang_text_size_lh_large'            => 'ang_text_size_large_line_height',
		'ang_text_size_lh_large_tablet'     => 'ang_text_size_large_line_height_tablet',
		'ang_text_size_lh_large_mobile'     => 'ang_text_size_large_line_height_mobile',
		'ang_text_size_lh_medium'           => 'ang_text_size_medium_line_height',
		'ang_text_size_lh_medium_tablet'    => 'ang_text_size_medium_line_height_tablet',
		'ang_text_size_lh_medium_mobile'    => 'ang_text_size_medium_line_height_mobile',
		'ang_text_size_lh_small'            => 'ang_text_size_small_line_height',
		'ang_text_size_lh_small_tablet'     => 'ang_text_size_small_line_height_tablet',
		'ang_text_size_lh_small_mobile'     => 'ang_text_size_small_line_height_mobile',
	);

	$must_haves = array(
		'ang_size_xxl'         => 'ang_size_xxl_typography',
		'ang_size_xl'          => 'ang_size_xl_typography',
		'ang_size_large'       => 'ang_size_large_typography',
		'ang_size_medium'      => 'ang_size_medium_typography',
		'ang_size_small'       => 'ang_size_small_typography',
		'ang_text_size_xxl'    => 'ang_text_size_xxl_typography',
		'ang_text_size_xl'     => 'ang_text_size_xl_typography',
		'ang_text_size_large'  => 'ang_text_size_large_typography',
		'ang_text_size_medium' => 'ang_text_size_medium_typography',
		'ang_text_size_small'  => 'ang_text_size_small_typography',
	);

	$query = new \WP_Query(
		array(
			'post_type'      => 'ang_tokens',
			'posts_per_page' => -1,
		)
	);

	if ( $query->have_posts() ) {
		$posts = $query->posts;

		foreach ( $posts as $post ) {
			$tokens_raw = get_post_meta( $post->ID, '_tokens_data', true );
			$tokens     = json_decode( $tokens_raw, true );

			foreach ( $keys as $old => $new ) {
				if ( isset( $tokens[ $old ] ) && is_array( $tokens[ $old ] ) && count( $tokens[ $old ] ) ) {
					$tokens[ $new ] = $tokens[ $old ];

					if ( \array_key_exists( $old, $must_haves ) ) {
						$key            = $must_haves[ $old ];
						$tokens[ $key ] = 'custom';
					}
				}
			}

			update_post_meta( $post->ID, '_tokens_data', wp_slash( wp_json_encode( $tokens ) ) );
		}
	}

	$posts_with_stylekit = \Analog\Utils::posts_using_stylekit();

	if ( count( $posts_with_stylekit ) ) {
		foreach ( $posts_with_stylekit as $post_id ) {
			$settings = get_post_meta( $post_id, '_elementor_page_settings', true );

			foreach ( $keys as $old => $new ) {
				if ( isset( $settings[ $old ] ) && is_array( $settings[ $old ] ) && count( $settings[ $old ] ) ) {
					$settings[ $new ] = $settings[ $old ];

					if ( \array_key_exists( $old, $must_haves ) ) {
						$key              = $must_haves[ $old ];
						$settings[ $key ] = 'custom';
					}
				}
			}

			update_post_meta( $post_id, '_elementor_page_settings', wp_slash( $settings ) );
		}
	}
}

/**
 * Run upgrade functions for v1.3.8.
 *
 * @since 1.3.8
 *
 * @return void
 */
function ang_v138_upgrades() {
	Utils::clear_elementor_cache();

	delete_transient( 'analog_stylekits' );
	delete_transient( 'analogwp_template_info' );
}

/**
 * Version 1.3.15 upgrades.
 *
 * @since 1.3.15
 */
function version_1_3_15_upgrades() {
	$key = '_tokens_data';

	$query_args = array(
		'post_type'      => 'ang_tokens',
		'post_status'    => 'any',
		'fields'         => 'ids',
		'posts_per_page' => -1,
	);

	$query = new \WP_Query( $query_args );

	foreach ( $query->posts as $id ) {
		$settings = get_post_meta( $id, $key, true );
		$settings = json_decode( $settings, ARRAY_A );

		// Remove "Page Template" setting.
		if ( isset( $settings['template'] ) ) {
			unset( $settings['template'] );
		}

		// Remove "Background Image" setting.
		if ( isset( $settings['background_image'] ) ) {
			unset( $settings['background_image'] );
		}

		update_post_meta( $id, $key, wp_slash( wp_json_encode( $settings ) ) );
	}

	Utils::clear_elementor_cache();
}

/*
 * Version 1.4.0 upgrades.
 *
 * @since 1.4.0
 */
function version_140_upgrades() {
	delete_transient( 'analog_blocks' );
	delete_transient( 'analog_stylekits' );

	Utils::clear_elementor_cache();
}

/**
 * Version 1.5.0 upgrades.
 *
 * @since 1.5.0
 */
function version_1_5_upgrades() {

	$run_migration = function( array $settings ) {
		/**
		 * "Text and Heading Colors" was removed, in favor of:
		 * New Text Color and Heading Color, both these settings should inherit the value.
		 *
		 * Also "Light Background > Text Color" should inherit it.
		 */
		if ( isset( $settings['ang_color_text_light'] ) ) {
			$text_light = $settings['ang_color_text_light'];

			$settings += array(
				'ang_color_text'               => $text_light,
				'ang_color_heading'            => $text_light,
				'ang_background_light_text'    => $text_light,
				'ang_background_light_heading' => $text_light,
			);

			 unset( $settings['ang_color_text_light'] );
		}

		/**
		 * Migrate old keys to new keys.
		 */
		$migrate_keys = array(
			'ang_color_text_dark'        => array(
				'ang_background_dark_text',
				'ang_background_dark_heading',
			),
			'ang_color_background_light' => 'ang_background_light_background',
			'ang_color_background_dark'  => 'ang_background_dark_background',
		);

		foreach ( $migrate_keys as $old_key => $new_key ) {
			if ( isset( $settings[ $old_key ] ) ) {
				if ( is_array( $new_key ) ) {
					foreach ( $new_key as $subkey ) {
						$settings += array( $subkey => $settings[ $old_key ] );
					}
				} else {
					$settings += array( $new_key => $settings[ $old_key ] );
				}

				 unset( $settings[ $old_key ] );
			}
		}

		return $settings;
	};

	$query = new \WP_Query(
		array(
			'post_type'      => 'ang_tokens',
			'post_status'    => 'any',
			'fields'         => 'ids',
			'posts_per_page' => -1,
		)
	);

	if ( count( $query->posts ) ) {
		foreach ( $query->posts as $id ) {
			$settings = get_post_meta( $id, '_tokens_data', true );
			$settings = json_decode( $settings, ARRAY_A );

			if ( is_array( $settings ) ) {
				$updated_settings = $run_migration( $settings );
				update_post_meta( $id, '_tokens_data', wp_slash( wp_json_encode( $updated_settings ) ) );
			}
		}
	}

	$posts_with_stylekit = \Analog\Utils::posts_using_stylekit();

	if ( count( $posts_with_stylekit ) ) {
		foreach ( $posts_with_stylekit as $id ) {
			$settings = get_post_meta( $id, '_elementor_page_settings', true );

			if ( is_array( $settings ) ) {
				$updated_settings = $run_migration( $settings );
				update_post_meta( $id, '_elementor_page_settings', wp_slash( $updated_settings ) );
			}
		}
	}

	Utils::clear_elementor_cache();
}

/**
 * Version 1.5.1 upgrades.
 *
 * @since 1.5.1
 */
function version_1_5_1_upgrades() {
	$color_items = get_option( 'elementor_scheme_color-picker' );

	if ( is_array( $color_items ) ) {
		foreach ( $color_items as $color ) {
			if ( empty( $color ) || $color === '' || $color === null ) {
				unset( $color_items[ $color ] );
			}
		}
	}

	update_option( 'elementor_scheme_color-picker', $color_items );

	delete_transient( 'analogwp_template_info' );
}
