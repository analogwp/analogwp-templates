/* jQuery, angLocalKits */
( function( $, data ) {
	$(
		function() {
			function processGlobalKitChange( e ) {
				if ( e.preventDefault ) {
					e.preventDefault();
				}

				const elSubmitBtn = $( e.target );

				elSubmitBtn.val( data.processingBtnText );
				elSubmitBtn.attr( 'disabled', 'disabled' );

				setTimeout( function() {
					const globalKit = $( '#global_kit' ).find( ':selected' ).val();

					$.ajax(
						{
							url: ajaxurl, // This is a variable that WordPress has already defined for us.
							type: 'POST',
							async: false,
							cache: false,
							data: {
								action: 'ang_global_kit',
								ang_global_kit_nonce: data.nonce,
								global_kit: globalKit,
							},
						}
					).fail(
						function() {
							elSubmitBtn.val( data.initialBtnText );
							elSubmitBtn.removeAttr( 'disabled' );
						}
					).done(
						function() {
							window.location.href = data.redirectURL;
						}
					);
				}, 1 );
			}
			$( '#apply-kit' ).on( 'click', processGlobalKitChange );

			function processImporterToggle( e ) {
				if ( e.preventDefault ) {
					e.preventDefault();
				}

				$( '#analog-import-template-area' ).toggle();
			}

			$( '#import-kit' ).on( 'click', processImporterToggle );
		}
	);
}( jQuery, angLocalKits ) );
