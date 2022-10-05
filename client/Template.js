import classnames from 'classnames';
import Image from './helpers/Image';
import Star from './icons/star';
import { isNewTheme } from './utils';

const { decodeEntities } = wp.htmlEntities;
const { __ } = wp.i18n;
const { Button, Card, CardBody, CardFooter } = wp.components;

const Template = ( { template, setModalContent, importLayout, favorites, makeFavorite } ) => {
	const isValid = ( isPro ) => ! ( isPro && AGWP.license.status !== 'valid' );

	const fallbackImage = AGWP.pluginURL + 'assets/img/placeholder.svg';

	return (
		<li>
			<Card>
				<CardBody>
					{ ( isNewTheme( template.published ) > -14 ) && (
						<span className="new">{ __( 'New', 'ang' ) }</span>
					) }

					<figure>
						{ template.thumbnail ? <Image template={ template } /> : <img src={fallbackImage} /> }
						<div className="actions">
							<Button isSecondary className="black-transparent" onClick={ () => setModalContent( template ) }>
								{ __( 'Preview', 'ang' ) }
							</Button>
							{ ! isValid( template.is_pro ) && (
								<a className="ang-promo" href="https://analogwp.com/style-kits-pro/?utm_medium=plugin&utm_source=library&utm_campaign=style+kits+pro" target="_blank">
									<Button isPrimary>{ __( 'Go Pro', 'ang' ) }</Button>
								</a>
							) }
							{ isValid( template.is_pro ) && (
								<Button isPrimary onClick={ () => importLayout( template ) }>
									{ __( 'Import', 'ang' ) }
								</Button>
							) }
						</div>

						<button
							className={ classnames( 'button-plain favorite', {
								'is-active': template.id in favorites,
							} ) }
							onClick={ () => makeFavorite( template.id ) }
						>
							<Star />
						</button>
					</figure>
				</CardBody>
				<CardFooter>
					<div className="content">
						<span>
							<h3>{ decodeEntities( template.title ) }</h3>
							{ template.tags && (
								<div className="tags">
									{ template.tags.map( tag => (
										<span key={ tag }>{ tag }</span>
									) ) }
								</div>
							) }
						</span>
						{ template.is_pro && (
							<span className="pro">{ __( 'Pro', 'ang' ) }</span>
						) }
					</div>
				</CardFooter>
			</Card>
		</li>
	);
};

export default Template;
