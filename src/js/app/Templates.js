import classnames from 'classnames';
import styled from 'styled-components';
import AnalogContext from './AnalogContext';
import { requestDirectImport, requestElementorImport } from './api';
import Collection from './collection/Collection';
import { Theme } from './contexts/ThemeContext';
import Empty from './helpers/Empty';
import Loader from './icons/loader';
import CustomModal from './modal';
import { NotificationConsumer } from './Notifications';
import Popup from './popup';
import ProModal from './ProModal';
import Template from './Template';

const { decodeEntities } = wp.htmlEntities;
const { __ } = wp.i18n;
const { TextControl, Button } = wp.components;
const { Fragment } = React;
const { addQueryArgs } = wp.url;

const TemplatesContainer = styled.div`
	.templates-list {
		margin: 0;
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(280px, 280px));
		grid-gap: 25px;
		color: #000;

		&.hide {
			display: none;
		}

		li {
			background: #fff;
			position: relative;
			border-radius: 4px;
		}

		.new,
		.pro {
			position: absolute;
			top: -8px;
			right: -8px;
			background: var(--ang-accent);
			color: #fff;
			z-index: 110;
			font-weight: bold;
			padding: 8px 10px;
			line-height: 1;
			border-radius: 4px;
			text-transform: uppercase;
			font-size: 14.22px;
			letter-spacing: .5px;
		}

		.pro {
			bottom: 15px;
			right: 20px;
			top: auto;
			background: rgba(92, 50, 182, 0.1);
			font-size: 12px;
			color: var(--ang-accent);
			padding: 4px 7px;
		}

		.new {
			background: ${ Theme.accent };
		}

		p {
			color: #939393;
			font-size: 10px;
			margin: 0;
			font-weight: 500;
		}

		.content {
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 13px 20px 7px;

			svg {
				fill: #d0d0d0;
				transition: all 100ms ease-in;
			}

			button:hover,
			button.is-active {
				svg {
					fill: #FFB443;
				}
			}
		}

		h3 {
			font-size: inherit;
			text-transform: capitalize;
			margin: 0;
			font-weight: bold;
		}

		img {
			width: 100%;
			height: auto;
			border-top-left-radius: 4px;
			border-top-right-radius: 4px;
		}

		figure {
			margin: 0;
			position: relative;
			min-height: 100px;

			&:hover {
				.actions {
					opacity: 1;
					button {
						transform: none;
						opacity: 1;
					}
				}
				.favorite {
					opacity: 1;
				}
			}
		}

		.actions {
			opacity: 0;
			position: absolute;
			width: 100%;
			height: 100%;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			background: rgba(0, 0, 0, 0.7);
			top: 0;
			left: 0;
			z-index: 100;
			transition: all 200ms;
			border-top-left-radius: 4px;
			border-top-right-radius: 4px;
		}

		.actions button {
			opacity: 0;

			&:nth-child(1) {
				transform: translateX(-20px);
			}
			&:nth-child(2) {
				transform: translateX(20px);
			}

			+ button {
				margin-top: 10px;
			}
		}

		.tags {
			color: #888;
			text-transform: capitalize;
			padding: 0 20px 15px 20px;
			font-size: 12px;

			span + span:before {
				content: " / ";
			}
		}

		.favorite {
			position: absolute;
			top: 0;
			left: 0;
			z-index: 200;
			display: inline-flex;
			justify-content: center;
			align-items: center;
			width: 25px;
			height: 25px;

			&:not(.is-active) {
				opacity: 0;
			}

			&:before {
				content: '';
				width: 0;
				height: 0;
				border-style: solid;
				border-width: 42px 42px 0 0;
				border-color: var(--ang-accent) transparent transparent transparent;
				position: absolute;
				top: 0;
				left: 0;
				z-index: 190;
			}

			svg {
				fill: #fff;
				position: relative;
				z-index: 195;
			}
			&.is-active svg {
				fill: #FFB443;
			}
		}
	}
`;

const initialState = {
	template: null,
	pageName: null,
	showingModal: false,
	importing: false,
	importedPage: false,
	importingElementor: false,
};

class Templates extends React.Component {
	state = initialState;

	resetState() {
		this.context.dispatch( { isOpen: false } );
		this.setState( initialState );
	}

	closeOnEsc = ( event ) => {
		if ( event.keyCode === 27 ) {
			this.resetState();
		}
	}

	componentDidMount() {
		window.addEventListener( 'keyup', this.closeOnEsc );
	}

	componentWillUnmount() {
		this.resetState();
		window.removeEventListener( 'keyup', this.closeOnEsc );
	}

	setModalContent = template => {
		window.scrollTo( 0, 0 );
		this.context.dispatch( {
			isOpen: ! this.context.state.isOpen,
		} );
		this.setState( {
			template: template,
		} );
	}

	makeFavorite = ( id ) => {
		const favorites = this.context.state.favorites;

		this.context.markFavorite( id, ! ( id in favorites ) );

		if ( id in favorites ) {
			delete favorites[ id ];
		} else {
			favorites[ id ] = ! ( id in favorites );
		}

		this.context.dispatch( { favorites } );

		if ( this.context.state.showing_favorites ) {
			const filteredTemplates = this.context.state.templates.filter( t => t.id in favorites );

			this.context.dispatch( {
				templates: filteredTemplates,
			} );
		}
	};

