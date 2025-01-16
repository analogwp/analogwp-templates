=== Style Kits - Advanced Theme Styles for Elementor ===
Contributors: analogwp, lushkant, johnpixle, mauryaratan
Requires at least: 6.0
Requires PHP: 7.4
Tested up to: 6.7.1
Stable tag: 2.3.2
Tags: elementor, patterns, global styles, elementor addons, design system
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Power-up your Elementor workflow with global theme style presets, container-based patterns, and more global design controls.

== Description ==

[Style Kits](https://analogwp.com/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=analogwp) extends the flexibility of Elementor theme styles with more global design controls, and introduces the most advanced container-based pattern library and theme style presets that will jumpstart your design process in Elementor.

Style Kits creates the foundation for a design framework that will help you create better, more consistent websites with Elementor.

### Library of Theme Style Presets

Get an unfair design advantage by importing read-made theme style presets for pre-configured  typography, colors, spacing and more. Give your Elementor website a completely different look in just a few clicks.

- Use them as a starting point, customise them or create your own.
- Outer Section Padding
- Override your global theme styles on specific pages, with another theme style preset
- Import and export style presets across sites.

### The most advanced Elementor Pattern library

A one-of-a-kind pattern library for Elementor, powered by flexbox containers and native widgets. Skyrocket your layout-building process with a collection of mix-and-match patterns that automatically adapt to your Global theme styles.

- 100% built with Elementor flexbox containers
- Consistent, customisable styles across all patterns, that automatically adapt to the theme styles of your site
- Wide collection of patterns to facilitate all your layout-building needs

### More tools for global design control

Manage container spacing globally, adjust button styles per size, and many more tools that will help you scale-up your layouts with consistency.

Style Kits for Elementor adds a set of extra UI controls to Theme Styles editor, for the most important aspects of your Elementor design system.

- Global Style Kit fonts
- Global Style Kit colors
- Containers Padding
- Shadows
- Background classes
- Button Styles per size
- Outer Section Padding
- Column Gaps
- Text Sizes

### Boost your Elementor design workflow with Style Kits Pro

- **Unlimited access** to all container-based patterns
- **Unlimited access** to all theme style presets
- **Unlimited access** to all global design controls
- **User Role** management to hide Style Kits from your clients
- **Cleanup tools** to help you clean layouts from inline styles
- Many more PRO features and tools to improve your workflow

**Find us**:
- [Visit the AnalogWP Website](https://analogwp.com/?utm_medium=wp.org&utm_source=wordpressorg&utm_campaign=readme&utm_content=analogwp)
- [Follow on Twitter](https://twitter.com/AnalogWP/)
- [Like us on Facebook](https://www.facebook.com/analogwp)
- [Documentation](https://analogwp.com/docs/)

== Installation ==

This section describes how to install the plugin and get it working. e.g.

1. Upload the plugin files to the `/wp-content/plugins/analogwp-templates` directory, or install the plugin through the WordPress plugins screen directly..
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Head to 'Style Kits' page from WordPress sidebar menu.

== Frequently Asked Questions ==

= Do I need to have containers activated in my Elementor site in order to use Style Kits? =
Ideally, yes. To take advantage of the new Style Kits pattern library you will need to activate the Elementor experiment at your website. If you still work with sections and columns though, you will still get access to the old library of Template Kits.

= Do I need Elementor PRO in order to use Style Kits? =
No, Style Kits will still work with the FREE version of Elementor. However, any patterns that include Elementor PRO widgets (Contact form, Call to action etc) will not be imported into your pages. The rest of the Style Kits functionality will work with the free version of Elementor.

= What is a Style Kit? =
In simple words, a Style Kit is a collection of your Global theme styles. Includes Typography, Colors, spacing and more. In Style Kits for Elementor you get access to a number of such theme style presets that you can import and apply on your website.

= Will the patterns work with my existing theme? =
Yes, if you have Elementor installed and activated. In general, patterns will inherit the styles of your existing theme (especially for typography). However it is recommended that you use Style Kits on a fresh site, and apply any of the included theme style presets as a starting point for your Global styles.
Using Elementor Hello theme is also recommended.

= Do I get support if I need help? =
We offer high-level support for all Style Kits users. Reach out to https://analogwp.com/support/ and submit a support request. We’ll get back to you asap.

== Screenshots ==

1. The Style Kits pattern library
2. A collection of ready-to-import theme style presets
3. Tools for better global design control
4. Style Kits Global Fonts
5. Style Kits Global Colors
6. Container spacing presets

== Changelog ==

= 2.3.2 - January 16, 2024 =
* Fix: Inline kit data at widgets not including page kit data
* Fix: Add body class when global kit class is missing, mostly on pages with page kit
* Fix: Avoid kit shadow presets getter from running on frontend, as per support ticket props to @baraklevy
* Improvement: Compatibility with Elementor v3.26.5 and Elementor Pro v3.26.3
* Improvement: Updated translation files
* Improvement: Other minor code changes

= 2.3.1 - December 07, 2024 =
* Fix: Onboarding not working in some cases
* Fix: Library showing Non-container based templates when Container feature is set to default
* Improvement: Updated translation files
* Improvement: Other minor code changes

= 2.3.0 - December 06, 2024 =
* New: Button Sizes/Styles back to Active features
* New: Heading Sizes/Styles back to Active features
* Improvement: Reset and enable size control for Button and Heading widgets
* Improvement: Set minimum PHP required version back to v7.4
* Improvement: Updated translation files
* Improvement: Other minor code changes

= 2.2.3 - November 27, 2024 =
* Improvement: Other minor code changes

= 2.2.2 - November 27, 2024 =
* Fix: New license switch causing Pro template imports to fail
* Fix: Old templates library not showing templates on page load
* Improvement: Other minor code changes

= 2.2.1 - November 25, 2024 =
* New: Freemius Addons page at admin
* Fix: Manual container padding overrides not working [#673](https://github.com/analogwp/analogwp-templates/issues/673)
* Improvement: Enables box shadow presets in nested containers [#674](https://github.com/analogwp/analogwp-templates/issues/674)
* Improvement: Remove old migrations till v1.5 and DOM optimization class target for El v3.19 and down
* Improvement: Other minor code changes
* Improvement: Compatibility with Elementor v3.25.10 and Elementor Pro v3.25.4

= 2.2.0 - Novemeber 19, 2024 =
* New: Freemius integration for licensing and payments
* New: Adds notices for Freemius migration conditionally to existing EDD based SK Pro users
* Improvements: Remove unused code
* Improvements: Other major code changes
* Improvements: Compatibility with WordPress v6.7
* Improvements: Compatibility with Elementor v3.25.7 and Elementor Pro v3.25.3

= 2.1.0 - August 22, 2024 =
* Fix: Fixed an issue where in some cases the patterns would not load in the library
* Fix: React throwing `regenerator-runtime` issue at pattern library
* Fix: Fixed an issue with google fonts causing a fatal error
* Fix: Fix `count` function throwing an error on null values
* Improvements: Changed the way that pattern thumbnails are served
* Improvements: Includes a migration upgrader to sync library
* Improvements: Compatibility with latest elementor version
* Improvements: Compatibility with WordPress v6.6.1
* Improvements: Compatibility with Elementor v3.23.4 and Elementor Pro v3.23.3
* Improvements: Updated translation files
* Improvements: Other minor code changes

= 2.0.9 - April 19, 2024 =
* New: Deprecate Button Sizes Panel in support of new changes from Elementor v3.20.0
* New: Move Button sizes at Style Kits Panel to legacy section
* New: Show "Edit in stylekits" button prior to Elementor v3.20.0
* Fix: Legacy Column gaps not workin due to always active DOM optimization (props @marian-kadanka)
* Improvements: Improve SK Container experiment -> feature dialogues
* Improvements: Compatibility with WordPress v6.5.2
* Improvements: Compatibility with Elementor v3.21.1 and Elementor Pro v3.21.0
* Improvements: Updated translation files
* Improvements: Other minor code changes

= 2.0.8 - December 08, 2023 =
* New: Bumped minimum WordPress and Elementor required versions to v6.0 and v3.10.0 respectively
* Fix: Fix typography controls deprecated schemes
* Fix: Minimum system requirements checkers
* Improvements: Compatibility with Elementor v3.18.2 and Elementor Pro v3.18.1
* Improvements: Other minor code changes

= 2.0.7 - December 04, 2023 =
* Fix: Cannot add a control outside of a section at Typography Section in Kit Settings
* Improvements: Compatibility with Elementor v3.18.0 and Elementor Pro v3.18.0

= 2.0.6 - September 16, 2023 =
* New: Added support for spacing presets and bg classes in nested containers
* Fix: Inline padding values being respected in Containers
* Fix: Nested containers taking spacing styles from parent
* Improvements: Compatibility with Elementor v3.16.3 and Elementor Pro v3.16.1
* Improvements: Compatibility with WordPress v6.3.1
* Improvements: Updated translation files
* Improvements: Other minor code changes

= 2.0.5 - July 10, 2023 =
* New: Added new method to support Kit Import/Export via Local Kits library
* Fix: Warnings at editor for missing `active_breakpoints` data in kits
* Fix: Warning at Kit trashing action for string interpolation
* Improvements: Compatibility with Elementor v3.14.1 and Elementor Pro v3.14.1
* Improvements: Compatibility with WordPress v6.2.2
* Improvements: Updated translation files
* Improvements: Other minor code changes

= 2.0.4 - May 18, 2023 =
* Fix: Missing translation strings
* Improvements: Remove unused utility functions
* Improvements: Compatibility with Elementor v3.13.2 and Elementor Pro v3.13.1
* Improvements: Compatibility with WordPress v6.2.1
* Improvements: Updated translation files
* Improvements: Other minor code changes

= 2.0.3 - April 19, 2023 =
* New: Starter Kit downloads at the settings page
* Fix: Global kit issues when it is not in sync with the active kit
* Fix: Dialog widgets appearing unstyled in the editor
* Improvements: Toast styles for kit changes in the editor
* Improvements: Compatibility with Elementor Pro v3.12.2
* Improvements: Updated translation files

= 2.0.2 - April 05, 2023 =
* New: Preselect pattern category based on theme builder template type
* New: Updated size units for controls with newly supported units such as rem, vw, custom
* New: Bumped minimum WordPress and PHP required versions to v5.9 and v7.0 respectively
* Improvements: Updated client-side React dependency to support v18 with Backwards compatibility
* Improvements: Compatibility with WordPress v6.2.0
* Improvements: Compatibility with Elementor v3.12.1 & Elementor Pro v3.12.1
* Improvements: Updated translation files

= 2.0.1 - Feb 14, 2023 =
* Fix: Issue with undefined documents in some rare cases
* Improvements: Compatibility with Elementor v3.11.0 & Elementor Pro v3.11.0
* Improvements: Remove unused code

= 2.0.0 - Feb 04, 2023 =
* New: Kit trashing and confirmation page from Local Kits screen
* New: Settings toggle for Legacy features
* New: Rearranged the Style Kits panel for legacy features
* New: Add pattern plugin requirements warning and hide Woo patterns if unfulfilled
* Improvements: Updated Settings sidebar form and Promo page
* Improvements: Code cleanup and improve uninstall process
* Improvements: PHP 8.1 Compatibility
* Improvements: Compatibility with Elementor v3.10.2 & Elementor Pro v3.10.3
* Improvements: Synchronisation of version numbers for Style Kits free and Pro (2.0)
* Fix: Deprecated dynamic tags hook
* Fix: Deprecated params at finder shortcuts
* Fix: Incorrect feature links for docs
* Fix: Updated translation files

= 1.9.8 - November 30, 2022 =
* Fix: Failed redirects after assigning a global kit at Local Kits page

= 1.9.7 - November 29, 2022 =
* Fix: Library sidebar is not scrollable
* Fix: Accent background appears in dropdown even if PRO is not active
* Fix: Onboarding wizard shows all items completed regardless of toggled actions

= 1.9.6 - November 25, 2022 =
* New: Onboarding wizard for simpler setup
* New: Translation support for JS files
* Improvements: Revamped Kit "Save as" feature to "Clone Kit"
* Improvements: Remove feature image from Kit imports
* Improvements: Compatibility with Elementor v3.8.1 & Elementor Pro v3.8.2
* Improvements: Compatible up to WordPress v6.1.1

= 1.9.5 - November 04, 2022 =
* New: Container-based Pattern library experiment
* New: Library UI v2
* New: Style Kit Global Colors and Fonts now in stable
* New: Default labels and values for Global Colors, Fonts, Container Spacing and Shadows
* New: Add SVG support and toggle at settings
* Improvements: Define Global Style Kit on the new Local Style Kits page
* Improvements: Migrate existing Global Colors, Fonts, and Container Spacing with new defaults and format
* Improvements: Remove outdated onboarding screen
* Improvements: Improved loading sequence when switching Style Kits on pages
* Improvements: Add Box shadows control to Image widget borders
* Improvements: Unused code cleanup
* Improvements: PHP 8 Compatibility
* Improvements: Compatibility with Elementor v3.8.0 & Elementor Pro v3.8.0
* Improvements: Compatible up to WordPress v6.1
* Fix: Quick edit and bulk quick edit for applying Style Kits
* Fix: Plugin throwing warning at activation when Elementor is not installed/active
* Fix: Update Box shadows CSS classes in support with Elementor v3.8.0
* Fix: Rating notice appearing on new installs

= 1.9.4 - August 31, 2022 =
* Fix: Potential fatal error at custom kit usage checker (props to Mark Westguard)
* Fix: Promotions teaser template warnings (props to Mayur Thakkkar)
* Improvements: Compatibility with Elementor v3.7.3 & Elementor Pro v3.7.4
* Improvements: Compatible up to WordPress v6.0.2

= 1.9.3 - August 26, 2022 =
* New: Additional controls for Global fonts and colors in a tabbed layout
* New: Additional Container Spacing controls in a tabbed layout
* New: Global Shadow presets now in Style Kits free
* New: Show a redirect hint to Container Spacing at Elementor Layout site settings
* Improvements: Update Container Spacing presets to work with the new Default Elementor padding
* Improvements: Conditionally only load Container Spacing and Shadow presets with values at widgets
* Improvements: Migrates old Shadows and Container Spacing controls to the new multi-tab controls
* Improvements: Compatibility with Elementor v3.7.2 & Elementor Pro v3.7.3
* Improvements: Compatible up to WordPress v6.0.1
* Fix: Background classes presets not working
* Fix: `elementorDevTools` notice at console due to deprecated $control_id param at Control registrations
* Fix: Section redirect script at Site settings
* Fix: Kit reset action not working

= 1.9.2 - June 24, 2022 =
* New: Added Kit sizes helper links at Heading & Button widgets
* New: Added default values for Container spacing presets
* Improvements: Show Style Kit colors & fonts links at Contextual popup with respect to active experiments
* Improvements: Reset actions now directly take you to their respected sections
* Improvements: Remove section titles & revise labels from Style Kit fonts
* Improvements: Remove section titles from Style Kit colors
* Fix: Style Kit font presets reset button not working
* Fix: Contextual links now directly take you to their respected sections

= 1.9.1 - June 18, 2022 =
* New: Add "Edit Style Kit Fonts" link to the context menu
* New: Add "Edit Style Kit Colors" link to the context menu
* New: Added Container Background Classes experiment to stable
* Experiments: Added Style Kits Global Fonts experiment
* Experiments: Added Style Kits Global Colors experiment
* Improvements: Rename Style Kit's last section to "Manage Style Kit"
* Improvements: Compatibility with Elementor v3.6.6 & Elementor Pro v3.7.2
* Improvements: Compatible up to WordPress v6.0
* Fix: Kit Settings section redirect not working

= 1.9.0 - May 11, 2022 =
* New: Added Experiments tab at Style Kits Settings
* Experiments: Added Flexbox Container Padding control presets to tweak container padding
* Experiments: Added Flexbox Container Style Kits Background Classes preset to tweak container styles
* Fix: Elementor kit imports not working
* Improvements: Compatibility with Elementor v3.6.5 and Elementor Pro v3.7.0

= 1.8.5 - April 13, 2022 =
* Fix: Editor not loading due to a deprecated class (h/t Ryan HS#5914 )
* Fix: Improve CSS export handling using the newer Clipboard API with backwards compat
* Improvements: Compatibility with Elementor v3.6.3 & Elementor Pro v3.6.5
* Improvements: Compatible upto WordPress v5.9.3

= 1.8.4 - Feb 08, 2022 =
* Fix: Template imports not working
* Improvements: Compatibility with Elementor Pro v3.6+

= 1.8.3 - Jan 24, 2022 =
* New: Compatible up to WordPress v5.9
* New: Elementor required version is now v3.5+
* Fix: Column gaps not working with fresh installs and Optimized DOM feature
* Fix: Required Elementor version notice update link not working
* Improvements: Compatibility with Elementor v3.5+ & Elementor Pro v3.5.2
* Improvements: Remove unused Google fonts class

= 1.8.2 - June 14, 2021 =
* New: Compatible up to WordPress v5.7.2
* Fix: Kit re-saving control not working as expected
* Improvements: Fix fatal error at search/archive pages for Elementor based pages (h/t Anthony HS#5639)
* Improvements: Compatibility with Elementor v3.2+ & Elementor Pro v3.3

= 1.8.1 - Feb 12, 2021 =
* Fix: Issue with nonce verification when quick editing a post/page

= 1.8.0 - Jan 30, 2021 =
* New: Self updating Google fonts library, updated every 24 hours
* Fix: Horizontal line glitch in template library at Elementor editor popup
* Fix: Fix Elementor menus not showing up at site settings
* Improvements: Added Elementor 3.1 compatibility tags
* Improvements: Detect DOM optimization key in Elementor 3.1 and prior version to work accordingly
