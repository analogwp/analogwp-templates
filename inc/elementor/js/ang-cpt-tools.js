jQuery( document ).ready( function( $ ) {
	const ANGTools = function() {
		/**
		 * Hold reusable elements.
		 *
		 * @type {Object}
		 */
		const cache = {};

		function init() {
			cacheElements();

			initStyleKitImport();
		}

		function cacheElements() {
			cache.$body = $( 'body' );
			cache.$importButton = $( '#analog-import-template-trigger' );
			cache.$importArea = $( '#analog-import-template-area' );
		}

		function initStyleKitImport() {
			if ( ! cache.$body.hasClass( 'post-type-ang_tokens' ) ) {
				return;
			}

			$( '#wpbody-content' ).find( '.page-title-action:last' ).after( cache.$importButton );

			cache.$formAnchor = $( 'h1.wp-heading-inline' );
			cache.$formAnchor.after( cache.$importArea );

			cache.$importButton.on( 'click', () => cache.$importArea.toggle() );

			cache.$importArea.find( 'input[type=submit]' ).attr( 'disabled', true );

			cache.$importArea.find( 'input[type=file]' ).on( 'change', function() {
				cache.$importArea.find( 'input[type=submit]' ).removeAttr( 'disabled' );
			} );
		}

		init();
	};

	new ANGTools();
} );