	/**
	 * Handle different states for Importing direct layout.
	 *
	 * @param {function} add Adds a notification.
	 * @param {boolean} withPage Determine if import needs a page.
	 */
	handleImport = async( add, withPage = false ) => {
		this.setState( { importing: true } );

		const version = this.state.template.version;

		if ( version ) {
			if ( parseFloat( AGWP.version ) < parseFloat( version ) ) {
				this.resetState();
				add(
					__( 'Please update Analog Template plugin to latest version.', 'ang' ),
					'error', 'ang',
					'import-error',
					false
				);
				return;
			}
		}

		await requestDirectImport( this.state.template, withPage ).then( response => {
			this.setState( {
				importedPage: response.page,
			} );
		} ).catch( error => {
			this.resetState();
			if ( error.data.errors ) {
				add( error.data.errors[ Object.keys( error.data.errors )[ 0 ] ], 'error', 'import-error', false );
			} else {
				add( error.message, 'error', 'import-error', false );
			}
		} );
	}

	/**
	 * Determine if current user can import template.
	 * Mainly to check pro template capabilities.
	 *
	 * @param {object} template Template data.
	 * @return {bool} True if Pro and license is valid, else false.
	 */
	canImportTemplate = ( template ) => {
		if ( ! template ) {
			template = this.state.template;
		}

		if ( ! template.is_pro ) {
			return true;
		}

		if ( template.is_pro && AGWP.license.status === 'valid' ) {
			return true;
		}

		return false;
	}

	importLayout = ( template ) => {
		if ( ! template ) {
			template = this.state.template;
		} else {
			this.setState( { template } );
		}

		if ( typeof elementor !== 'undefined' ) {
			this.setState( { showingModal: true, importing: true } );
			requestElementorImport( template ).then( () => {
				this.setState( { showingModal: false, importing: false } );
			} );
		} else {
			this.setState( {
				showingModal: true,
			} );
		}
	};

	render() {
		return (
			<TemplatesContainer
				style={ {
					position: 'relative',
					minHeight: '80vh',
				} }
			>
				{ this.context.state.isOpen && (
					<CustomModal
						template={ this.state.template }
						onRequestClose={ () => this.context.dispatch( { isOpen: false } ) }
						onRequestImport={ () => this.importLayout() }
					/>
				) }

				{ ( ( this.state.template !== null ) && this.canImportTemplate() && this.state.showingModal ) && (
					<Popup
						title={ decodeEntities( this.state.template.title ) }
						onRequestClose={ () => this.resetState() }
					>
						{ this.state.importing &&
							<div style={ { textAlign: 'center', fontSize: '15px' } }>
								{ this.state.importedPage ?
									( <Fragment>
										<p>{ __( 'Blimey! Your template has been imported.', 'ang' ) }</p>
										<p>
											<a
												className="ang-button"
												href={ addQueryArgs( 'post.php', { post: this.state.importedPage, action: 'elementor' } ) }
											>{ __( 'Edit Template' ) }</a>
										</p>
									</Fragment> ) :
									<Loader />
								}
								<p>{ ! this.state.importedPage ? __( 'Importing ', 'ang' ) : __( 'Imported ', 'ang' ) } { decodeEntities( this.state.template.title ) }</p>
							</div>
						}
						{ ! this.state.importing &&
							<Fragment>
								<div>
									<p>
										{ __( 'Import this template to your library to make it available in your Elementor ', 'ang' ) }
										<a href={ AGWP.elementorURL }>{ __( 'Saved Templates', 'ang' ) }</a>
										{ __( ' list for future use.', 'ang' ) }
									</p>
									<p>
										<NotificationConsumer>
											{ ( { add } ) => (
												<Button
													className="ang-button"
													onClick={ () => this.handleImport( add ) }
												>
													{ __( 'Import to Library', 'ang' ) }
												</Button>
											) }
										</NotificationConsumer>
									</p>
								</div>

								<hr />

								<div>
									<p>
										{ __( 'Create a new page from this template to make it available as a draft page in your Pages list.', 'ang' ) }
									</p>
									<div className="form-row">
										<TextControl
											placeholder={ __( 'Enter a Page Name', 'ang' ) }
											style={ { maxWidth: '60%' } }
											onChange={ val => this.setState( { pageName: val } ) }
										/>
										<NotificationConsumer>
											{ ( { add } ) => (
												<Button
													className="ang-button"
													disabled={ ! this.state.pageName }
													style={ {
														marginLeft: '15px',
													} }
													onClick={ () => this.handleImport( add, this.state.pageName ) }
												>
													{ __( 'Import to page', 'ang' ) }
												</Button>
											) }
										</NotificationConsumer>
									</div>
								</div>
							</Fragment>
						}
					</Popup>
				) }

				{ ( this.state.template !== null ) && ! this.canImportTemplate() && this.state.showingModal &&
					<ProModal onDimiss={ () => this.resetState() } />
				}

				{ ! this.context.state.isOpen && this.context.state.templates.length < 1 && (
					<Empty />
				) }

				{ this.context.state.group ? (
					<Collection
						templates={ this.context.state.templates }
						kits={ this.context.state.kits }
						showFree={ this.context.state.showFree }
						favorites={ this.context.state.favorites }
						setModalContent={ this.setModalContent }
						importLayout={ this.importLayout }
						makeFavorite={ this.makeFavorite }
					/>
				) : (
					<ul
						className={ classnames( 'templates-list', {
							hide: ( this.state.template && this.state.showingModal && ! this.canImportTemplate() ),
						} ) }
					>
						{ ! this.context.state.isOpen && this.context.state.count >= 1 && this.context.state.templates.map( template => {
							if ( this.context.state.showFree && Boolean( template.is_pro ) ) {
								return;
							}

							return (
								<Template
									key={ template.id }
									template={ template }
									favorites={ this.context.state.favorites }
									setModalContent={ this.setModalContent }
									importLayout={ this.importLayout }
									makeFavorite={ this.makeFavorite }
								/>
							);
						} ) }
					</ul>
				) }
			</TemplatesContainer>
		);
	}
}

Templates.contextType = AnalogContext;

export default Templates;
