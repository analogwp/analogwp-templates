const { apiFetch } = wp;

export async function markFavorite(id, favorite = true) {
	let data = [];

	await apiFetch({
		path: "/agwp/v1/mark_favorite",
		method: "post",
		data: {
			template_id: id,
			favorite
		}
	}).then(response => {
		data = response;
	});

	return data;
}
