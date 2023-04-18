<?php
/**
 * Elementor core integration.
 *
 * @package AnalogWP
 */

namespace Analog;

use Analog\Elementor\ANG_Action;
use Analog\Elementor\Globals\Controller;
use Elementor\Core\Common\Modules\Finder\Categories_Manager;
use Elementor\Core\DynamicTags\Manager;
use Analog\Elementor\Tags\Light_Background;
use Analog\Elementor\Tags\Dark_Background;
use Elementor\TemplateLibrary\Source_Local as Local;
use Elementor\Core\Kits\Manager as Kits_Manager;

/**
 * Intializes scripts/styles needed for AnalogWP modal on Elementor editing page.
 */
class Elementor {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_editor_scripts' ) );
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueue_editor_scripts' ) );

		add_action( 'wp_ajax_elementor_library_direct_actions', array( $this, 'maybe_add_elementor_data' ) );

		add_action(
			'elementor/finder/register',
			static function ( Categories_Manager $categories_manager ) {
				include_once ANG_PLUGIN_DIR . 'inc/elementor/class-finder-shortcuts.php';
				$categories_manager->register( new Finder_Shortcuts() );
			}
		);

		add_action( 'elementor/controls/register', array( $this, 'register_controls' ) );

		add_action(
			'elementor/dynamic_tags/register',
			static function( Manager $dynamic_tags ) {

				$dynamic_tags->register_group(
					'ang_classes',
					array(
						'title' => __( 'AnalogWP Classes', 'ang' ),
					)
				);

				include_once ANG_PLUGIN_DIR . 'inc/elementor/tags/class-dark-background.php';
				include_once ANG_PLUGIN_DIR . 'inc/elementor/tags/class-light-background.php';

				$dynamic_tags->register( new Light_Background() );
				$dynamic_tags->register( new Dark_Background() );

			}
		);

		add_action( 'elementor/template-library/after_save_template', array( $this, 'fix_kit_import' ), 999, 2 );

		// Update global kit data when active kit is changed, also fixes issues with Site kit importer.
		$active_kit_key = Kits_Manager::OPTION_ACTIVE;
		add_action( "update_option_{$active_kit_key}", array( $this, 'fix_active_kit_updates' ), 999, 2 );

		$this->register_data_controllers();
	}

	/**
	 * Register custom Elementor REST data controllers.
	 *
	 * @return void
	 */
	public function register_data_controllers() {
		require_once ANG_PLUGIN_DIR . 'inc/elementor/globals/class-controller.php';
		require_once ANG_PLUGIN_DIR . 'inc/elementor/globals/class-colors.php';
		require_once ANG_PLUGIN_DIR . 'inc/elementor/globals/class-typography.php';

		add_action(
			'elementor/editor/init',
			function() {
				/**
				 * Set current page id.
				 */
				Options::get_instance()->set( 'ang_current_page_id', get_the_ID() );
			}
		);

		Plugin::elementor()->data_manager_v2->register_controller( new Controller() );
	}

	/**
	 * Register custom Elementor control.
	 */
	public function register_controls() {
		require_once ANG_PLUGIN_DIR . 'inc/elementor/class-ang-action.php';

		$controls_manager = Plugin::elementor()->controls_manager;

		$controls_manager->register( new ANG_Action() );
	}

	/**
	 * Load styles and scripts for Elementor modal.
	 *
	 * @return void
	 */
	public function enqueue_editor_scripts() {

		// Independent components.
		wp_enqueue_style( 'analogwp-components-css', ANG_PLUGIN_URL . 'assets/css/sk-components.css', array(), filemtime( ANG_PLUGIN_DIR . 'assets/css/sk-components.css' ) );

		do_action( 'ang_loaded_templates' );

		wp_enqueue_script( 'analogwp-elementor-modal', ANG_PLUGIN_URL . 'assets/js/elementor-modal.js', array( 'jquery' ), filemtime( ANG_PLUGIN_DIR . 'assets/js/elementor-modal.js' ), false );
		wp_enqueue_style( 'analogwp-elementor-modal', ANG_PLUGIN_URL . 'assets/css/elementor-modal.css', array( 'dashicons' ), filemtime( ANG_PLUGIN_DIR . 'assets/css/elementor-modal.css' ) );

		wp_enqueue_script(
			'analogwp-app',
			ANG_PLUGIN_URL . 'assets/js/app.js',
			array(
				'react',
				'react-dom',
				'jquery',
				'wp-components',
				'wp-hooks',
				'wp-i18n',
				'wp-api-fetch',
				'wp-html-entities',
			),
			filemtime( ANG_PLUGIN_DIR . 'assets/js/app.js' ),
			true
		);
		wp_set_script_translations( 'analogwp-app', 'ang', ANG_PLUGIN_DIR . 'languages' );

		wp_enqueue_style( 'wp-components' );

		wp_enqueue_style( 'analog-google-fonts', 'https://fonts.googleapis.com/css?family=Inter:400,500,600,700&display=swap', array(), '20221016' );

		$options = Options::get_instance();

		// By default, set it to allow.
		$is_legacy_hidden = $options->has( 'hide_legacy_features' ) ? $options->get( 'hide_legacy_features' ) : true;

		if ( $is_legacy_hidden ) {
			wp_add_inline_style(
				'analogwp-components-css',
				'.elementor-control.elementor-control-ang_section_padding,
						 .elementor-control.elementor-control-ang_column_gaps,
						 .elementor-control.elementor-control-ang_colors {
					      display: none !important;
					}'
			);
		}

		$l10n = apply_filters( // phpcs:ignore
			'analog/app/strings',
			array(
				'is_settings_page' => false,
				'global_kit'       => get_option( 'elementor_active_kit' ),
			)
		);

		wp_localize_script( 'analogwp-app', 'AGWP', $l10n );
	}

	/**
	 * In some cases, A Kit as empty data content inside '_elementor_data'.
	 *
	 * Since an export requires that to be non-empty. We programmatically add
	 * Kit content to kit to make it exportable.
	 *
	 * @since 1.6.9
	 * @return void
	 */
	public function maybe_add_elementor_data() {
		if ( isset( $_REQUEST['library_action'] ) && 'export_template' === $_REQUEST['library_action'] ) {
			$template_id = filter_input( INPUT_GET, 'template_id' );

			if ( $template_id ) {
				$template_data = get_post_meta( $template_id, '_elementor_data', true );

				if ( ! $template_data || '[]' === $template_data ) {
					$kit = new \Analog\Elementor\Kit\Manager();
					update_post_meta( $template_id, '_elementor_data', $kit->get_kit_content() );
				}
			}
		}
	}

	/**
	 * Fixes kit imports.
	 *
	 * @param int   $id Template id.
	 * @param array $data Template data.
	 *
	 * @return void
	 */
	public function fix_kit_import( $id, $data ) {
		if ( ! empty( $data['type'] ) && 'kit' === $data['type'] ) {
			$post_data['ID']        = $id;
			$post_data['post_type'] = Local::CPT;

			wp_update_post( $post_data );
		}
	}

	/**
	 * Fixes global kit updates.
	 *
	 * @param int $old_value Old Kit id.
	 * @param int $value New Kit id.
	 *
	 * @return void
	 */
	public function fix_active_kit_updates( $old_value, $value ) {
		if ( $value && $old_value !== $value ) {
			Options::get_instance()->set( 'global_kit', $value );
			Utils::set_elementor_active_kit( $value );

			Utils::clear_elementor_cache();
		}
	}
}

new Elementor();
