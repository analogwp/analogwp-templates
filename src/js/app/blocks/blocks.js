import styled from 'styled-components';
import { CSSTransition } from 'react-transition-group';

import { requestBlocksList, requestBlockContent } from '../api';
import Empty from '../helpers/Empty';
import BlockList from './BlockList';
import Filters from './Filters';
import Popup from '../popup';
import Loader from '../icons/loader';

const { __ } = wp.i18n;
const { decodeEntities } = wp.htmlEntities;
const { Component, Fragment } = wp.element;

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
		background: var(--ang-accent);
		min-width: 25px;
		height: 28px;
		padding: 0 5px;
		top: -14px;
		right: -17px;
		font-weight: 700;
		font-size: 15px;
		border-radius: 4px;
		display: inline-flex;
		justify-content: center;
		align-items: center;
		color: #fff;
		z-index: 100;
	}
`;

const initialState = {
	blocks: [],
	activeBlock: false,
	blockImported: false,
	syncing: true,
	category: false,
	modalActive: false,
};

export default class Blocks extends Component {
	constructor() {
		super( ...arguments );

		this.state = {
			...initialState,
		};

		this.setCategory = this.setCategory.bind( this );
		this.importBlock = this.importBlock.bind( this );
		this.handleImport = this.handleImport.bind( this );
	}

	async componentDidMount() {
		await this.getBlocks();
	}

	async getBlocks( $force = false ) {
		const request = await requestBlocksList( $force );
		const blocks = request.blocks;

		this.setState( {
			blocks,
			syncing: false,
			categories: [ ...new Set( blocks.map( block => block.tags[ 0 ] ) ) ],
		} );
	}

	setCategory( category ) {
		this.setState( { category } );
	}

	importBlock( block ) {
		this.setState( {
			modalActive: true,
			activeBlock: block,
		} );

		this.handleImport( block );
	}

	handleImport( block ) {
		const method = 'library';

		requestBlockContent( block, method )
			.then( ( response ) => {
				console.log(response);
			} )
			.catch( error => {
				console.error( error );
			} );
	}

	getItemCount( category ) {
		const blocks = this.state.blocks;

		const foundItems = blocks.filter( block => block.tags.indexOf( category ) > -1 );

		if ( foundItems ) {
			return foundItems.length;
		}

		return false;
	}

	render() {
		return (
			<Fragment>
				<Filters category={ this.state.category } setCategory={ this.setCategory } />

				{ this.state.syncing && <Empty text={ __( 'Loading blocks...', 'ang' ) } /> }

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
							} );
						} }
					>
						{ ! this.state.blockImported && <Loader /> }
					</Popup>
				) }

				{ ! this.state.syncing && this.state.blocks && ! this.state.category && (
					<Categories>
						{ this.state.categories && this.state.categories.map( ( category ) => (
							<li key={ category } onClick={ () => this.setCategory( category ) }>
								{ this.getItemCount( category ) && <span>{ this.getItemCount( category ) }</span> }
								{ category }
							</li>
						) ) }
					</Categories>
				) }

				<CSSTransition
					in={ !! this.state.category }
					timeout={ 150 }
					classNames="slide-in"
					unmountOnExit
				>
					<BlockList
						state={ this.state }
						importBlock={ this.importBlock }
					/>
				</CSSTransition>
			</Fragment>
		);
	}
}
