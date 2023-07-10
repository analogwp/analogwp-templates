<?php
/**
 * Utility class.
 *
 * @package Analog
 */

namespace Analog;

use Analog\Core\Storage\Transients;
use Elementor\Core\Base\Document;
use Elementor\Core\Kits\Manager;
use Elementor\TemplateLibrary\Source_Local;
use WP_Query;

/**
 * Helper functions.
 *
 * @package Analog
 */
class Utils extends Base {

	/**
	 * Transients object.
	 *
	 * @since 1.6.0
	 *
	 * @var Transients
	 */
	private $transients;

	/**
	 * Utils constructor.
	 *
	 * @since 1.6.0
	 */
	public function __construct() {
		if ( ! $this->transients ) {
			$this->transients = new Transients();
		}

		$delete_kit_cache = function ( $post_id ) {
			if ( Source_Local::CPT !== get_post_type( $post_id ) ) {
				return;
			}

			$type = get_post_meta( $post_id, '_elementor_template_type', true );
			if ( 'kit' !== $type ) {
				return;
			}

			$this->transients->delete( 'analog_get_kits' );
		};

		add_action( 'delete_post', $delete_kit_cache );
		add_action( 'save_post', $delete_kit_cache );
	}

	/**
	 * Debugging log.
	 *
	 * @param mixed $log Log data.
	 * @return void
	 */
	public static function log( $log ) {
		if ( ! defined( 'WP_DEBUG_LOG' ) || ! WP_DEBUG_LOG ) {
			return;
		}

		if ( is_array( $log ) || is_object( $log ) ) {
			error_log( print_r( $log, true ) ); // @codingStandardsIgnoreLine
		} else {
			error_log( $log ); // @codingStandardsIgnoreLine
		}
	}

	/**
	 * Get import logo data.
	 *
	 * @since  1.1
	 * @return array
	 */
	public static function get_import_log() {
		return \get_option( '_ang_import_history' );
	}

	/**
	 * Recording import details on import.
	 *
	 * @since 1.1
	 *
	 * @param int    $id Template ID.
	 * @param int    $post_id Post ID, in which this template was imported to.
	 * @param string $method How was this template imported.
	 *
	 * @return void
	 */
	public static function add_import_log( $id, $post_id, $method ) {
		$imports = self::get_import_log();
		if ( ! $imports ) {
			$imports = array();
		}

		$time = time();

		$imports[] = compact( 'id', 'post_id', 'method', 'time' );

		update_option( '_ang_import_history', $imports );
	}

	/**
	 * Get registered tokens.
	 *
	 * @param boolean $prefix Where to prefix selected Global Style Kit with 'Global: '.
	 * @return array
	 */
	public static function get_tokens( $prefix = true ) {
		$posts = \get_posts(
			array(
				'post_type'      => 'ang_tokens',
				'posts_per_page' => -1,
			)
		);

		$tokens = array();

		foreach ( $posts as $post ) {
			$global_token = self::get_global_kit_id();

			$title = $post->post_title;

			if ( $global_token && $post->ID === $global_token && $prefix ) {
				/* translators: Global Style Kit post title. */
				$title = sprintf( __( 'Global: %s', 'ang' ), $title );
			}

			$tokens[ $post->ID ] = $title;
		}

		return $tokens;
	}

	/**
	 * Return the Post ID for Global Style Kit.
	 *
	 * @return int Post ID.
	 */
	public static function get_global_kit_id() {
		return (int) Options::get_instance()->get( 'global_kit' );
	}

	/**
	 * Get global token post metadata.
	 *
	 * @return array|bool Tokens data.
	 */
	public static function get_global_token_data() {
		$global_token = self::get_global_kit_id();

		if ( $global_token ) {
			return \get_post_meta( $global_token, '_tokens_data', true );
		}

		return false;
	}

	/**
	 * Clear cache.
	 *
	 * Delete all meta containing files data. And delete the actual
	 * files from the upload directory.
	 *
	 * @since 1.2.1
	 * @access public
	 */
	public static function clear_elementor_cache() {
		Plugin::elementor()->files_manager->clear_cache();
	}

