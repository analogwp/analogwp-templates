import Select from 'react-select';
import styled from 'styled-components';
import AnalogContext from '../AnalogContext';
import { requestElementorImport } from '../api';
import Loader from '../icons/loader';
import { NotificationConsumer } from '../Notifications';
import Popup from '../popup';

const { Fragment, useState, useContext } = React;

const { decodeEntities } = wp.htmlEntities;
const { Button, TextControl, ExternalLink } = wp.components;
const { __, sprintf } = wp.i18n;
const { addQueryArgs } = wp.url;

const Container = styled.div`
	.row {
		display: flex;
		align-items: center;

		> div {
			height: 45px;
			margin-right: 20px;
			flex: 1;
			> div {
				min-height: 100%;
			}
		}
	}

	button.is-tertiary {
		text-transform: uppercase;
		font-weight: 700;
		color: #222;
	}

	footer {
		padding: 20px 35px;
		font-size: 12px;
		color: #4A5157;
		background: #fff;
		margin: 30px -35px -20px -35px;
		border-radius: 3px;

		a {
			color: #5c32b6;
			text-decoration: underline;
		}
	}
`;

const groupStyles = {
	display: 'flex',
	alignItems: 'center',
	justifyContent: 'space-between',
};

const groupBadgeStyles = {
	backgroundColor: '#EBECF0',
	borderRadius: '2em',
	color: '#172B4D',
	display: 'inline-block',
	fontSize: 12,
	fontWeight: 'normal',
	lineHeight: '1',
	minWidth: 1,
	padding: '0.16666666666667em 0.5em',
	textAlign: 'center',
};

const formatGroupLabel = data => (
	<div style={ groupStyles }>
		<span>{ data.label }</span>
		<span style={ groupBadgeStyles }>{ data.options.length }</span>
	</div>
);

const ImportTemplate = ( { onRequestClose, state, handler, handleImport, getStyleKitInfo } ) => {
	const [ step, setStep ] = useState( 1 );
	const [ title, setTitle ] = useState( __( 'Select a Style Kit to apply on this layout', 'ang' ) );

	const kit = state.kit;
	const { state: { styleKits, installedKits }, dispatch } = useContext( AnalogContext );
	const { template } = state;

	const filterOptions = installedKits.map( filter => {
		return { value: filter, label: filter };
	} );

	const importables = styleKits
		.filter( k => parseInt( k.site_id ) === parseInt( state.template.site_id ) )
		.filter( k => ! installedKits.includes( k.title ) );

	const importableOptions = importables.map( ( k ) => {
		return { value: k.title, label: k.title };
	} );

	const activeKit = styleKits.find( option => parseInt( option.site_id ) === parseInt( state.template.site_id ) );

	const groupedOptions = [
		{
			label: __( 'Default', 'ang' ),
			options: importableOptions,
		},
		{
			label: __( 'Installed', 'ang' ),
			options: filterOptions,
		},
	];

	const defaultOption = importableOptions.length ? importableOptions[ 0 ] : filterOptions.find( ( option ) => {
		if ( activeKit && option.value === activeKit.title ) {
			return true;
		}

		return false;
	} );

	const importElementor = () => {
		requestElementorImport( state.template, getStyleKitInfo( state.kit ) ).then( () => {
			handler( { showingModal: false, importing: false, importingElementor: false } );

			const kits = [ ...installedKits ];
			kits.push( state.kit );
			dispatch( {
				installedKits: kits,
			} );

			onRequestClose();
		} );
	};

	const footer = sprintf(
		__( 'Learn more about this in %s.', 'ang' ),
		sprintf(
			'<a href="https://docs.analogwp.com/article/608-sk-select-template-import" target="_blank" rel="noopener noreferer">%s</a>',
			__( 'Style Kits Docs', 'ang' )
		)
	);

	return (
		<Popup
			title={ title }
			onRequestClose={ onRequestClose }
		>
			<Container>
				{ ( step === 1 ) && (
					<div>
						<p>The default Style Kit for this template is auto-selected below. You can always apply any of your available Style Kits to this template if you want.</p>
						<div className="row">
							<Select
								options={ groupedOptions }
								formatGroupLabel={ formatGroupLabel }
								isSearchable={ false }
								placeholder={ __( 'Choose a Style Kit...', 'ang' ) }
								defaultValue={ defaultOption }
								onChange={ ( e ) => {
									handler( { kit: e.value } );
								} }
							/>
							<button
								className="ang-button"
								onClick={ () => {
									setStep( 2 );
									setTitle( decodeEntities( template.title ) );

									// Fallback if the Default kit in dropdown in unchanged.
									if ( ! kit ) {
										handler( { kit: defaultOption.value } );
									}
								} }
							>{ __( 'Next', 'ang' ) }</button>
						</div>

						<footer dangerouslySetInnerHTML={ { __html: footer } } />
					</div>
				) }

				{ ( step === 2 ) && ! state.importingElementor && (
					<div>
						<Button
							isTertiary
							onClick={ () => {
								setStep( 1 );
								handler( { kit: false } );
							} }
						>
						&larr; { __( 'Change Style Kit', 'ang' ) }
						</Button>

						<p>
							{ __( 'Import this template to your library to make it available in your Elementor ', 'ang' ) }
							<ExternalLink href={ AGWP.elementorURL }>{ __( 'Saved Templates', 'ang' ) }</ExternalLink>
							{ __( ' list for future use.', 'ang' ) }
						</p>

						<p>
							<NotificationConsumer>
								{ ( { add } ) => (
									<Button
										className="ang-button"
										onClick={ () => {
											handleImport( add, false );

											const kits = [ ...installedKits ];
											kits.push( state.kit );
											dispatch( {
												installedKits: kits,
											} );

											setStep( 3 );
										} }
									>
										{ __( 'Import to Library', 'ang' ) }
									</Button>
								) }
							</NotificationConsumer>
						</p>

						<hr />

						<p>{ __( 'Create a new page from this template to make it available as a draft page in your Pages list.', 'ang' ) }</p>

						<div className="form-row">
							<TextControl
								placeholder={ __( 'Enter a Page Name', 'ang' ) }
								style={ { maxWidth: '60%' } }
								onChange={ val => {
									handler( { pageName: val } );
								} }
							/>
							<NotificationConsumer>
								{ ( { add } ) => (
									<Button
										className="ang-button"
										disabled={ ! state.pageName }
										style={ {
											marginLeft: '15px',
										} }
										onClick={ () => {
											handleImport( add, state.pageName );
											setStep( 3 );
										} }
									>
										{ __( 'Import to page', 'ang' ) }
									</Button>
								) }
							</NotificationConsumer>
						</div>
					</div>
				) }

				{ ( step >= 2 ) && state.importing && (
					<div style={ { textAlign: 'center', fontSize: '15px' } }>
						{ state.importedPage ?
							( <Fragment>
								<p>{ __( 'All done! The template has been imported.', 'ang' ) }</p>
								<p>
									<a
										className="ang-button"
										href={ addQueryArgs( 'post.php', { post: state.importedPage, action: 'elementor' } ) }
									>{ __( 'Edit Template' ) }</a>
								</p>
							</Fragment> ) :
							(
								<Fragment>
									{ state.importingElementor && importElementor() }
									<Loader />
								</Fragment>
							)
						}
					</div>
				) }
			</Container>
		</Popup>
	);
};

export default ImportTemplate;
