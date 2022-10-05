import styled from 'styled-components';
import AnalogContext from '../AnalogContext';
import { requestBlockContent, doElementorInsert } from '../api';
import Empty from '../helpers/Empty';
import BlockList from './BlockList';
import Sidebar from './Sidebar';

const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;

const LibraryWrapper = styled.div`
	display: flex;
	justify-content: space-between;
	position: relative;
`;

const initialState = {
	blocks: [],
	activeBlock: false,
	blockImported: false,
	modalActive: false,
};

export default class Blocks extends Component {
	static contextType = AnalogContext;

	constructor() {
		super( ...arguments );

		this.state = {
			...initialState,
		};

		this.importBlock = this.importBlock.bind( this );
		this.handleImport = this.handleImport.bind( this );
	}

	importBlock( block, add ) {
		this.setState( {
			modalActive: true,
			activeBlock: block,
		} );

		this.handleImport( block, add );
	}

	handleImport( block, add ) {
		const method = ( Boolean( AGWP.is_settings_page ) ) ? 'library' : 'elementor';

		requestBlockContent( block, method )
			.then( ( response ) => {
				if ( method === 'elementor' ) {
					const parsedTemplate = response.data;

					doElementorInsert( parsedTemplate.content, 'block' );

					this.setState( {
						modalActive: false,
						activeBlock: false,
					} );

					window.analogModal.hide();
				} else {
					this.setState( {
						blockImported: true,
					} );
				}
			} )
			.catch( error => {
				add( error.message, 'error', 'import-error', false );

				this.setState( {
					modalActive: false,
					activeBlock: false,
				} );
			} );
	}

	getItemCount( category ) {
		const blocks = this.context.state.blocks;

		const foundItems =
			blocks
				.filter( block => block.tags.indexOf( category ) > -1 );

		if ( foundItems ) {
			return foundItems.length;
		}

		return false;
	}

	makeFavorite = ( id ) => {
		const blockFavorites = this.context.state.blockFavorites;

		this.context.markFavorite( id, ! ( id in blockFavorites ), 'block' );

		if ( id in blockFavorites ) {
			delete blockFavorites[ id ];
		} else {
			blockFavorites[ id ] = ! ( id in blockFavorites );
		}

		this.context.dispatch( { blockFavorites } );

		if ( this.context.state.showing_favorites ) {
			const filteredBlocks = this.context.state.blocks.filter( t => t.id in blockFavorites );

			this.context.dispatch( {
				blocks: filteredBlocks,
			} );
		}
	};

	render() {
		const dataSet = {
			state: this.state,
			dispatch: action => this.setState( action ),
		};

		return (
			<Fragment>
				<LibraryWrapper>
					<Sidebar
						state={ dataSet }
					/>
					<BlockList
						state={ dataSet }
						importBlock={ this.importBlock }
						favorites={ this.context.state.blockFavorites }
						makeFavorite={ this.makeFavorite }
					/>
				</LibraryWrapper>
			</Fragment>
		);
	}
}
