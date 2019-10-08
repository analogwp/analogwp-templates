/**
 * Component for Template kit collections.
 */
import styled from 'styled-components';
const { __ } = wp.i18n;

const List = styled.ul`
	margin: 0;
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(280px,280px));
    grid-gap: 25px;
	color: #000;
	li {
		background: #fff;
		position: relative;
		border-radius: 4px;
		position: relative;
	}
	figure {
		margin: 0;
		position: relative;
		min-height: 100px;
		&:hover .actions {
			opacity: 1;
		}
	}
	img {
		width: 100%;
		height: auto;
		border-top-left-radius: 4px;
		border-top-right-radius: 4px;
	}
	h3 {
		font-size: 13px;
		padding: 13px 20px;
		text-transform: capitalize;
		margin: 0;
		font-weight: bold;
	}
	span {
		background: var(--ang-accent);
		width: 35px;
		height: 28px;
		position: absolute;
		top: -14px;
		right: -17px;
		font-weight: 700;
		font-size: 15px;
		border-radius: 4px;
		display: inline-flex;
		justify-content: center;
		align-items: center;
		color: #fff;
		z-index: 100;
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
		background: rgba(0, 0, 0, 0.7);
		top: 0;
		left: 0;
		z-index: 100;
		transition: all 200ms;
		border-top-left-radius: 4px;
		border-top-right-radius: 4px;
	}
`;

export default class Collection extends React.Component {
	constructor() {
		super( ...arguments );

		this.state = {
			isPopupOpen: false,
		};

		this.getCollectionCount = this.getCollectionCount.bind( this );
		this.getGroupedCollection = this.getGroupedCollection.bind( this );
	}

	getGroupedCollection() {
		if ( ! this.props.templates ) {
			return;
		}

		return this.props.templates.reduce( ( accumulator, currentValue ) => {
			if ( ! accumulator[ currentValue.site_id ] ) {
				accumulator[ currentValue.site_id ] = [];
			}

			accumulator[ currentValue.site_id ].push( currentValue );

			return accumulator;
		}, {} );
	}

	getCollectionCount( id ) {
		const collection = this.getGroupedCollection();
		let count = false;

		if ( collection[ id ] ) {
			count = Object.keys( collection[ id ] ).length;
		}

		return count;
	}

	render() {
		return (
			<div>
				<List>
					{ ! this.state.isPopupOpen && this.props.kits.map( ( kit ) => {
						if ( ! this.getCollectionCount( kit.site_id ) ) {
							return;
						}

						return (
							<li key={ kit.site_id }>
								{ /* TODO: Remove placeholder image */ }
								<figure>
									<img src={ AGWP.pluginURL + 'assets/img/placeholder.svg' } loading="lazy" alt={ kit.title } />
									<div className="actions">
										<button className="ang-button" onClick={ () => {
											this.setState( { isPopupOpen: true } );
										} }>
											{ __( 'View Kit', 'ang' ) }
										</button>
									</div>
								</figure>
								<h3>{ kit.title }</h3>
								<span>{ this.getCollectionCount( kit.site_id ) }</span>
							</li>
						);
					} ) }
				</List>
			</div>
		);
	}
}
