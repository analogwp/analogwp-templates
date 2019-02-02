import styled from 'styled-components';
import { AnalogContext } from './../AnalogContext';
import { getSettings, requestSettingUpdate } from './../api';
import Sidebar from './../Sidebar';
const { TextControl, CheckboxControl } = wp.components;
const { __ } = wp.i18n;

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
