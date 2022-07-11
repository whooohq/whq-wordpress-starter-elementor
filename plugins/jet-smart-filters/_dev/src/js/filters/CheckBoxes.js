import CheckboxControl from 'bases/controls/Checkbox';
import AdditionalFilterSettings from 'modules/AdditionalFilterSettings';

export default class CheckBoxes extends CheckboxControl {
	name = 'checkboxes';

	constructor($container) {
		const $filter = $container.find('.jet-checkboxes-list');

		super($container, $filter);

		this.mergeSameQueryKeys = true;

		// Init modules
		this.additionalFilterSettings = new AdditionalFilterSettings(this);
	}
}