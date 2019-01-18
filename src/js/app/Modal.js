import styled from 'styled-components';

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
	}

	.button--plain {
		-webkit-appearance: none;
		-moz-appearance: none;
		padding: 0;
		margin: 0;
		text-transform: uppercase;
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
		text-transform: uppercase;
		font-weight: bold;
		color: #fff;
		border-radius: 0;
		border: none;
		background: #FF7865;
		outline: 0;
		box-shadow: none;
		padding: 15px 30px;
		cursor: pointer;
	}
`;

const Modal = (props) => (
	<Container>
		<div className="frame-header">
			<button className="button--plain" onClick={props.onRequestClose}>Back to Library</button>
			<button className="button--accent" onClick={props.onRequestImport}>Insert Layout</button>
		</div>
		<iframe src={props.template.url} frameBorder="0"></iframe>
	</Container>
);

export default Modal;
