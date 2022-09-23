const { ExternalLink, Button } = wp.components;
const { __ } = wp.i18n;

const ProModal = () => (
	<div className="pro-modal-container">
		<p>{ __( 'Get unlimited access to all library and features with Style Kits PRO.', 'ang' ) }</p>
		<Button isSecondary ><ExternalLink href="https://analogwp.com/style-kits-pro/?utm_medium=plugin&utm_source=library&utm_campaign=style+kits+pro">{ __( 'Learn More', 'ang' ) }</ExternalLink></Button>
	</div>
);

export default ProModal;
