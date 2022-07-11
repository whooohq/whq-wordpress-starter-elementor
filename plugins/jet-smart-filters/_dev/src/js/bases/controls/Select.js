import Filter from 'bases/Filter';

export default class SelectControl extends Filter {
	constructor($container, $filter, $select) {
		super($filter, $container);

		this.$select = $select || $filter.find('select');
		this.$allOption = this.getItemByValue('all');
		this.isSelect = this.$select.prop('tagName') === 'SELECT' ? true : false;
		this.canDeselect = this.$filter.data('can-deselect');

		if (this.$allOption)
			this.$allOption.data('all-option', '1').val('');

		this.processData();
		this.initEvent();

		// reset the select when returning to the page
		this.resetSelectOnInitialization();
	}

	addFilterChangeEvent() {
		this.$select.on('change', () => {
			this.processData();
			this.emitFiterChange();
		})

		if (!this.isSelect && this.canDeselect) {
			this.$select.on('click', evt => {
				const $radioItem = jQuery(evt.target);

				if ($radioItem.val() === this.dataValue)
					$radioItem.prop('checked', false).trigger('change');
			})
		}
	}

	removeChangeEvent() {
		this.$select.off();
	}

	processData() {
		this.dataValue = this.$selected.val();

		if (!this.dataValue)
			this.checkAllOption();

		if (this.additionalFilterSettings)
			this.additionalFilterSettings.dataUpdated();
	}

	setData(newData) {
		const $item = this.getItemByValue(newData);

		if ($item)
			$item.prop(this.isSelect ? 'selected' : 'checked', true);

		this.processData();
	}

	reset() {
		this.$selected.prop(this.isSelect ? 'selected' : 'checked', false);
		this.processData();
	}

	resetSelectOnInitialization() {
		if (!this.isSelect)
			return;

		$(document).ready(function () {
			if (this.filterGroup && this.filterGroup.currentQuery && this.filterGroup.currentQuery[this.queryKey])
				return;

			setTimeout(() => {
				this.$select.prop('selectedIndex', 0);
			});
		}.bind(this));
	}

	resetSelectOnInitialization() {
		if (!this.isSelect)
			return;

		$(document).ready(function () {
			if (this.filterGroup && this.filterGroup.currentQuery && this.filterGroup.currentQuery[this.queryKey])
				return;

			setTimeout(() => {
				this.$select.prop('selectedIndex', 0);
			});
		}.bind(this));
	}

	get activeValue() {
		const $item = this.getItemByValue(this.data);

		if ($item)
			return $item.data('label');
	}

	get $selected() {
		return this.isSelect ?
			this.$select.find(':checked') :
			this.$select.filter(':checked');
	}

	// Additional methods
	getItemByValue(value) {
		let $item = false;

		if (this.isSelect) {
			this.$select.find('option').each((index, item) => {
				const $option = $(item);

				if ($option.val() === value)
					$item = $option
			});
		} else {
			$item = this.$select.filter('[value="' + value + '"]');
		}

		return $item;
	}

	checkAllOption() {
		if (!this.$allOption)
			return;

		this.$allOption.prop('checked', true);
	}
}