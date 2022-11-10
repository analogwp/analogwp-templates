const { ExternalLink, Button } = wp.components;
const { __ } = wp.i18n;

const ProModal = () => (
	<div className="pro-modal-container">
		<p>{ __( 'Get unlimited access to the Style Kits library and features with the PRO version.', 'ang' ) }</p>
		<a href="https://analogwp.com/pricing/?utm_medium=plugin&utm_source=library&utm_campaign=style+kits+pro" target="_blank">{ __( 'View Plans', 'ang' ) }</a>
	</div>
);

export default ProModal;
