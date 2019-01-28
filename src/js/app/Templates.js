import classNames from 'classnames';
import styled from 'styled-components';
import AnalogContext from './AnalogContext';
import { requestDirectImport } from './api';
import Loader from './icons/loader';
import Star from './icons/star';
import CustomModal from './modal';
const { decodeEntities } = wp.htmlEntities;
const { apiFetch } = wp;
const { __ } = wp.i18n;
const { Modal, TextControl, Button, Dashicon } = wp.components;
const { Fragment } = React;
const { addQueryArgs } = wp.url;

const TemplatesList = styled.ul`
	margin: 0;
	display: grid;
	/* grid-template-columns: repeat(4, 1fr); */
	grid-template-columns: repeat(auto-fit, minmax(280px, 280px));
	grid-gap: 25px;
	color: #000;

	li {
		background: #fff;
		position: relative;
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
	}

	figure {
		margin: 0;
		position: relative;

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
	}

	.actions button {
		display: block;
		border: none;
		outline: 0;
		font-size: 12px;
		padding: 10px;
		font-weight: bold;
		background: #ff7865;
		width: 100px;
		color: #fff;
		cursor: pointer;
		transition: all 200ms ease-in;
		opacity: 0;

		&:nth-child(1) {
			transform: translateX(-20px);
		}
		&:nth-child(2) {
			transform: translateX(20px);
		}

		&:hover {
			background: rgba(255, 120, 101, 0.9);
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

const StyledButton = styled.button`
	padding: 5px 10px;
`;

class Templates extends React.Component {
	state = {
		template: null,
		pageName: null,
		showingModal: false,
		importing: false,
		importedPage: false,
	};

	setModalContent = template => {
		window.scrollTo( 0, 0 );
		this.context.dispatch( {
			isOpen: ! this.context.state.isOpen,
		} );
		this.setState( {
			template: template,
		} );
	};

	importLayout = template => {
		if ( ! template ) {
			template = this.state.template;
		} else {
			this.setState( { template } );
		}

		if ( typeof elementor !== 'undefined' ) {
			const editorId =
				'undefined' !== typeof ElementorConfig ?
					ElementorConfig.post_id :
					false;

			apiFetch( {
				path: '/agwp/v1/import/elementor',
				method: 'post',
				data: {
					template_id: template.id,
					editor_post_id: editorId,
				},
			} ).then( data => {
				const parsedTemplate = JSON.parse( data );

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
				window.analogModal.hide();
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

				{ this.state.showingModal && (
					<Modal
						title={ decodeEntities( this.state.template.title ) }
						onRequestClose={ () => this.setState( { showingModal: false } ) }
						style={ {
							textAlign: 'center',
							width: '380px',
						} }
					>
						{ this.state.importing &&
							<Fragment>
								<p>{ ! this.state.importedPage ? __( 'Importing:', 'ang' ) : __( 'Imported:', 'ang' ) } { decodeEntities( this.state.template.title ) }</p>

								{ this.state.importedPage ?
									( <Fragment>
										<p>{ __( 'Blimey! Your template has been imported.', 'ang' ) }</p>
										<p>
											<a
												className="button button-primary"
												href={ addQueryArgs( 'post.php', { post: this.state.importedPage, action: 'elementor' } ) }
											>{ __( 'Edit Template' ) }</a>
										</p>
									</Fragment> ) :
									<Loader width={ 100 } />
								}
							</Fragment>
						}
						{ ! this.state.importing &&
							<Fragment>
								<div>
									<p>
										{ __( 'Import this template to make it available in your Elementor ', 'ang' ) }
										<a href={ AGWP.elementorURL }>{ __( 'Saved Templates', 'ang' ) }</a>
										{ __( ' list for future use.', 'ang' ) }
									</p>
									<p>
										<Button
											isPrimary
											onClick={ () => {
												this.setState( { importing: true } );

												requestDirectImport( this.state.template ).then( response => {
													this.setState( {
														importedPage: response.page,
													} );
												} ).catch( error => {
													console.log( error );
													this.setState( {
														importing: false,
														showingModal: false,
													} );
												} );
											} }
										>
											{ __( 'Import Template', 'ang' ) }
											{ ' ' }
											{ this.state.importing && <Dashicon icon="update" /> }
										</Button>
									</p>
								</div>
								<p className="or">{ __( 'or', 'ang' ) }</p>

								<div>
									<p>
										{ __( 'Create a new page from this template to make it available as a draft page in your Pages list.', 'ang' ) }
									</p>
									<p>
										<TextControl
											placeholder={ __( 'Enter a Page Name', 'ang' ) }
											style={ { maxWidth: '60%' } }
											onChange={ val => this.setState( { pageName: val } ) }
										/>
									</p>
									<p>
										<Button
											isDefault
											disabled={ ! this.state.pageName }
											onClick={ () => {
												this.setState( { importing: true } );
												{/* Required, second arg represents page name */}
												const result = requestDirectImport( this.state.template, this.state.pageName );
												result.then( ( response ) => {
													this.setState( {
														importedPage: response.page,
													} );
												} );
											} }
										>
											{ __( 'Create New Page', 'ang' ) }
											{ ' ' }
											{ this.state.importing && <Dashicon icon="update" /> }
										</Button>
									</p>
								</div>
							</Fragment>
						}
					</Modal>
				) }

				<TemplatesList>
					<AnalogContext.Consumer>
						{ context =>
							! context.state.isOpen &&
							context.state.count >= 1 &&
							context.state.templates.map( template => (
								<li key={ template.id }>
									{ template.is_pro && (
										<span className="pro">{ __( 'Pro', 'ang' ) }</span>
									) }
									<figure>
										{ template.thumbnail && <img alt={ template.title } src={ template.thumbnail } /> }
										<div className="actions">
											<StyledButton
												onClick={ () => this.setModalContent( template ) }
											>
												{ __( 'Preview', 'ang' ) }
											</StyledButton>
											<StyledButton onClick={ () => this.importLayout( template ) }>
												{ __( 'Import', 'ang' ) }
											</StyledButton>
										</div>
									</figure>
									<div className="content">
										<h3>{ decodeEntities( template.title ) }</h3>
										<button
											href="#"
											className={ classNames( 'button-plain', {
												'is-active': template.id in this.context.state.favorites,
											} ) }
											onClick={ e => {
												e.preventDefault();

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
								</li>
							) )
						}
					</AnalogContext.Consumer>
				</TemplatesList>
			</div>
		);
	}
}

Templates.contextType = AnalogContext;

export default Templates;