	/**
	 * Get public post types.
	 *
	 * @param array $args Arguments.
	 *
	 * @since 1.2.3
	 * @return array
	 */
	public static function get_public_post_types( $args = array() ) {
		$post_type_args = array(
			// Default is the value $public.
			'show_in_nav_menus' => true,
		);

		// Keep for backwards compatibility.
		if ( ! empty( $args['post_type'] ) ) {
			$post_type_args['name'] = $args['post_type'];
			unset( $args['post_type'] );
		}

		$post_type_args = wp_parse_args( $post_type_args, $args );

		$_post_types = get_post_types( $post_type_args, 'objects' );

		$post_types = array();

		foreach ( $_post_types as $post_type => $object ) {
			$post_types[ $post_type ] = $object->label;
		}

		/**
		 * Public Post types
		 *
		 * Allow 3rd party plugins to filters the public post types elementor should work on
		 *
		 * @since 1.3.0
		 *
		 * @param array $post_types Elementor supported public post types.
		 */
		return apply_filters( 'analog/utils/get_public_post_types', $post_types );
	}

	/**
	 * Returns an array of Post IDs using global kit.
	 *
	 * Not passing a Kit ID will returns posts using Global Kit, if set.
	 * Pass Style kit ID to find posts/pages using specific style kit.
	 *
	 * @param int|bool $kit_id Style Kit ID to search for.
	 * @return array
	 */
	public static function posts_using_stylekit( $kit_id = false ) {
		$post_types = get_post_types( array( 'public' => true ) );
		unset( $post_types['attachment'] );

		if ( ! in_array( 'elementor_library', $post_types, true ) ) {
			$post_types += array( 'elementor_library' );
		}

		$query_args = array(
			'post_type'      => apply_filters( 'analog/stylekit/posttypes', $post_types ),
			'post_status'    => 'any',
			'fields'         => 'ids',
			'posts_per_page' => -1,
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => '_elementor_edit_mode',
					'value'   => false,
					'compare' => 'LIKE',
				),
				array(
					'key'     => '_elementor_template_type',
					'value'   => 'kit',
					'compare' => 'NOT LIKE',
				),
			),
		);

		$query = new WP_Query( $query_args );

		$posts = array();

		foreach ( $query->posts as $post_id ) {
			$settings = \get_post_meta( $post_id, '_elementor_page_settings', true );

			/**
			 * This conditional divides results in for two conditions:
			 *
			 * 1. When a kit ID is provided to check against.
			 * 2. When there is a global style kit active or either blank. We return all Elementor posts.
			 */
			if ( $kit_id ) {
				if ( isset( $settings['ang_action_tokens'] ) && (int) $settings['ang_action_tokens'] === (int) $kit_id ) {
					$posts[] = $post_id;
				}
			} elseif ( ! $kit_id && '' !== self::get_global_kit_id() ) {
				if (
					! isset( $settings['ang_action_tokens'] )
					|| '' === $settings['ang_action_tokens']
					|| (int) self::get_global_kit_id() === (int) $settings['ang_action_tokens']
				) {
					$posts[] = $post_id;
				}
			}
		}

		wp_reset_postdata();

		return $posts;
	}

	/**
	 * Get a list of Style Kits that were imported from library.
	 *
	 * @since 1.3.4
	 * @return array
	 */
	public static function imported_remote_kits() {
		$kits = array();

		$query = new WP_Query(
			array(
				'post_type'              => 'elementor_library',
				'post_status'            => 'publish',
				'posts_per_page'         => -1,
				'meta_query'     => array( // @codingStandardsIgnoreLine
					array(
						'key'   => Document::TYPE_META_KEY,
						'value' => 'kit',
					),
				),
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			)
		);

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				$kits[] = get_post_field( 'post_title' );
			}
		}

		wp_reset_postdata();

		return $kits;
	}

	/**
	 * Get valid rollback versions.
	 *
	 * @since 1.3.7
	 * @return array|mixed
	 */
	public static function get_rollback_versions() {
		$rollback_versions = get_transient( 'ang_rollback_versions_' . ANG_VERSION );

		if ( false === $rollback_versions ) {
			$max_versions = 30;

			require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

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

				$current_index++;
				$rollback_versions[] = $version;
			}

			set_transient( 'ang_rollback_versions_' . ANG_VERSION, $rollback_versions, WEEK_IN_SECONDS );
		}

		return $rollback_versions;
	}

	/**
	 * Convert string to boolean.
	 *
	 * @param array $data Array object.
	 * @since 1.3.8
	 * @return array
	 */
	public static function convert_string_to_boolean( $data ) {
		if ( ! is_array( $data ) ) {
			return $data;
		}

		array_walk_recursive(
			$data,
			function( &$value, $key ) {
				if ( 'isInner' === $key || 'isLinked' === $key ) {
					$value = (bool) $value;
				}
			}
		);

		return $data;
	}

	/**
	 * This function strips off the allowed keys that are part of a Style Kit.
	 *
	 * @param array $settings Post Meta settings.
	 *
	 * @since 1.3.15
	 * @return array Modified settings array.
	 */
	public static function remove_stored_kit_keys( $settings ) {
		/**
		 * List of settings key prefixes that needs to be removed prior to updating an SK.
		 *
		 * @since 1.3.15
		 */
		$allowed = apply_filters(
			'analog/stylekit/allowed/setting/prefixes',
			array( 'ang_', 'hide', 'background_background', 'background_color', 'background_grad', 'custom_css' )
		);

		return array_filter(
			$settings,
			function( $key ) use ( $allowed ) {
				foreach ( $allowed as $allow ) {
					if ( strpos( $key, $allow ) === 0 ) {
						return false;
					}
				}

				return true;
			},
			ARRAY_FILTER_USE_KEY
		);
	}

	/**
	 * Update Style Kit for a specific post.
	 *
	 * @param int   $post_id Post ID for which Style Kit will be updated.
	 * @param array $tokens Style Kit data.
	 *
	 * @since 1.3.15
	 * @return void
	 */
	public static function update_style_kit_for_post( $post_id, array $tokens ) {
		$page_settings = \get_post_meta( $post_id, '_elementor_page_settings', true );

		$allowed_types = array( 'post', 'wp-post', 'page', 'wp-page', 'global-widget', 'popup', 'section', 'header', 'footer', 'single', 'archive' );

		$document_type = \get_post_meta( $post_id, '_elementor_template_type', true );

		if ( ! $document_type || ! in_array( $document_type, $allowed_types, true ) ) {
			return;
		}

		$preserved_settings = array();
		if ( is_array( $page_settings ) ) {
			$preserved_settings = self::remove_stored_kit_keys( $page_settings );
		}
		$modified_settings = array_merge( $preserved_settings, $tokens );

		\update_post_meta( $post_id, '_elementor_page_settings', wp_slash( $modified_settings ) );
	}

	/**
	 * Check if current user has a valid license.
	 *
	 * @access public
	 * @since 1.4.0
	 * @return bool Whether license is valid or not.
	 */
	public static function has_valid_license() {
		$license = Options::get_instance()->get( 'ang_license_key' );
		$message = Options::get_instance()->get( 'ang_license_key_status' );

		return ! empty( $license ) && 'valid' === $message;
	}

	/**
	 * Returns a list of all keys for color controls defined by Style Kits.
	 *
	 * @since 1.5.0
	 * @return array
	 */
	public static function get_keys_for_color_controls() {
		$keys = array(
			'ang_color_accent_primary',
			'ang_color_accent_secondary',
			'ang_color_text',
			'ang_color_heading',
			'ang_background_light_background',
			'ang_background_light_text',
			'ang_background_light_heading',
			'ang_background_dark_background',
			'ang_background_dark_text',
			'ang_background_dark_heading',
		);

		return apply_filters( 'analog_color_scheme_items', $keys );
	}

	/**
	 * Check if a string starts with certain character.
	 *
	 * @param string $string String to search into.
	 * @param string $start_string String to search for.
	 *
	 * @return bool
	 */
	public static function string_starts_with( $string, $start_string ) {
		$len = strlen( $start_string );

		return ( substr( $string, 0, $len ) === $start_string );
	}

	/**
	 * Get a list of all Elementor Kits.
	 * Returns an associative arrray with [id] => [title].
	 *
	 * @param bool $prefix Whether to prefix Global Kit with "Global :".
	 *
	 * @since 1.6.0
	 * @return array
	 */
	public static function get_kits( $prefix = true ) {
		$transients = self::get_instance()->transients;
		$posts      = $transients->get( 'analog_get_kits' );

		if ( ! $posts ) {
			$posts = \get_posts(
				array(
					'post_type'      => Source_Local::CPT,
					'post_status'    => array( 'publish' ),
					'posts_per_page' => -1,
					'orderby'        => 'title',
					'order'          => 'DESC',
					'meta_query'     => array( // @codingStandardsIgnoreLine
						array(
							'key'   => Document::TYPE_META_KEY,
							'value' => 'kit',
						),
					),
				)
			);

			$transients->set( 'analog_get_kits', $posts, WEEK_IN_SECONDS );
		}

		$kits = array();

		foreach ( $posts as $post ) {
			$global_kit = (int) get_option( Manager::OPTION_ACTIVE );

			$title = $post->post_title;

			if ( $global_kit && $post->ID === $global_kit && $prefix ) {
				/* translators: Global Style Kit post title. */
				$title = sprintf( __( 'Global: %s', 'ang' ), $title );
			}

			$kits[ $post->ID ] = $title;
		}

		return $kits;
	}

	/**
	 * Log a message to CLI.
	 *
	 * @param string $message CLI message to output.
	 * @since 1.6.0
	 * @return string|void Return message if in CLI, or void.
	 */
	public static function cli_log( $message ) {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			\WP_CLI::line( $message );
		}
	}

	/**
	 * Get Style Kit Pro link.
	 *
	 * @since 1.6.0
	 * @access public
	 * @static
	 *
	 * @param array $args UTM arguments.
	 *
	 * @return string
	 */
	public static function get_pro_link( $args = array() ) {
		static $theme_name = false;

		if ( ! $theme_name ) {
			$theme_obj = wp_get_theme();
			if ( $theme_obj->parent() ) {
				$theme_name = $theme_obj->parent()->get( 'Name' );
			} else {
				$theme_name = $theme_obj->get( 'Name' );
			}

			$theme_name = sanitize_key( $theme_name );
		}

		$default_args = array(
			'utm_source'   => 'wp-plugin',
			'utm_campaign' => 'gopro',
			'utm_medium'   => 'wp-dash',
			'utm_term'     => $theme_name,
		);

		return add_query_arg( wp_parse_args( $args, $default_args ), 'https://analogwp.com/style-kits-pro/' );
	}

	/**
	 * Allow to remove method for an hook when, it's a class method used and class don't have variable, but you know the class name.
	 *
	 * @since 1.6.0
	 *
	 * @param string $hook_name Hook/action name to remove.
	 * @param string $class_name Class name. `Colors::class` for example.
	 * @param string $method_name Class method name.
	 * @param int    $priority Action priority.
	 *
	 * @return bool
	 */
	public static function remove_filters_for_anonymous_class( $hook_name = '', $class_name = '', $method_name = '', $priority = 0 ) {
		global $wp_filter;

		// Take only filters on right hook name and priority.
		if ( ! isset( $wp_filter[ $hook_name ][ $priority ] ) || ! is_array( $wp_filter[ $hook_name ][ $priority ] ) ) {
			return false;
		}

		// Loop on filters registered.
		foreach ( $wp_filter[ $hook_name ][ $priority ] as $unique_id => $filter_array ) {
			// Test if filter is an array ! (always for class/method).
			// Test if object is a class, class and method is equal to param !
			if ( isset( $filter_array['function'] ) && is_array( $filter_array['function'] ) && is_object( $filter_array['function'][0] ) && get_class( $filter_array['function'][0] ) && get_class( $filter_array['function'][0] ) === $class_name && $filter_array['function'][1] === $method_name ) {
				// Test for WordPress >= 4.7 WP_Hook class (https://make.wordpress.org/core/2016/09/08/wp_hook-next-generation-actions-and-filters/).
				if ( $wp_filter[ $hook_name ] instanceof \WP_Hook ) {
					unset( $wp_filter[ $hook_name ]->callbacks[ $priority ][ $unique_id ] );
				} else {
					unset( $wp_filter[ $hook_name ][ $priority ][ $unique_id ] );
				}
			}
		}

		return false;
	}

	/**
	 * Get specific Kit setting.
	 *
	 * @since 1.6.2
	 *
	 * @param int         $kit_id Kit ID.
	 * @param null|string $setting Optional. Post meta key to retrieve value for.
	 *
	 * @return mixed
	 */
	public static function get_kit_settings( $kit_id, $setting = null ) {
		$document = Plugin::elementor()->documents->get( $kit_id );

		if ( ! $document ) {
			return false;
		}

		return $document->get_settings( $setting );
	}


	/**
	 * Get Kit active on document.
	 *
	 * @since 1.9.0
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return mixed
	 */
	public static function get_document_kit( $post_id ) {
		// Exit early if no post id provided.
		if ( ! $post_id ) {
			return false;
		}

		$document = Plugin::elementor()->documents->get_doc_for_frontend( $post_id );

		// Check if document exists.
		if ( ! $document ) {
			return false;
		}

		$kit_id = $document->get_settings( 'ang_action_tokens' );

		// Check if this is a valid kit or not.
		if ( ! Plugin::elementor()->kits_manager->is_kit( $kit_id ) ) {
			return false;
		}

		return Plugin::elementor()->documents->get_doc_for_frontend( $kit_id );
	}

	/**
	 * Check if the installed version of Elementor is older than a specified version.
	 *
	 * @param string $version Version number.
	 *
	 * @since 1.8.0
	 *
	 * @return bool
	 */
	public static function is_elementor_pre( $version ) {
		if ( ! defined( 'ELEMENTOR_VERSION' ) || version_compare( ELEMENTOR_VERSION, $version, '<' ) ) {
			$elementor_is_pre_version = true;
		} else {
			$elementor_is_pre_version = false;
		}

		return $elementor_is_pre_version;
	}

	/**
	 * Determine the tab settings should be added to.
	 *
	 * @return string
	 */
	public static function get_kit_settings_tab() {
		$tab = 'theme-style-kits';

		return $tab;
	}

	/**
	 * Get the current kit ID.
	 *
	 * @param $id int
	 *
	 * @return bool
	 */
	public static function set_elementor_active_kit( $id ) {
		$default_kit       = Options::get_instance()->get( 'global_kit' );
		$elementor_kit_key = Manager::OPTION_ACTIVE;
		$elementor_kit     = \get_option( $elementor_kit_key );

		if ( $id !== $default_kit || $id !== $elementor_kit ) {
			if ( empty( $id ) || '-1' === $id ) {
				\update_option( $elementor_kit_key, Options::get_instance()->get( 'default_kit' ) );
			}

			\update_option( $elementor_kit_key, $id );

			return true;
		}

		return false;
	}

	/**
	 * Returns true if Elementor Container experiment is on.
	 *
	 * @return bool
	 */
	public static function is_elementor_container() {
		$flexbox_container           = get_option( 'elementor_experiment-container' );
		$is_flexbox_container_active = \Elementor\Core\Experiments\Manager::STATE_ACTIVE === $flexbox_container;

		if ( 'default' === $flexbox_container ) {
			$experiments                 = new \Elementor\Core\Experiments\Manager();
			$is_flexbox_container_active = $experiments->is_feature_active( 'container' );
		}

		if ( ! $is_flexbox_container_active ) {
			return false;
		}

		return true;
	}

	/**
	 * Returns true if Container experiment is on.
	 *
	 * @return bool
	 */
	public static function is_container() {
		$sk_container_lib = Options::get_instance()->get( 'container_library_experiment' );

		if ( self::is_elementor_container() && ( 'default' === $sk_container_lib || false === $sk_container_lib ) ) {
			return true;
		}

		return 'active' === $sk_container_lib;
	}

	/**
	 * Returns true if the Pro plugin is active.
	 *
	 * @return bool
	 */
	public static function has_pro() {
		return defined( 'ANG_PRO_PLUGIN_BASE' );
	}

	/**
	 * Returns true if Pro license is active.
	 *
	 * @return bool
	 */
	public static function is_pro() {
		$status = Options::get_instance()->get( 'ang_license_key_status' );
		return self::has_pro() && 'valid' === $status;
	}

	/**
	 * Returns sanitized super global value from super globals if exists.
	 *
	 * @since 2.0.5
	 *
	 * @param $super_global
	 * @param $key
	 * @return mixed|null
	 */
	public static function get_super_global_value( $super_global, $key ) {
		if ( ! isset( $super_global[ $key ] ) ) {
			return null;
		}

		if ( $_FILES === $super_global ) {
			$super_global[ $key ]['name'] = sanitize_file_name( $super_global[ $key ]['name'] );

			return $super_global[ $key ];
		}

		return wp_kses_post_deep( wp_unslash( $super_global[ $key ] ) );
	}

	/**
	 * Returns file content.
	 *
	 * @since 2.0.5
	 *
	 * @param $file
	 * @param mixed ...$args
	 * @return false|string
	 */
	public static function file_get_contents( $file, ...$args ) {
		if ( ! is_file( $file ) || ! is_readable( $file ) ) {
			return false;
		}

		return file_get_contents( $file, ...$args );
	}
}

new Utils();
