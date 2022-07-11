import Filter from 'bases/Filter';
import eventBus from 'includes/event-bus';

export default class SearchControl extends Filter {
	searchInputSelector = '.jet-search-filter__input';
	searchSubmitSelector = '.jet-search-filter__submit';
	searchClearSelector = '.jet-search-filter__input-clear';

	searchLoadingClass = 'jet-filters-single-loading';
	inputNotEmptyClass = 'jet-input-not-empty';
	delayID = null;

	constructor($container, $filter, $searchInput, $searchSubmit, $searchClear) {
		super($filter, $container);

		this.$searchInput = $searchInput || $filter.find(this.searchInputSelector);
		this.$searchSubmit = $searchSubmit || $filter.find(this.searchSubmitSelector);
		this.$searchClear = $searchClear || $filter.find(this.searchClearSelector);

		this.processData();
		this.addFilterChangeEvent();

		// Event subscriptions
		eventBus.subscribe('ajaxFilters/end-loading', () => {
			this.$filter.removeClass(this.searchLoadingClass);
		});
	}

	addFilterChangeEvent() {
		this.$searchSubmit.on('click', () => {
			this.emitFiterChange();
		});

		this.$searchClear.on('click', () => {
			this.$searchInput.val('');
			this.$searchInput.removeClass(this.inputNotEmptyClass);
			this.emitFiterChange();
		});

		this.$searchInput.on('keyup', evt => {
			const value = evt.target.value;

			if (value === this.dataValue)
				return;

			if (this.applyType === 'ajax-ontyping') {
				if (this.minLettersCount <= value.length) {
					this.emitFiterChangeWithDelay();

					this.$searchInput.addClass(this.inputNotEmptyClass);
				} else {
					if (this.$searchInput.hasClass(this.inputNotEmptyClass)) {
						this.emitFiterChangeWithDelay();
					}

					this.$searchInput.removeClass(this.inputNotEmptyClass);
				}
			} else if (evt.keyCode === 13) {
				this.emitFiterChange();
			}
		});
	}

	removeChangeEvent() {
		this.$searchSubmit.off();
		this.$searchClear.off();
		this.$searchInput.off();
	}

	processData() {
		this.dataValue = this.$searchInput.val();

		if (this.minLettersCount && this.minLettersCount > this.dataValue.length) {
			this.dataValue = '';
		}
	}

	setData(newData) {
		this.$searchInput.val(newData);

		if (this.applyType === 'ajax-ontyping') {
			if (this.minLettersCount <= newData.length) {
				this.$searchInput.addClass(this.inputNotEmptyClass);
			}
		}

		this.processData();
	}

	reset() {
		this.dataValue = false;
		this.$searchInput.val('');
		this.$searchInput.removeClass(this.inputNotEmptyClass);
	}

	emitFiterChange() {
		this.processData();
		super.emitFiterChange();
	}

	emitFiterChangeWithDelay(delay = 350) {
		clearTimeout(this.delayID);
		this.delayID = setTimeout(() => {
			this.$filter.addClass(this.searchLoadingClass);
			this.processData();
			this.emitFiterChange();
		}, delay);
	}

	get minLettersCount() {
		return this.$filter.data('min-letters-count');
	}

	get activeValue() {
		return this.dataValue;
	}
}