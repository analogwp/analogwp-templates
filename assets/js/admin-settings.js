/* global ang_settings_data, wp */
( function( $, data, wp ) {
	$( function() {
		const { __ } = wp.i18n;
		const { addQueryArgs } = wp.url;

		// Process Plugin Rollback.
		function processPluginRollback( e ) {
			if ( e.preventDefault ) {
				e.preventDefault();
			}

			const version = $( '#ang_rollback_version_select_option' ).val();
			const rollbackUrl = addQueryArgs( data.rollback_url, { version: version } );

			window.location.href = rollbackUrl;
			return false;
		}
		$( '#ang_rollback_version_button' ).on( 'click', processPluginRollback );

		// Edit prompt
		$( function() {
			let changed = false;

			$( 'input, textarea, select, checkbox' ).change( function() {
				changed = true;
			} );

			$( '.ang-nav-tab-wrapper a' ).click( function() {
				if ( changed ) {
					window.onbeforeunload = function() {
						return data.i18n_nav_warning;
					};
				} else {
					window.onbeforeunload = '';
				}
			} );

			$( '.submit :input' ).click( function() {
				window.onbeforeunload = '';
			} );
		} );

		// Select all/none
		$( '.ang' ).on( 'click', '.select_all', function() {
			$( this )
				.closest( 'td' )
				.find( 'select option' )
				.attr( 'selected', 'selected' );
			$( this )
				.closest( 'td' )
				.find( 'select' )
				.trigger( 'change' );
			return false;
		} );

		$( '.ang' ).on( 'click', '.select_none', function() {
			$( this )
				.closest( 'td' )
				.find( 'select option' )
				.removeAttr( 'selected' );
			$( this )
				.closest( 'td' )
				.find( 'select' )
				.trigger( 'change' );
			return false;
		} );

		const collBtn = document.getElementsByClassName( 'collapsible' );
		let i;

		for ( i = 0; i < collBtn.length; i++ ) {
			collBtn[ i ].addEventListener( 'click', function( e ) {
				e.preventDefault();
				this.classList.toggle( 'active' );
				const content = this.nextElementSibling;
				if ( content.style.maxHeight ) {
					content.style.maxHeight = null;
				} else {
					content.style.maxHeight = content.scrollHeight + 'px';
				}
			} );
			if ( i === 0 ) {
				$( collBtn[ i ] ).trigger( 'click' );
			}
		}

		function submitDiscountRequest( e ) {
			e.preventDefault();

			const email = $( this ).find( 'input[name="email"]' ).val();
			const fname = $( this ).find( 'input[name="first_name"]' ).val();
			const lname = $( this ).find( 'input[name="last_name"]' ).val();

			const elSubmitBtn = $( this ).find( 'input[type=submit]' );
			const messageEl = $( this ).find( '.ang-discount-response span' );
			const defaultLabel = elSubmitBtn.data( 'default-label' );
			messageEl.text( '' );
			elSubmitBtn.val( 'Sending...' );

			$.post(
				'https://analogwp.com/?ang-api=pro_discount_code',
				{
					email: email,
					first_name: JSON.stringify( fname ),
					last_name: JSON.stringify( lname ),
					slug: 'style-kits',
				}
			).done( function( res ) {
				messageEl.text( res?.message );
				elSubmitBtn.val( defaultLabel );
				elSubmitBtn.attr( 'disabled', 'disabled' );
			} ).fail( function(res) {
				messageEl.text( 'Failed to send, please contact support.' );
				elSubmitBtn.attr( 'disabled', 'disabled' );
				setTimeout( function() {
					messageEl.text( 'Send me the coupon' );
					elSubmitBtn.removeAttr( 'disabled' );
				}, 2000 );
			} );
		}

		$( '#js-ang-request-discount' ).on( 'submit', submitDiscountRequest );

		// Handle promo hide functionality.
		$( '.ang-hide-promo' ).on( 'click', function( e ) {
			e.preventDefault();

			const $link = $( this );
			const promoId = $link.data( 'promo-id' );
			const $promo = $link.closest( '.promo' );

			$.ajax( {
				url: data.ajax_url,
				type: 'POST',
				data: {
					action: 'ang_hide_promo',
					nonce: data.hide_promo_nonce,
					promo_id: promoId,
				},
				success: function( response ) {
					if ( response.success ) {
						$promo.fadeOut( 300, function() {
							$promo.remove();
						} );
					}
				},
			} );
		} );

		function processKitDownload() {
			if ( ! $( '.titledesc + #starter-kits-message' ).length ) {
				const el = $( '.titledesc' ),
					content = '<div id="starter-kits-message" class="updated inline"><p>' + data.sitekit_importer_notice + '&nbsp;<a href="' + data.sitekit_importer_url + '">' + data.sitekit_importer_url_text + '</a></p></div>';

				el.after( content );
			}
		}
		$( '.kit-btns .kit-download-btn' ).on( 'click', processKitDownload );
	} );
}( jQuery, ang_settings_data, wp ) );
