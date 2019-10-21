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

	p {
		font-size: 14px;
	}

	.ang-button {
		width: 200px;
		margin-left: 20px;
	}

	span {
		font-weight: bold;
	}
`;

const ProModal = ( { onDimiss } ) => (
	<Container>
		<p>{ __( 'Premium template kits are coming soon with Style Kits Pro. You can only preview these layouts for now but feel free to sign up to our mailing list if you want to learn when they become available.', 'ang' ) }</p>
		<ExternalLink className="ang-button secondary" href="https://analogwp.com/style-kits-pro">{ __( 'Learn More', 'ang' ) }</ExternalLink>
	</Container>
);

export default ProModal;
