import RangeControl from 'bases/controls/Range';

export default class Range extends RangeControl {
	name = 'range';

	constructor($container) {
		const $filter = $container.find('.jet-range');

		super($container, $filter);
	}
}