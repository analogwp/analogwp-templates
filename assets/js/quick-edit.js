( function( $ ) {
	// we create a copy of the WP inline edit post function
	const $wp_inline_edit = inlineEditPost.edit;

	// and then we overwrite the function with our own code
	inlineEditPost.edit = function( id ) {
		// "call" the original WP edit function
		// we don't want to leave WordPress hanging
		$wp_inline_edit.apply( this, arguments );

		// now we take care of our business

		// get the post ID
		let $post_id = 0;
		if ( typeof ( id ) === 'object' ) {
			$post_id = parseInt( this.getId( id ) );
		}

		if ( $post_id > 0 ) {
			// define the edit row
			const $edit_row = $( '#edit-' + $post_id );
			const $post_row = $( '#post-' + $post_id );

			// get the data
			let $style_kit = $( '.column-ang_stylekit', $post_row ).text();
			const globalKit = parseInt( angQuickEdit.globalKit );

			// Hide Style Kit dropdown for posts without Stylekit.
			if ( $style_kit === '' && ! $post_row.hasClass( 'type-elementor_library' ) ) {
				$( '#ang-stylekit-fieldset' ).hide();
			}

			if ( $style_kit !== '' ) {
				if ( ! ( parseInt( $style_kit ) in angQuickEdit.kits ) ) {
					$style_kit = globalKit;
				}

				$( 'select[name=ang_stylekit]' ).val( $style_kit );
			}
		}
	};

	$( document ).on( 'click', '#bulk_edit', function() {
		// define the bulk edit row
		const $bulk_row = $( '#bulk-edit' );

		// get the selected post ids that are being edited
		const $post_ids = new Array();
		$bulk_row.find( '#bulk-titles-list' ).children().each( function() {
			$post_ids.push( $( this.firstChild ).attr( 'id' ).replace( /^(_)/i, '' ) );
		} );

		// get the data
		const $style_kit = $bulk_row.find( 'select[name="ang_stylekit"]' ).val();

		// get the nonce
		const $ang_sk_update_nonce = $( '#ang_sk_update_nonce' ).val();

		// save the data
		$.ajax( {
			url: ajaxurl, // this is a variable that WordPress has already defined for us
			type: 'POST',
			async: false,
			cache: false,
			data: {
				action: 'save_bulk_edit_stylekit',
				post_ids: $post_ids,
				kit_id: $style_kit,
				ang_sk_update_nonce: $ang_sk_update_nonce,
			},
		} );
	} );

	$( document ).ready( function() {
		if ( '?post_type=elementor_library' !== window.location.search ) {
			return;
		}

		$( document ).on( 'click', '.editinline', function() {
			const data = this.parentElement.parentElement.parentElement.parentElement;
			const $type = $( '.column-elementor_library_type', data ).text();
			if ( 'Section' === $type ) {
				let id = data.id;
				id = id.split( '-' );
				$( '#edit-' + id[ 1 ] + ' #ang-stylekit-fieldset' ).hide();
			}
		} );
	} );
}( jQuery ) );
