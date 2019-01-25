import classNames from "classnames";
import Select from "react-select";
import styled from "styled-components";
import AnalogContext from "./AnalogContext";
import Star from "./icons/star";
const { __ } = wp.i18n;

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
	}

	.dropdown {
		width: 140px;
		z-index: 1000;
	}
`;

class Filters extends React.Component {
	constructor() {
		super(...arguments);

		this.searchInput = React.createRef();
	}
	render() {
		const filterOptions = [
			{ value: "all", label: __("Show All", "ang") },
			{ value: "packs", label: __("Only Packs", "ang") }
		];

		const sortOptions = [
			{ value: "latest", label: __("Latest", "ang") },
			{ value: "popular", label: __("Popular", "ang") }
		];
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
						? __("Back to all", "ang")
						: __("My Favorites", "ang")}
				</a>
				<List
					style={{
						display: "none"
					}}
				>
					<label htmlFor="filter">{__("Filter", "ang")}</label>
					<Select
						inputId="filter"
						className="dropdown"
						defaultValue={filterOptions[0]}
						isSearchable={false}
						options={filterOptions}
					/>
				</List>
				<List>
					<label htmlFor="sort">{__("Sort By", "ang")}</label>
					<Select
						inputId="sort"
						className="dropdown"
						defaultValue={sortOptions[0]}
						isSearchable={false}
						options={sortOptions}
					/>
				</List>
				<input
					type="search"
					placeholder="Search"
					ref={this.searchInput}
					onChange={() =>
						this.context.handleSearch(
							this.searchInput.current.value.toLowerCase()
						)
					}
				/>
			</FiltersContainer>
		);
	}
}

Filters.contextType = AnalogContext;

export default Filters;
