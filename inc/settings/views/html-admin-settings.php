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
$current_tab_label = $tabs[ $current_tab ] ?? '';

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

			<?php if ( ! class_exists( '\AnalogWP\CustomLibrary\Plugin' ) && ! get_option( 'ang_hide_custom_library_promo' ) ) : ?>
				<div class="promo" data-promo-id="custom_library_promo">
					<a href="#" class="ang-hide-promo" data-promo-id="custom_library_promo"><?php esc_html_e( 'Hide', 'ang' ); ?></a>
					<span class="sticker-tag">New</span>
					<div class="promo-header">
						<svg width="48" height="48" viewBox="0 0 99 99" fill="none" xmlns="http://www.w3.org/2000/svg">
							<g clip-path="url(#clip0_4170_3917)">
								<path d="M49.5 99C76.8381 99 99 76.8381 99 49.5C99 22.1619 76.8381 0 49.5 0C22.1619 0 0 22.1619 0 49.5C0 76.8381 22.1619 99 49.5 99Z" fill="#232624"/>
								<path fill-rule="evenodd" clip-rule="evenodd" d="M61.0206 35.3574H35.3572V61.0208H38.4217V38.4219H61.0206V35.3574Z" fill="white"/>
								<path d="M66.0007 40.3359H40.3373V65.9993H66.0007V40.3359Z" fill="white"/>
							</g>
							<defs>
								<clipPath id="clip0_4170_3917">
									<rect width="99" height="99" fill="white"/>
								</clipPath>
							</defs>
						</svg>
						<h3><a href="https://analogwp.com/custom-library-for-elementor/?utm_medium=plugin&utm_source=settings&utm_campaign=style+kits" target="_blank"><?php esc_html_e( 'Meet Custom Library for Elementor', 'ang' ); ?></a></h3>
					</div>

					<p>Create and manage your own template library directly in the editor, and share it across any Elementor site. Build faster, stay organized, and give your clients consistent, ready-to-use design patterns.</p>

					<ul class="features">
						<li>✅ <b>Custom Template Library</b></li>
						<li>✅ <b>Share Library Across Websites</b></li>
						<li>✅ <b>Template Usage Reports/Analytics</b></li>
						<li>✅ <b>Customizable and White-label ready</b></li>
						<li>✅ <b>Role-Based Access Controls</b></li>
						<li>✅ <b>Self-hosted, Secure and No Signups</b></li>
						<li><b>and so much more...</b></li>
					</ul>

					<p class="short-desc">Use code <a href="https://analogwp.com/custom-library-for-elementor/#pricing" target="_blank">REMOTE20</a> at checkout to get a special discount on our annual plans—limited time only.</p>

					<div class="buttons">
						<a href="https://analogwp.com/custom-library-for-elementor/?utm_medium=plugin&utm_source=settings&utm_campaign=style+kits" target="_blank" class="button button-primary">🚀 Explore Custom Library</a>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( ! class_exists( '\AnalogPro\Plugin' ) ) : ?>
			<div class="upgrade-box special">
				<h3>🔥 Upgrade to Style Kits PRO with a Special Discount</h3>

				<p>Get additional features like <strong>Global Design Features, Libraries of Patterns and Style Kits, Role-Based Access Controls, Priority Support and so much more</strong> while helping us support its development and maintenance.</p>

				<form id="js-ang-request-discount" method="post">
					<input required type="email" class="regular-text" name="email" value="<?php echo esc_attr( $current_user->user_email ); ?>" placeholder="<?php esc_attr_e( 'Your Email', 'ang' ); ?>">
					<input required type="text" class="regular-text" name="first_name" value="<?php echo esc_attr( $current_user->first_name ); ?>" placeholder="<?php esc_attr_e( 'First Name', 'ang' ); ?>">
					<input type="submit" class="button" style="width:100%" value="<?php esc_attr_e( 'Send me the coupon', 'ang' ); ?>" data-default-label="<?php esc_attr_e( 'Send me the coupon', 'ang' ); ?>">
					<p class="ang-discount-response"><span></span></p>
				</form>

				<p>
					<?php
					printf(
							/* translators: %s: Link to AnalogWP privacy policy. */
						esc_html__( 'By submitting your details, you agree to our %s.', 'ang' ),
						'<a target="_blank" href="https://analogwp.com/privacy-policy/">' . esc_html__( 'privacy policy', 'ang' ) . '</a>'
					);
					?>
				</p>
			</div>
			<?php endif; ?>

			<div class="promo">
				<div class="docs">
					<h3><?php esc_html_e( 'Documentation', 'ang' ); ?></h3>
					<p>
						<?php esc_html_e( 'Need help with Style Kits?', 'ang' ); ?>
						<a href="<?php echo esc_url( 'https://analogwp.com/docs/' ); ?>" target="_blank"><?php esc_html_e( 'Visit the online docs', 'ang' ); ?></a>
					</p>
				</div>
				<div class="social">
					<a href="https://x.com/analogwp" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4l11.733 16h4.267l-11.733 -16z" /><path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772" /></svg></span></a>
					<a href="https://facebook.com/analogwp" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-facebook"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" /></svg></a>
					<a href="https://instagram.com/analogwp" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-instagram"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 8a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M16.5 7.5v.01" /></svg></a>
				</div>
			</div>

			<?php do_action( 'ang_sidebar_end' ); ?>
		</div>
	</div>
</div>
