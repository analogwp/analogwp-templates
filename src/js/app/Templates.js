import classNames from "classnames";
import styled from "styled-components";
import AnalogContext from "./AnalogContext";
import Star from "./icons/star";
import Modal from "./Modal";
const { decodeEntities } = wp.htmlEntities;
const { apiFetch } = wp;
const { __ } = wp.i18n;

const TemplatesList = styled.ul`
	margin: 0;
	display: grid;
	/* grid-template-columns: repeat(4, 1fr); */
	grid-template-columns: repeat(auto-fit, minmax(280px, 280px));
	grid-gap: 25px;
	text-transform: uppercase;
	color: #000;

	li {
		background: #fff;
	}

	p {
		color: #939393;
		font-size: 10px;
		margin: 0;
		font-weight: 500;
	}

	.content {
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding: 13px 20px 7px;

		svg {
			fill: #d0d0d0;
			transition: all 100ms ease-in;
		}

		a:hover,
		a.is-active {
			svg {
				fill: #ff7865;
			}
		}
	}

	h3 {
		font-size: 12px;
		margin: 0;
		font-weight: bold;
	}

	img {
		width: 100%;
		height: auto;
	}

	figure {
		margin: 0;
		position: relative;

		&:hover .actions {
			opacity: 1;
		}
	}

	.actions {
		opacity: 0;
		position: absolute;
		width: 100%;
		height: 100%;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		background: rgba(0, 0, 0, 0.7);
		top: 0;
		left: 0;
		z-index: 100;
	}

	button {
		display: block;
		border: none;
		outline: 0;
		font-size: 12px;
		text-transform: uppercase;
		padding: 10px;
		font-weight: bold;
		background: #ff7865;
		width: 100px;
		color: #fff;
		cursor: pointer;

		+ button {
			margin-top: 10px;
		}
	}

	.tags {
		color: #999999;
		text-transform: capitalize;
		padding: 0 20px 15px 20px;
		letter-spacing: 0;

		span + span:before {
			content: " / ";
		}
	}
`;

const StyledButton = styled.button`
	text-transform: uppercase;
	padding: 5px 10px;
`;

class Templates extends React.Component {
	state = {
		template: null
	};

	setModalContent = template => {
		this.context.dispatch({
			isOpen: !this.context.state.isOpen
		});
		this.setState({
			template: template
		});
	};

	importLayout = template => {
		if (!template) {
			template = this.state.template;
		}

		const editor_id =
			"undefined" !== typeof ElementorConfig ? ElementorConfig.post_id : false;

		apiFetch({
			path: "/agwp/v1/import/elementor",
			method: "post",
			data: {
				template_id: template.id,
				editor_post_id: editor_id
			}
		}).then(data => {
			const template = JSON.parse(data);

			if (typeof elementor !== "undefined") {
				const model = new Backbone.Model({
					getTitle: function getTitle() {
						return "Test";
					}
				});

				elementor.channels.data.trigger("template:before:insert", model);
				for (let i = 0; i < template.content.length; i++) {
					elementor.getPreviewView().addChildElement(template.content[i]);
				}
				elementor.channels.data.trigger("template:after:insert", {});
				window.analogModal.hide();
			}
		});
	};

	render() {
		return (
			<div
				style={{
					position: "relative",
					minHeight: "80vh"
				}}
			>
				{this.context.state.isOpen && (
					<Modal
						template={this.state.template}
						onRequestClose={() => this.context.dispatch({ isOpen: false })}
						onRequestImport={() => this.importLayout()}
					/>
				)}
				<TemplatesList>
					<AnalogContext.Consumer>
						{context =>
							context.state.count >= 1 &&
							context.state.templates.map(template => (
								<li key={template.id}>
									<figure>
										{template.thumbnail && <img src={template.thumbnail} />}
										<div className="actions">
											<StyledButton
												onClick={() => this.setModalContent(template)}
											>
												{__("Preview", "ang")}
											</StyledButton>
											<StyledButton onClick={() => this.importLayout(template)}>
												{__("Import", "ang")}
											</StyledButton>
										</div>
									</figure>
									<div className="content">
										<h3>{decodeEntities(template.title)}</h3>
										<a
											href="#"
											className={classNames({
												"is-active": template.id in this.context.state.favorites
											})}
											onClick={e => {
												e.preventDefault();

												let favorites = this.context.state.favorites;

												this.context.markFavorite(
													template.id,
													!(template.id in favorites)
												);

												template.id in favorites
													? delete favorites[template.id]
													: (favorites[template.id] = !(
															template.id in favorites
													  ));

												this.context.dispatch({ favorites });

												if (this.context.state.showing_favorites) {
													const filtered_templates = this.context.state.templates.filter(
														template => template.id in favorites
													);
													this.context.dispatch({
														templates: filtered_templates
													});
												}
											}}
										>
											<Star />
										</a>
									</div>
									{template.tags && (
										<div className="tags">
											{template.tags.map(tag => (
												<span key={tag}>{tag}</span>
											))}
										</div>
									)}
								</li>
							))
						}
					</AnalogContext.Consumer>
				</TemplatesList>
			</div>
		);
	}
}

Templates.contextType = AnalogContext;

export default Templates;
