(function($) {
	$('.analog-notice').on( 'click', 'button.notice-dismiss', function(){
		$.post( ajaxurl, {
			action: 'analog_set_admin_notice_viewed',
			key: $( this ).closest('.analog-notice').data( 'key' ),
			nonce: AnalogAdmin.nonce || '',
		} );
	} );

	// Library screen has no .wrap h1, so core leaves notices above the app header. Move them into the content area once the app mounts.
	var app = document.getElementById( 'analogwp-templates' );
	var $notices = $( '.analog-notice' );
	if ( app && $notices.length && 'MutationObserver' in window ) {
		var place = function() {
			var content = app.querySelector( '.analogwp-content' );
			if ( ! content ) {
				return false;
			}
			$notices.insertBefore( content );
			return true;
		};

		if ( ! place() ) {
			var observer = new MutationObserver( function() {
				if ( place() ) {
					observer.disconnect();
				}
			} );
			observer.observe( app, { childList: true, subtree: true } );
		}
	}
})(jQuery);
