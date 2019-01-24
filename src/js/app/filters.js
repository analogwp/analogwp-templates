import classNames from "classnames";
import styled from "styled-components";
import AnalogContext from "./AnalogContext";
import Star from "./icons/star";

const FiltersContainer = styled.div`
	margin: 0 0 40px 0;
	display: flex;
	text-transform: uppercase;
	font-weight: 600;
	align-items: center;
	color: #060606;

	a {
		text-decoration: none;
		color: currentColor;
		&:hover {
			color: #000;
		}
	}
	input[type="search"] {
		margin-left: auto;
		text-transform: uppercase;
		padding: 12px;
		border: none;
		outline: none;
		width: 250px;
	}
	p {
		margin: 0;
		line-height: 1;
	}

	.favorites {
		margin-right: 20px;
		svg {
			fill: #060606;
		}
	}

	.is-active {
		svg {
			fill: #ff7865;
		}
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

class Filters extends React.Component {
	render() {
		return (
			<FiltersContainer>
				<a
					href="#"
					onClick={this.context.toggleFavorites}
					className={classNames("favorites", {
						"is-active": this.context.state.showing_favorites
					})}
				>
					<Star />{" "}
					{this.context.state.showing_favorites
						? "Back to all"
						: "My Favorites"}
				</a>
				<List>
					<li>Sort By:</li>
					<li>
						<a href="#">Popular</a>
					</li>
					<li>
						<a href="#">New</a>
					</li>
				</List>
				<List>
					<li>Show:</li>
					<li>
						<a href="#">All</a>
					</li>
					<li>
						<a href="#">Only Packs</a>
					</li>
				</List>

				<input type="search" placeholder="Search" />
			</FiltersContainer>
		);
	}
}

Filters.contextType = AnalogContext;

export default Filters;
