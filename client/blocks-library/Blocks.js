import styled from 'styled-components';
import BlocksContext from './BlocksContext';
import BlocksList from './BlocksList';
import Empty from '../helpers/Empty';
const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { TextControl } = wp.components;

const Container = styled.div`
	.components-base-control {
		margin-bottom: 30px;
	}
`;

class Blocks extends React.Component {
	constructor() {
		super( ...arguments );
	}

	render() {
		return (
			<Fragment>
				<Container>
					<TextControl
						placeholder={ __( 'Search blocks', 'ang' )}
						value={ this.searchInput }
						onChange={ ( value ) =>
							this.context.handleSearch( value )
						}
					/>

					{ this.context.state.blocks.length < 1 && (
						<Empty text={ __( 'No blocks found.', 'ang' ) }/>
					) }

					<BlocksList />

				</Container>
			</Fragment>
		);
	}
}

Blocks.contextType = BlocksContext;

export default Blocks;
