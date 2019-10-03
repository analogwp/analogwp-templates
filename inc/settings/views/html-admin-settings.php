<?php
/**
 * Admin View: Settings
 *
 * @package Analog
 */

namespace Analog\settings\views;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$tab_exists        = isset( $tabs[ $current_tab ] ) || has_action( 'ang_sections_' . $current_tab ) || has_action( 'ang_settings_' . $current_tab ) || has_action( 'ang_settings_tabs_' . $current_tab );
$current_tab_label = isset( $tabs[ $current_tab ] ) ? $tabs[ $current_tab ] : '';

if ( ! $tab_exists ) {
	wp_safe_redirect( admin_url( 'admin.php?page=ang-settings' ) );
	exit;
}
?>
<div class="wrap ang <?php echo esc_attr( $current_tab );?>">
	<h1 class="menu-title"><?php esc_html_e( 'Style Kits Settings', 'pulse' ); ?></h1>
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
					do_action( 'ang_settings_tabs_' . $current_tab ); // @deprecated hook. @todo remove in 4.0.
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
			<div class="docs">
				<h1><?php esc_html_e( 'Documentation', 'ang' ); ?></h1>
				<p><?php esc_html_e( 'Style Kits come with easy-to-follow docs to get you started.', 'ang' ); ?></p>
				<a href="<?php echo esc_url( 'https://docs.analogwp.com/' ); ?>"><?php esc_html_e( 'Go to Docs', 'ang' ); ?></a>
			</div>
			<div class="social-group">
				<h1><?php esc_html_e( 'Facebook group', 'ang' ); ?></h1>
				<p><?php esc_html_e( 'Join our Facebook community and share workflows, get ideas and support.', 'ang' ); ?></p>
				<a href="<?php echo esc_url( 'https://www.facebook.com/groups/analogwp/' ); ?>"><?php esc_html_e( 'Join Facebook Group', 'ang' ); ?></a>
			</div>
			<div class="newsletter-list">
				<h1><?php esc_html_e( 'Sign up to the mailing list', 'ang' ); ?></h1>
				<form id="ang-newsletter" action="" class="form-newsletter">
					<input id="ang-newsletter-email" type="email" placeholder="Enter your email" />
					<button id="ang-newsletter-submit" class="ang-button" type="submit"><?php esc_html_e( 'Subscribe up to newsletter', 'ang'); ?></button>
				</form>
				<p><?php esc_html_e( 'By signing up you agree to our ', 'ang' ); ?><a href="#"><?php esc_html_e( 'privacy and terms' ); ?></a></p>
			</div>
			<div class="social"></div>
		</div>
	</div>
</div>
