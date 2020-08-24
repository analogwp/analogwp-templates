const Empty = ( { text = 'No templates found.', ...rest } ) => {
	return (
		<div className="empty-container" { ...rest }>
			<p>{ text }</p>
		</div>
	);
};

export default Empty;
