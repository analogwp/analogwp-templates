import styled from 'styled-components';

const FiltersContainer = styled.div`
	margin: 0 0 40px 0;
	display: flex;
	text-transform: uppercase;
	font-weight: 600;
	align-items: center;
	color: #959595;

	a {
		text-decoration: none;
		color: currentColor;
		&:hover {
			color: #000;
		}
	}
	input[type=search] {
		margin-left: auto;
		text-transform: uppercase;
		padding: 12px;
		border: none;
		outline: none;
		width: 250px;
	}
`;

const List = styled.ul`
	margin: 0;
	padding: 0;
	display: inline-flex;
	align-items: center;

	+ ul {
		margin-left: 100px;
	}

	li {
		margin-bottom: 0;
		+ li {
			margin-left: 20px;
		}
	}
`;

const Filters = () => (
	<FiltersContainer>
		<List>
			<li>Sort By:</li>
			<li><a href="#">Popular</a></li>
			<li><a href="#">New</a></li>
		</List>
		<List>
			<li>Show:</li>
			<li><a href="#">All</a></li>
			<li><a href="#">Only Packs</a></li>
		</List>

		<input type="search" placeholder="Search" />
	</FiltersContainer>
);

export default Filters;
