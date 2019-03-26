import classnames from 'classnames';
import styled from 'styled-components';
import { isObject } from 'util';
import { NotificationConsumer } from '../Notifications';
import AnalogContext from './../AnalogContext';
import { requestLicenseInfo, requestSettingUpdate } from './../api';
import { Theme } from './../contexts/ThemeContext';
import Sidebar from './../Sidebar';
import { hasProTemplates } from './../utils';

const { TextControl, CheckboxControl, Button, ExternalLink } = wp.components;
const { __ } = wp.i18n;
const { Fragment } = React;

const Container = styled.div`
	display: grid;
	grid-gap: 30px;
	grid-template-columns: 1.5fr 1fr;

	.components-base-control {
		font-family: inherit;
	}

	.components-checkbox-control__label {
		font-weight: 500;
		color: #999;
	}

	.components-text-control__input {
		background: #F3F3F3;
		border: 1px solid #e9e9e9;
		padding: 15px;
		box-shadow: none;
	}

	.components-base-control .components-base-control__help {
		margin-top: 0px;
		font-style: normal;
	}

	.license-status {
		margin-bottom: 0;
		font-weight: 500;
		font-size: 14.22px;
		margin-top: 25px;
		&.valid {
			color: #61A670;
		}
	}
`;

const ChildContainer = styled.div`
	.instructions {
		color: #6D6D6D;
		font-size: 15px;
		font-weight: 500;
		margin-bottom: 20px;
	}

	.checkbox {
		label {
			color: #060606;
		}

		.components-base-control__help {
			padding-left: 37px;
			margin-top: 5px;
			color: #888;
			font-size: 14.22px;
		}
	}

	.global-settings {
		.components-external-link {
			transform: translateX(37px);
			margin-top: 20px;
			display: inline-block;
		}
	}
`;

const Field = styled.section`
	background: #fff;
	padding: 75px 85px;
	border-radius: 4px;
	margin-bottom: 40px;

	.heading,
	.components-base-control__label {
		color: #060606;
		font-weight: 700;
		font-size: 20.25px;
		margin-top: 0;
		margin-bottom: 30px;
	}

	.instruction {
		font-size: 16px;
		color: #060606;
	}

	.license-action {
		height: auto;
		width: 175px;
		box-shadow: none !important;
		outline: 0 !important;
		&:hover {
			background: ${ Theme.accent };
			color: #fff;
		}
	}

	.fieldgroup {
		display: flex;

		> .components-base-control {
			flex-basis: 60%;
			margin: 0;

			.components-text-control__input,
			.components-base-control__field {
				margin: 0;
			}
		}
		> button {
			margin-left: 2%;
			flex-basis: 38%;
		}
	}

	.checkbox {
		.components-base-control__field {
			display: flex;
			align-items: top;
		}
		input[type="checkbox"] {
			min-width: 22px;
			margin-right: 15px;
			transform: translateY(6px);
		}
	}

	.components-checkbox-control__label {
		font-size: 16px;
		font-weight: normal;
		line-height: 1.7;
	}
`;

export default class Settings extends React.Component {
	static contextType = AnalogContext;

	constructor() {
		super( ...arguments );

		this.state = {
			licenseStatus: AGWP.license.status,
			licenseMessage: AGWP.license.message || null,
			requesting: false,
		};
	}

	updateSetting( key, val, avoidRequest = false ) {
		const settings = this.context.state.settings;
		const updatedSetting = {
			...settings,
			[ key ]: val,
		};

		// Update <App /> settings.
		this.context.dispatch( { settings: updatedSetting } );

		// Avoid API request for saving data, instead save it in App state only.
		if ( ! avoidRequest ) {
			requestSettingUpdate( key, val ).catch( ( e ) => {
				console.error( 'An error occured updating settings', e ); // eslint-disable-line
			} );
		}
	}

