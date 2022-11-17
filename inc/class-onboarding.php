<?php
/**
 * Plugin onboarding.
 *
 * @package AnalogWP
 */

namespace Analog;

/**
 * Initializes plugin onboarding.
 */
class Onboarding {

	/**
	 * Onboarding version.
	 *
	 * @var string
	 */
	public static $version = '2.0.0';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'wp_ajax_analog_onboarding', array( $this, 'ajax_actions' ) );

		$existing_version = get_option( 'analog_onboarding' );

		if ( version_compare( self::$version, $existing_version, '>' ) ) {
			// Redirect to onboarding page.
			wp_safe_redirect( admin_url( 'admin.php?page=analog_onboarding' ) );
			update_option( 'analog_onboarding', self::$version );
		}
	}

	/**
	 * Registers onboarding page.
	 */
	public function register_menu() {
		add_submenu_page(
			null,
			__( 'Welcome to Style Kits', 'ang' ),
			__( 'Welcome to Style Kits', 'ang' ),
			'manage_options',
			'analog_onboarding',
			array( $this, 'render_markup' )
		);
	}


	/**
	 * Enqueues scripts and styles.
	 *
	 * @return void
	 */
	public function enqueue_assets() {
		wp_enqueue_style(
			'analog-onboarding-screen',
			ANG_PLUGIN_URL . 'assets/css/onboarding-screen.css',
			array(),
			filemtime( ANG_PLUGIN_DIR . 'assets/css/onboarding-screen.css' )
		);

		wp_enqueue_script(
			'analog-onboarding-screen',
			ANG_PLUGIN_URL . 'assets/js/onboarding-screen.js',
			array( 'jquery' ),
			filemtime( ANG_PLUGIN_DIR . 'assets/js/onboarding-screen.js' ),
			true
		);
	}

	/**
	 * Get required onboarding steps.
	 *
	 * @return array[]
	 */
	public function get_steps() {
		$steps = array();

		// Show this option in case of Elementor is not active.
		if ( ! did_action( 'elementor/loaded' ) ) {
			$steps[] = array(
				'id'            => 'install-elementor',
				'label'         => __( 'Install and Activate Elementor', 'ang' ),
				'description'   => __( 'This will install and activate Elementor from the WordPress repository', 'ang' ),
				'label_success' => __( 'Elementor is installed and activated', 'ang' ),
				'checked'       => true,
			);
		}

		// Show this option in case of Elementor is not active or Elementor Container experiment is not enabled.
		if ( ! did_action( 'elementor/loaded' ) || ( method_exists( '\Analog\Utils', 'is_elementor_container' ) && ! \Analog\Utils::is_elementor_container() ) ) {
			$steps[] = array(
				'id'            => 'enable-el-container-experiment',
				'label'         => __( 'Enable Elementor container experiment', 'ang' ),
				'description'   => __( 'Style Kits 2.0 works with Elementor containers. We will enable this experiment in Elementor', 'ang' ),
				'label_success' => __( 'Container experiment is now active', 'ang' ),
				'checked'       => true,
			);
		}

		// Show this option in case of either Elementor default colors or fonts are not disabled.
		if ( ! get_option( 'elementor_disable_color_schemes' ) || ! get_option( 'elementor_disable_typography_schemes' ) ) {
			$steps[] = array(
				'id'            => 'disable-el-defaults',
				'label'         => __( 'Disable Elementor default colors and fonts', 'ang' ),
				'description'   => __( 'For Global Styles to work properly, Elementor default fonts and colors need to be disabled', 'ang' ),
				'label_success' => __( 'Elementor default colors and fonts are disabled', 'ang' ),
				'checked'       => true,
			);
		}

		// Show this option in case of either Hello Elementor theme is not installed and active.
		$themes        = wp_get_themes();
		$current_theme = wp_get_theme()->get_stylesheet();
		$needle        = 'hello-elementor';

		if ( ! in_array( $needle, array_keys( $themes ), true ) || $needle !== $current_theme ) {
			$steps[] = array(
				'id'            => 'install-hello-theme',
				'label'         => __( 'Install and activate Hello Elementor Theme', 'ang' ),
				'description'   => __( 'Style Kits works best with Elementor Hello theme. This will replace your currently active theme', 'ang' ),
				'label_success' => __( 'Hello Elementor theme is installed and activated', 'ang' ),
				'checked'       => false,
			);

		}

		// Show this option in case of base kit is not imported.
		$all_kits = method_exists( '\Analog\Utils', 'get_kits' ) ? \Analog\Utils::get_kits() : array();

		if ( ! in_array( 'Style Kit: Base', array_values( $all_kits ), true ) ) {
			$steps[] = array(
				'id'            => 'import-base-kit',
				'label'         => __( 'Import a starter theme style preset', 'ang' ),
				'description'   => __( 'Use a basic Style Kit as your starting point. This will replace your existing global styles', 'ang' ),
				'label_success' => __( 'A theme style preset "Style Kit: Base" has been imported', 'ang' ),
				'checked'       => true,
			);
		}

		return $steps;
	}

	/**
	 * Admin page contents for Theme Style Kit migration screen.
	 *
	 * @return void
	 */
	public function render_markup() {
		// Enqueue assets.
		$this->enqueue_assets();

		// Gets available steps.
		$steps = $this->get_steps();
		?>
		<div id="analog-welcome-screen" class="analog-welcome-screen">
			<form id="onboarding-modal" class="onboarding-modal">
				<div class="entry-header">
					<div class="logo">
						<span class="brand-icon">
							<svg width="41" height="41" viewBox="0 0 41 41" fill="none" xmlns="http://www.w3.org/2000/svg">
								<circle cx="20.5" cy="20.5" r="20.5" fill="#413EC5"/>
								<path fill-rule="evenodd" clip-rule="evenodd" d="M21.5261 10.1484C21.1412 9.48177 20.1789 9.48177 19.794 10.1484L9.73663 27.5684C9.35173 28.235 9.83285 29.0684 10.6027 29.0684H30.7174C31.4872 29.0684 31.9684 28.235 31.5835 27.5684L21.5261 10.1484ZM21.5261 17.8359C21.1412 17.1693 20.1789 17.1693 19.794 17.8359L16.3942 23.7246C16.0093 24.3913 16.4904 25.2246 17.2602 25.2246H24.0599C24.8297 25.2246 25.3108 24.3913 24.9259 23.7246L21.5261 17.8359Z" fill="white"/>
							</svg>
						</span>
						<span class="brand-title">Style Kits</span>
					</div>
					<nav>
						<a href="<?php echo esc_url( 'https://docs.analogwp.com' ); ?>" target="_blank"><?php esc_html_e( 'Docs', 'ang' ); ?></a>
					</nav>
				</div>
				<div class="content-wrapper">
					<?php if ( empty( $steps ) ) : ?>
						<p class="short-description"><?php esc_html_e( 'Looks like you have everything in place.', 'ang' ); ?></p>
					<?php else : ?>
					<p class="short-description"><?php esc_html_e( 'Setup Elementor properly for a seamless Style Kits Experience.', 'ang' ); ?> <a href="#">Learn more</a></p>
					<div class="steps-wrapper">
						<?php
						foreach ( $steps as $step ) :
							if ( empty( $step ) ) {
								continue;
							}
							?>

							<div class="step <?php echo esc_attr( 'step-' . $step['id'] ); ?>">
								<div class="control current">
									<div class="switch">
										<div class="switch__field">
											<input id="<?php echo esc_attr( $step['id'] ); ?>" type="checkbox" <?php echo $step['checked'] ? esc_attr( 'checked' ) : ''; ?>>
											<label for="<?php echo esc_attr( $step['id'] ); ?>"></label>
										</div>
									</div>
									<div>
										<p class="switch-label"><?php echo esc_html( $step['label'] ); ?></p>
										<p class="switch-description"><?php echo esc_html( $step['description'] ); ?></p>
									</div>
								</div>
								<div class="in-process">
									<div>
										<div class="spinner-box">
											<div class="pulse-container">
												<div class="pulse-bubble pulse-bubble-1"></div>
												<div class="pulse-bubble pulse-bubble-2"></div>
												<div class="pulse-bubble pulse-bubble-3"></div>
											</div>
										</div>
									</div>
									<div>
										<p class="switch-label"><?php echo esc_html( $step['label'] ); ?></p>
									</div>
								</div>
								<div class="failed">
									<div>
										<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512"><title>Close Circle</title><path d="M256 48C141.31 48 48 141.31 48 256s93.31 208 208 208 208-93.31 208-208S370.69 48 256 48zm75.31 260.69a16 16 0 11-22.62 22.62L256 278.63l-52.69 52.68a16 16 0 01-22.62-22.62L233.37 256l-52.68-52.69a16 16 0 0122.62-22.62L256 233.37l52.69-52.68a16 16 0 0122.62 22.62L278.63 256z" fill="#7F6097"/></svg>
									</div>
									<div>
										<p class="switch-label"><?php echo esc_html( $step['label'] ); ?></p>
									</div>
								</div>
								<div class="success">
									<div>
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M8.79289 19.1643L2.29288 12.6643C1.90237 12.2738 1.90237 11.6406 2.29288 11.2501L3.70706 9.83585C4.09757 9.44531 4.73077 9.44531 5.12128 9.83585L9.5 14.2145L18.8787 4.83585C19.2692 4.44534 19.9024 4.44534 20.2929 4.83585L21.7071 6.25007C22.0976 6.64058 22.0976 7.27374 21.7071 7.66429L10.2071 19.1643C9.81656 19.5548 9.1834 19.5548 8.79289 19.1643Z" fill="#6DB17C"/>
										</svg>
									</div>
									<div>
										<p class="switch-label"><?php echo esc_html( $step['label_success'] ); ?></p>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
				</div>
				<div class="entry-footer">
					<div class="prev">
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=ang-settings' ) ); ?>"><?php esc_html_e( 'Skip wizard', 'ang' ); ?></a>
					</div>
					<div class="next <?php echo empty( $steps ) ? esc_attr( 'hidden' ) : ''; ?>">
						<button id="start-onboarding" class="button btn-primary"><?php esc_html_e( 'Apply', 'ang' ); ?></button>
					</div>
					<div class="next-success <?php echo ! empty( $steps ) ? esc_attr( 'hidden' ) : ''; ?>">
						<a href="<?php echo esc_url( admin_url( 'index.php' ) ); ?>" class="button btn-secondary">Go to Dashboard</a>
						<a href="#" class="button btn-primary">Open a template</a>
					</div>
				</div>
			</form>
		</div>
		<?php
	}

	/**
	 * Required classes & files.
	 *
	 * @return void
	 */
	private function recommended_files() {
		include_once ABSPATH . 'wp-admin/includes/update.php';
		include_once ABSPATH . 'wp-admin/includes/upgrade.php';
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		include_once ABSPATH . 'wp-admin/includes/class-automatic-upgrader-skin.php';
	}

	/**
	 * Required plugin classes & files.
	 *
	 * @return void
	 */
	private function recommended_plugin_files() {
		include_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	/**
	 * Required theme classes & files.
	 *
	 * @return void
	 */
	private function recommended_theme_files() {
		include_once ABSPATH . 'wp-admin/includes/theme.php';
	}

	/**
	 * Install and activate Elementor plugin.
	 *
	 * @return array
	 */
	private function install_elementor() {
		// Include required files.
		$this->recommended_files();
		$this->recommended_plugin_files();

		$file    = 'elementor/elementor.php';
		$plugins = get_plugins();

		if ( ! in_array( $file, array_keys( $plugins ), true ) ) {
			$slug      = 'elementor';
			$installer = new \Plugin_Upgrader( new \Automatic_Upgrader_Skin() );

			$api = plugins_api(
				'plugin_information',
				array(
					'slug'   => $slug,
					'fields' => array(
						'sections' => false,
					),
				)
			);

			if ( is_wp_error( $api ) ) {
				return array(
					'error' => $api->get_error_message(),
				);
			}

			$results = $installer->install(
				$api->download_link,
			);

			if ( is_wp_error( $results ) ) {
				return array( 'error' => $results->get_error_message() );
			} elseif ( ! $results ) {
				return array( 'error' => $results );
			}
		}

		$results = activate_plugin( $file );

		if ( is_wp_error( $results ) ) {
			return array( 'error' => $results->get_error_message() );
		}

		return array(
			'success' => true,
		);
	}

	/**
	 * Install and activate Elementor Pro plugin.
	 *
	 * @return array
	 */
	private function install_hello_elementor() {
		// Include required files.
		$this->recommended_files();
		$this->recommended_theme_files();

		$themes     = wp_get_themes();
		$stylesheet = 'hello-elementor';

		if ( ! in_array( $stylesheet, array_keys( $themes ), true ) ) {
			$installer = new \Theme_Upgrader( new \Automatic_Upgrader_Skin() );

			$api = themes_api(
				'theme_information',
				array(
					'slug'   => $stylesheet,
					'fields' => array(
						'sections' => false,
					),
				)
			);

			if ( is_wp_error( $api ) ) {
				return array(
					'error' => $api->get_error_message(),
				);
			}

			$results = $installer->install(
				$api->download_link,
			);

			if ( is_wp_error( $results ) ) {
				return array( 'error' => $results->get_error_message() );
			} elseif ( ! $results ) {
				return array( 'error' => $results );
			}
		}

		// Switch theme.
		switch_theme( 'hello-elementor' );

		return array(
			'success' => true,
		);
	}

	/**
	 * Enables Elementor Container Experiment.
	 *
	 * @return array
	 */
	private function enable_el_container_experiment() {
		$result = update_option( 'elementor_experiment-container', 'active' );

		if ( ! $result ) {
			return array(
				'error' => __( 'Failed to activate Elementor Container Experiment.', 'ang' ),
			);
		}
		return array(
			'success' => true,
		);
	}

	/**
	 * Enables Elementor Container Experiment.
	 *
	 * @return array
	 */
	private function disable_el_defaults() {
		$color_schemes      = update_option( 'elementor_disable_color_schemes', 'yes' );
		$typography_schemes = update_option( 'elementor_disable_typography_schemes', 'yes' );

		if ( ! $color_schemes || ! $typography_schemes ) {
			return array(
				'error' => 'Failed to disable Elementor default colors and fonts.',
			);
		}
		return array(
			'success' => true,
		);
	}

	/**
	 * Imports base style kit.
	 *
	 * @return array
	 */
	private function import_base_kit() {
		$kit = array(
			'id'             => 6491,
			'title'          => 'Style Kit: Base',
			'image'          => 'https://preview.analogwp.com/designsystem/wp-content/uploads/sites/26/2022/11/base.svg',
			'site_id'        => 26,
			'is_pro'         => false,
			'uses_container' => true,
		);

		if ( method_exists( 'Analog\Elementor\Kit\Manager', 'import_kit' ) ) {
			$kit_manager = new \Analog\Elementor\Kit\Manager();
			$result      = $kit_manager->import_kit( $kit );

			if ( is_wp_error( $result ) ) {
				return array(
					'error' => $result->get_error_message(),
				);
			}

			return array(
				'success' => true,
			);
		}

		return array(
			'error' => __( 'Failed to import Style Kit: Base', 'ang' ),
		);
	}

	/**
	 * AJAX actions to complete onboarding steps.
	 *
	 * @return void
	 */
	public function ajax_actions() {
		$action  = isset( $_POST['stepId'] ) ? sanitize_key( $_POST['stepId'] ) : '';
		$results = array();

		switch ( $action ) {
			case 'install-elementor':
				$results[ $action ] = $this->install_elementor();
				break;
			case 'enable-el-container-experiment':
				$results[ $action ] = $this->enable_el_container_experiment();
				break;
			case 'disable-el-defaults':
				$results[ $action ] = $this->disable_el_defaults();
				break;
			case 'install-hello-theme':
				$results[ $action ] = $this->install_hello_elementor();
				break;
			case 'import-base-kit':
				$results[ $action ] = $this->import_base_kit();
				break;
		}

		wp_send_json_success( $results );
	}
}

new Onboarding();
