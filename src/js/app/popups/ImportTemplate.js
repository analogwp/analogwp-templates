import Select from 'react-select';
import styled from 'styled-components';
import AnalogContext from '../AnalogContext';
import Loader from '../icons/loader';
import { NotificationConsumer } from '../Notifications';
import Popup from '../popup';

const { Fragment, useState, useContext } = React;

const { decodeEntities } = wp.htmlEntities;
const { Button, Dashicon, TextControl, ExternalLink } = wp.components;
const { __ } = wp.i18n;
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

const ImportTemplate = ( { onRequestClose, state, handler, handleImport } ) => {
	const [ step, setStep ] = useState( 1 );
	const [ title, setTitle ] = useState( __( 'Select a Style Kit to apply on this layout', 'ang' ) );
	const [ kit, setKit ] = useState( false );

	const { state: { styleKits } } = useContext( AnalogContext );

	const { template } = state;

	const filterOptions = AGWP.installed_kits.map( filter => {
		return { value: filter, label: filter };
	} );

	const importables = styleKits
		.filter( k => parseInt( k.site_id ) === parseInt( state.template.site_id ) )
		.filter( k => ! AGWP.installed_kits.includes( k.title ) );

	const importableOptions = importables.map( ( k ) => {
		return { value: k.title, label: k.title };
	} );

	const groupedOptions = [
		{
			label: __( 'Import', 'ang' ),
			options: importableOptions,
		},
		{
			label: __( 'Installed', 'ang' ),
			options: filterOptions,
		},
	];

	/**
	 * Determine if a selected kit from state is being
	 * imported from library and inserted from local site.
	 *
	 * @returns {string} import or insert.
	 */
	const getStyleKitInfo = () => {
		const isKitInstalled = AGWP.installed_kits.filter( ( k ) => kit === k );
		const method = isKitInstalled.length > 0 ? 'insert' : 'import';
		let data = false;

		if ( method === 'insert' ) {
			data = kit;
		} else {
			data = styleKits.find( k => k.title === kit );
		}

		const info = {
			method,
			data,
		};

		return info;
	};

	return (
		<Popup
			title={ title }
			onRequestClose={ onRequestClose }
		>
			<Container>
				{ ( step === 1 ) && (
					<div>
						<p>You can import the template with its default Style Kit or choose any other of your available Style kits:</p>
						<div className="row">
							<Select
								options={ groupedOptions }
								formatGroupLabel={ formatGroupLabel }
								isSearchable={ false }
								placeholder={ __( 'Choose a Style Kit...', 'ang' ) }
								defaultValue={ filterOptions.find( option => option.value === kit ) }
								onChange={ ( e ) => {
									setKit( e.value );
								} }
							/>
							<button
								className="ang-button"
								onClick={ () => {
									setStep( 2 );
									setTitle( decodeEntities( template.title ) );
								} }
							>{ __( 'Choose', 'ang' ) }</button>
						</div>
					</div>
				) }

				{ ( step === 2 ) && (
					<div>
						<Button
							isTertiary
							onClick={ () => {
								setStep( 1 );
								setKit( false );
							} }
						>
							<Dashicon icon="arrow-left" />{ ' ' }{ __( 'Change Style Kit', 'ang' ) }
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
											handleImport( add, false, getStyleKitInfo() );
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
											handleImport( add, state.pageName, getStyleKitInfo() );
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

				{ state.importing && (
					<div style={ { textAlign: 'center', fontSize: '15px' } }>
						{ state.importedPage ?
							( <Fragment>
								<p>{ __( 'Blimey! Your template has been imported.', 'ang' ) }</p>
								<p>
									<a
										className="ang-button"
										href={ addQueryArgs( 'post.php', { post: state.importedPage, action: 'elementor' } ) }
									>{ __( 'Edit Template' ) }</a>
								</p>
							</Fragment> ) :
							<Loader />
						}
					</div>
				) }
			</Container>
		</Popup>
	);
};

export default ImportTemplate;
