import SelectControl from 'bases/controls/Select';

export default class Select extends SelectControl {
	name = 'select';

	constructor($container) {
		const $filter = $container.find('.jet-select');

		super($container, $filter);

		this.mergeSameQueryKeys = true;
	}
}