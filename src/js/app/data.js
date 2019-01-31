const { apiFetch, data } = wp;
const { registerStore, dispatch, select } = data;

const DEFAULT_STATE = {
	importCount: '',
	importedTemplates: [],
};

registerStore( 'ang/settings', {
	reducer( state = DEFAULT_STATE, action ) {
		if ( action.type === 'SET_IMPORT_COUNT' ) {
			return {
				...state,
				importCount: action.importCount,
			};
		}

		if ( action.type === 'SET_IMPORTED_TEMPLATES' ) {
			return {
				...state,
				importedTemplates: action.importedTemplates,
			};
		}

		return state;
	},

	selectors: {
		getImportCount( state ) {
			const { importCount } = state;
			return importCount;
		},
		getImportedTemplates( state ) {
			const { importedTemplates } = state;
			return importedTemplates;
		},
	},

	actions: {
		setImportCount( count ) {
			return {
				type: 'SET_IMPORT_COUNT',
				importCount: count,
			};
		},
		updateImportCount( count ) {
			apiFetch( {
				path: '/wp/v2/settings',
				method: 'POST',
				data: {
					ang_import_count: count || 0,
				},
			} ).then( response => {
				dispatch( 'ang/settings' ).setImportCount( response.ang_import_count );
			} );

			return {
				type: 'SET_IMPORT_COUNT',
				importCount: count,
			};
		},

		setImportedTemplates( templates ) {
			return {
				type: 'SET_IMPORTED_TEMPLATES',
				importedTemplates: templates,
			};
		},
		updateImportedTemplates( templates ) {
			if ( ! templates ) {
				templates = [];
			}

			const prevTemplates = select( 'ang/settings' ).getImportedTemplates();

			if ( Array.isArray( prevTemplates ) ) {
				templates = prevTemplates.push( templates );
			}

			apiFetch( {
				path: '/wp/v2/settings',
				method: 'POST',
				data: {
					ang_imported_templates: templates,
				},
			} ).then( response => {
				dispatch( 'ang/settings' ).setImportedTemplates( response.ang_imported_templates );
			} );

			return {
				type: 'SET_IMPORTED_TEMPLATES',
				importedTemplates: templates,
			};
		},
	},

	resolvers: {
		async getImportCount() {
			const settings = await apiFetch( { path: '/wp/v2/settings' } );
			const { ang_import_count } = settings; // eslint-disable-line
			dispatch( 'ang/settings' ).setImportCount( ang_import_count );
		},
		async getImportedTemplates() {
			const settings = await apiFetch( { path: '/wp/v2/settings' } );
			const { ang_imported_templates } = settings; // eslint-disable-line
			dispatch( 'ang/settings' ).setImportedTemplates( ang_imported_templates );
		},
	},
} );
