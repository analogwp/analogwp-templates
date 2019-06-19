=== Analog Templates for Elementor ===
Contributors: analogwp, mauryaratan
Requires at least: 5.0
Requires PHP: 5.4
Tested up to: 5.2.2
Stable tag: 1.2.4
Tags: elementor, landing page, design, website builder, templates
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A handcrafted design library for Elementor templates

== Description ==

[AnalogWP](https://analogwp.com/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=analogwp) follows a design-first approach on template design for Elementor. We create niche-aware templates with scalable layouts that bring consistency and fine aesthetics into your Elementor compositions.

- 100% Free for personal and client projects
- Sync your library with the latest templates, on a consistent release cycle
- Preview and import templates right from your Elementor Editor
- Multi-niche professional design that makes sense, is scalable and consistent.

#### Insert templates with a single click
Import any of our fine-tuned Elementor templates with a single click, directly into your Elementor page, your Elementor Library, or even create a new page to plug your template into. All media files associated with the template are imported automatically.

#### Save your favorite templates
Save your favorite layouts and filter them for easy access later. Favorites are saved on a per-user basis, so all your editors/administrators can have their own.

**Find us**:
- [Visit the AnalogWP Website](https://analogwp.com/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=analogwp)
- [Follow on Twitter](https://twitter.com/AnalogWP/)
- [Like us on Facebook](https://www.facebook.com/analogwp)

== Installation ==

This section describes how to install the plugin and get it working. e.g.

1. Upload the plugin files to the `/wp-content/plugins/analogwp-templates` directory, or install the plugin through the WordPress plugins screen directly..
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Head to 'AnalogWP' page from WordPress sidebar menu.

== Frequently Asked Questions ==

= Do the templates require Elementor Pro? =
No, you do not need Elementor Pro at this point. All the templates that are provided for free are only using the Contact Form widget (which is an Elementor Pro feature). But you can always replace with your favorite contact form plugin.

= What fonts are you using in designs? =
All font pairs in our templates are Google web fonts, we love typography and are always in the look-out for great font combinations.

= Can I use the templates for client projects as well? =
Yes, you can. You can use the templates in any number of personal or client projects. However you cannot re-distribute the templates without our prior consent. You can view the full terms and template licensing here: https://analogwp.com/terms-of-use/.

= What are Style Kits? =
Style Kits (since v1.2) is a collection of the custom Typography and Spacing styles that you have added to your templates, which can be reused with other templates, making the process of creating new templates a lot easier.

= How frequently do you add new designs? =
We try to follow a consistent release cycle of two templates per week.

= Will the templates work with my theme? =
Definitely, given that you have Elementor in your theme setup, the templates will get imported and work just fine.

= Where can I get help? =
Our dedicated support team has your back. Please reach out via our website at https://analogwp.com/.

== Screenshots ==

1. AnalogWP Settings page.
2. Import screen: import to Elementor library or create a new page.
3. Access AnalogWP templates directly under Elementor.

== Changelog ==

= 1.2.4 =
* Fix: A critical error where templates won't import due to internal error
* Improve: Replace `body` selectors with `{{WRAPPER}}` to scope it to specific template
* Improve: Reorganize settings in Heading and Text sizes sections

= 1.2.3 =
* New: Added a [notice](https://github.com/mauryaratan/analogwp-templates/issues/92) to reopen tabs when setting a global Style Kit.
* New: [Updating](https://github.com/mauryaratan/analogwp-templates/issues/107) an existing Style Kit now shows a modal window on pages using the same kit, to choose whether to pull latest changes or keep old.
* New: Added option to [rollback](https://github.com/mauryaratan/analogwp-templates/issues/99) to a previous stable version under AnalogWP > Settings.
* New: Added a visual [indicator](https://github.com/mauryaratan/analogwp-templates/issues/101) on posts list to display which Style Kit is active.
* New: Added a quick post action to "Apply Global Style Kit"
* Fix: Clear Elementor cache when a Style Kit is [updated](https://github.com/mauryaratan/analogwp-templates/issues/103), so other posts sharing same kit can take effect.
* Fix: [Extend](https://github.com/mauryaratan/analogwp-templates/issues/106) heading selector to include `a` tags inside to match styles.
* Improve: Stop users from creating a new style kit directly from CPT [page](https://github.com/mauryaratan/analogwp-templates/issues/97), as it results in empty style kit.

= 1.2.2 =
* Fixed an issue with padding section media queries
* Fix broken dependency on Settings page ahead of new Gutenberg version

= 1.2.1 =
* New: Added ability to import/export Style Kits 🎉.
* Fix: Issue with page style column gap not being overridden with advanced section padding.
* Fix: Incorrect documentation link.
* Improve: Include page background as part of style kits.
* Improve: Add visual notification when a setting is changed on Settings page.
* Improve: Add a visual indicator in notification based on notification timeout duration on Settings page.

= 1.2 =
* New: Introducing [Style Kits](https://analogwp.com/style-kits-for-elementor/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=analogwp). 🎉
* New: Added Elementor Finder shortcuts to quickly navigate to AnalogWP setting/library.
* New: Added a new settings to assign global style kit under Elementor > Settings > Styles > Global Style kit.
* New: Added Contextual menu to Style Kit for quick access in Elementor.
* New: Close Import modal via ESC key on settings screen.
* Improve: Use minified versions of scripts
* Improve: Added better and visible server side error handling.
* Improve: Added notices if importing a template doesn't meet specified requirements.

= 1.1.2 =
* New: Added [Page style settings](https://analogwp.com/testing-global-page-styles-in-analog-templates). 🎉
* New: Added option to enable beta features under AnalogWP > Settings.
* Fix: Remove User First/Last name collection on newsletter signup, as it wasn't communicated.
* Tweak: Added documentation links to settings with more instructions.
* Tweak: Show 'New' badge for 14 days, to match new template publish timing.

= 1.1.1 =
* Fixed a styling issue with Elementor popup modal, caused with Elementor v2.5.5

= 1.1 =
* New: All new designed settings page. 🎉
* New: Added option to Strip Typography under settings tab. [More info](https://docs.analogwp.com/article/544-remove-styling-from-typographic-elements).
* New: Lazy load images on templates page to avoid unnecessary loading, making page load faster.
* New: Keep a log of imported templates for user.
* New: Added an option to opt-in data tracking, optional.
* Fix: Install and import count not being when importing from within Elementor.
* Improve: Switched to custom version for React better upgrades.
* Improve: Added loading indicator for when template preview is loading.
* Improve: Check WordPress version before loading files to avoid errors.
* Fix: Set right data type to _ang_import_type post meta

= 1.0.0 =
* Initial Release

== Upgrade Notice ==

= 1.2.1 =
Added Style kit import/export feature. Fixed an issue with column gaps.

= 1.2 =
Introducing Style Kits for Elementor, added a bunch of QoL improvements.
