import classnames from 'classnames';
import styled from 'styled-components';
import AnalogContext from './AnalogContext';
import { requestDirectImport, requestElementorImport } from './api';
import Image from './helpers/Image';
import Loader from './icons/loader';
import Star from './icons/star';
import CustomModal from './modal';
import { NotificationConsumer } from './Notifications';
import Popup from './popup';
import ProModal from './ProModal';

const { decodeEntities } = wp.htmlEntities;
const { __ } = wp.i18n;
const { TextControl, Button } = wp.components;
const { Fragment } = React;
const { addQueryArgs } = wp.url;

const TemplatesList = styled.ul`
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

	.pro {
		position: absolute;
		top: 8px;
		right: -8px;
		background: #ff7865;
		color: #fff;
		z-index: 110;
		font-weight: bold;
		font-size: 12px;
		padding: 8px 10px;
		line-height: 1;
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
				fill: #ff7865;
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

		&:hover .actions {
			opacity: 1;
			button {
				transform: none;
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
		color: #999999;
		text-transform: capitalize;
		padding: 0 20px 15px 20px;
		font-size: 12px;

		span + span:before {
			content: " / ";
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

	setModalContent = template => {
		window.scrollTo( 0, 0 );
		this.context.dispatch( {
			isOpen: ! this.context.state.isOpen,
		} );
		this.setState( {
			template: template,
		} );
	}

	/**
	 * Handle different states for Importing direct layout.
	 *
	 * @param {function} add Adds a notification.
	 * @param {boolean} withPage Determine if import needs a page.
	 */
	handleImport = async( add, withPage = false ) => {
		this.setState( { importing: true } );

		await requestDirectImport( this.state.template, withPage ).then( response => {
			this.setState( {
				importedPage: response.page,
			} );
		} ).catch( error => {
			this.resetState();
			add( error.message, 'error' );
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
			<div
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
												className="button button-accent"
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
													className="button-accent"
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
													className="button-accent"
													disabled={ ! this.state.pageName }
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

				<TemplatesList
					className={ classnames( {
						hide: ( this.state.template && this.state.showingModal && ! this.canImportTemplate() ),
					} ) }
				>
					<AnalogContext.Consumer>
						{ context =>
							! context.state.isOpen &&
							context.state.count >= 1 &&
							context.state.templates.map( template => {
								if ( context.state.showFree && Boolean( template.is_pro ) ) {
									return;
								}

								return <li key={ template.id }>
									{ template.is_pro && (
										<span className="pro">{ __( 'Pro', 'ang' ) }</span>
									) }
									<figure>
										{ template.thumbnail && <Image template={ template } /> }
										<div className="actions">
											<button className="ang-button"
												onClick={ () => this.setModalContent( template ) }
											>
												{ __( 'Preview', 'ang' ) }
											</button>
											<button className="ang-button" onClick={ () => this.importLayout( template ) }>
												{ __( 'Import', 'ang' ) }
											</button>
										</div>
									</figure>
									<div className="content">
										<h3>{ decodeEntities( template.title ) }</h3>
										<button
											className={ classnames( 'button-plain', {
												'is-active': template.id in this.context.state.favorites,
											} ) }
											onClick={ () => {
												const favorites = this.context.state.favorites;

												this.context.markFavorite(
													template.id,
													! ( template.id in favorites )
												);

												if ( template.id in favorites ) {
													delete favorites[ template.id ];
												} else {
													favorites[ template.id ] = ! ( template.id in favorites );
												}

												this.context.dispatch( { favorites } );

												if ( this.context.state.showing_favorites ) {
													const filteredTemplates = this.context.state.templates.filter(
														t => t.id in favorites
													);
													this.context.dispatch( {
														templates: filteredTemplates,
													} );
												}
											} }
										>
											<Star />
										</button>
									</div>
									{ template.tags && (
										<div className="tags">
											{ template.tags.map( tag => (
												<span key={ tag }>{ tag }</span>
											) ) }
										</div>
									) }
								</li>;
							} )
						}
					</AnalogContext.Consumer>
				</TemplatesList>
			</div>
		);
	}
}

Templates.contextType = AnalogContext;

export default Templates;
