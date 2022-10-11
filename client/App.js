import styled from 'styled-components';
import AnalogContext from './AnalogContext';
import { getSettings, markFavorite, requestTemplateList } from './api';
import ThemeContext, { Theme } from './contexts/ThemeContext';
import Header from './Header';
import Notifications from './Notifications';
import { getTime, getPageComponents, hasProTemplates } from './utils';
const { apiFetch } = wp;

const Analog = styled.div`
	margin: 0 0 0 -20px;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	font-size: 13px;
	position: relative;

	--ang-accent: rgb(0, 112, 167);

	.ang-notices {
		position: fixed;
		right: 0;
		top: 75px;
		padding: 8px;
		z-index: 100000;
	}

	.components-form-toggle.is-checked .components-form-toggle__track {
		background-color: var(--ang-primary);
	}

	.components-form-toggle .components-form-toggle__input:focus + .components-form-toggle__track {
		box-shadow: 0 0 0 2px #fff, 0 0 0 4px var(--ang-primary);
	}

	.ang-button {
		font-size: 14.22px;
		font-weight: bold;
		text-align: center;
		border-radius: 4px;
		color: #fff;
		background: ${ props => props.theme.accent };
		padding: 12px 24px;
		display: inline-flex;
		justify-content: center;
		align-items: center;
		border: none;
		outline: 0;
		cursor: pointer;
		transition: all 200ms ease-in;
		min-width: 100px;
		text-decoration: none;
		box-sizing: border-box;
		height: auto;
		a {
			color: #fff !important;
			text-decoration: none;
		}

		&.secondary {
			background: #000222;
			border-radius: 0;
			text-transform: uppercase;
			font-size: 12px;
		}

		&:disabled {
			cursor: not-allowed;
			opacity: 0.4;
			filter: grayscale(1);
		}
	}

	a {
		outline: 0;
		box-shadow: none;
	}

	.button-plain {
		padding: 0;
		margin: 0;
		border: none;
		border-radius: 0;
		box-shadow: none;
		cursor: pointer;
		-webkit-appearance: none;
		appearance: none;
		outline: 0;
		background: transparent;
		font-weight: bold;
		color: #060606;
		font-size: 14.22px;
	}


	button {
		font-family: inherit;
	}

	.button-accent {
		background: var(--ang-accent);
		border: 0;
		border-radius: 0;
		text-transform: uppercase;
		font-size: 12px;
		letter-spacing: 1px;
		font-weight: bold;
		box-shadow: none;
		text-shadow: none;
		color: #fff;
		padding: 15px 24px;
		height: auto;
		&:focus,
		&:active {
			border: none !important;
			background: rgb(255, 120, 101, 0.9) !important;
			box-shadow: none !important;
			color: #fff !important;
		}
		&:hover {
			color: #fff;
			background: rgb(255, 120, 101, 0.9);
			border: none;
		}
	}

	.components-external-link {
		font-weight: 500;
	}

	.ang-link {
		color: var(--ang-accent);
		text-transform: uppercase;
		border-bottom: 1px solid var(--ang-accent);
		font-size: 12.64px;
		letter-spacing: 1px;
		text-decoration: none;
		font-weight: bold;
	}

	.preview-active .templates-list {
		visibility: hidden;
	}
`;

