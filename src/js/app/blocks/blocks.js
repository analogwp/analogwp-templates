import styled from 'styled-components';
import { CSSTransition } from 'react-transition-group';

import { requestBlockContent } from '../api';
import Empty from '../helpers/Empty';
import BlockList from './BlockList';
import Filters from './Filters';
import Popup from '../popup';
import Loader from '../icons/loader';
import AnalogContext from '../AnalogContext';

const { __ } = wp.i18n;
const { decodeEntities } = wp.htmlEntities;
const { Component, Fragment } = wp.element;
const { Dashicon } = wp.components;
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
	category: false,
	modalActive: false,
};

export default class Blocks extends Component {
	static contextType = AnalogContext;

	constructor() {
		super( ...arguments );

		this.state = {
			...initialState,
			categories: [ ...new Set( this.context.state.blocks.map( block => block.tags[ 0 ] ) ) ],
		};

		this.setCategory = this.setCategory.bind( this );
		this.importBlock = this.importBlock.bind( this );
		this.handleImport = this.handleImport.bind( this );
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
		const method = ( Boolean( AGWP.is_settings_page ) ) ? 'library' : 'elementor';

		requestBlockContent( block, method )
			.then( ( response ) => {
				if ( method === 'elementor' ) {
					const parsedTemplate = response.data;

					const model = new Backbone.Model( {
						getTitle: function getTitle() {
							return 'Test';
						},
					} );

					elementor.channels.data.trigger( 'template:before:insert', model );
					for ( let i = 0; i < parsedTemplate.content.length; i++ ) {
						elementor.getPreviewView().addChildElement( parsedTemplate.content[ i ] );
					}
					elementor.channels.data.trigger( 'template:after:insert', {} );

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
				console.error( error );
			} );
	}

	getItemCount( category ) {
		const blocks = this.context.state.blocks;

		const foundItems =
			blocks
				.filter( block => block.tags.indexOf( category ) > -1 )
				.filter( block => ! ( this.context.state.showFree && Boolean( block.is_pro ) ) );

		if ( foundItems ) {
			return foundItems.length;
		}

		return false;
	}

	render() {
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
								<p>{ __( 'The Block has been imported and is now available in the list of the available Sections.', 'ang' ) }</p>
								<p>
									<a
										className="ang-button"
										target="_blank"
										rel="noopener noreferrer"
										href={ addQueryArgs( 'edit.php', { post_type: 'elementor_library', tabs_group: true, elementor_library_type: 'section' } ) }
									>{ __( 'Ok, thanks', 'ang' ) } <Dashicon icon="yes" /></a>
								</p>
							</Fragment>

						) }
					</Popup>
				) }

				{ ! this.context.state.syncing && this.context.state.blocks && ! this.state.category && (
					<Categories>
						{ this.state.categories && this.state.categories.map( ( category ) => {
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
