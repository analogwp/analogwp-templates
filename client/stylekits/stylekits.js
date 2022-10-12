import styled from 'styled-components';
import AnalogContext from '../AnalogContext';
import { requestStyleKitData } from '../api';
import Loader from '../icons/loader';
import { NotificationConsumer } from '../Notifications';
import Popup from '../popup';
import Preview from './../modal/Preview';

const { TextControl, Button, Dashicon, Card, CardBody, CardFooter } = wp.components;

const { decodeEntities } = wp.htmlEntities;
const { __, sprintf } = wp.i18n;
const { addQueryArgs } = wp.url;

const Container = styled.section`
	> p {
		font-size: 16px;
		line-height: 24px;
	}
	h3 {
		margin: 0;
		font-size: 14.2px;
	}
	.inner {
    	text-align: center;
    }
    .error {
    	color: indianred;
    	text-align: left;
    }
    a {
    	color: var(--ang-primary);
    }
    .tab-description {
		font-size: 14px;
		line-height: 1.5;
		margin: 12px 0 40px;
	    padding: 16px;
	    background-color: #F9F9F8;
	    border-radius: 6px;
	    box-shadow: rgb(0 0 0 / 10%) 0px 0px 0px 1px;
    }
    .popup-description {
        text-align: left;
		font-size: 14px;
		line-height: 1.5;
		color: var(--ang-main-text);
		padding-bottom: 10px;

		a {
			text-decoration: underline !important;
		}
    }

	.gap {
		display: flex;
        gap: 10px;

        & > button {
            margin-left: 0 !important;
        }
	}

    .success-buttons {
        button {
            margin-left: 0 !important;
        }
    }
`;

const ChildContainer = styled.ul`
    > li {
    	border-radius: 4px;
    	background: #fff;
    }

    figure {
    	margin: 0;
		position: relative;
		display: flex;
		justify-content: center;
		img {
			width: 100%;
			height: auto;
		}

		&:hover {
			.preview {
				opacity: 1;
				button {
					transform: none;
					opacity: 1;
				}
			}
		}
	}

	 .preview {
		button {
			transform: translateY(20px);
			opacity: 0;

			+ button, + .ang-promo {
				margin-top: 10px;
				text-decoration: none;
			}
		}
		img {
			width: 100%;
			height: auto;
		}
    }
`;

const initialState = {
	modalActive: false,
	importing: false,
	activeKit: [],
	importedKit: false,
	hasError: false,
	kitname: '',
	previewing: null,
};

const footer = sprintf(
	__( '%s <span class="footer-text">in WordPress dashboard.</span>', 'ang' ),
	sprintf(
		`<a href="${ addQueryArgs( 'admin.php', { page: 'style-kits' } ) }" target="_blank" rel="noopener noreferer">%s</a>`,
		__( 'Manage your Style Kits', 'ang' )
	)
);

export default class StyleKits extends React.Component {
	static contextType = AnalogContext;

	constructor() {
		super( ...arguments );

		this.state = {
			...initialState,
		};
	}

	resetState() {
		this.setState( initialState );
	}

	handleImport( kit, add, checkKitStatus = false ) {
		this.setState( {
			activeKit: kit,
			modalActive: true,
		} );

		if ( checkKitStatus ) {
			const kitExists = this.context.state.installedKits.indexOf( this.state.kitname || kit.title ) > -1;
			if ( kitExists ) {
				this.setState( { hasError: true } );
				return;
			}
		}

		requestStyleKitData( kit )
			.then( response => {
				if ( response.errors ) {
					const message = Object.values( response.errors )[ 0 ];
					add( message, 'error', 'kit-error', false );
					this.resetState();

					return;
				}

				const kits = [ ...this.context.state.installedKits ];
				kits.push( kit.title );
				this.setState( {
					importedKit: true,
				} );

				this.context.dispatch( {
					installedKits: kits,
				} );

				add( response.message );

				if ( ! AGWP.is_settings_page && elementor ) {
					let options = elementor.settings.page.model.controls.ang_action_tokens.options;
					if ( options.length === 0 ) {
						options = {};
					}
					options[ response.id ] = kit.title;

					elementor.settings.page.model.controls.ang_action_tokens.options = options;

					elementor.reloadPreview();
					analog.redirectToSection();
				}
			} )
			.catch( error => {
				add( error.message, 'error', 'kit-error', false );
				this.resetState();
			} );
	}

	getPopupTitle( title, hasImported, hasError ) {
		if ( hasImported && ! hasError ) {
			return decodeEntities( title ) + __( ' Imported!', 'ang' );
		}

		return decodeEntities( title );
	}

