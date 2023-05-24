import eventBus from 'includes/event-bus';

export default class TabIndex {
	constructor(filter) {
		if (!filter.$filter)
			return;

		this.filter = filter;

		switch (this.filter.name) {
			case 'checkboxes':
			case 'radio':
			case 'check-range':
			case 'alphabet':
			case 'visual':
				this.checkboxes();

				if (this.filter.additionalFilterSettings && this.filter.additionalFilterSettings.$dropdown.length)
					this.checkboxesDropdown();

				break;

			case 'rating':
				this.rating();

				break;

			case 'range':
				this.range();

				break;

			case 'date-period':
				this.datePeriod();

				break;

			case 'pagination':
				this.pagination();

				break;

			case 'active-filters':
			case 'active-tags':
				this.activeItems();

				break;
		}

		// Indexer addition
		this.indexerAddition();
	}

	// Methods
	itemsTriggerClickOnEnterPress($items) {
		$items.keypress(e => {
			e.preventDefault();

			if (e.keyCode === 13)
				$(e.target).trigger('click');
		});
	}

	/* 
	 * TabIndex types
	 */
	checkboxes() {
		this.filter.$filter.find('label[tabindex]').keypress(e => {
			e.preventDefault();

			if (![13, 32].includes(e.keyCode))
				return;

			const $itemInput = $(e.target).find('input');

			$itemInput.prop('checked', !$itemInput.prop('checked'));
			this.filter.processData();
			this.filter.emitFiterChange();
		});

		this.filter.$filter.find('.jet-filter-items-moreless[tabindex]').keypress(e => {
			e.preventDefault();

			if (![13, 32].includes(e.keyCode))
				return;

			this.filter.additionalFilterSettings.moreLessToggle();
		});
	}

	checkboxesDropdown() {
		this.filter.additionalFilterSettings.$dropdown.find('.jet-filter-items-dropdown__label').keypress(e => {
			e.preventDefault();

			if (![13, 32].includes(e.keyCode))
				return;

			this.filter.additionalFilterSettings.dropdownToggle();
		});

		this.filter.$filter.find('[tabindex]').last().keydown(e => {
			if (e.keyCode === 9)
				this.filter.additionalFilterSettings.dropdownClose();
		});
	}

	rating() {
		this.filter.$filter.find('[tabindex]').keypress(e => {
			e.preventDefault();

			$(e.target).prev('input').trigger('click');
		});
	}

	range() {
		this.filter.$filter.find('[tabindex]').keydown(e => {
			if (![13, 32, 37, 38, 39, 40].includes(e.keyCode))
				return;

			e.preventDefault();

			const $input = $(e.target);

			// arrow keys processing
			if ([37, 38, 39, 40].includes(e.keyCode)) {
				// decrease
				if ([37, 40].includes(e.keyCode))
					$input.val(parseFloat($input.val()) - parseFloat($input.attr('step')));

				//increase
				if ([38, 39].includes(e.keyCode))
					$input.val(parseFloat($input.val()) + parseFloat($input.attr('step')));

				$input.trigger('input');
				this.filter.processData();
			}

			if (e.keyCode === 13)
				this.filter.emitFiterChange();
		});
	}

	datePeriod() {
		if (!this.filter.$datepickerBtn.is('[tabindex]'))
			return;

		this.filter.$datepickerBtn.focus(() => {
			this.filter.datepicker.show();
		});

		this.filter.$datepickerBtn.blur(() => {
			setTimeout(() => {
				if ($(':focus').length)
					this.filter.datepicker.hide();
			});
		});

		this.filter.$datepickerBtn.keydown(e => {
			if (![13, 32, 37, 39].includes(e.keyCode))
				return;

			e.preventDefault();

			if (37 == e.keyCode)
				this.filter.prevPeriod();

			if (39 == e.keyCode)
				this.filter.nextPeriod();
		});

		this.filter.$prevPeriodBtn.keypress(e => {
			if (![13, 32, 37, 39].includes(e.keyCode))
				return;

			e.preventDefault();

			if (e.keyCode === 13)
				this.filter.prevPeriod();
		});

		this.filter.$nextPeriodBtn.keypress(e => {
			if (![13, 32, 37, 39].includes(e.keyCode))
				return;

			e.preventDefault();

			if (e.keyCode === 13)
				this.filter.nextPeriod();
		});
	}

	pagination() {
		this.itemsTriggerClickOnEnterPress(this.filter.$filter.find('[tabindex]'));

		eventBus.subscribe('pagination/itemsBuilt', (filter) => {
			this.itemsTriggerClickOnEnterPress(filter.$filter.find('[tabindex]'));
		});
	}

	activeItems() {
		this.itemsTriggerClickOnEnterPress(this.filter.$activeItemsContainer.find('[tabindex]'));

		eventBus.subscribe('activeItems/itemsBuilt', (filter) => {
			this.itemsTriggerClickOnEnterPress(filter.$activeItemsContainer.find('[tabindex]'));
		});
	}

	indexerAddition() {
		if (!this.filter.indexer)
			return;

		// disable tabindex if item disabled by indexer
		if (this.filter.indexer.indexerRule === 'disable') {
			const disableTabindex = () => {
				this.filter.$filter.find('.jet-filter-row [tabindex="-1"]').attr('tabindex', '0');
				this.filter.$filter.find('.jet-filter-row-disable [tabindex="0"]').attr('tabindex', '-1');
			};

			disableTabindex();

			eventBus.subscribe('ajaxFilters/updated', (provider, queryId) => {
				if (!this.filter.isCurrentProvider({ provider, queryId }))
					return;

				disableTabindex();
			});
		}
	}
}