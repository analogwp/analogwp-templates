const { ExternalLink, Button } = wp.components;
const { __ } = wp.i18n;

const ProModal = ( { type } ) => (
	<div className="pro-modal-container">
		<p>
			<span role="img" aria-label="zap">⚡</span> { __( 'To import Pro ', 'ang' ) + type + __( ', you’ll need an active ', 'ang' ) } <strong>{ __( 'Style Kits Pro', 'ang' ) }</strong> { __( 'license.', 'ang' ) }
		</p>
		<Button isSecondary ><ExternalLink href="https://analogwp.com/style-kits-pro/?utm_medium=plugin&utm_source=library&utm_campaign=style+kits+pro">{ __( 'Learn More', 'ang' ) }</ExternalLink></Button>
	</div>
);

export default ProModal;
