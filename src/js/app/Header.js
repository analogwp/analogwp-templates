import classNames from "classnames";
import { default as styled, keyframes } from "styled-components";
import AnalogContext from "./AnalogContext";
import Close from "./icons/close";
import Logo from "./icons/logo";
import Refresh from "./icons/refresh";
const { __ } = wp.i18n;

const rotate = keyframes`
  from {
    transform: rotate(0deg);
  }

  to {
    transform: rotate(360deg);
  }
`;

const Container = styled.div`
	background: #fff;
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 12px 24px;

	svg {
		vertical-align: bottom;
	}

	a {
		color: #060606;
		text-transform: uppercase;
		font-size: 12px;
		font-weight: bold;
		text-decoration: none;
		display: inline-flex;
		align-items: center;

		&.is-active {
			pointer-events: none;
			svg {
				animation: ${rotate} 2s linear infinite;
			}
		}

		svg {
			margin-left: 10px;
		}

		&:first-of-type {
			margin-left: auto;
		}
		+ a {
			position: relative;
			margin-left: 30px;
			&:before {
				content: "";
				background: #d4d4d4;
				width: 2px;
				height: 25px;
				position: absolute;
				display: block;
				left: -16px;
				top: -4px;
			}
		}
	}
`;

const Header = () => (
	<Container>
		<Logo />
		<AnalogContext.Consumer>
			{context => (
				<a
					href="#"
					className={classNames({
						"is-active": context.state.syncing
					})}
					onClick={e => {
						e.preventDefault();
						context.forceRefresh();
					}}
				>
					{context.state.syncing
						? __("Syncing...", "ang")
						: __("Sync Library", "ang")}
					<Refresh />
				</a>
			)}
		</AnalogContext.Consumer>
		{!AGWP.is_settings_page && (
			<a className="close-modal" href="#">
				{__("Close", "ang")} <Close />
			</a>
		)}
	</Container>
);

export default Header;
