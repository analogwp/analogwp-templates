<?php
/**
 * Admin View: Settings
 *
 * @package Analog
 * @since 1.3.8
 */

namespace Analog\Settings\views;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$tab_exists        = isset( $tabs[ $current_tab ] ) || has_action( 'ang_sections_' . $current_tab ) || has_action( 'ang_settings_' . $current_tab ) || has_action( 'ang_settings_tabs_' . $current_tab );
$current_tab_label = isset( $tabs[ $current_tab ] ) ? $tabs[ $current_tab ] : '';

global $current_user;

if ( ! $tab_exists ) {
	wp_safe_redirect( admin_url( 'admin.php?page=ang-settings' ) );
	exit;
}
?>
<div class="wrap ang <?php echo esc_attr( $current_tab ); ?>">
	<h1 class="menu-title"><?php esc_html_e( 'Style Kits Settings', 'ang' ); ?></h1>
	<div class="ang-wrapper">
		<form method="<?php echo esc_attr( apply_filters( 'ang_settings_form_method_tab_' . $current_tab, 'post' ) ); ?>" id="mainform" action="" enctype="multipart/form-data">
			<nav class="nav-tab-wrapper ang-nav-tab-wrapper">
				<?php

				foreach ( $tabs as $slug => $label ) {
					echo '<a href="' . esc_html( admin_url( 'admin.php?page=ang-settings&tab=' . esc_attr( $slug ) ) ) . '" class="nav-tab ' . ( $current_tab === $slug ? 'nav-tab-active' : '' ) . '">' . esc_html( $label ) . '</a>';
				}

				do_action( 'ang_settings_tabs' );

				?>
			</nav>
			<div class="tab-content">
				<h1 class="screen-reader-text"><?php echo esc_html( $current_tab_label ); ?></h1>
				<?php
					do_action( 'ang_sections_' . $current_tab );

					self::show_messages();

					do_action( 'ang_settings_' . $current_tab );
				?>
				<p class="submit">
					<?php if ( empty( $GLOBALS['hide_save_button'] ) ) : ?>
						<button name="save" class="button-primary ang-save-button" type="submit" value="<?php esc_attr_e( 'Save changes', 'ang' ); ?>"><?php esc_html_e( 'Save changes', 'ang' ); ?></button>
					<?php endif; ?>
					<?php wp_nonce_field( 'ang-settings' ); ?>
				</p>
			</div>
		</form>
		<div class="sidebar">
			<?php do_action( 'ang_sidebar_start' ); ?>

			<div class="docs">
				<h3><?php esc_html_e( 'Documentation', 'ang' ); ?></h3>
				<p><?php esc_html_e( 'Need help setting up? We have a number of handy articles to get you started.', 'ang' ); ?></p>
				<a href="<?php echo esc_url( 'https://docs.analogwp.com/' ); ?>" target="_blank"><?php esc_html_e( 'Read Documentation', 'ang' ); ?></a>
			</div>
			<div class="social-group">
				<h3><?php esc_html_e( 'Join our Facebook group', 'ang' ); ?></h3>
				<p><?php esc_html_e( 'Get insights, tips and updates in our facebook community. Let\'s take Elementor design to a whole new level.', 'ang' ); ?></p>
				<a href="<?php echo esc_url( 'https://www.facebook.com/groups/analogwp/' ); ?>" target="_blank"><?php esc_html_e( 'Join the AnalogWP community', 'ang' ); ?></a>
			</div>
			<div class="newsletter-list">
				<h3><?php esc_html_e( 'Sign up for updates', 'ang' ); ?></h3>
				<p><?php esc_html_e( 'Sign up to Analog Newsletter and get notified about product updates, freebies and more.', 'ang' ); ?></p>
				<form id="ang-newsletter" action="" class="form-newsletter">
					<input id="ang-newsletter-email" type="email" placeholder="Enter your email" value="<?php echo esc_attr( $current_user->user_email ); ?>"/>
					<button id="ang-newsletter-submit" class="ang-button button-primary" type="submit"><?php esc_html_e( 'Sign me up', 'ang' ); ?></button>
				</form>
				<p><?php esc_html_e( 'By signing up you agree to our', 'ang' ); ?> <a href="<?php echo esc_url( 'https://analogwp.com/privacy-policy/' ); ?>" target="_blank"><?php esc_html_e( 'privacy and terms', 'ang' ); ?></a></p>
			</div>
			<div class="social">
				<a href="https://facebook.com/analogwp" target="_blank"><span class="dashicons dashicons-facebook-alt"></span></a>
				<a href="https://twitter.com/analogwp" target="_blank"><span class="dashicons dashicons-twitter"></span></a>
				<a href="https://instagram.com/analogwp" target="_blank"><span class="dashicons dashicons-instagram"></span></span></a>
			</div>

			<?php do_action( 'ang_sidebar_end' ); ?>
		</div>
	</div>
</div>
