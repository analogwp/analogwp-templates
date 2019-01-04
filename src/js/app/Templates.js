import { AllHtmlEntities } from "html-entities";

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
			<ul>
				{ this.state.count >= 1 && this.state.templates.map( (template) => (
					<li data-type={ template.type }>
						<h3>{ AllHtmlEntities.decode(template.title) }</h3>
						<p>{ template.category }</p>
					</li>
				) ) }
			</ul>
		)
	}
}
