import styled from 'styled-components';
import AnalogPro from './icons/analogpro';
const { __ } = wp.i18n;

const Container = styled.div`
	display: flex;
	align-items: center;
	max-width: 850px;
	margin: auto;

	h3 {
		font-size: 25px;
		font-weight: 600;
		color: #23282C;
		line-height: 1.4;
		margin-top: 0;
	}

	svg {
		margin-bottom: 30px;
	}

	p {
		font-size: 19px;
		color: #6D6D6D;
		font-weight: 500;
	}

	> div {
		width: 50%;
	}

	> div:nth-child(2) {
		background: #fff;
		padding: 40px;
		flex-basis: 40%;
		margin-left: 30px;
	}

	.button {
		color: #fff;
		background: var(--ang-accent);
		border-radius: 0;
		font-weight: 600;
		font-size: 15px;
		border: none;
		box-shadow: none;
		padding: 10px 20px;
		height: auto;
		margin-top: 30px;
	}

	ul {
		font-size: 15px;
		color: #6D6D6D;
		font-weight: 500;
	}
	li {
		margin-bottom: 15px;
	}

	.sticky {
		font-size: 15px;
		font-weight: 500;
		color: #C2C2C2;
		margin-top: 30px;
		margin-bottom: 0;
	}
	.button-plain.button-plain {
		font-size: 13px;
		margin-bottom: 10px;
	}
`;

const ProModal = ( { onDimiss } ) => (
	<Container>
		<div>
			<p><button className="button-plain" onClick={ () => {
				window.scrollTo( 0, 0 );
				onDimiss();
			} }>{ __( 'Back to library', 'ang' ) }</button></p>
			<AnalogPro />
			<h3>{ __( 'Elevate your Elementor design with Analog Pro', 'ang' ) }</h3>
			<p>{ __( 'Step up your workflow with unlimited design resources for your Elementor-powered projects.', 'ang' ) }</p>
			<a href="https://analogwp.com/" className="ang-button" target="_blank" rel="external noopener noreferrer">{ __( 'Learn More', 'ang' ) }</a>
		</div>
		<div>
			<h3>{ __( 'Why Pro', 'ang' ) }</h3>
			<ul>
				<li>{ __( 'Access to all template library', 'ang' ) }</li>
				<li>{ __( 'Templates for singles, archives, popups and more', 'ang' ) }</li>
				<li>{ __( 'Multi-niche, human made design that makes sense', 'ang' ) }</li>
				<li>{ __( 'Unlimited license for peace of mind', 'ang' ) }</li>
			</ul>

			<p className="sticky">{ __( '* Requires Elementor Pro', 'ang' ) }</p>
		</div>
	</Container>
);

export default ProModal;
