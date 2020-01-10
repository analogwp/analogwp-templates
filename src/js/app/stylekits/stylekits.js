import styled from 'styled-components';
import AnalogContext from '../AnalogContext';
import { requestStyleKitData } from '../api';
import Loader from '../icons/loader';
import { NotificationConsumer } from '../Notifications';
import Popup from '../popup';

const { TextControl, Button, Dashicon } = wp.components;

const { decodeEntities } = wp.htmlEntities;
const { __, sprintf } = wp.i18n;
const { addQueryArgs } = wp.url;

const Container = styled.section`
	> p {
		font-size: 16px;
		line-height: 24px;
	}
	> .tab-description {
		color: #444;
		position: absolute;
		top: 0;
		background: #fff;
		left: 0;
		right: 0;
		margin: 0 !important;
		padding: 20px;
	}
	.title {
		padding: 15px;
		display: flex;
		justify-content: space-between;
		align-items: center;
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
    	color: var(--ang-accent);
    }

	footer {
		padding: 20px 35px;
		font-size: 12px;
		color: #4A5157;
		background: #fff;
		margin: 30px -35px -20px -35px;
		border-radius: 3px;

		a {
			color: #5c32b6;
			text-decoration: underline;
		}
	}
`;

const ChildContainer = styled.ul`
	display: grid;
    grid-template-columns: repeat(auto-fit,minmax(280px,280px));
    grid-gap: 25px;
    margin: 75px 0 0;
    padding: 0;

    > li {
    	border-radius: 4px;
    	overflow: hidden;
    	background: #fff;
		.ang-button {
			font-size: 12px;
			line-height: 18px;
			padding: 6px 12px;
			text-transform: uppercase;
			&[disabled] {
				cursor: not-allowed;
				background: #e3e3e3;
				color: #747474;
			}
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
};

const footer = sprintf(
	__( '%s in WordPress dashboard.', 'ang' ),
	sprintf(
		`<a href="${ addQueryArgs( 'edit.php', { post_type: 'ang_tokens' } ) }" target="_blank" rel="noopener noreferer">%s</a>`,
		__( 'Manage your Style Kits', 'ang' )
	)
);

export default class StyleKits extends React.Component {
	static contextType = AnalogContext;

	constructor() {
		super( ...arguments );

		this.state = {
			installedKits: AGWP.installed_kits,
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
			const kitExists = this.state.installedKits.indexOf( this.state.kitname || kit.title ) > -1;
			if ( kitExists ) {
				this.setState( { hasError: true } );
				return;
			}
		}

		requestStyleKitData( kit )
			.then( response => {
				const kits = [ ...this.state.installedKits ];
				kits.push( kit.title );
				this.setState( {
					importedKit: true,
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
			} );
	}

	render() {
		let successButtonProps = {
			target: '_blank',
			rel: 'noopener noreferrer',
			href: addQueryArgs( 'edit.php', { post_type: 'ang_tokens' } ),
		};

		if ( ! Boolean( AGWP.is_settings_page ) ) {
			successButtonProps = false;
		}

		return (
			<Container>
				<p className="tab-description">
					{ __( 'These are some Style Kit presets that you can use as a starting point. Once you import a Style Kit, it will be added to your', 'ang' ) } <a href={ addQueryArgs( 'edit.php', { post_type: 'ang_tokens' } ) }>{ __( 'Style Kits list', 'ang' ) }</a>.
					&nbsp;{ __( 'You will then be able to apply it on any Elementor page.', 'ang' ) }
					&nbsp;<a href="https://docs.analogwp.com/article/590-style-kit-library" target="_blank" rel="noopener noreferrer">{ __( 'Learn more', 'ang' ) }</a>.
				</p>
				<ChildContainer>
					{ this.context.state.styleKits.length > 0 && this.context.state.styleKits.map( ( kit ) => {
						return (
							<li key={ kit.id }>
								<img src={ kit.image } alt={ kit.title } />
								<div className="title">
									<h3>{ kit.title }</h3>
									<NotificationConsumer>
										{ ( { add } ) => (
											<button
												onClick={ () => this.handleImport( kit, add, true ) }
												className="ang-button"
											>{ __( 'Import', 'ang' ) }</button>
										) }
									</NotificationConsumer>
								</div>
							</li>
						);
					} ) }
				</ChildContainer>

				{ this.state.modalActive && (
					<Popup
						title={ decodeEntities( this.state.activeKit.title ) }
						onRequestClose={ () => this.setState( { modalActive: false } ) }
					>
						{ this.state.hasError && (
							<div>
								<p style={ { textAlign: 'left' } }>
									{ __( 'A Style Kit already exists with the same name. To import it again please enter a new name below:', 'ang' ) }
								</p>

								{ this.state.installedKits.indexOf( this.state.kitname ) > -1 && (
									<p className="error">{ __( 'Please try a different as a Style Kit with same name already exists.', 'ang' ) }</p>
								) }
								<div className="form-row">
									<TextControl
										placeholder={ __( 'Enter a Style Kit Name', 'ang' ) }
										style={ { maxWidth: '60%' } }
										onChange={ val => this.setState( { kitname: val } ) }
									/>

									<NotificationConsumer>
										{ ( { add } ) => (
											<Button
												className="ang-button"
												disabled={ ! this.state.kitname || this.state.installedKits.indexOf( this.state.kitname ) > -1 }
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
							<React.Fragment>
								<p>{ __( 'The Style Kit has been imported and is now available in the list of the available Style Kits.', 'ang' ) }</p>
								<p>
									<a // eslint-disable-line
										className="ang-button"
										onClick={ ( e ) => {
											this.resetState();

											if ( ! Boolean( AGWP.is_settings_page ) ) {
												e.preventDefault();
												window.analogModal.hide();
												analog.redirectToSection();
											}
										} }
										{ ...successButtonProps }
									>{ __( 'Ok, thanks', 'ang' ) } <Dashicon icon="yes" /></a>
								</p>

								{ ! Boolean( AGWP.is_settings_page ) && <footer dangerouslySetInnerHTML={ { __html: footer } } /> }
							</React.Fragment>
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
