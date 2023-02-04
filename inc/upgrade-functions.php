<?php
/**
 * Run upgrade functions.
 *
 * @package AnalogWP
 * @since 1.2
 */

namespace Analog\Upgrade;

use Analog\Admin\Notice;
use Analog\Core\Util\Migration;
use Analog\Plugin;
use Analog\Utils;

defined( 'ABSPATH' ) || exit;

use Analog\Options;

/**
 * Perform automatic upgrades when necessary.
 *
 * @deprecated 1.8.0 Use class Database_Upgrader instead.
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

	if (
		version_compare( $installed_version, '1.6.0', '<' )
		 && ! Options::get_instance()->get( 'theme_style_kit_migrated' )
	) {
		version_1_6_0_upgrades();
	}

	if ( version_compare( $installed_version, '1.6.3', '<' ) ) {
		Utils::clear_elementor_cache();
	}

	if ( version_compare( $installed_version, '1.6.6', '<' ) ) {
		version_1_6_6_upgrades();
	}

	if ( version_compare( $installed_version, '1.6.7', '<' ) ) {
		Utils::clear_elementor_cache();
	}

	if ( version_compare( $installed_version, '1.6.8', '<' ) ) {
		Utils::clear_elementor_cache();
	}

	if ( version_compare( $installed_version, '1.9.3', '<' ) ) {
		version_1_9_3_upgrades();
	}

	if ( version_compare( $installed_version, '1.9.5', '<' ) ) {
		version_1_9_5_upgrades();
	}

	// Dismissible sticky notice for v1.9.5.
	if ( version_compare( ANG_VERSION, '1.9.5', '=' ) ) {
		// Add notice.
		add_filter(
			'analog_admin_notices',
			function( $notices ) {
				$notices[] = new Notice(
					'update_success',
					array(
						'content'     => sprintf(
							'%1$s&nbsp;<a href="%2$s" target="_blank">%3$s</a>',
							__( 'Welcome to Style Kits 1.9.5. This version includes a brand new container-based pattern library, and a lot of other improvements.', 'ang' ),
							'https://analogwp.com/stylekits-195/',
							__( 'See what’s new.', 'ang' )
						),
						'type'        => Notice::TYPE_INFO,
						'dismissible' => true,
					)
				);
				return $notices;
			}
		);
	}

	// Use global options table as it is stored there intentionally.
	$ran_onboarding = get_option( 'ran_onboarding' );

	// Dismissible sticky notice for v1.9.6.
	if ( ! $ran_onboarding && version_compare( ANG_VERSION, '1.9.6', '=' ) ) {
		// Add notice.
		add_filter(
			'analog_admin_notices',
			function( $notices ) {
				$notices[] = new Notice(
					'update_success_196',
					array(
						'content'     => sprintf(
							'%1$s&nbsp;<a href="%2$s" target="_blank">%3$s</a>',
							__( 'The New version of Style Kits introduces a setup wizard. You can trigger it at any time under Style Kits Settings.', 'ang' ),
							'https://analogwp.com/docs/the-setup-wizard/',
							__( 'Learn more', 'ang' )
						),
						'type'        => Notice::TYPE_INFO,
						'dismissible' => true,
					)
				);
				return $notices;
			}
		);
	}

	if ( $did_upgrade ) {
		// Bump version.
		Options::get_instance()->set( 'version', ANG_VERSION );
	}
}
add_action( 'admin_init', __NAMESPACE__ . '\do_automatic_upgrades' );

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

/**
 * Version 1.6.0 upgrades.
 *
 * @since 1.6.0
 */
function version_1_6_0_upgrades() {
	// Perform Kits migration.
	$migration = new Migration();
	$migration->convert_all_sk_to_kits();

	// Set Kit migrated flag.
	Options::get_instance()->set( 'theme_style_kit_migrated', true );

	Options::get_instance()->set( 'version', ANG_VERSION );

	// Clear cache.
	Utils::clear_elementor_cache();
}

/**
 * Version 1.6.6 upgrades.
 *
 * - Fixes OSP for sections
 * - - Iterates through each Kit to find posts using that Kit.
 * - - See if that Kit had a OSP set
 * - - If yes, set that OSP to each Section that was set to use default OSP.
 *
 * @since 1.6.6
 * @return void
 */
