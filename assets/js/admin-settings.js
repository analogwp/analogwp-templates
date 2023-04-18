/* global ang_settings_data, wp */
( function( $, data, wp ) {
	$( function() {
		const { __ } = wp.i18n;
		const { addQueryArgs } = wp.url;

		// Process Newsletter.
		function processNewsletter( e ) {
			if ( e.preventDefault ) {
				e.preventDefault();
			}
			const elSubmitBtn = $( '#ang-newsletter-submit' );
			let status = __( 'Subscribing', 'ang' );
			const angEmail = $( '#ang-newsletter-email' ).val();
			elSubmitBtn.text( status );

			$.ajax( {
				url: 'https://analogwp.com/?ang-api=asdf&request=subscribe_newsletter',
				cache: ! 1,
				type: 'POST',
				dataType: 'JSON',
				data: {
					email: angEmail,
				},
				error: function() {
					status = __( 'Failed', 'ang' );
					elSubmitBtn.text( status );
					setTimeout( function() {
						elSubmitBtn.text( __( 'Subscribe up to newsletter', 'ang' ) );
					}, 2000 );
				},
				success: function() {
					status = __( 'Subscribed', 'ang' );
					elSubmitBtn.text( status );
					elSubmitBtn.attr( 'disabled', 'disabled' );
				},
			} );

			return false;
		}
		$( '#ang-newsletter' ).submit( processNewsletter );

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
			elSubmitBtn.val( 'Sending...' );

			$.post(
				'https://analogwp.com/?ang-api=analogwp-templates&request=discount_code',
				{
					email: email,
					first_name: fname,
					last_name: lname,
				}
			).done( function() {
				status = 'Coupon sent!';
				elSubmitBtn.val( status );
				elSubmitBtn.attr( 'disabled', 'disabled' );
			} ).fail( function() {
				status = 'Failed to send, please contact support.';
				elSubmitBtn.val( status );
				setTimeout( function() {
					elSubmitBtn.val( 'Send me the coupon' );
				}, 2000 );
			} );
		}

		$( '#js-ang-request-discount' ).on( 'submit', submitDiscountRequest );

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
