import styled from 'styled-components';
const { decodeEntities } = wp.htmlEntities;

const TemplatesList = styled.ul`
	padding: 20px;
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
		align-items: center;
		justify-content: center;
		background: rgba(0,0,0,0.7);
		top: 0;
		left: 0;
		z-index: 100;
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
	}

	componentDidMount() {
		let callbackURL = 'https://analogwp.com/wp-json/analogwp/v1/templates';

		fetch( callbackURL )
			.then( ( response ) => response.json() )
			.then( ( response ) => {
				this.setState({
					templates: response.templates,
					count: response.total_templates
				});
			} );
	}

	render() {
		return (
			<TemplatesList>
				{ this.state.count >= 1 && this.state.templates.map( (template) => (
					<li data-type={ template.type }>
						<figure>
							<img src={ template.thumb } />
							<div className="actions">
								<StyledButton link={ template.previewURL }>Preview</StyledButton>
							</div>
						</figure>
						<h3>{ decodeEntities(template.title) }</h3>
						<p>{ template.category }</p>
					</li>
				) ) }
			</TemplatesList>
		)
	}
}
