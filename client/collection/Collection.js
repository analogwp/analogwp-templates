/**
 * Component for Template kit collections.
 */
import styled from 'styled-components';
import AnalogContext from '../AnalogContext';
import Template from '../Template';
const { __ } = wp.i18n;
const { Button, Card, CardBody, CardFooter } = wp.components;

const List = styled.ul`
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
		vertical-align: middle;
	}

	.actions {
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
			count = Object.keys( kit ).length;
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
					<List className="templates-collection">
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
													<Button isSecondary className="black-transparent" onClick={ () => {
														this.context.dispatch( {
															activeKit: kit,
														} );
													} }>
														{ __( 'View Templates', 'ang' ) }
													</Button>
												</div>
											</figure>
										</CardBody>
										<CardFooter>
											<div className="content">
												<h3>{ kit.title }</h3>
												<span>{ this.getCollectionCount( kit.site_id ) }</span>
											</div>
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
							if ( AGWP.license.status !== 'valid' ) {
								const isPro = this.context.state.showFree === false && this.context.state.showPro === true &&
									Boolean( template.is_pro ) === true;

								const isFree = this.context.state.showFree === true && this.context.state.showPro === false &&
									Boolean( template.is_pro ) === false;

								const isAll = this.context.state.showFree === true && this.context.state.showPro === true;

								if ( ! ( isPro ||
										isFree ||
										isAll ) ) {
									return;
								}
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