class App extends React.Component {
	constructor() {
		super( ...arguments );

		const initialContainerTab = ! AGWP.is_settings_page ? 'blocks' : 'styleKits';

		this.state = {
			templates: [],
			kits: [],
			styleKits: [],
			blocks: [],
			count: null,
			isOpen: false, // Determines whether modal to preview template is open or not.
			syncing: false,
			favorites: AGWP.favorites,
			blockFavorites: AGWP.blockFavorites,
			showing_favorites: false,
			archive: [], // holds template archive temporarily for filter/favorites, includes all templates, never set on it.
			blockArchive: [], // same as archive above just for blocks.
			showFree: true,
			showPro: true,
			group: true,
			activeKit: false,
			installedKits: AGWP.installed_kits || {},
			tab: AGWP.isContainer ? initialContainerTab : 'blocks',
			blocksTab: AGWP.isContainer ? 'all-patterns' : 'all-blocks',
			hasPro: false,
			settings: {
				ang_sync_colors: true,
			},
			blocksSearchInput: '',
			itemFilteredWithSearchTerm: function( foundItems, searchInput ) {
				let searchTags = [];
				return foundItems.filter( single => {
					if ( single.tags ) {
						searchTags = single.tags.filter( tag => {
							return tag.toLowerCase().includes( searchInput );
						} );
					}
					return (
						single.title.toLowerCase().includes( searchInput ) || searchTags.length >= 1
					);
				} );
			},
		};

		this.refreshAPI = this.refreshAPI.bind( this );
		this.toggleFavorites = this.toggleFavorites.bind( this );
		this.handleSearch = this.handleSearch.bind( this );
		this.handleSort = this.handleSort.bind( this );
		this.handleFilter = this.handleFilter.bind( this );
		this.switchTabs = this.switchTabs.bind( this );
	}

	switchTabs() {
		const hash = location.hash;
		const validHashes = [ '#templates', '#styleKits', '#blocks' ];

		if ( validHashes.indexOf( hash ) > -1 && AGWP.is_settings_page ) {
			this.setState( {
				tab: hash.substr( 1 ),
				templates: this.state.archive,
				blocks: this.state.blockArchive,
				showing_favorites: false,
			} );
		}
	}

	async componentDidMount() {
		window.addEventListener( 'hashchange', this.switchTabs, false );
		window.addEventListener( 'DOMContentLoaded', this.switchTabs, false );

		if ( window.localStorage.getItem( 'analog::show-free' ) === 'false' ) {
			this.setState( {
				showFree: false,
			} );
		}

		if ( window.localStorage.getItem( 'analog::show-pro' ) === 'false' ) {
			this.setState( {
				showPro: false,
			} );
		}

		if ( window.localStorage.getItem( 'analog::group-kit' ) === 'false' ) {
			this.setState( {
				group: false,
			} );
		}

		this.setState( {
			syncing: true,
		} );

		const templates = await requestTemplateList();
		const library = templates.library;

		this.setState( {
			templates: library.templates,
			kits: library.template_kits,
			archive: library.templates,
			blockArchive: library.blocks,
			count: library.templates.length,
			timestamp: templates.timestamp,
			hasPro: hasProTemplates( library.templates ),
			styleKits: library.stylekits,
			blocks: library.blocks,
			syncing: false,
		} );

		this.handleSort( 'latest', 'templates' );
		this.handleSort( 'latest', 'blocks' );

		// Listen for Elementor modal close, so we can reset some states.
		document.addEventListener( 'modal-close', () => {
			this.setState( {
				isOpen: false,
				showing_favorites: false,
				templates: this.state.archive,
			} );
		} );

		getSettings().then( settings => this.setState( { settings } ) );
	}

	handleFilter( type, library = 'templates' ) {
		const templates = [ ...this.state.archive ];
		const blocks = [ ...this.state.blockArchive ];

		if ( 'blocks' !== library ) {
			if ( type === 'all' ) {
				this.setState( { templates: this.state.archive } );
				return;
			}

			const filtered = templates.filter( template => template.type === type );
			this.setState( { templates: filtered } );
		} else {
			if ( type === 'all' ) {
				this.setState( { blocks: this.state.blockArchive } );
				return;
			}

			const filtered = blocks.filter( block => block.tags[ 0 ] === type );
			this.setState( { blocks: filtered } );
		}
	}

	handleSort( value, library = 'templates' ) {
		this.setState( {
			showing_favorites: false,
		} );

		const sortData = this.state[ library ];

		if ( 'popular' === value ) {
			const sorted = sortData.sort( ( a, b ) => {
				if ( 'popularityIndex' in a ) {
					if ( parseInt( a.popularityIndex ) < parseInt( b.popularityIndex ) ) {
						return 1;
					}
					if ( parseInt( a.popularityIndex ) > parseInt( b.popularityIndex ) ) {
						return -1;
					}
				}
				return 0;
			} );

			this.setState( { [ library ]: sorted } );
		}

		if ( 'latest' === value ) {
			const sorted = sortData.sort( ( a, b ) => {
				if ( 'published' in a ) {
					if ( parseInt( getTime( a.published ) ) < parseInt( getTime( b.published ) ) ) {
						return 1;
					}
					if ( parseInt( getTime( a.published ) ) > parseInt( getTime( b.published ) ) ) {
						return -1;
					}
				}
				return 0;
			} );

			this.setState( { [ library ]: sorted } );
		}
	}

