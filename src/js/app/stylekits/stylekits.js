import classnames from 'classnames';
import styled from 'styled-components';
import AnalogContext from '../AnalogContext';
import { requestStyleKitsList } from '../api';
const { decodeEntities } = wp.htmlEntities;

const { __, sprintf } = wp.i18n;

const Container = styled.section`
	
`;

const ChildContainer = styled.ul`
	display: grid;
    grid-template-columns: repeat(auto-fit,minmax(280px,280px));
    grid-gap: 25px;
    margin: 0;
    padding: 0;
`;

export default class Settings extends React.Component {
	static contextType = AnalogContext;

	constructor() {
		super( ...arguments );

		this.state = {
			kits: [],
		};
	}

	async componentDidMount() {
		const kits = await requestStyleKitsList();

		this.setState( {
			kits,
		} );
	}

	render() {
		return (
			<Container>
				<p>
					{ __( 'Below are the available Style Kits. When you choose to import a Style Kit, it will be added to your available', 'ang' ) } <a href="/wp-admin/edit.php?post_type=ang_tokens">{ __( 'Style Kits list', 'ang' ) }</a>.
				</p>
				<ChildContainer>
					{ this.state.kits.length > 0 && this.state.kits.map( ( kit ) => (
						<li key={ kit.id }>
							<h3>{ kit.title }</h3>
							<img src="{ kit.image }" alt="{ kit.title }" />
						</li>
					) ) }
				</ChildContainer>
			</Container>
		);
	}
}
