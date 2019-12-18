/* global elementor, ANG_Action */
jQuery( document ).ready( function() {
	function handleFonts( font ) {
		elementor.helpers.enqueueFont( font );
	}

	const pageSettings = elementor.settings.page.model.attributes;
	_.map( pageSettings, function( value, key ) {
		if ( key.startsWith( 'ang_' ) && key.endsWith( '_font_family' ) ) {
			elementor.settings.page.addChangeCallback( key, handleFonts );
		}
	} );

	elementor.on( 'preview:loaded', () => {
		const settings = elementor.settings.page.model.attributes;
		_.map( settings, function( value, key ) {
			if ( key.startsWith( 'ang_' ) && key.endsWith( '_font_family' ) ) {
				if ( value ) {
					elementor.helpers.enqueueFont( value );
				}
			}
		} );
	} );

	function addPageStyleSettings( groups ) {
		const PageStyles = {
			name: 'ang_styles',
			actions: [
				{
					name: 'page_styles',
					title: ANG_Action.translate.pageStyles,
					callback: switchToStyleTab,
				},
			],
		};

		groups.splice( 3, 0, PageStyles );
		groups.join();

		return groups;
	}

	if ( ANG_Action.skPanelsAllowed ) {
		elementor.hooks.addFilter( 'elements/widget/contextMenuGroups', addPageStyleSettings );
		elementor.hooks.addFilter( 'elements/section/contextMenuGroups', addPageStyleSettings );
		elementor.hooks.addFilter( 'elements/column/contextMenuGroups', addPageStyleSettings );
	}

	function switchToStyleTab() {
		if ( elementor.helpers.compareVersions( ElementorConfig.document.version, '2.7.0', '<' ) ) {
			const currentView = elementor.panel.currentView;

			currentView.setPage( 'page_settings' );
			currentView.getCurrentPageView().activateTab( 'style' );
			currentView.getCurrentPageView().activateSection( 'ang_style_settings' );
			currentView.getCurrentPageView().render();
		} else {
			$e.route( 'panel/page-settings/style' );
		}
	}
} );
