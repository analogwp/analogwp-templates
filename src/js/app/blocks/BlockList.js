const BlockList = ( { blocks, category } ) => {
	const filteredBlocks = blocks.filter( block => block.tags.indexOf( category ) > -1 );

	return (
		<ul className="slide-in">
			{ filteredBlocks.map( ( block ) => (
				<li key={ block.id }>
					{ block.thumbnail !== '0' && (
						<figure>
							<img src={ block.thumbnail } loading="lazy" alt={ block.title } />
						</figure>
					) }

					<div className="content">
						{ block.title }
						{ block.isPro && <span className="pro">Pro</span> }
					</div>
				</li>
			)) }
		</ul>
	);
};

export default BlockList;
