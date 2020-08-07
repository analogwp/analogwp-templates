import BlocksContext from './BlocksContext';
import BlocksList from './BlocksList';
import Empty from '../helpers/Empty';
import Loader from '../icons/loader';
import Popup from '../popup';
import ProModal from '../ProModal';
import { requestBlockContent, doElementorInsert } from '../api';
const { __ } = wp.i18n;
const { decodeEntities } = wp.htmlEntities;
const { Fragment } = wp.element;
const { TextControl, Button, Dashicon } = wp.components;
const { addQueryArgs } = wp.url;

class Blocks extends React.Component {
	constructor() {
		super( ...arguments );

		this.state = {
			activeBlock: false,
			blockImported: false,
			modalActive: false,
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
		const method = ( Boolean( AGWP.is_dashboard_page ) ) ? 'library' : 'elementor';

		requestBlockContent( block, method )
			.then( ( response ) => {
				if ( method === 'elementor' ) {
					const parsedTemplate = response.data;

					doElementorInsert( parsedTemplate.content, 'block' );

					this.setState( {
						modalActive: false,
						activeBlock: false,
					} );

					window.analogBlocksModal.hide();
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

	render() {
		return (
			<Fragment>
				<div className="blocks-area">
					<TextControl
						placeholder={ __( 'Search blocks', 'ang' ) }
						value={ this.searchInput }
						onChange={ ( value ) =>
							this.context.handleSearch( value )
						}
					/>

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

					{ this.context.state.blocks.length > 1 && AGWP.license.status !== 'valid' && (
						<ProModal type={ __( 'blocks', 'ang' ) } />
					) }

					{ this.context.state.blocks.length < 1 && (
						<Empty text={ __( 'No blocks found.', 'ang' ) } />
					) }

					<BlocksList
						importBlock={ this.importBlock }
					/>

				</div>
			</Fragment>
		);
	}
}

Blocks.contextType = BlocksContext;

export default Blocks;
