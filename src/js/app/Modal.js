import styled from 'styled-components';
const { Tooltip, FocusableIframe } = wp.components;
const { __ } = wp.i18n;

const Container = styled.div`
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	overflow: scroll;
	background: #e3e3e3;
	z-index: 999;

	iframe {
		width: 100%;
		height: 100%;
	}

	.frame-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 25px;

		a {
			margin-left: auto;
			margin-right: 20px;
			color: currentColor;
			text-decoration: none;
		}

		.dashicons {
			font-size: 25px;
		}
	}

	.button--plain {
		-webkit-appearance: none;
		-moz-appearance: none;
		padding: 0;
		margin: 0;
		font-size: 12px;
		font-weight: bold;
		color: #060606;
		background: transparent;
		border: none;
		outline: 0;
		cursor: pointer;
	}

	.button--accent {
		font-size: 12px;
		font-weight: bold;
		color: #fff;
		border-radius: 0;
		border: none;
		background: #ff7865;
		outline: 0;
		box-shadow: none;
		padding: 15px 30px;
		cursor: pointer;
	}
`;

const Modal = props => (
	<Container>
		<div className="frame-header">
			<button className="button--plain" onClick={ props.onRequestClose }>
				Back to Library
			</button>
			<Tooltip text={ __( 'Open Preview in New Tab', 'ang' ) }>
				<a href={ props.template.url } rel="noopener noreferrer" target="_blank">
					<span className="dashicons dashicons-external" />
				</a>
			</Tooltip>
			<button className="button--accent" onClick={ props.onRequestImport }>
				{ __( 'Insert Layout', 'ang' ) }
			</button>
		</div>
		<FocusableIframe src={ props.template.url } />
	</Container>
);

export default Modal;
