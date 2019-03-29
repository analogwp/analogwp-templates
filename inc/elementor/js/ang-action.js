/* global elementor, elementorCommon, ANG_Action */
jQuery( window ).on( 'elementor:init', function() {
	const BaseData = elementor.modules.controls.BaseData;
	const ControlANGAction = BaseData.extend( {
		ui: function() {
			const ui = BaseData.prototype.ui.apply( this, arguments );

			_.extend( ui, {
				actionButton: 'button',
			} );

			return ui;
		},

		events: function() {
			const events = BaseData.prototype.events.apply( this, arguments );

			events[ 'click @ui.actionButton' ] = 'onChangeEvent';

			return events;
		},

		performAction: function( name ) {
			const actions = {
				export_css: 'handleCSSExport',
				reset_css: 'handleCSSReset',
			};

			return this[ actions[ name ] ]();
		},

		onChangeEvent: function( event ) {
			const element = event.currentTarget;
			const action = jQuery( element ).data( 'action' );

			this.performAction( action );
		},

		handleCSSExport: function() {
			/* TODO: Write code for exporting CSS */
			console.log( 'handleCSSExport' );

			// Get the whole Page CSS
			const allStyles = elementor.settings.page.getControlsCSS().elements.$stylesheetElement[ 0 ];

			// Then remove Page's custom CSS.
			const pageCSS = elementor.settings.page.model.get( 'custom_css' );
			const formattedCSS = allStyles.replace( pageCSS, '' );
		},

		handleCSSReset: function() {
			/* TODO: reset all settings, right now its typography only */
			elementorCommon.dialogsManager.createWidget( 'confirm', {
				message: ANG_Action.translate.resetMessage,
				headerMessage: ANG_Action.translate.resetHeader,
				strings: {
					confirm: elementor.translate( 'yes' ),
					cancel: elementor.translate( 'cancel' ),
				},
				defaultOption: 'cancel',
				onConfirm: function() {
					const keys = [
						'ang_heading_1',
						'ang_heading_2',
						'ang_heading_3',
						'ang_heading_4',
						'ang_heading_5',
						'ang_heading_6',
						'ang_default_heading',
						'ang_body',
						'ang_paragraph',
					];

					const patterns = [
						'typography',
						'font_family',
						'font_size',
						'font_size_mobile',
						'font_size_tablet',
						'font_style',
						'font_weight',
						'line_height',
						'line_height_mobile',
						'line_height_tablet',
						'letter_spacing',
						'letter_spacing_mobile',
						'letter_spacing_tablet',
						'text_decoration',
						'text_transform',
					];

					_.each( keys, function( key ) {
						_.each( patterns, function( pattern ) {
							const settingKey = key + '_' + pattern;
							elementor.settings.page.model.setExternalChange( settingKey, false );
						} );
					} );
				},
			} ).show();

			// elementor.panel.currentView.getCurrentPageView().render();
		},

		onReady: function() {},
		saveValue: function() {},
		onBeforeDestroy: function() {},
	} );
	elementor.addControlView( 'ang_action', ControlANGAction );
} );
