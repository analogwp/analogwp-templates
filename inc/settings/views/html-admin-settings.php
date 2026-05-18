<?php
/**
 * Admin View: Settings
 *
 * @package Analog
 * @since 1.3.8
 */

namespace Analog\Settings\views;

use Analog\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $current_user;

if ( ! isset( $tabs[ $ang_current_tab ] ) && ! has_action( 'ang_sections_' . $ang_current_tab ) && ! has_action( 'ang_settings_' . $ang_current_tab ) && ! has_action( 'ang_settings_tabs_' . $ang_current_tab ) ) {
	wp_safe_redirect( admin_url( 'admin.php?page=ang-settings' ) );
	exit;
}
?>
<div class="wrap ang <?php echo esc_attr( $ang_current_tab ); ?>">
	<h1 class="menu-title"><?php esc_html_e( 'Style Kits Settings', 'analogwp-templates' ); ?></h1>
	<div class="ang-wrapper">
		<form method="<?php echo esc_attr( apply_filters( 'ang_settings_form_method_tab_' . $ang_current_tab, 'post' ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound ?>" id="mainform" action="" enctype="multipart/form-data">
			<nav class="nav-tab-wrapper ang-nav-tab-wrapper">
				<?php

				array_walk(
					$tabs,
					static function ( $tab_label, $tab_slug ) use ( $ang_current_tab ) {
						echo '<a href="' . esc_url( admin_url( 'admin.php?page=ang-settings&tab=' . $tab_slug ) ) . '" class="ang-nav-tab nav-tab-' . esc_attr( $tab_slug ) . ( $ang_current_tab === $tab_slug ? ' ang-nav-tab-active' : '' ) . '">' . esc_html( $tab_label ) . '</a>';
					}
				);

				do_action( 'ang_settings_tabs' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

				?>
			</nav>
			<div class="tab-content">
				<h1 class="screen-reader-text"><?php echo esc_html( $tabs[ $ang_current_tab ] ?? '' ); ?></h1>
				<?php
					do_action( 'ang_sections_' . $ang_current_tab ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

					self::show_messages();

					do_action( 'ang_settings_' . $ang_current_tab ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
				?>
			</div>
			<p class="submit">
				<?php if ( empty( $GLOBALS['hide_save_button'] ) ) : ?>
					<button name="save" class="button-primary ang-save-button" type="submit" value="<?php esc_attr_e( 'Save changes', 'analogwp-templates' ); ?>"><?php esc_html_e( 'Save changes', 'analogwp-templates' ); ?></button>
				<?php endif; ?>
				<?php wp_nonce_field( 'ang-settings' ); ?>
			</p>
		</form>
		<div class="sidebar">
			<?php do_action( 'ang_sidebar_start' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound ?>


			<?php if ( ! class_exists( '\AnalogWP\CustomLibrary\Plugin' ) && ! get_option( 'ang_hide_custom_library_promo' ) ) : ?>
				<div class="promo" data-promo-id="custom_library_promo">
					<a href="#" class="ang-hide-promo" data-promo-id="custom_library_promo"><?php esc_html_e( 'Hide', 'analogwp-templates' ); ?></a>
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
						<h3><a href="https://analogwp.com/custom-library-for-elementor/?utm_medium=plugin&utm_source=settings&utm_campaign=style+kits" target="_blank"><?php esc_html_e( 'Meet Custom Library for Elementor', 'analogwp-templates' ); ?></a></h3>
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

			<div class="plugin-banner">
				<div class="header">
					<div class="brand">
						<svg xmlns="http://www.w3.org/2000/svg" width="450" height="450" viewBox="0 0 450 450" fill="none"><rect width="450" height="450" fill="#413EC5"/><path fill-rule="evenodd" clip-rule="evenodd" d="M226.504 142.557C225.848 141.42 224.207 141.42 223.551 142.557L128.476 307.232C127.82 308.368 128.64 309.789 129.952 309.789H320.103C321.415 309.789 322.235 308.368 321.579 307.232L226.504 142.557ZM226.504 206.477C225.848 205.341 224.207 205.341 223.551 206.477L183.833 275.272C183.177 276.408 183.997 277.828 185.309 277.828H264.746C266.058 277.828 266.878 276.408 266.222 275.272L226.504 206.477Z" fill="white"/><script xmlns=""/></svg>
						<div>
							<h4><?php esc_html_e( 'Style Kits for Elementor', 'analogwp-templates' ); ?></h4>
						</div>
					</div>
				</div>
				<ul class="feature-list">
					<li><a href="https://analogwp.com/docs/?utm_source=plugin&utm_medium=referral&utm_campaign=settings-sidebar" target="_blank"><?php esc_html_e( 'Documentation', 'analogwp-templates' ); ?></a></li>
					<?php if ( ! class_exists( '\AnalogPro\Plugin' ) ) : ?>
					<li><a href="https://analogwp.com/style-kits-pro/?utm_source=plugin&utm_medium=referral&utm_campaign=settings-sidebar" target="_blank"><?php esc_html_e( 'Explore Style Kits PRO Features', 'analogwp-templates' ); ?></a></li>
					<?php endif; ?>
					<?php if ( ! defined( 'AGWP_LIBRARY_VERSION' ) ) : ?>
					<li><span class="inline-badge"><?php esc_html_e( 'New', 'analogwp-templates' ); ?></span>&nbsp;<a href="https://analogwp.com/custom-library-for-elementor/?utm_source=plugin&utm_medium=referral&utm_campaign=settings-sidebar" target="_blank"><?php esc_html_e( 'Custom Library for Elementor', 'analogwp-templates' ); ?></a></li>
					<?php endif; ?>
					<?php if ( ! class_exists( '\AnalogPro\Plugin' ) ) : ?>
					<li><a href="https://analogwp.com/all-access-pass/?utm_source=plugin&utm_medium=referral&utm_campaign=settings-sidebar" target="_blank"><?php esc_html_e( 'Unlimited Access Pass', 'analogwp-templates' ); ?></a></li>
					<?php endif; ?>
				</ul>
			</div>

			<?php if ( ! class_exists( '\AnalogPro\Plugin' ) ) : ?>
			<div class="upgrade-box special">
				<h3>🔥 Upgrade to Style Kits PRO with a Special Discount</h3>

				<p>Get additional features like <strong>Global Design Features, Libraries of Patterns and Style Kits, Role-Based Access Controls, Priority Support and so much more</strong> while helping us support its development and maintenance.</p>

				<form id="js-ang-request-discount" method="post">
					<input required type="email" class="regular-text" name="email" value="<?php echo esc_attr( $current_user->user_email ); ?>" placeholder="<?php esc_attr_e( 'Your Email', 'analogwp-templates' ); ?>">
					<input required type="text" class="regular-text" name="first_name" value="<?php echo esc_attr( $current_user->first_name ); ?>" placeholder="<?php esc_attr_e( 'First Name', 'analogwp-templates' ); ?>">
					<input type="submit" class="button" style="width:100%" value="<?php esc_attr_e( 'Send me the coupon', 'analogwp-templates' ); ?>" data-default-label="<?php esc_attr_e( 'Send me the coupon', 'analogwp-templates' ); ?>">
					<p class="ang-discount-response"><span></span></p>
				</form>

				<p>
					<?php
					printf(
							/* translators: %s: Link to AnalogWP privacy policy. */
						esc_html__( 'By submitting your details, you agree to our %s.', 'analogwp-templates' ),
						'<a target="_blank" href="https://analogwp.com/privacy-policy/">' . esc_html__( 'privacy policy', 'analogwp-templates' ) . '</a>'
					);
					?>
				</p>
			</div>
			<?php endif; ?>

			<div class="help-box">
				<div>
					<h3>🙋 <?php esc_html_e( 'Looking for help or have a feature request?', 'analogwp-templates' ); ?></h3>
				</div>
				<div class="action-buttons">
					<?php if ( Utils::has_pro() ) : ?>
						<a class="button button-secondary" href="<?php echo esc_url( admin_url( 'admin.php?page=analogwp_templates-account' ) ); ?>">Account</a>
					<?php endif; ?>
					<a class="button button-secondary" href="<?php echo esc_url( admin_url( 'admin.php?page=analogwp_templates-contact' ) ); ?>">Create a Support Request</a>
					<a class="button button-secondary" href="https://analogwp.com/docs/?utm_source=plugin&utm_medium=referral&utm_campaign=settings-help" target="_blank"><?php esc_html_e( 'Visit Documentation', 'analogwp-templates' ); ?></a>
				</div>
			</div>

			<?php do_action( 'ang_sidebar_end' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound ?>
		</div>
	</div>
</div>
