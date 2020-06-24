import styled from 'styled-components';
import AnalogContext from '../AnalogContext';
import { requestBlockContent, doElementorInsert } from '../api';
import Empty from '../helpers/Empty';
import Loader from '../icons/loader';
import Popup from '../popup';
import BlockList from './BlockList';
import Filters from './Filters';
import ProModal from '../ProModal';

const { __ } = wp.i18n;
const { decodeEntities } = wp.htmlEntities;
const { Component, Fragment } = wp.element;
const { Dashicon, Button } = wp.components;
const { addQueryArgs } = wp.url;

const Categories = styled.ul`
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
	grid-gap: 25px;
	grid-auto-rows: 154px;

	li {
		background: #fff;
		box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.12);
		border-radius: 4px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-weight: bold;
		font-size: 15px;
		line-height: 21px;
		color: #060606;
		margin-bottom: 0;
		cursor: pointer;
		position: relative;
	}

	span {
		position: absolute;
		min-width: 25px;
		height: 28px;
		padding: 0 5px;
		top: 8px;
		right: 8px;
		font-weight: 700;
		font-size: 15px;
		border-radius: 4px;
		display: inline-flex;
		justify-content: center;
		align-items: center;
		z-index: 100;
		border: 1px solid black;
	}
`;

const initialState = {
	blocks: [],
	activeBlock: false,
	blockImported: false,
	category: false,
	modalActive: false,
	categories: [],
};

export default class Blocks extends Component {
	static contextType = AnalogContext;

	constructor() {
		super( ...arguments );

		this.state = {
			...initialState,
		};

		this.setCategory = this.setCategory.bind( this );
		this.importBlock = this.importBlock.bind( this );
		this.handleImport = this.handleImport.bind( this );
	}

	setCategory( category ) {
		this.setState( { category } );
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
		const categories = [ ...new Set( this.context.state.blocks.map( block => block.tags[ 0 ] ) ) ];

		return (
			<Fragment>
				<Filters category={ this.state.category } setCategory={ this.setCategory } />

				{ this.context.state.syncing && <Empty text={ __( 'Loading blocks...', 'ang' ) } /> }

				{ this.state.modalActive && (
					<Popup
						title={ decodeEntities( this.state.activeBlock.title ) }
						style={ {
							textAlign: 'center',
						} }
						onRequestClose={ () => {
							this.setState( {
								activeBlock: false,
								modalActive: false,
								blockImported: false,
							} );
						} }
					>
						{ ! this.state.blockImported && <Loader /> }
						{ this.state.blockImported && (
							<Fragment>
								<p>
									{ __( 'The block has been imported and is now available in the', 'ang' ) }
									{ ' ' }
									<a
										target="_blank"
										rel="noopener noreferrer"
										href={ addQueryArgs( 'edit.php', {
											post_type: 'elementor_library',
											tabs_group: true,
											elementor_library_type: 'section',
										} ) }
									>
										{ __( 'Elementor section library', 'ang' ) }
									</a>.
								</p>
								<p>
									<Button
										isPrimary
										onClick={ () => {
											this.setState( {
												activeBlock: false,
												modalActive: false,
												blockImported: false,
											} );
										} }
									>
										{ __( 'Ok, thanks', 'ang' ) } <Dashicon icon="yes" />
									</Button>
								</p>
							</Fragment>

						) }
					</Popup>
				) }

				{ AGWP.license.status !== 'valid' && (
					<ProModal type={ __( 'blocks', 'ang' ) } />
				) }

				{ this.context.state.blocks.length < 1 && (
					<Empty text={ __( 'No blocks found.', 'ang' ) }/>
				) }

				{ ! this.context.state.syncing && this.context.state.blocks && ! this.state.category && this.context.state.group && (
					<Categories className="blocks-categories">
						{ categories && categories.map( ( category ) => {
							const count = this.getItemCount( category );
							if ( ! count ) {
								return;
							}

							return (
								<li key={ category } onClick={ () => this.setCategory( category ) }>
									<span>{ count }</span>
									{ category }
								</li>
							);
						} ) }
					</Categories>
				) }

				<BlockList
					state={ this.state }
					importBlock={ this.importBlock }
					favorites={ this.context.state.blockFavorites }
					makeFavorite={ this.makeFavorite }
				/>
			</Fragment>
		);
	}
}
