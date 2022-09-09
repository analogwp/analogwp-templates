import styled, { keyframes } from 'styled-components';
const { FocusableIframe, Button } = wp.components;
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
	background: #F1F1F1;
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

	const { onRequestClose, onRequestImport, ...rest } = props;

	const previewURL = props.template.url || props.template.preview;

	return (
		<Container loading={ loading } { ...rest }>
			<div className="frame-header">
				<Button isSecondary onClick={ onRequestClose }>
					{ __( 'Back to Library', 'ang' ) }
				</Button>

				<a href={ previewURL } rel="noopener noreferrer" target="_blank">
					<Button isSecondary>
						{ __( 'Open in new tab', 'ang' ) }
					</Button>
				</a>

				{ ! ( props.template.is_pro && AGWP.license.status !== 'valid' ) && (
					<Button isPrimary onClick={ onRequestImport }>
						{ props.insertText || __( 'Import Template', 'ang' ) }
					</Button>
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
