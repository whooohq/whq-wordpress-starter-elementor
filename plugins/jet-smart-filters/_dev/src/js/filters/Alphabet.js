import CheckboxControl from 'bases/controls/Checkbox';

export default class Alphabet extends CheckboxControl {
	name = 'alphabet';

	constructor($container) {
		const $filter = $container.find('.jet-alphabet-list');

		super($container, $filter, $filter.find('.jet-alphabet-list__input'));
	}
}