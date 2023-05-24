import {
	getNesting
} from 'includes/utility';

export default class CustomProvider {
	constructor(filterGroup) {
		this.filterGroup = filterGroup;

		// jetEngine Calendar add current query to request
		$(document).on('jet-engine-request-calendar', () => {
			const currentRequest = getNesting(JetEngine, 'currentRequest');

			if (!currentRequest || this.filterGroup.provider !== 'jet-engine-calendar')
				return;

			if (currentRequest.settings && currentRequest.settings.hasOwnProperty('_element_id')) {

				const queryId = currentRequest.settings._element_id ? currentRequest.settings._element_id : 'default';

				if (this.filterGroup.queryId !== queryId) {
					return;
				}
			}

			currentRequest.query = this.filterGroup.currentQuery;
			currentRequest.provider = this.filterGroup.provider + '/' + this.filterGroup.queryId;

			const monthData = currentRequest.month.split(' ');

			if (2 === monthData.length
				&& window.JetSmartFilterSettings.settings
				&& window.JetSmartFilterSettings.settings[this.filterGroup.provider]
				&& window.JetSmartFilterSettings.settings[this.filterGroup.provider][this.filterGroup.queryId]
			) {
				window.JetSmartFilterSettings.settings[this.filterGroup.provider][this.filterGroup.queryId]['custom_start_from'] = true;
				window.JetSmartFilterSettings.settings[this.filterGroup.provider][this.filterGroup.queryId]['start_from_month'] = monthData[0];
				window.JetSmartFilterSettings.settings[this.filterGroup.provider][this.filterGroup.queryId]['start_from_year'] = monthData[1];
			}

		});

		// jetWooBuilder
		$(document).on('jet-woo-builder-content-rendered', () => {
			if (this.filterGroup.provider !== 'woocommerce-archive')
				return;

			this.filterGroup.getFiltersByName('pagination').forEach(paginationFilter => {
				paginationFilter.resetMoreActive();
			});
		});
	}
}