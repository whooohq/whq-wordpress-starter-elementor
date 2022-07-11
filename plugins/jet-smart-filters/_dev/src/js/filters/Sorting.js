import SelectControl from 'bases/controls/Select';

export default class Sorting extends SelectControl {
	name = 'sorting';

	constructor($container) {
		const $filter = $container.find('.jet-sorting');

		super($container, $filter, $filter.find('.jet-sorting-select'));

		this.mergeSameQueryKeys = true;
	}
}