	handleSearch( value, library = 'templates' ) {
		let searchData = this.state.blockArchive;
		if ( 'templates' === library ) {
			searchData = this.state.archive;
		}
		let filtered = [];
		let searchTags = [];

		if ( value ) {
			filtered = searchData.filter( single => {
				if ( 'patterns' === library && single.keywords ) {
					searchTags = single.keywords.filter( keyword => keyword.toLowerCase().includes( value.toLowerCase() ) );
				} else if ( single.tags ) {
					searchTags = single.tags.filter( tag => tag.toLowerCase().includes( value.toLowerCase() ) );
				}

				return (
					single.title.toLowerCase().includes( value.toLowerCase() ) || searchTags.length >= 1
				);
			} );

			if ( filtered.length > 0 ) {
				if ( 'templates' === library ) {
					this.setState( {
						templates: filtered,
					} );

					return;
				}

				this.setState( {
					blocks: filtered,
					blocksSearchInput: value,
				} );

				return;
			}
		}
		if ( 'templates' === library ) {
			this.setState( {
				templates: value ? [] : this.state.archive,
			} );
		} else {
			this.setState( {
				blocks: value ? [] : this.state.blockArchive,
				blocksSearchInput: '',
			} );
		}
	}

	async refreshAPI() {
		this.setState( {
			syncing: true,
			blocksSearchInput: '',
		} );

		wp.hooks.doAction( 'analog.refreshLibrary' );

		return await apiFetch( {
			path: '/agwp/v1/templates/?force_update=true',
		} ).then( data => {
			const library = data.library;

			this.setState( {
				templates: library.templates,
				archive: library.templates,
				blockArchive: library.blocks,
				count: library.templates.length,
				kits: library.template_kits,
				timestamp: data.timestamp,
				styleKits: library.stylekits,
				blocks: library.blocks,
				syncing: false,
				blocksSearchInput: '',
			} );
		} ).catch( () => {
			this.setState( {
				syncing: false,
			} );
		} );
	}

	toggleFavorites() {
		// Reset group state to false.
		this.setState( {
			group: false,
		} );
		window.localStorage.setItem( 'analog::group-block', false );

		const filteredTemplates = this.state.archive.filter(
			template => template.id in this.state.favorites
		);
		const filteredBlocks = this.state.blockArchive.filter(
			block => block.id in this.state.blockFavorites
		);

		this.setState( {
			showing_favorites: ! this.state.showing_favorites,
			templates: ! this.state.showing_favorites ?
				filteredTemplates :
				this.state.archive,
			blocks: ! this.state.showing_favorites ?
				filteredBlocks :
				this.state.blockArchive,
		} );
	}

	render() {
		return (
			<ThemeContext.Provider value={ {
				theme: Theme,
			} }>
				<ThemeContext.Consumer>
					{ ( { theme } ) => (
						<Analog theme={ theme }>
							<Notifications>
								<AnalogContext.Provider
									value={ {
										state: this.state,
										forceRefresh: this.refreshAPI,
										markFavorite: markFavorite,
										toggleFavorites: this.toggleFavorites,
										handleSearch: this.handleSearch,
										handleSort: this.handleSort,
										handleFilter: this.handleFilter,
										dispatch: action => this.setState( action ),
									} }
								>
									<Header />

									<div className="analogwp-content">
										<div className="ang-container">
											{ getPageComponents( this.state ) }
										</div>
									</div>
								</AnalogContext.Provider>
							</Notifications>
						</Analog>
					) }
				</ThemeContext.Consumer>
			</ThemeContext.Provider>
		);
	}
}

export default App;
