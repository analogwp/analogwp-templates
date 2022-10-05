import Close from './icons/close';

const { Card, CardBody, CardDivider, CardHeader } = wp.components;

const Popup = ( props ) => {
	const { title, onRequestClose, children, ...rest } = props;
	return (
		<div className="popup-container" { ...rest }>
			<div className="inner">
				<Card>
					<CardHeader>
						<div className="inner-popup-header">
							<h1>{ title }</h1>
							{ onRequestClose && (
								<button className="button-plain" onClick={ () => onRequestClose() }>
									<Close />
								</button>
							) }
						</div>
					</CardHeader>
					<CardDivider />
					<CardBody>
						<div className="inner-popup-content">
							{ children }
						</div>
					</CardBody>
				</Card>
			</div>
		</div>
	);
};

export default Popup;
