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
		<?php self::output_sidebar(); ?>
	</div>
</div>
