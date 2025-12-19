<?php
/**
 * Rollback Feature
 *
 * @package Analog\Featuresets
 */

namespace Analog\Featuresets\Rollback;

defined( 'ABSPATH' ) || exit;

use Analog\Featuresets\Rollback\Rollbacker;

/**
 * Init Class.
 */
class Init {
	/**
	 * Class instance.
	 *
	 * @var $instance
	 */
	protected static $instance;

	/**
	 * Get a class instance.
	 *
	 * @return Init
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'ang_settings_data', array( $this, 'add_rollback_data' ) );
		add_action( 'admin_post_analog_style_kits_rollback', array( $this, 'post_analog_style_kits_rollback' ) );
	}

	/**
	 * Add rollback related data to localized settings data.
	 *
	 * @param array $data Existing localized data.
	 * @return array Modified localized data with rollback info.
	 */
	public function add_rollback_data( $data ) {
		$data['rollback_url'] = wp_nonce_url( admin_url( 'admin-post.php?action=analog_style_kits_rollback&version=VERSION' ), 'analog_style_kits_rollback' );

		return $data;
	}

	/**
	 * Rollback plugin version.
	 *
	 * @return void
	 */
	public function post_analog_style_kits_rollback() {
		check_admin_referer( 'analog_style_kits_rollback' );

		if ( defined( 'ANALOGWP_DEBUG' ) || ! current_user_can( 'update_plugins' ) ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to rollback Style Kits plugin for this site.', 'ang' ) );
		}

		$rollback_versions = self::get_rollback_versions();

		$version = filter_input( INPUT_GET, 'version', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		if ( ! $version || ! in_array( $version, $rollback_versions, true ) ) {
			wp_die( esc_html__( 'Error occurred, the version selected is invalid. Try selecting different version.', 'ang' ) );
		}

		$plugin_slug = 'analogwp-templates';
		$plugin_name = ANG_PLUGIN_BASE;

		$rollbacker = new Rollbacker(
			array(
				'version'     => $version,
				'plugin_name' => $plugin_name,
				'plugin_slug' => $plugin_slug,
				'package_url' => sprintf( 'https://downloads.wordpress.org/plugin/%s.%s.zip', $plugin_slug, $version ),
			)
		);

		$rollbacker->run();

		wp_die(
			'',
			esc_html__( 'Rollback to Previous Version', 'ang' ),
			array(
				'response' => 200,
			)
		);
	}

	/**
	 * Get valid rollback versions.
	 *
	 * @return array|mixed
	 */
	public static function get_rollback_versions() {
		$rollback_versions = get_transient( 'ang_rollback_versions_' . ANG_VERSION );

		if ( false === $rollback_versions ) {
			$max_versions = 30;

			if ( ! function_exists( 'plugins_api' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			}

			$plugin_information = plugins_api(
				'plugin_information',
				array( 'slug' => 'analogwp-templates' )
			);

			if ( empty( $plugin_information->versions ) || ! is_array( $plugin_information->versions ) ) {
				return array();
			}

			krsort( $plugin_information->versions, SORT_NATURAL );

			$rollback_versions = array();

			$current_index = 0;

			foreach ( $plugin_information->versions as $version => $download_link ) {
				if ( $max_versions <= $current_index ) {
					break;
				}

				if ( preg_match( '/(trunk|beta|rc)/i', strtolower( $version ) ) ) {
					continue;
				}

				if ( version_compare( $version, ANG_VERSION, '>=' ) ) {
					continue;
				}

				++$current_index;
				$rollback_versions[] = $version;
			}

			set_transient( 'ang_rollback_versions_' . ANG_VERSION, $rollback_versions, WEEK_IN_SECONDS );
		}

		return $rollback_versions;
	}
}

new Init();
