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
		svg {
			margin-right: 8px;
			fill: #060606;
		}
	}

	.is-active {
		svg {
			fill: #ff7865;
		}
	}
`;

const List = styled.div`
	margin: 0;
	padding: 0;
	display: inline-flex;
	align-items: center;
	position: relative;
	margin-left: 40px;
	&:before {
		content: "";
		width: 2px;
		height: 25px;
		background: #d4d4d4;
		transform: translateX(-21px);
	}

	label {
		color: #969696;
		margin-right: 15px;
		letter-spacing: 1px;
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
				{/* Might use this for custom select https://react-select.com/home */}
				<List>
					<label htmlFor="filter">Filter</label>
					<select id="filter" name="filter">
						<option value="all">Show All</option>
						<option value="packs">Only Packs</option>
					</select>
				</List>
				<List>
					<label htmlFor="sort">Sort By</label>
					<select id="sort" name="sort">
						<option value="latest">Latest</option>
						<option value="popular">Popular</option>
					</select>
				</List>

				<input type="search" placeholder="Search" />
			</FiltersContainer>
		);
	}
}

Filters.contextType = AnalogContext;

export default Filters;
