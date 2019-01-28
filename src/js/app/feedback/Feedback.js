import styled from 'styled-components';
import { AnalogContext } from './../AnalogContext';
import Sidebar from './../Sidebar';
const { __ } = wp.i18n;
const { TextControl, TextareaControl, CheckboxControl, Button, ExternalLink } = wp.components;

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

	.components-textarea-control__input,
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

	h2 {
		font-size: 25px;
		font-weight: 600;
		color: #23282C;
	}
	> p {
		font-weight: 500;
		color: #6D6D6D;
		font-size: 15px;
		margin-bottom: 50px;
	}

	.components-button {
		padding: 15px;
		background: #FF7865;
		color: #fff;
		font-weight: 600;
	}

	p:last-of-type a {
		color: #23282C;
		text-decoration: none;
	}
`;

export default class Settings extends React.Component {
	static contextType = AnalogContext;

	render() {
		return (
			<Container>
				<ChildContainer>
					<h2>{ __( 'Send your feedback through this form', 'ang' ) }</h2>
					<p>{ __( 'Feel free to submit your feedback on the plugin through this form. All messages are going through our support system, your feedback matters.', 'ang' ) }</p>

					<TextControl label={ __( 'Email Address', 'ang' ) } />

					<TextareaControl label={ __( 'Feedback', 'ang' ) } />

					<CheckboxControl
						label={ __( 'Sign me up to newsletter, I want to know when new layouts are added!', 'ang' ) }
					/>
					<Button>{ __( 'Send Feedback', 'ang' ) }</Button>

					<p>
						{ __( 'By using this form you agree to our', 'ang' ) }{ ' ' }
						<ExternalLink href="https://analogwp.com/privacy-policy/">{ __( 'privacy policy', 'ang' ) }</ExternalLink>
					</p>
				</ChildContainer>
				<Sidebar />
			</Container>
		);
	}
}
