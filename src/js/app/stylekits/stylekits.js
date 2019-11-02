import styled from 'styled-components';
import AnalogContext from '../AnalogContext';
import { requestStyleKitData } from '../api';
import Loader from '../icons/loader';
import { NotificationConsumer } from '../Notifications';
import Popup from '../popup';

const { TextControl, Button } = wp.components;

const { decodeEntities } = wp.htmlEntities;
const { __, sprintf } = wp.i18n;
const { addQueryArgs } = wp.url;

const Container = styled.section`
	> p {
		font-size: 16px;
		line-height: 24px;
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
`;

const ChildContainer = styled.ul`
	display: grid;
    grid-template-columns: repeat(auto-fit,minmax(280px,280px));
    grid-gap: 25px;
    margin: 40px 0 0;
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
		return (
			<Container>
				<p>
					{ __( 'These are some Style Kit presets that you can use as a starting point. Once you import a Style Kit, it will be added to your', 'ang' ) } <a href={ addQueryArgs( 'edit.php', { post_type: 'ang_tokens' } ) }>{ __( 'Style Kits list', 'ang' ) }</a>.
					&nbsp;{ __( 'You will then be able to apply on any page.', 'ang' ) }
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
						onRequestClose={ () => this.resetState() }
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
								<p>{ __( 'The Style Kit has been imported to your library.', 'ang' ) }</p>
								<p>{ sprintf( __( '%s has been imported and is now available in the Style Kits dropdown', 'ang' ), this.state.activeKit.title ) }</p>
								<p>
									<a
										className="ang-button"
										target="_blank"
										rel="noopener noreferrer"
										href={ addQueryArgs( 'edit.php', { post_type: 'ang_tokens' } ) }
									>{ __( 'Manage Style Kits', 'ang' ) }</a>
								</p>
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
