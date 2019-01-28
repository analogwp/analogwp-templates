import styled from 'styled-components';
import { AnalogContext } from './../AnalogContext';
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

	render() {
		return (
			<Container>
				<ChildContainer>
					<TextControl
						label={ __( 'Your License', 'ang' ) }
						help={ __( 'If you own an AnalogPro License, then please enter your license key here.', 'ang' ) }
					/>
					<CheckboxControl
						label={ __( 'Opt-in to our anonymous plugin data collection and to updates. We guarantee no sensitive data is collected.', 'ang' ) }
					/>
				</ChildContainer>

				<Sidebar />
			</Container>
		);
	}
}
