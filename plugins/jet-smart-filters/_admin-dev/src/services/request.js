let filtersAbortController;

export default {
	async fetch(url, options = {}) {
		let response = await fetch(url, options);

		if (response.status != 200)
			throw new Error(response.status);

		return response;
	},

	async fetchJson(url, params = {}, signal = false) {
		const options = {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-WP-Nonce': window.JetSmartFiltersAdminData.nonce
			},
			body: JSON.stringify(params)
		};

		if (signal)
			options.signal = signal;

		const response = await this.fetch(url, options);

		return response.json();
	},

	async ajax(params) {
		const data = new FormData();

		for (const key in params)
			data.append(key, params[key]);

		return this.fetch(window.JetSmartFiltersAdminData.urls.ajaxurl, {
			method: 'POST',
			body: data,
		});
	},

	async getFilters(params = {}) {
		if (filtersAbortController)
			filtersAbortController.abort();

		filtersAbortController = new window.AbortController();

		return this.fetchJson(this.endpoints.Filters, params, filtersAbortController.signal);
	},

	async getFilter(id) {
		return this.fetchJson(this.endpoints.Filter, { id });
	},

	async updateFilter(id, data) {
		return this.fetchJson(this.endpoints.Filter, { id, data });
	},

	async getTaxonomyTerms(taxonomy) {
		return this.fetchJson(this.endpoints.TaxonomyTerms, { taxonomy });
	},

	async getPostsList(postType, args = {}) {
		args.post_type = postType;

		return this.fetchJson(this.endpoints.PostsList, args);
	},

	async adminModeSwitch(mode) {
		return this.fetchJson(this.endpoints.AdminModeSwitch, { mode });
	},

	async reindexFilters() {
		return this.ajax({
			action: 'jet_smart_filters_admin_indexer',
			nonce: window.JetSmartFiltersAdminData.nonce
		});
	},

	get endpoints() {
		return window.JetSmartFiltersAdminData.urls.endpoints;
	},
};