import classnames from 'classnames';
import styled from 'styled-components';
import { NotificationConsumer } from '../Notifications';
import AnalogContext from './../AnalogContext';
import { requestLicenseInfo, requestSettingUpdate } from './../api';
import Sidebar from './../Sidebar';

const { TextControl, CheckboxControl, Button, ExternalLink } = wp.components;
const { __ } = wp.i18n;
const { Fragment } = React;

const Container = styled.div`
	display: grid;
	grid-gap: 30px;
	grid-template-columns: 1.5fr 1fr;

	.components-base-control {
		font-family: inherit;
		margin-bottom: 30px;
	}

	.components-checkbox-control__label {
		font-weight: 500;
		color: #999;
	}
	.components-base-control__label {
		color: #060606;
		font-weight: 600;
		font-size: 15px;
		margin-bottom: 10px;
	}

	.components-text-control__input {
		background: #F3F3F3;
		border: 1px solid #e9e9e9;
		padding: 15px;
		box-shadow: none;
	}

	.components-base-control .components-base-control__help {
		margin-top: -4px;
		font-style: normal;
		font-weight: 500;
		color: #999;
	}

	.license-action {
		margin-bottom: 30px;
		margin-top: -10px;
	}

	.license-container {
		position: relative;
		.license-action {
			position: absolute;
			top: 39px;
			right: -1px;
			padding: 13px 24px;
			height: 53px;
		}
	}

	.license-status {
		margin-bottom: 30px;
		font-weight: 500;
		&.valid {
			color: green;
		}
	}
`;

const ChildContainer = styled.div`
	background: #fff;
	padding: 50px 70px;

	.instructions {
		color: #6D6D6D;
		font-size: 15px;
		font-weight: 500;
		margin-bottom: 20px;
	}

	.checkbox {
		label {
			color: #060606;
			font-weight: 600;
			margin-left: 10px;
		}

		.components-base-control__help {
			padding-left: 30px;
			margin-top: 5px;
			color: #6D6D6D;
		}
	}

	.components-external-link {
		padding-left: 30px;
		transform: translateY(-20px);
		display: block;
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
		return (
			<Container>
				<ChildContainer>
					<h2 style={ { fontSize: '25px', marginBottom: '50px' } }>{ __( 'Settings', 'ang' ) }</h2>

					<div className="license-container">
						<TextControl
							label={ __( 'Your License', 'ang' ) }
							help={ __( 'If you own an AnalogPro License, then please enter your license key here.', 'ang' ) }
							value={ settings.ang_license_key || '' }
							onChange={ ( value ) => this.updateSetting( 'ang_license_key', value, true ) }
						/>
						{ settings.ang_license_key && (
							<Fragment>
								{ this.state.licenseMessage &&
									<p
										className={ classnames( 'license-status', this.state.licenseStatus ) }
										dangerouslySetInnerHTML={ { __html: this.state.licenseMessage } }
									/>
								}
								<NotificationConsumer>
									{ ( { add } ) => (
										<Button
											isDefault
											isLarge
											isBusy={ this.state.requesting }
											className="button-accent license-action"
											onClick={ async() => {
												this.setState( { requesting: true } );

												const action = this.state.licenseStatus === 'valid' ? 'deactivate' : 'activate';

												await requestLicenseInfo( action ).then( response => {
													this.setState( {
														licenseStatus: response.status,
														licenseMessage: response.message,
													} );
												} ).catch( () => add( __( 'Connection timeout, please try again.', 'ang' ), 'error' ) );

												this.setState( { requesting: false } );
											} }
										>
											{ ( this.state.licenseStatus === 'valid' ) ? __( 'Deactivate', 'ang' ) : __( 'Activate', 'ang' ) }
										</Button>
									) }
								</NotificationConsumer>
							</Fragment>
						) }
					</div>
					<CheckboxControl
						label={ __( 'Usage Data Tracking', 'ang' ) }
						help={ __( 'Opt-in to our anonymous plugin data collection and to updates. We guarantee no sensitive data is collected.', 'ang' ) }
						checked={ settings.ang_data_collection ? settings.ang_data_collection : false }
						className="checkbox"
						onChange={ ( value ) => this.updateSetting( 'ang_data_collection', value ) }
					/>

					<div className="global-settings">
						<p className="instructions">{ __( 'These settings affect the way you import Analog templates on this site, and they apply globally.', 'ang' ) }</p>

						<CheckboxControl
							label={ __( 'Remove Styling from typographic elements', 'ang' ) }
							help={ __( 'This setting will remove any values that have been manually added in the templates. Existing templates are not affected.', 'ang' ) }
							checked={ settings.ang_remove_typography || false }
							className="checkbox"
							onChange={ ( isChecked ) => this.updateSetting( 'ang_remove_typography', isChecked ) }
						/>

						<ExternalLink href="https://docs.analogwp.com/article/544-remove-styling-from-typographic-elements">{ __( 'More Info', 'ang' ) }</ExternalLink>
					</div>
				</ChildContainer>

				<Sidebar />
			</Container>
		);
	}
}
