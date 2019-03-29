/* global elementor */
console.log( 'file loaded' );
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
				export_css: this.handleCSSExport,
				reset_css: this.handleCSSReset,
			};

			return actions[ name ]();
		},

		onChangeEvent: function( event ) {
			const element = event.currentTarget;
			const action = jQuery( element ).data( 'action' );

			this.performAction( action );
		},

		handleCSSExport: function() {
			/* TODO: Write code for exporting CSS */
			console.log( 'handleCSSExport' );
		},

		handleCSSReset: function() {
			/* TODO: Write code for exporting CSS */
			console.log( 'handleCSSReset' );

			// elementor.settings.page.model.setExternalChange(key, value);
			// elementor.panel.currentView.getCurrentPageView().render();
		},

		onReady: function() {},
		saveValue: function() {},
		onBeforeDestroy: function() {},
	} );
	elementor.addControlView( 'ang_action', ControlANGAction );
} );
