import styled from 'styled-components';
const { ExternalLink } = wp.components;
const { __ } = wp.i18n;

const Container = styled.div`
	background: var(--ang-accent);
	color: #fff;
	padding: 20px;
	display: flex;
	align-items: center;
	margin-bottom: 40px;
	border-radius: 6px;

	p {
		font-size: 15px;
		margin: 0;
	}

	.ang-button.ang-button {
		width: 200px;
		margin-left: auto;
		background: transparent;
		border-radius: 100px;
		border: 3px solid #fff;
		color: #fff;
		width: auto;
	}

	span {
		font-weight: bold;
	}
`;

const ProModal = ( { onDimiss } ) => (
	<Container>
		<p>
			<span role="img" aria-label="zap">⚡</span> { __( 'Template Kits are', 'ang' ) } <strong>{ __( 'coming soon with Style Kits Pro.', 'ang' ) }</strong> { __( 'Sign up for an exclusive launch discount.', 'ang' ) }
		</p>
		<ExternalLink className="ang-button" href="https://analogwp.com/style-kits-pro">{ __( 'Learn More', 'ang' ) }</ExternalLink>
	</Container>
);

export default ProModal;
