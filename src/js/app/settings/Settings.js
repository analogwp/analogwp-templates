import classnames from 'classnames';
import styled from 'styled-components';
import { AnalogContext } from './../AnalogContext';
import { getSettings, requestLicenseInfo, requestSettingUpdate } from './../api';
import Sidebar from './../Sidebar';
const { TextControl, CheckboxControl, Button } = wp.components;
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
`;

const ChildContainer = styled.div`
	background: #fff;
	padding: 50px 70px;
`;

export default class Settings extends React.Component {
	static contextType = AnalogContext;

	constructor() {
		super( ...arguments );

		this.state = {
			settings: [],
			licenseStatus: AGWP.license.status,
			licenseMessage: AGWP.license.message || null,
			requesting: false,
		};
	}

	componentDidMount() {
		getSettings().then( settings => this.setState( { settings } ) );
	}

	updateSetting( key, val ) {
		const settings = this.state.settings;
		const updatedSetting = {
			...settings,
			[ key ]: val,
		};

		this.setState( {
			settings: updatedSetting,
		} );

		setTimeout( () => {
			requestSettingUpdate( key, val ).catch( () => {
				console.error( 'An error occured updating settings' ); // eslint-disable-line
			} );
		}, 1000 );
	}

	render() {
		return (
			<Container>
				<ChildContainer>
					<TextControl
						label={ __( 'Your License', 'ang' ) }
						help={ __( 'If you own an AnalogPro License, then please enter your license key here.', 'ang' ) }
						value={ this.state.settings.ang_license_key || '' }
						onChange={ ( value ) => this.updateSetting( 'ang_license_key', value ) }
					/>
					{ this.state.settings.ang_license_key && (
						<Fragment>
							{ this.state.licenseMessage &&
								<p
									className={ classnames( 'license-status', this.state.licenseStatus ) }
								>{ this.state.licenseMessage }</p>
							}
							<Button
								isDefault
								isLarge
								isBusy={ this.state.requesting }
								className="license-action"
								onClick={ async() => {
									this.setState( { requesting: true } );
									const action = this.state.licenseStatus === 'valid' ? 'deactivate' : 'activate';
									await requestLicenseInfo( action ).then( response => {
										this.setState( {
											licenseStatus: response.status,
											licenseMessage: response.message,
										} );
									} ).catch( error => {
										console.error( error, action ); // eslint-disable-line
									} );

									this.setState( { requesting: false } );
								} }
							>
								{ ( this.state.licenseStatus === 'valid' ) ? __( 'Deactivate License', 'ang' ) : __( 'Activate License', 'ang' ) }
							</Button>
						</Fragment>
					) }
					<CheckboxControl
						label={ __( 'Opt-in to our anonymous plugin data collection and to updates. We guarantee no sensitive data is collected.', 'ang' ) }
						checked={ this.state.settings.ang_data_collection ? this.state.settings.ang_data_collection : false }
						onChange={ ( value ) => this.updateSetting( 'ang_data_collection', value ) }
					/>
				</ChildContainer>

				<Sidebar />
			</Container>
		);
	}
}
