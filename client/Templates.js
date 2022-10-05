import classnames from 'classnames';
import styled from 'styled-components';
import AnalogContext from './AnalogContext';
import { requestDirectImport } from './api';
import Collection from './collection/Collection';
import { Theme } from './contexts/ThemeContext';
import Empty from './helpers/Empty';
import CustomModal from './Modal';
import ImportTemplate from './popups/ImportTemplate';
import Template from './Template';
import ProModal from './ProModal';

const { __ } = wp.i18n;

const TemplatesContainer = styled.div`
	.templates-list {
		&.hide {
			display: none;
		}

		li {
			background: #fff;
			position: relative;
			border-radius: 4px;
		}

		.new {
			position: absolute;
			top: -8px;
			right: -8px;
			background: var(--ang-primary);
			color: #fff;
			z-index: 110;
			font-weight: bold;
			padding: 8px 10px;
			line-height: 1;
			border-radius: 4px;
			text-transform: uppercase;
			font-size: 14.22px;
			letter-spacing: .5px;
		}

		.new {
			background: ${ Theme.accent };
		}

		p {
			color: #939393;
			font-size: 10px;
			margin: 0;
			font-weight: 500;
		}

		.content {
			svg {
				fill: #d0d0d0;
				transition: all 100ms ease-in;
			}

			button:hover,
			button.is-active {
				svg {
					fill: #FFB443;
				}
			}
		}

		h3 {
			font-size: inherit;
			text-transform: capitalize;
			margin: 0;
			font-weight: bold;
		}

		img {
			width: 100%;
			height: auto;
			border-top-left-radius: 4px;
			border-top-right-radius: 4px;
			vertical-align: middle;
		}

		figure {
			margin: 0;
			position: relative;
			min-height: 100px;

			&:hover {
				.actions {
					opacity: 1;
					button {
						transform: none;
						opacity: 1;
					}
				}
				.favorite {
					opacity: 1;
				}
			}
		}

		.actions button {
			opacity: 0;

			&:nth-child(1) {
				transform: translateX(-20px);
			}
			&:nth-child(2) {
				transform: translateX(20px);
			}

			+ button, + .ang-promo {
				margin-top: 10px;
				text-decoration: none;
			}
		}

		.favorite {
			position: absolute;
			top: 0;
			left: 0;
			z-index: 200;
			display: inline-flex;
			justify-content: center;
			align-items: center;
			width: 25px;
			height: 25px;

			&:not(.is-active) {
				opacity: 0;
			}

			&:before {
				content: '';
				width: 0;
				height: 0;
				border-style: solid;
				border-width: 42px 42px 0 0;
				border-color: var(--ang-dark-bg) transparent transparent transparent;
				position: absolute;
				top: 0;
				left: 0;
				z-index: 190;
			}

			svg {
				fill: #fff;
				position: relative;
				z-index: 195;
			}
			&.is-active svg {
				fill: #FFB443;
			}
		}
	}
`;

const initialState = {
	template: null,
	pageName: null,
	showingModal: false,
	importing: false,
	importedPage: false,
	importingElementor: false,
	kit: false,
};

class Templates extends React.Component {
	constructor() {
		super( ...arguments );

		this.state = initialState;

		this.handler = this.handler.bind( this );
		this.handleImport = this.handleImport.bind( this );
		this.getStyleKitInfo = this.getStyleKitInfo.bind( this );
	}

	resetState() {
		this.context.dispatch( { isOpen: false } );
		this.setState( initialState );
	}

	handler( value ) {
		this.setState( value );
	}

	closeOnEsc = ( event ) => {
		if ( event.keyCode === 27 ) {
			this.resetState();
		}
	}

	componentDidMount() {
		if ( AGWP.isGlobalSkEnabled ) {
			this.setState( { kit: AGWP.globalKit[ 0 ].value } );
		}
		window.addEventListener( 'keyup', this.closeOnEsc );
	}

	componentWillUnmount() {
		this.resetState();
		window.removeEventListener( 'keyup', this.closeOnEsc );
	}

	getStyleKitInfo() {
		const isKitInstalled = this.context.state.installedKits.filter( ( k ) => this.state.kit === k );
		const method = isKitInstalled.length > 0 ? 'insert' : 'import';

		let data = ( method === 'insert' )
					? this.state.kit
					: this.context.state.styleKits.find( k => k.title === this.state.kit );

		if ( typeof data === "undefined" ) {
			data = false;
		}

		return { method, data };
	}

	setModalContent = template => {
		window.scrollTo( 0, 0 );
		this.context.dispatch( {
			isOpen: ! this.context.state.isOpen,
		} );
		this.setState( {
			template: template,
		} );
	};

