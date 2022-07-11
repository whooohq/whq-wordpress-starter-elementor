import Filter from 'bases/Filter';

export default class CheckboxControl extends Filter {
	constructor($container, $filter, $checkboxes) {
		super($filter, $container);

		this.$checkboxes = $checkboxes || $filter.find(':checkbox');
		this.$checkboxesList = $container.find('.jet-checkboxes-list');
		this.relationalOperator = this.$filter.data('relational-operator');
		this.canDeselect = this.$filter.data('can-deselect');
		this.inputNotEmptyClass = 'jet-input-not-empty';

		this.processData();
		this.initEvent();
	}

	addFilterChangeEvent() {
		this.$checkboxes.on('change', () => {
			this.processData();
			this.emitFiterChange();
		});

		if (this.canDeselect) {
			this.$checkboxes.on('click', evt => {
				const $checkboxItem = jQuery(evt.target);

				if ($checkboxItem.val() === this.dataValue)
					$checkboxItem.prop('checked', false).trigger('change');
			})
		}
	}

	removeChangeEvent() {
		this.$checkboxes.off();
		this.$dropdownLabel.off();
	}

	processData() {
		const $checked = this.$checked;
		let dataValue = false;

		if ($checked.length === 1) {
			dataValue = $checked.val();
		} else if ($checked.length > 1) {
			dataValue = [];

			$checked.each(index => {
				dataValue.push($checked.get(index).value);
			})

			if (this.relationalOperator)
				dataValue.push('operator_' + this.relationalOperator);
		}

		this.dataValue = dataValue;

		if (this.additionalFilterSettings)
			this.additionalFilterSettings.dataUpdated();
	}

	setData(newData) {
		this.getItemsByValue(newData).forEach($item => {
			$item.prop('checked', true);
		});

		this.processData();
	}

	reset(value = false) {
		if (value) {
			// reset one value
			this.getItemByValue(value).prop('checked', false);
			this.processData();
		} else {
			// reset filter
			this.getItemsByValue(this.dataValue).forEach($item => {
				$item.prop('checked', false);
			});

			this.processData();
		}
	}

	get activeValue() {
		let currentData = this.data,
			activeValue = '',
			delimiter = '';

		if (!Array.isArray(currentData))
			currentData = [currentData];

		currentData.forEach(value => {
			const label = this.getValueLabel(value);

			if (label) {
				activeValue += delimiter + label;
				delimiter = ', ';
			}
		});

		return activeValue || false;
	}

	get $checked() {
		return this.$checkboxes.filter(':checked');
	}

	// Additional methods
	getItemsByValue(values) {
		const items = [];

		if (!Array.isArray(values))
			values = [values];

		values.forEach(value => {
			items.push(this.getItemByValue(value));
		});

		return items;
	}

	getItemByValue(value) {
		return this.$checkboxes.filter('[value="' + value + '"]');
	}

	getValueLabel(value) {
		return this.$checkboxes.filter('[value="' + value + '"]').data('label');
	}
}