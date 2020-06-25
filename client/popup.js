import { default as styled, keyframes } from 'styled-components';
import Close from './icons/close';

const { __ } = wp.i18n;
const { Card, CardBody, CardDivider, CardHeader } = wp.components;

const animate = keyframes`
  from {
    margin-top: 50px;
  }

  to {
    margin-top: 0;
  }
`;

const Container = styled.div`
	font-size: 15px;
	color: #000222;
	position: fixed;
    top: 0;
    left: 0;
    background: rgba(0,0,0,0.62);
    width: 100%;
    height: 100%;
    z-index: 10000;
	display: flex;
    align-items: center;
    justify-content: center;

	.inner {
		background: #fff;
		overflow: visible;
		width: 600px;
		max-height: 80vh;
		animation-fill-mode: forwards;
		animation: ${ animate } 0.1s ease-out;
	}

	a {
		text-decoration: none;
		font-weight: 500;
	}
`;

const Header = styled.div`
	
	.header-center & {
		justify-content: center;
	}
	
	svg {
		fill: #000;
		margin-left: 5px;
	}

	button {
		font-size: 12px !important;
	}
`;

const Content = styled.div`
	h2 {
		font-size: 25px;
	}

	hr {
		border-top: 2px solid #dadada;
		border-bottom: none;
		margin: 2em 0;
	}

	.form-row {
		display: flex;
		align-items: baseline;
	}

`;

const Popup = ( props ) => {
	const { title, onRequestClose, children, ...rest } = props;
	return (
		<Container { ...rest }>
			<div className="inner">
				<Card>
					<CardHeader>
						<Header className="inner-popup-header">
							<h1>{ title }</h1>
							{ onRequestClose && (
								<button className="button-plain" onClick={ () => onRequestClose() }>
									<Close />
								</button>
							) }
						</Header>
					</CardHeader>
					<CardDivider>&nbsp;</CardDivider>
					<CardBody>
						<Content className="inner-popup-content">
							{ children }
						</Content>
					</CardBody>
				</Card>
			</div>
		</Container>
	);
};

export default Popup;
