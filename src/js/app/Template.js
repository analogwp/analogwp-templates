import classnames from 'classnames';
import Image from './helpers/Image';
import Star from './icons/star';
import { isNewTheme } from './utils';

const { decodeEntities } = wp.htmlEntities;
const { __ } = wp.i18n;

const Template = ( { template, setModalContent, importLayout, favorites, makeFavorite } ) => {
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
					<button className="ang-button" onClick={ () => importLayout( template ) }>
						{ __( 'Import', 'ang' ) }
					</button>
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
