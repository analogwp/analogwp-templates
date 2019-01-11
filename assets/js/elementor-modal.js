'undefined' != typeof jQuery && ! (function ($) {
	$(function () {
		function modal() {
			elementorCommon && (window.analogModal || (window.analogModal = elementorCommon.dialogsManager.createWidget("lightbox", {
				id: "analogwp-templates-modal",
				headerMessage: 'What is this???',
				message: "",
				hide: {
					auto: !1,
					onClick: !1,
					onOutsideClick: !1,
					onOutsideContextMenu: !1,
					onBackgroundClick: !0
				},
				position: {
					my: "center",
					at: "center"
				},
				onShow: function () {
					const content = window.analogModal.getElements("content");
					content.append('<div id="analogwp-templates"></div>');
				},
				onHide: function () {}
			}), window.analogModal.getElements("header").remove(), window.analogModal.getElements("message").append(window.analogModal.addElement("content"))), window.analogModal.show())
		}

		window.analogModal = null;

		const template = $("#tmpl-elementor-add-section");

		if(template.length > 0 && typeof elementor !== undefined) {
			let text = template.text();

			text = text.replace('<div class="elementor-add-section-drag-title', '<div class="elementor-add-section-area-button elementor-add-analogwp-button" title="AnalogWP Templates"> <i class="fa fa-circle-o"></i></div> <div class="elementor-add-section-drag-title'), template.text(text), elementor.on("preview:loaded", function () {
				$(elementor.$previewContents[0].body).on("click", ".elementor-add-analogwp-button", modal);
			});
		}
	});
}(jQuery));
