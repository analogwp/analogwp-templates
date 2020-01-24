import styled, { keyframes } from 'styled-components';
const { Tooltip, FocusableIframe } = wp.components;
const { __ } = wp.i18n;

const rotateOpacity = keyframes`
  0% {
    opacity: 0.5;
  }

  50% {
    opacity: 0.1;
  }

  100% {
    opacity: 0.5;
  }
`;

const Img = styled.img`
	opacity: 0.5;
	transition: all 200ms ease-in-out;
	animation: ${ rotateOpacity } 2s linear infinite;
`;

const Container = styled.div`
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	overflow: hidden;
	overflow: -moz-scrollbars-none;
	background: #e3e3e3;
	z-index: 999;
	text-align: center;

	iframe {
		width: 100%;
		height: 100%;
		display: ${ props => props.loading ? 'none' : 'block' };
	}

	.frame-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 25px;

		a {
			margin-left: auto;
			margin-right: 20px;
			color: currentColor;
			text-decoration: none;
		}

		.dashicons {
			font-size: 25px;
		}
	}

	.button--plain {
		-webkit-appearance: none;
		-moz-appearance: none;
		padding: 0;
		margin: 0;
		font-size: 14.22px;
		font-weight: bold;
		color: #060606;
		background: transparent;
		border: none;
		outline: 0;
		cursor: pointer;
	}

	.button--accent {
		font-size: 12px;
		font-weight: bold;
		color: #fff;
		border-radius: 0;
		border: none;
		background: var(--ang-accent);
		outline: 0;
		box-shadow: none;
		padding: 15px 30px;
		cursor: pointer;
	}
`;

const Preview = ( props ) => {
	const [ loading, setLoading ] = React.useState( true );

	const previewURL = props.template.url || props.template.preview;

	return (
		<Container loading={ loading }>
			<div className="frame-header">
				<button className="button--plain" onClick={ props.onRequestClose }>
					{ __( 'Back to Library', 'ang' ) }
				</button>

				<Tooltip text={ __( 'Open Preview in New Tab', 'ang' ) }>
					<a href={ previewURL } rel="noopener noreferrer" target="_blank">
						<span className="dashicons dashicons-external" />
					</a>
				</Tooltip>

				{ ! ( props.template.is_pro && AGWP.license.status !== 'valid' ) && (
					<button className="ang-button" onClick={ props.onRequestImport }>
						{ __( 'Insert Layout', 'ang' ) }
					</button>
				) }
			</div>

			{ loading && <Img
				src={ `${ AGWP.pluginURL }assets/img/placeholder.svg` }
				alt={ __( 'Loading icon', 'ang' ) }
			/> }

			<FocusableIframe src={ previewURL } onLoad={ () => setLoading( false ) } />
		</Container>
	);
};

export default Preview;
