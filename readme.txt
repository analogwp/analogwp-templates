=== Style Kits for Elementor ===
Contributors: analogwp, mauryaratan
Requires at least: 5.0
Requires PHP: 5.4
Tested up to: 5.2.2
Stable tag: 1.3.1
Tags: elementor, landing page, design, website builder, templates
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Style Kits adds intuitive styling controls in the Elementor editor that power-up your design workflow with unparalleled flexibility.

== Description ==

[Style Kits for Elementor](https://analogwp.com/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=analogwp) (formerly Analog Templates for Elementor) adds a number of intuitive styling controls in the Elementor editor that allow you to apply styles globally or per page.

Stop manually adding / copying / pasting styles across layouts and experience macro control of the most essential aspects of your layout system:

- Body text
- Headings
- Headings and text sizes
- Column Gaps
- Buttons
- Colors and more are coming soon

#### Typography

Edit the styles for Body and Headings and see the effect taking place in your design immediately, right from within the Editor.

#### Column Gap controls

Achieve site-wide spacing consistency through the column gap controls.

Apply the native column gaps on your outer and inner sections and manage the spacing of your layout system from a single place.

#### Save your page Styles as a Style Kit and apply on any page, or globally

All your custom page styles can be saved as a Stylekit and then apply it on any page.

You can also make a Global Stylekit, and it will apply on your entire site.

#### Button Styles

Now you have a single source of control for your different button sizes. Set the button styles on your Style kit and enjoy consistent, site-wide button control.

#### A collection of fine-tuned, Style-kit powered templates. For free.

Trigger the template gallery popup, preview and import any of the templates in our collection.

In most of the templates, styles are managed from the Style Kit panel, so you can experience design-macro control right away.

#### The perfect addition to Elementor Hello Theme

Hello Elementor is a great, lightweight theme but it lacks basic typography controls. Now with Style Kits, you can set the rules for your Typography save them as a global Style Kit and enjoy site-wide typographic control.

**Find us**:
- [Visit the AnalogWP Website](https://analogwp.com/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=analogwp)
- [Follow on Twitter](https://twitter.com/AnalogWP/)
- [Like us on Facebook](https://www.facebook.com/analogwp)

== Installation ==

This section describes how to install the plugin and get it working. e.g.

1. Upload the plugin files to the `/wp-content/plugins/analogwp-templates` directory, or install the plugin through the WordPress plugins screen directly..
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Head to 'Style Kits' page from WordPress sidebar menu.

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

1. Style Kits Settings page.
2. Import screen: import to Elementor library or create a new page.
3. Access Style Kits templates directly under Elementor.

== Changelog ==

= 1.3.1 =
* New: Added Color Controls in page settings
* New: Added Plugin setting to sync Color controls output colors with Elementor color picker
* New: Added Control for spacing between widgets under Style Kits > Column Gaps
* New: Added "Outer Section Padding" control to tweak Section padding
* New: Added plugin action link to settings page on plugins screen
* Improve: Reorganized setting sections
* Improve: Renamed 'Page Styles' to 'Style Kit' in Elementor contextual menu
* Fix: Issue with plugin page app crash on switching tabs while a preview is open
* Fix: Fatal error with `use function` usage in PHP 5.5.x
* Fix: Issue with Style kit post state showing empty title

= 1.3 =
* New: Added Button Controls under Page Styles 🎉
* New: Plugin rebranded as "Style Kits for Elementor"
* Fix: Text/Heading size controls not being persistent on style kit change
* Fix: Column gap individual controls not taking place
* Fix: Style Kit update modal displaying on page where it was updated from
* Fix: Dependency error with Gutenberg 6.2
* Improve: Added a modal window to indicate if page has Global Style Kit

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
* New: Added Elementor Finder shortcuts to quickly navigate to Style Kits settings/library.
* New: Added a new settings to assign global style kit under Elementor > Settings > Styles > Global Style kit.
* New: Added Contextual menu to Style Kit for quick access in Elementor.
* New: Close Import modal via ESC key on settings screen.
* Improve: Use minified versions of scripts
* Improve: Added better and visible server side error handling.
* Improve: Added notices if importing a template doesn't meet specified requirements.

= 1.1.2 =
* New: Added [Page style settings](https://analogwp.com/testing-global-page-styles-in-analog-templates). 🎉
* New: Added option to enable beta features under Style Kits > Settings.
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
