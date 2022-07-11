import CheckBoxesFilter from './CheckBoxes';

export default class CheckRange extends CheckBoxesFilter {
	name = 'check-range';

	constructor($container) {
		super($container);

		this.mergeSameQueryKeys = false;
	}
}