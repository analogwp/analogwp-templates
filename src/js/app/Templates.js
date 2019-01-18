import styled from 'styled-components';
import Modal from './Modal';
const { decodeEntities } = wp.htmlEntities;

const TemplatesList = styled.ul`
	margin: 0;
	display: grid;
	grid-template-columns: repeat(4, 1fr);
	grid-gap: 20px;
	text-transform: uppercase;
	color: #000;

	p {
		color: #939393;
		letter-spacing: 1px;
		font-size: 10px;
		margin: 0;
		font-weight: 500;
	}

	h3 {
		font-size: 15px;
		margin: 10px 0 5px;
	}

	img {
		width: 100%;
		height: auto;
	}

	figure {
		margin: 0;
		position: relative;

		&:hover .actions {
			opacity: 1;
		}
	}

	.actions {
		opacity: 0;
		position: absolute;
		width: 100%;
		height: 100%;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		background: rgba(0,0,0,0.7);
		top: 0;
		left: 0;
		z-index: 100;
	}

	button {
		display: block;
		border: none;
		outline: 0;
		font-size: 12px;
		text-transform: uppercase;
		padding: 10px;
		font-weight: bold;
		background: #FF7865;
		width: 100px;
		color: #fff;
		cursor: pointer;

		+ button {
			margin-top: 10px;
		}
	}
`;

const StyledButton = styled.button`
	text-transform: uppercase;
	padding: 5px 10px;
`;

export default class Templates extends React.Component {
	state = {
		templates: [],
		count: null,
		isOpen: false,
		modalContent: 'Dummy Content',
	}

	componentDidMount() {
		let callbackURL = 'https://analogwp.com/wp-json/analogwp/v1/templates/';

		fetch( callbackURL )
			.then( ( response ) => response.json() )
			.then( ( response ) => {
				this.setState({
					templates: response.templates,
					count: response.count
				});
			} );
	}

	setModalContent = (template) => {
		this.setState({
			isOpen: ! this.state.isOpen,
			template: template,
		});
	}

	importLayout = (template) => {
		if ( ! template ) {
			template = this.state.template;
		}
		const speak = new SpeechSynthesisUtterance('This action should initiate import process in future.');
		speechSynthesis.speak(speak);
	}

	render() {
		return (
			<div style={{
				position: 'relative',
				minHeight: '80vh',
			}}>
				{ this.state.isOpen && <Modal
					template={ this.state.template }
					onRequestClose={ () => this.setState( { isOpen: false } ) }
					onRequestImport={ () => this.importLayout() }
					/> }
				<TemplatesList>
					{ this.state.count >= 1 && this.state.templates.map( (template) => (
						<li key={ template.id }>
							<figure>
								{ template.thumbnail && <img src={ template.thumbnail } /> }
								<div className="actions">
									<StyledButton onClick={ () => this.setModalContent(template) }>Preview</StyledButton>
									<StyledButton onClick={ () => this.importLayout(template) }>Import</StyledButton>
								</div>
							</figure>
							<h3>{ decodeEntities(template.title) }</h3>
						</li>
					) ) }
				</TemplatesList>
			</div>
		)
	}
}
