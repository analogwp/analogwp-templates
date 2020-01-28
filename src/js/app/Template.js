import classnames from 'classnames';
import Image from './helpers/Image';
import Star from './icons/star';
import { isNewTheme } from './utils';

const { ExternalLink } = wp.components;
const { decodeEntities } = wp.htmlEntities;
const { __ } = wp.i18n;

const Template = ( { template, setModalContent, importLayout, favorites, makeFavorite } ) => {
	const isValid = ( isPro ) => ! ( isPro && AGWP.license.status !== 'valid' );

	return (
		<li>
			{ ( isNewTheme( template.published ) > -14 ) && (
				<span className="new">{ __( 'New', 'ang' ) }</span>
			) }

			<figure>
				{ template.thumbnail && <Image template={ template } /> }
				<div className="actions">
					<button className="ang-button" onClick={ () => setModalContent( template ) }>
						{ __( 'Preview', 'ang' ) }
					</button>
					{ ! isValid( template.is_pro ) && (
						<button className="ang-button"><ExternalLink href="https://analogwp.com/style-kits-pro/?utm_medium=plugin&utm_source=library&utm_campaign=style+kits+pro">{ __( 'Go PRO', 'ang' ) }</ExternalLink></button>
					) }
					{ isValid( template.is_pro ) && (
						<button className="ang-button" onClick={ () => importLayout( template ) }>
							{ __( 'Import', 'ang' ) }
						</button>
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

			<div className="content">
				<h3>{ decodeEntities( template.title ) }</h3>
			</div>
			{ template.tags && (
				<div className="tags">
					{ template.tags.map( tag => (
						<span key={ tag }>{ tag }</span>
					) ) }
				</div>
			) }

			{ template.is_pro && (
				<span className="pro">{ __( 'Pro', 'ang' ) }</span>
			) }
		</li>
	);
};

export default Template;
