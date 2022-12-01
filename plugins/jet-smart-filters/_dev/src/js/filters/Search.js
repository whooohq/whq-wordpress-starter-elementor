import SearchControl from 'bases/controls/Search';

export default class Search extends SearchControl {
	name = 'search';

	constructor($container) {
		const $filter = $container.find('.jet-search-filter');

		super($container, $filter);
	}
}