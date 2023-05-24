import SelectControl from 'bases/controls/Select';
import AdditionalFilterSettings from 'modules/AdditionalFilterSettings';

export default class Radio extends SelectControl {
	name = 'radio';

	constructor ($container) {
		const $filter = $container.find('.jet-radio-list');

		super($container, $filter, $filter.find(':radio'));

		this.mergeSameQueryKeys = true;

		// Init modules
		this.additionalFilterSettings = new AdditionalFilterSettings(this);
	}
}