	makeFavorite = ( id ) => {
		const favorites = this.context.state.favorites;

		this.context.markFavorite( id, ! ( id in favorites ) );

		if ( id in favorites ) {
			delete favorites[ id ];
		} else {
			favorites[ id ] = ! ( id in favorites );
		}

		this.context.dispatch( { favorites } );

		if ( this.context.state.showing_favorites ) {
			const filteredTemplates = this.context.state.templates.filter( t => t.id in favorites );

			this.context.dispatch( {
				templates: filteredTemplates,
			} );
		}
	};

	/**
	 * Handle different states for Importing direct layout.
	 *
	 * @param {function} add Adds a notification.
	 * @param {boolean} withPage Determine if import needs a page.
	 * @param {object} kit Include Style Kit info from library.
	 */
	handleImport = async( add, withPage = false ) => {
		this.setState( { importing: true } );
		const kit = this.getStyleKitInfo();
		const version = this.state.template.version;

		if ( version && parseFloat( AGWP.version ) < parseFloat( version ) ) {
			this.resetState();
			add(
				__( 'This template requires an updated version, please update your plugin to latest version.', 'ang' ),
				'error', 'ang',
				'import-error',
				false
			);
			return;
		}

		await requestDirectImport(
			this.state.template,
			withPage,
			kit
		).then( response => {
			this.setState( {
				importedPage: response.page,
			} );
		} ).catch( error => {
			this.resetState();
			if ( error.data && error.data.errors ) {
				add( error.data.errors[ Object.keys( error.data.errors )[ 0 ] ], 'error', 'import-error', false );
			} else {
				add( error.message, 'error', 'import-error', false );
			}
		} );
	}

	/**
	 * Determine if current user can import template.
	 * Mainly to check pro template capabilities.
	 *
	 * @param {object} template Template data.
	 * @return {boolean} True if Pro and license is valid, else false.
	 */
	canImportTemplate = ( template ) => {
		if ( ! template ) {
			template = this.state.template;
		}

		if ( ! template.is_pro ) {
			return true;
		}

		if ( template.is_pro && AGWP.license.status === 'valid' ) {
			return true;
		}

		return false;
	}

	importLayout = ( template ) => {
		if ( ! template ) {
			template = this.state.template;
		} else {
			this.setState( { template } );
		}

		if ( typeof elementor !== 'undefined' ) {
			this.setState( {
				showingModal: true,
			} );
		} else {
			this.setState( {
				showingModal: true,
			} );
		}
	};

	render() {
		return (
			<TemplatesContainer
				className={ classnames( {
					hide: ( this.state.template && this.state.showingModal && ! this.canImportTemplate() ),
					'preview-active': this.context.state.isOpen,
				} ) }
			>
				{ this.context.state.isOpen && (
					<CustomModal
						template={ this.state.template }
						onRequestClose={ () => this.context.dispatch( { isOpen: false } ) }
						onRequestImport={ () => this.importLayout() }
					/>
				) }

				{ ( ( this.state.template !== null ) && this.canImportTemplate() && this.state.showingModal ) && (
					<ImportTemplate
						onRequestClose={ () => this.resetState() }
						state={ this.state }
						handleImport={ this.handleImport }
						handler={ this.handler }
						getStyleKitInfo={ this.getStyleKitInfo }
					/>
				) }

				{ AGWP.license.status !== 'valid' && (
					<ProModal />
				) }

				{ ! this.context.state.isOpen && this.context.state.templates.length < 1 && (
					<Empty />
				) }

				{ this.context.state.group ? (
					<Collection
						setModalContent={ this.setModalContent }
						importLayout={ this.importLayout }
						makeFavorite={ this.makeFavorite }
					/>
				) : (
					<ul
						className={ classnames( 'templates-list', {
							hide: ( this.state.template && this.state.showingModal && ! this.canImportTemplate() ),
						} ) }
					>
						{ ! this.context.state.isOpen && this.context.state.count >= 1 && this.context.state.templates.map( template => {
							if ( AGWP.license.status !== 'valid' ) {

								let isPro = this.context.state.showFree === false && this.context.state.showPro === true &&
									Boolean(template.is_pro) === true;

								let isFree = this.context.state.showFree === true && this.context.state.showPro === false &&
									Boolean(template.is_pro) === false;

								let isAll = this.context.state.showFree === true && this.context.state.showPro === true;

								if ( !( isPro || isFree || isAll) ) {
									return;
								}
							}

							return (
								<Template
									key={ `${template.id}-${template.site_id}` }
									template={ template }
									favorites={ this.context.state.favorites }
									setModalContent={ this.setModalContent }
									importLayout={ this.importLayout }
									makeFavorite={ this.makeFavorite }
								/>
							);
						} ) }
					</ul>
				) }
			</TemplatesContainer>
		);
	}
}

Templates.contextType = AnalogContext;

export default Templates;
