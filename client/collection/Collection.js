/**
 * Component for Template kit collections.
 */
import styled from 'styled-components';
import AnalogContext from '../AnalogContext';
import Template from '../Template';
const { __ } = wp.i18n;
const { Button, Card, CardBody, CardDivider, CardFooter } = wp.components;

const List = styled.ul`
	margin: 0;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
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
		padding: 20px;
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
		padding: 20px 20px;
		text-transform: capitalize;
		margin: 0;
		font-weight: bold;
	}
	span {
		width: 35px;
		height: 28px;
		position: absolute;
		bottom: 26px;
		right: 5px;
		font-weight: 700;
		font-size: 15px;
		border-radius: 4px;
		display: inline-flex;
		justify-content: center;
		align-items: center;
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

		button.is-large {
			font-size: 24px;
			height: 48px;
		}
	}
`;

export default class Collection extends React.Component {
	constructor() {
		super( ...arguments );

		this.getCollectionCount = this.getCollectionCount.bind( this );
		this.getGroupedCollection = this.getGroupedCollection.bind( this );
		this.getActiveKit = this.getActiveKit.bind( this );
	}

	getGroupedCollection() {
		if ( ! this.context.state.templates ) {
			return;
		}

		return this.context.state.templates.reduce( ( accumulator, currentValue ) => {
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
			const kit = collection[ id ];
			if ( AGWP.license.status !== 'valid' && this.context.state.showFree ) {
				const filtered = kit.filter( ( t ) => t.is_pro !== true );
				count = filtered.length;
			} else {
				count = Object.keys( kit ).length;
			}
		}

		return count;
	}

	getActiveKit() {
		const groupedCollection = this.getGroupedCollection();
		const activeKit = this.context.state.activeKit;

		if ( ! activeKit || ! ( activeKit.site_id in groupedCollection ) ) {
			return false;
		}

		return groupedCollection[ activeKit.site_id ];
	}

	render() {
		return (
			<div className="collection">
				{ ! this.context.state.activeKit && (
					<List>
						{ this.context.state.kits && this.context.state.kits.map( ( kit ) => {
							if ( ! this.getCollectionCount( kit.site_id ) ) {
								return;
							}

							return (
								
								<li key={ kit.site_id }>
									<Card>
										<CardBody>
											<figure>
												<img src={ kit.thumbnail || AGWP.pluginURL + 'assets/img/placeholder.svg' } loading="lazy" alt={ kit.title } />
												<div className="actions">
													<Button isPrimary isLarge onClick={ () => {
														this.context.dispatch( {
															activeKit: kit,
														} );
													} }>
														{ __( 'View Templates', 'ang' ) }
													</Button>
												</div>
											</figure>
										</CardBody>
										<CardDivider>&nbsp;</CardDivider>
										<CardFooter>
											<h3>{ kit.title }</h3>
											<span>{ this.getCollectionCount( kit.site_id ) }</span>
										</CardFooter>
									</Card>
								</li>
							);
						} ) }
					</List>
				) }

				{ this.getActiveKit() && (
					<ul className="templates-list">
						{ this.getActiveKit().map( ( template ) => {
							if ( AGWP.license.status !== 'valid' && this.context.state.showFree && Boolean( template.is_pro ) ) {
								return;
							}
							return (
								<Template
									key={ template.id }
									template={ template }
									favorites={ this.context.state.favorites }
									setModalContent={ this.props.setModalContent }
									importLayout={ this.props.importLayout }
									makeFavorite={ this.props.makeFavorite }
								/>
							);
						} ) }
					</ul>
				) }
			</div>
		);
	}
}

Collection.contextType = AnalogContext;
