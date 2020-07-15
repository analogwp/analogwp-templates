import styled from 'styled-components';
import BlocksContext from './BlocksContext';
import Blocks from './Blocks';
import Header from './Header';
import Sidebar from './Sidebar';
import Notifications from '../Notifications';
import { markFavorite, requestTemplateList } from '../api';
import { getTime, hasProTemplates } from '../utils';
import Empty from '../helpers/Empty';
const { __ } = wp.i18n;
const { apiFetch } = wp;

const AnalogBlocks = styled.div`
	margin: 0 0 0 -20px;
	position: relative;
	color: #000000;

	h1, h2, h3, h4, h5, h6 {
		color: #000000;
	}
`;

const Container = styled.div`
	display: grid;
	grid-template-columns: 2fr 10fr;
	margin: 10px 20px;
	grid-gap: 20px;
`;

class BlocksLibrary extends React.Component {
	constructor() {
		super( ...arguments );

		this.state = {
			blocks: [],
			archive: [],
			favorites: AGWP.blockFavorites,
			showFree: false,
			showingFavorites: false,
			hasPro: false,
			count: null,
			syncing: false,
			isOpen: false,
			tab: 'all',
			activeBlock: false,
			blockImported: false,
			modalActive: false,
		};

		this.refreshAPI = this.refreshAPI.bind( this );
		this.handleSort = this.handleSort.bind( this );
		this.handleSearch = this.handleSearch.bind( this );
		this.toggleFavorites = this.toggleFavorites.bind( this );
	}

	async componentDidMount() {

		if ( window.localStorage.getItem( 'analogBlocks::show-free' ) === 'false' ) {
			this.setState( {
				showFree: false,
			} );
		}

		const templates = await requestTemplateList();
		const library = templates.library;

		this.setState( {
			blocks: library.blocks,
			archive: library.blocks,
			count: library.blocks.length,
			hasPro: hasProTemplates( library.templates ),
		} );

		this.handleSort( 'latest', 'blocks' );

		// Listen for Elementor modal close, so we can reset some states.
		document.addEventListener( 'modal-close', () => {
			this.setState( {
				isOpen: false,
				showingFavorites: false,
				blocks: this.state.archive,
			} );
		} );
	}

	handleSort( value ) {
		this.setState( {
			showingFavorites: false,
		} );

		const sortData = this.state.blocks;

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

			this.setState( { blocks: sorted } );
		}

		if ( 'latest' === value ) {
			const sorted = sortData.sort( ( a, b ) => {
				if ( 'popularityIndex' in a ) {
					if ( parseInt( getTime( a.published ) ) < parseInt( getTime( b.published ) ) ) {
						return 1;
					}
					if ( parseInt( getTime( a.published ) ) > parseInt( getTime( b.published ) ) ) {
						return -1;
					}
				}
				return 0;
			} );

			this.setState( { blocks: sorted } );
		}
	}

	handleSearch( value ) {
		const searchData = this.state.archive;
		let filtered = [];
		let searchTags = [];

		if ( value ) {
			filtered = searchData.filter( single => {
				if ( single.tags ) {
					searchTags = single.tags.filter( tag => {
						return tag.toLowerCase().includes( value );
					} );
				}
				return (
					single.title.toLowerCase().includes( value ) || searchTags.length >= 1
				);
			} );

			if ( filtered.length > 0 ) {
				this.setState( {
					blocks: filtered,
				} );

				return;
			}
		}
		this.setState( {
			blocks: value ? [] : this.state.archive,
		} );
	}

	async refreshAPI() {
		this.setState( {
			blocks: [],
			archive: [],
			count: null,
			syncing: true,
		} );

		wp.hooks.doAction( 'analog.refreshBlocksLibrary' );

		return await apiFetch( {
			path: '/agwp/v1/templates/?force_update=true',
		} ).then( data => {
			const library = data.library;

			this.setState( {
				blocks: library.blocks,
				archive: library.blocks,
				count: library.blocks.length,
				timestamp: data.timestamp,
				syncing: false,
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
			tab: 'favorites',
		} );

		const filteredBlocks = this.state.archive.filter(
			block => block.id in this.state.favorites
		);

		this.setState( {
			showingFavorites: ! this.state.showingFavorites,
			blocks: ! this.state.showingFavorites ?
				filteredBlocks :
				this.state.archive,
		} );
	}

	render() {
		return (
			<AnalogBlocks>
				<Notifications>
					<BlocksContext.Provider
						value={ {
							state: this.state,
							forceRefresh: this.refreshAPI,
							markFavorite: markFavorite,
							toggleFavorites: this.toggleFavorites,
							handleSearch: this.handleSearch,
							handleSort: this.handleSort,
							dispatch: action => this.setState( action ),
						} }
					>
						<Header />
						{ this.state.syncing && <Empty text={ __( 'Loading blocks...', 'ang' ) } /> }

						{ ! this.state.syncing &&
						<Container>
							<Sidebar />
							<Blocks />
						</Container> }
					</BlocksContext.Provider>
				</Notifications>
			</AnalogBlocks>
		);
	}
}

export default BlocksLibrary;
