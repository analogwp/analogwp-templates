import Select from 'react-select';
import styled from 'styled-components';
import AnalogContext from '../AnalogContext';
import { requestElementorImport } from '../api';
import Loader from '../icons/loader';
import { NotificationConsumer } from '../Notifications';
import Popup from '../popup';

const { Fragment, useState, useContext, useEffect } = React;

const { Button, TextControl, ExternalLink, CardDivider } = wp.components;
const { __, sprintf } = wp.i18n;
const { addQueryArgs } = wp.url;

const Container = styled.div`
	.row {
		display: flex;
		align-items: center;

		> div {
			flex: 1;
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
	const [ title, setTitle ] = useState( __( 'Import template', 'ang' ) );

	const kit = state.kit;
	const { state: { styleKits, installedKits }, dispatch } = useContext( AnalogContext );
	const { template } = state;

	const filterOptions = installedKits.map( filter => {
		return { value: filter, label: filter };
	} ).filter( ( kit ) => {
		return kit.value !== AGWP.globalKit[ 0 ].value;
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
			label: __( 'Global', 'ang' ),
			options: AGWP.globalKit,
		},
		{
			label: __( 'Default', 'ang' ),
			options: importableOptions,
		},
		{
			label: __( 'Installed', 'ang' ),
			options: filterOptions,
		},
	];

	const defaultOption = importableOptions.length > 0 ? importableOptions[ 0 ] : filterOptions.find( ( option ) => {
		return !! ( activeKit && option.value === activeKit.title );
	} );

	const defaultKitValue = defaultOption ? defaultOption : AGWP.globalKit[ 0 ];
	const defaultDropdownValue = ( AGWP.isGlobalSkEnabled === '1' ) ? AGWP.globalKit[ 0 ] : defaultKitValue;

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

	//componentDidMount
	useEffect( () => {
		handler( { kit: defaultDropdownValue.value } );
	}, [] );

	return (
		<Popup
			title={ title }
			onRequestClose={ onRequestClose }
		>
			<Container>
				{ ( step === 1 ) && (
					<div>
						{ ( AGWP.isGlobalSkEnabled ) ?
							<h3>{ __( 'The Global Style Kit will be applied on this template', 'ang' ) }</h3> :
							<h3>{ __( 'Choose a Theme Style Kit to apply on the page.', 'ang' ) }</h3>
						}
						{ ( AGWP.isGlobalSkEnabled ) ?
							<p id="gsk_name">{ sprintf(
								/* translators: 1: Global Style Kit label */
								__( '%1$s', 'ang' ),
								AGWP.globalKit[ 0 ].label
							) }</p> :
							<p>{ __( 'The original Style Kit is pre-selected for you.', 'ang' ) }</p>
						}
						{ ( ! AGWP.isGlobalSkEnabled ) &&
							<div className="row" style={ { width: '42%' } }>
								<Select
									options={ groupedOptions }
									formatGroupLabel={ formatGroupLabel }
									isSearchable={ false }
									placeholder={ __( 'Choose a Style Kit...', 'ang' ) }
									defaultValue={ defaultDropdownValue }
									onChange={ ( e ) => {
										handler( { kit: e.value } );
									} }
								/>
							</div>
						}
						{ ( AGWP.isGlobalSkEnabled ) ?
							<>
								<p>
									{ __( 'You can change the default import method at the ', 'ang' ) }
									<ExternalLink href={ AGWP.globalSkAlwaysEnableURL }>{ __( 'Settings Page', 'ang' ) }</ExternalLink>
								</p>
							</> :
							<>
								<p>
									{ __( 'You can manage and set a Global Style Kit at the ', 'ang' ) }
									<ExternalLink href={ AGWP.adminURL }>{ __( 'Settings Page', 'ang' ) }</ExternalLink>
								</p>
							</>
						}
					</div>
				) }

				{ ( step === 1 ) && (
					<>
						<CardDivider className="el-editor" />
						<div className="flex-row el-editor">
							<div className="col1">
								<h3>Import to this page</h3>
								<p>
									{ __( 'Import the template in the current page.', 'ang' ) }
								</p>
							</div>
							<div className="col2">
								<NotificationConsumer>
									{ ( { add } ) => (
										<Button
											isPrimary
											onClick={ () => {
												handler( {
													showingModal: true,
													importing: true,
													importingElementor: true,
												} );

												setStep( 2 );
											} }
										>
											{ __( 'Import to current page', 'ang' ) }
										</Button>
									) }
								</NotificationConsumer>
							</div>
						</div>
						<CardDivider />
						<div className="flex-row">
							<div className="col1">
								<h3>Import to Library</h3>
								<p>
									{ __( 'Import this template to your library to make it available in your Elementor ', 'ang' ) }
									<ExternalLink href={ AGWP.elementorURL }>{ __( 'Saved Templates', 'ang' ) }</ExternalLink>
									{ __( ' list for future use.', 'ang' ) }
								</p>
							</div>
							<div className="col2">
								<NotificationConsumer>
									{ ( { add } ) => (
										<Button
											isPrimary
											onClick={ () => {
												handleImport( add, false );

												const kits = [ ...installedKits ];
												kits.push( state.kit );
												dispatch( {
													installedKits: kits,
												} );

												setStep( 2 );
											} }
										>
											{ __( 'Import to Library', 'ang' ) }
										</Button>
									) }
								</NotificationConsumer>
							</div>
						</div>
						<CardDivider />
						<div className="flex-row">
							<div className="col1">
								<h3>{ __( 'Import to a new page', 'ang' ) }</h3>
								<p>{ __( 'Create a new page from this template to make it available as a draft page in your Pages list.', 'ang' ) }</p>
							</div>
							<div className="col2 gap">
								<TextControl
									placeholder={ __( 'Enter a Page Name', 'ang' ) }
									onChange={ val => {
										handler( { pageName: val } );
									} }
								/>
								<NotificationConsumer>
									{ ( { add } ) => (
										<Button
											isSecondary
											disabled={ ! state.pageName }
											onClick={ () => {
												handleImport( add, state.pageName );
												setStep( 2 );
											} }
										>
											{ __( 'Import to page', 'ang' ) }
										</Button>
									) }
								</NotificationConsumer>
							</div>
						</div>
					</>
				) }

				{ ( step >= 1 ) && state.importing && (
					<div style={ { textAlign: 'center', fontSize: '15px' } }>
						{ state.importedPage ?
							( <Fragment>
								<p>{ __( 'All done! The template has been imported.', 'ang' ) }</p>
								<p>
									<a
										href={ addQueryArgs( 'post.php', { post: state.importedPage, action: 'elementor' } ) }
									>
										<Button isPrimary>
											{ __( 'Edit Template' ) }
										</Button>
									</a>
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
