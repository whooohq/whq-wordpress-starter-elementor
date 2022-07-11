import ActiveItems from 'bases/ActiveItems';

export default class ActiveFilters extends ActiveItems {
	name = 'active-filters';

	constructor ($activeFilters) {
		const props = {
			templateName: 'active_filter',
			listClass: 'jet-active-filters__list',
			labelClass: 'jet-active-filters__title',
			itemClass: 'jet-active-filter'
		};

		super($activeFilters, props);
	}
}