	render() {
		const successButtonProps = {
			rel: 'noopener noreferrer',
			href: addQueryArgs( 'admin.php', { page: 'style-kits' } ),
		};

		const isValid = ( isPro ) => ! ( isPro && AGWP.license.status !== 'valid' );
		const fallbackImage = AGWP.pluginURL + 'assets/img/placeholder.svg';

		return (
			<Container>
				<p className="tab-description">
					{ __( 'Style Kits are ready-made configurations of theme styles. When you import a Style Kit, it will be available in the', 'ang' ) } <a href={ addQueryArgs( 'admin.php', { page: 'style-kits' } ) }>{ __( 'Local Style Kits', 'ang' ) }</a> { __( 'page', 'ang' ) }.
					&nbsp;{ __( 'You will then be able apply it globally, or on any page.', 'ang' ) }
					&nbsp;<a href="https://docs.analogwp.com/article/590-style-kit-library" target="_blank" rel="noopener noreferrer">{ __( 'Learn more', 'ang' ) }</a>.
				</p>

				{ this.state.previewing && this.state.previewing.preview && (
					<NotificationConsumer>
						{ ( { add } ) => (
							<Preview
								template={ this.state.previewing }
								onRequestClose={ () => this.resetState() }
								onRequestImport={ () => this.handleImport( this.state.previewing, add, true ) }
								insertText={ __( 'Import Style Kit', 'ang' ) }
								style={ {
									padding: '20px',
									boxSizing: 'border-box',
								} }
							/>
						) }
					</NotificationConsumer>
				) }

				<ChildContainer className="stylekit-list">
					{ this.context.state.styleKits.length > 0 && this.context.state.styleKits.map( ( kit ) => {
						return (
							<li key={ kit.id + '-' + kit.site_id }>
								<Card>
									<CardBody>
										{ kit.is_pro && (
											<span className="pro">{ __( 'Pro', 'ang' ) }</span>
										) }
										<figure>
											<img src={ kit.image || fallbackImage } alt={ kit.title } />

											<div className="preview">
												{ kit.preview && (
													<Button isSecondary
														className="black-transparent"
														onClick={ () => {
															window.scrollTo( 0, 0 );
															this.setState( { previewing: kit } );
														} }
													>
														{ __( 'Preview', 'ang' ) }
													</Button>
												) }

												{ ! isValid( kit.is_pro ) && (
													<a className="ang-promo" href="https://analogwp.com/style-kits-pro/?utm_medium=plugin&utm_source=library&utm_campaign=style+kits+pro" target="_blank">
														<Button isPrimary>{ __( 'Go Pro', 'ang' ) }</Button>
													</a>
												) }

												{ isValid( kit.is_pro ) && (
													<NotificationConsumer>
														{ ( { add } ) => (
															<Button isPrimary
																onClick={ () => this.handleImport( kit, add, true ) }
															>{ __( 'Import', 'ang' ) }</Button>
														) }
													</NotificationConsumer>
												) }
											</div>
										</figure>
									</CardBody>
									<CardFooter>
										<div className="content">
											<h3>{ kit.title }</h3>
										</div>
									</CardFooter>
								</Card>
							</li>
						);
					} ) }
				</ChildContainer>

				{ this.state.modalActive && (
					<Popup
						title={ this.getPopupTitle( this.state.activeKit.title, this.state.importedKit, this.state.hasError ) }
						onRequestClose={ () => this.resetState() }
					>
						{ this.state.hasError && (
							<div className="stylekit-popup-content" >
								<p className="popup-description">
									{ __( 'A Style Kit named ', 'ang' ) + decodeEntities( this.state.activeKit.title ) + __( ' already exists in your website. To import it again please use a different name.', 'ang' ) }
								</p>

								{ this.context.state.installedKits.indexOf( this.state.kitname ) > -1 && (
									<p className="error">{ __( 'Please try a different as a Style Kit with same name already exists.', 'ang' ) }</p>
								) }
								<div className="form-row gap">
									<TextControl
										placeholder={ __( 'Enter a Style Kit Name', 'ang' ) }
										onChange={ val => this.setState( { kitname: val } ) }
										className="kit-name"
									/>

									<NotificationConsumer>
										{ ( { add } ) => (
											<Button isPrimary
												disabled={ ! this.state.kitname || this.context.state.installedKits.indexOf( this.state.kitname ) > -1 }
												style={ {
													marginLeft: '15px',
												} }
												onClick={ () => {
													this.setState( { hasError: false } );

													const kit = {
														...this.state.activeKit,
														title: this.state.kitname,
													};
													this.handleImport( kit, add );
												} }
											>
												{ __( 'Import', 'ang' ) }
											</Button>
										) }
									</NotificationConsumer>
								</div>
							</div>
						) }

						{ ! this.state.hasError && ! this.state.importedKit && <Loader /> }

						{ ! this.state.hasError && this.state.importedKit && (
							<div className="stylekit-popup-content" >
								<p className="popup-description">{ decodeEntities( this.state.activeKit.title ) + __( ' has been successfully imported. You can now find it in the ', 'ang' ) }<a href={ AGWP.globalSkAlwaysEnableURL }>{ __( 'list of your Local Style Kits' ) }</a>.</p>
								<p className="success-buttons gap">
									<a // eslint-disable-line
										onClick={ ( e ) => {
											this.resetState();

											if ( ! Boolean( AGWP.is_settings_page ) ) {
												e.preventDefault();
												window.analogModal.hide();
												analog.redirectToSection();
											}
										} }
									>
										<Button isPrimary>
											{ __( 'Stay on this page', 'ang' ) }
										</Button>
									</a>
									<a // eslint-disable-line
										onClick={ ( e ) => {
											this.resetState();

											if ( ! Boolean( AGWP.is_settings_page ) ) {
												e.preventDefault();
												window.analogModal.hide();
												analog.redirectToSection();
											}
										} }
										{ ...successButtonProps }
									>
										<Button isSecondary>
											{ __( 'View local Style Kits', 'ang' ) }
										</Button>
									</a>
								</p>

								{ ! Boolean( AGWP.is_settings_page ) && <footer className="style-kit-footer" dangerouslySetInnerHTML={ { __html: footer } } /> }
							</div>
						) }

						{ ! this.state.hasError && ! this.state.importedKit && (
							<p>{ __( 'Importing ', 'ang' ) } { decodeEntities( this.state.activeKit.title ) }</p>
						) }
					</Popup>
				) }
			</Container>
		);
	}
}
