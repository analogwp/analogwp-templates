<?php
/**
 * Admin View: Sidebar shown on the Settings and Local Style Kits screens.
 *
 * @package Analog
 * @since 2.7.0
 */

namespace Analog\Settings\views;

use Analog\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="sidebar">
	<?php do_action( 'ang_sidebar_start' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound ?>

	<div class="plugin-banner">
		<div class="header">
			<div class="brand">
				<svg xmlns="http://www.w3.org/2000/svg" width="450" height="450" viewBox="0 0 450 450" fill="none"><rect width="450" height="450" fill="#413EC5"/><path fill-rule="evenodd" clip-rule="evenodd" d="M226.504 142.557C225.848 141.42 224.207 141.42 223.551 142.557L128.476 307.232C127.82 308.368 128.64 309.789 129.952 309.789H320.103C321.415 309.789 322.235 308.368 321.579 307.232L226.504 142.557ZM226.504 206.477C225.848 205.341 224.207 205.341 223.551 206.477L183.833 275.272C183.177 276.408 183.997 277.828 185.309 277.828H264.746C266.058 277.828 266.878 276.408 266.222 275.272L226.504 206.477Z" fill="white"/></svg>
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
		</ul>
	</div>

	<div class="help-box">
		<div>
			<h3>🙋 <?php esc_html_e( 'Looking for help or have a feature request?', 'analogwp-templates' ); ?></h3>
		</div>
		<div class="action-buttons">
			<?php if ( Utils::has_pro() ) : ?>
				<a class="button button-secondary" href="<?php echo esc_url( admin_url( 'admin.php?page=analogwp_templates-account' ) ); ?>"><?php esc_html_e( 'Account', 'analogwp-templates' ); ?></a>
			<?php endif; ?>
			<a class="button button-secondary" href="<?php echo esc_url( admin_url( 'admin.php?page=analogwp_templates-contact' ) ); ?>"><?php esc_html_e( 'Create a Support Request', 'analogwp-templates' ); ?></a>
			<a class="button button-secondary" href="https://analogwp.com/docs/?utm_source=plugin&utm_medium=referral&utm_campaign=settings-help" target="_blank"><?php esc_html_e( 'Visit Documentation', 'analogwp-templates' ); ?></a>
		</div>
	</div>

	<?php do_action( 'ang_sidebar_end' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound ?>
</div>