	render() {
		const settings = this.context.state.settings;
		const ButtonText = ( this.state.licenseStatus === 'valid' ) ? __( 'Deactivate', 'ang' ) : __( 'Activate', 'ang' );

		return (
			<Container>
				<ChildContainer>
					<h2 style={ { fontSize: '25px', marginBottom: '50px' } }>{ __( 'Plugin Settings', 'ang' ) }</h2>

					{ hasProTemplates( this.context.state.templates ) && (
						<Field>
							<div className="license-container">
								<h3 className="heading">{ __( 'Pro License', 'ang' ) }</h3>
								<p className="instruction">
									{ __( 'If you own an AnalogPro License, then please enter your license key here.', 'ang' ) }
								</p>

								<div className="fieldgroup">
									<TextControl
										label={ '' }
										value={ settings.ang_license_key || '' }
										onChange={ ( value ) => this.updateSetting( 'ang_license_key', value ) }
									/>

									{ settings.ang_license_key && (
										<NotificationConsumer>
											{ ( { add } ) => (
												<Button
													isDefault
													isLarge
													className="ang-button license-action"
													onClick={ async() => {
														this.setState( { requesting: true } );

														const action = this.state.licenseStatus === 'valid' ? 'deactivate' : 'activate';

														await requestLicenseInfo( action ).then( response => {
															if ( isObject( response.errors ) ) {
																Object.entries( response.errors ).map( ( err ) => {
																	add( err[ 1 ], 'error' );
																} );
															} else {
																this.setState( {
																	licenseStatus: response.status,
																	licenseMessage: response.message,
																} );
															}
														} ).catch( () => {
															add( __( 'Connection timeout, please try again.', 'ang' ), 'error' );
														} );

														this.setState( { requesting: false } );
													} }
												>
													{ this.state.requesting ? __( 'Processing...' ) : ButtonText }
												</Button>
											) }
										</NotificationConsumer>
									) }
								</div>

								{ settings.ang_license_key && (
									<Fragment>
										{ this.state.licenseMessage &&
										<p
											className={ classnames( 'license-status', this.state.licenseStatus ) }
											dangerouslySetInnerHTML={ { __html: this.state.licenseMessage } }
										/>
										}
									</Fragment>
								) }

								{ ! settings.ang_license_key && (
									<p>
										{ __( 'If you do not have a license key, you can get one from' ) }
										{ ' ' } <ExternalLink className="ang-link" href="https://analogwp.com/">AnalogWP</ExternalLink>
									</p>
								) }
							</div>
						</Field>
					) }

					<Field className="global-settings">
						<h3 className="heading">{ __( 'Usage Data Tracking', 'ang' ) }</h3>

						<CheckboxControl
							label={ __( 'Opt-in to our anonymous plugin data collection and to updates. We guarantee no sensitive data is collected.', 'ang' ) }
							checked={ settings.ang_data_collection ? settings.ang_data_collection : false }
							className="checkbox"
							onChange={ ( value ) => this.updateSetting( 'ang_data_collection', value ) }
						/>

						<ExternalLink className="ang-link" href="https://docs.analogwp.com/article/547-what-data-is-tracked-by-the-plugin">{ __( 'More Info', 'ang' ) }</ExternalLink>
					</Field>

					<Field className="global-settings">
						<h3 className="heading">{ __( 'Template Settings', 'ang' ) }</h3>
						<CheckboxControl
							label={ __( 'Remove Styling from typographic elements', 'ang' ) }
							help={ __( 'This setting will remove any values that have been manually added in the templates. Existing templates are not affected.', 'ang' ) }
							checked={ settings.ang_remove_typography || false }
							className="checkbox"
							onChange={ ( isChecked ) => this.updateSetting( 'ang_remove_typography', isChecked ) }
						/>

						<ExternalLink className="ang-link" href="https://docs.analogwp.com/article/544-remove-styling-from-typographic-elements">{ __( 'More Info', 'ang' ) }</ExternalLink>
					</Field>
				</ChildContainer>

				<Sidebar />
			</Container>
		);
	}
}