function version_1_6_6_upgrades() {
	$default_osp = 'no';

	$fix_osp = static function ( $post_ids ) use ( $default_osp ) {
		foreach ( $post_ids as $id ) {
			$settings = get_post_meta( $id, '_elementor_page_settings', true );

			if ( isset( $settings['ang_action_tokens'] ) ) {
				$kit_osp = Utils::get_kit_settings( $settings['ang_action_tokens'], 'ang_default_section_padding' );

				if ( $kit_osp && '' !== $kit_osp ) {
					$default_osp = $kit_osp;
				}
			} else {
				$global_kit = Options::get_instance()->get( 'global_kit' );
				if ( $global_kit ) {
					$kit_osp = Utils::get_kit_settings( $global_kit, 'ang_default_section_padding' );

					if ( $kit_osp && '' !== $kit_osp ) {
						$default_osp = $kit_osp;
					}
				}
			}

			$document = Plugin::elementor()->documents->get( $id );
			$data     = $document->get_elements_data();

			$data = Plugin::elementor()->db->iterate_data(
				$data,
				static function( $element ) use ( $default_osp ) {
					if ( 'section' === $element['elType'] && false === $element['isInner'] && ! isset( $element['settings']['ang_outer_gap'] ) ) {
						$element['settings']['ang_outer_gap'] = $default_osp;
					}

					return $element;
				}
			);

			$json_value = wp_slash( wp_json_encode( $data ) );
			update_metadata( 'post', $id, '_elementor_data', $json_value );
		}
	};

	$kits       = Utils::get_kits( false );
	$global_kit = Options::get_instance()->get( 'global_kit' );

	// Remove Global Kit to handle separately.
	if ( $global_kit && '' !== $global_kit ) {
		unset( $kits[ $global_kit ] );
	}

	$posts_using_global_kit = Utils::posts_using_stylekit();
	if ( count( $posts_using_global_kit ) ) {
		$fix_osp( $posts_using_global_kit );
	}

	foreach ( $kits as $kit_id => $title ) {
		$posts_using_different_kit = Utils::posts_using_stylekit( $kit_id );
		$fix_osp( $posts_using_different_kit );
	}
}

/**
 * Version 1.9.3 upgrades.
 *
 * Migrate existing set controls to load with new additional defaults.
 *
 * @since 1.9.3
 * @return void|bool
 */
function version_1_9_3_upgrades() {

	$migration_keys = array(
		'ang_box_shadows',
		'ang_container_padding',
	);

	$all_kits = \Analog\Utils::get_kits();

	foreach ( $all_kits as $id => $title ) {
		// Check if this is a valid kit or not.
		if ( ! Plugin::elementor()->kits_manager->is_kit( $id ) ) {
			return false;
		}

		$kit = Plugin::elementor()->documents->get_doc_for_frontend( $id );

		// Use raw settings that doesn't have default values.
		$kit_raw_settings = $kit->get_data( 'settings' );

		foreach ( $migration_keys as $control_key ) {
			if ( ! isset( $kit_raw_settings[ $control_key ] ) ) {
				continue;
			}

			$existing_controls = $kit_raw_settings[ $control_key ];
			$control           = $kit->get_controls( $control_key );

			$default_controls = $control['default'];
			$updated_settings = $default_controls;

			foreach ( $existing_controls as $ex_control ) {
				foreach ( $default_controls as $key => $default_control ) {
					if ( $ex_control['_id'] === $default_control['_id'] ) {
						$updated_settings[ $key ] = $ex_control;
					}
				}
			}

			$kit_raw_settings[ $control_key ] = $updated_settings;
		}

		$data = array(
			'settings' => $kit_raw_settings,
		);

		$kit->save( $data );
	}
}

/**
 * Version 1.9.5 upgrades.
 *
 * Migrate existing set controls to load with new additional defaults.
 *
 * @since 1.9.5
 * @return void|bool
 */
function version_1_9_5_upgrades() {

	// Settings to migrate.
	$migration_keys = array(
		'ang_container_padding',
		// Global colors.
		'ang_global_background_colors',
		'ang_global_accent_colors',
		'ang_global_text_colors',
		'ang_global_extra_colors',
		// Global typography.
		'ang_global_title_fonts',
		'ang_global_text_fonts',
	);

	$all_kits = \Analog\Utils::get_kits();

	foreach ( $all_kits as $id => $title ) {
		// Check if this is a valid kit or not.
		if ( ! Plugin::elementor()->kits_manager->is_kit( $id ) ) {
			return false;
		}

		$kit = Plugin::elementor()->documents->get_doc_for_frontend( $id );

		// Use raw settings that doesn't have default values.
		$kit_raw_settings = $kit->get_data( 'settings' );

		foreach ( $migration_keys as $control_key ) {
			if ( ! isset( $kit_raw_settings[ $control_key ] ) ) {
				continue;
			}

			$existing_controls = $kit_raw_settings[ $control_key ];
			$control           = $kit->get_controls( $control_key );

			$default_controls = $control['default'];
			$updated_settings = $default_controls;

			// Loop over existing set controls.
			foreach ( $existing_controls as $ex_control ) {
				// Loop over default controls.
				foreach ( $default_controls as $key => $default_control ) {
					// If existing control id matches with default control id.
					if ( $ex_control['_id'] === $default_control['_id'] ) {
						// Then we loop over existing single control keys.
						foreach ( $ex_control as $id => $value ) {
							// We set the existing value.
							$updated_settings[ $key ][ $id ] = $value;
						}
					}
				}
			}

			$kit_raw_settings[ $control_key ] = $updated_settings;
		}

		$data = array(
			'settings' => $kit_raw_settings,
		);

		$kit->save( $data );
	}

	// Regenerate Elementor CSS.
	Utils::clear_elementor_cache();
}
