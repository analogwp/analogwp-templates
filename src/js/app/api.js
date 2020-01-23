/* global elementorCommon, analog */
const { apiFetch } = wp;
const { __ } = wp.i18n;

export async function markFavorite( id, favorite = true ) {
	return await apiFetch( {
		path: '/agwp/v1/mark_favorite',
		method: 'post',
		data: {
			template_id: id,
			favorite,
		},
	} ).then( response => response );
}

export async function requestTemplateList() {
	return await apiFetch( { path: '/agwp/v1/templates' } ).then(
		response => response
	);
}

export async function requestDirectImport( template, withPage = false, kit = false ) {
	return await apiFetch( {
		path: '/agwp/v1/import/elementor/direct',
		method: 'post',
		data: {
			template,
			site_id: template.site_id || false,
			with_page: withPage,
			kit,
		},
	} ).then( response => {
		return response;
	} );
}

export async function requestStyleKitData( kit ) {
	return await apiFetch( {
		path: '/agwp/v1/import/kit',
		method: 'post',
		data: {
			kit,
		},
	} ).then( response => response );
}

export async function requestBlockContent( block, method ) {
	return await apiFetch( {
		path: '/agwp/v1/blocks/insert',
		method: 'post',
		data: {
			block,
			method,
		},
	} ).then( response => response );
}

/**
 * @deprecated
 * @use requestElementorImport()
 */
export async function requestImportLayout( template ) {
	const editorId =
		'undefined' !== typeof ElementorConfig ? ElementorConfig.post_id : false;

	apiFetch( {
		path: '/agwp/v1/import/elementor',
		method: 'post',
		data: {
			template_id: template.id,
			editor_post_id: editorId,
		},
	} ).then( data => {
		const parsedTemplate = JSON.parse( data );

		if ( typeof elementor !== 'undefined' ) {
			doElementorInsert( parsedTemplate.content );

			window.analogModal.hide();
		}
	} );
}

export async function getSettings() {
	return await apiFetch( { path: '/agwp/v1/get/settings' } ).then(
		response => response
	);
}

export async function requestSettingUpdate( key, value ) {
	if ( ! key ) {
		console.warn('No key/value pair found to update settings.'); // eslint-disable-line
		return false;
	}

	return await apiFetch( {
		path: '/agwp/v1/update/settings',
		method: 'POST',
		data: {
			key,
			value,
		},
	} ).then( response => response );
}

export async function requestLicenseInfo( action = 'check' ) {
	return await apiFetch( {
		path: '/agwp/v1/license',
		method: 'post',
		data: {
			action,
		},
	} ).then( response => {
		return response;
	} );
}

export async function getLicenseStatus() {
	return await apiFetch( { path: '/agwp/v1/license/status' } ).then(
		response => response
	);
}

export async function requestElementorImport( template, kit ) {
	if ( template.version ) {
		if ( parseFloat( AGWP.version ) < parseFloat( template.version ) ) {
			elementorCommon.dialogsManager.createWidget( 'alert', {
				message: 'This template requires an updated version, please update your plugin to latest version.',
			} ).show();
			return;
		}
	}

	const editorId =
				'undefined' !== typeof ElementorConfig ?
					ElementorConfig.post_id :
					false;

	return await apiFetch( {
		path: '/agwp/v1/import/elementor',
		method: 'post',
		data: {
			template_id: template.id,
			editor_post_id: editorId,
			is_pro: template.is_pro,
			site_id: template.site_id || false,
			kit,
		},
	} ).then( data => {
		const parsedTemplate = JSON.parse( data );

		if ( parsedTemplate.errors ) {
			const error = parsedTemplate.errors[ Object.keys( parsedTemplate.errors )[ 0 ] ];

			elementorCommon.dialogsManager.createWidget( 'alert', {
				message: error,
			} ).show();

			return;
		}

		if ( parsedTemplate.tokens ) {
			analog.resetStyles();
			elementor.settings.page.model.set( parsedTemplate.tokens );
		}

		doElementorInsert( parsedTemplate.content );

		window.analogModal.hide();
	} );
}

/**
 * Perform content insertion inside Elementor.
 *
 * @param {object} content Elementor content object with serialized data.
 * @param {string} context Import context, can be 'template' or 'block'.
 *
 * @since 1.5.2
 * @returns void
 */
export function doElementorInsert( content, context = 'template' ) {
	let contextText = __( 'Template', 'ang' );

	if ( context === 'block' ) {
		contextText = __( 'Block', 'ang' );
	}

	if ( typeof $e !== 'undefined' ) {
		const historyId = $e.run( 'document/history/start-log', {
			type: 'add',
			title: `${ __( 'Add Style Kits', 'ang' ) } ${ contextText }`,
		} );

		for ( let i = 0; i < content.length; i++ ) {
			$e.run( 'document/elements/create', {
				container: elementor.getPreviewContainer(),
				model: content[ i ],
			} );
		}

		$e.run( 'document/history/end-log', {
			id: historyId,
		} );
	} else {
		const model = new Backbone.Model( {
			getTitle() {
				return 'Test';
			},
		} );

		elementor.channels.data.trigger( 'template:before:insert', model );

		for ( let i = 0; i < json.data.content.length; i++ ) {
			elementor.getPreviewView().addChildElement( content[ i ] );
		}

		elementor.channels.data.trigger( 'template:after:insert', {} );
	}
}
