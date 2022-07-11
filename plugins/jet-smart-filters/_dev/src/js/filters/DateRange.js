import DateRangeControl from 'bases/controls/DateRange';

export default class DateRange extends DateRangeControl {
	name = 'date-range';

	constructor ($container) {
		const $filter = $container.find('.jet-date-range');

		super($container, $filter);
	}
}