export default class AdditionalFilterSettings {
	constructor(filter) {
		this.filter = filter;
		this.$items = this.filter.$filter.find('.jet-filter-row');
		this.inputNotEmptyClass = 'jet-input-not-empty';

		// Search
		this.searchClass = 'jet-filter-items-search';
		this.$searchContainer = this.filter.$container.find(`.${this.searchClass}`);
		if (this.$searchContainer.length) {
			this.searchInit();
		}

		// MoreLess
		this.morelessClass = 'jet-filter-items-moreless';
		this.$moreless = this.filter.$container.find(`.${this.morelessClass}`);
		if (this.$moreless.length) {
			this.morelessInit();
		}

		// Dropdown
		this.dropdownClass = 'jet-filter-items-dropdown';
		this.$dropdown = this.filter.$container.find(`.${this.dropdownClass}`);
		if (this.$dropdown.length) {
			this.dropdownInit();
		}

		this.toggleItemsVisibility();
	}

	// Search
	searchInit() {
		this.searchValue = '';
		this.$searchInput = this.$searchContainer.find(`.${this.searchClass}__input`);
		this.$searchClear = this.$searchContainer.find(`.${this.searchClass}__clear`);

		if (this.$searchInput.length)
			this.$searchInput.on('keyup', evt => {
				this.searchApply(evt.target.value);
			});

		if (this.$searchClear.length)
			this.$searchClear.on('click', () => {
				this.searchClear();
			});
	}

	searchApply(value) {
		this.searchValue = value.toLowerCase();

		if (this.searchValue) {
			this.$searchInput.addClass(this.inputNotEmptyClass);
		} else {
			this.$searchInput.removeClass(this.inputNotEmptyClass);
		}

		this.toggleItemsVisibility();
	}

	searchClear() {
		this.$searchInput.val('');
		this.searchApply('');
	}

	// MoreLess
	morelessInit() {
		this.$morelessToggle = this.$moreless.find(`.${this.morelessClass}__toggle`);
		this.numberOfDisplayed = this.$moreless.data('less-items-count');
		this.moreBtnText = this.$moreless.data('more-text');
		this.lessBtnText = this.$moreless.data('less-text');
		this.moreBtnClass = 'jet-more-btn';
		this.lessBtnClass = 'jet-less-btn';
		this.moreState = false;

		this.$morelessToggle.addClass(this.moreBtnClass);

		this.$morelessToggle.on('click', () => {
			this.moreLessToggle();
		});
	}

	moreLessToggle() {
		if (this.moreState) {
			this.switchToLess();
		} else {
			this.switchToMore();
		}
	}

	switchToMore() {
		this.moreState = true;
		this.$morelessToggle.removeClass(this.moreBtnClass).addClass(this.lessBtnClass).text(this.lessBtnText);

		this.toggleItemsVisibility();
	}

	switchToLess() {
		this.moreState = false;
		this.$morelessToggle.removeClass(this.lessBtnClass).addClass(this.moreBtnClass).text(this.moreBtnText);

		this.toggleItemsVisibility();
	}

	// Dropdown
	dropdownInit() {
		this.$dropdownLabel = this.$dropdown.find(`.${this.dropdownClass}__label`);
		this.$dropdownBody = this.$dropdown.find(`.${this.dropdownClass}__body`);
		this.dropdownOpenClass = 'jet-dropdown-open';
		this.dropdownPlaceholderText = this.$dropdownLabel.html();
		this.dropdownState = false;

		$(document).on('click', evt => {
			this.documentClick(evt);
		});

		if (this.$dropdownLabel.length)
			this.$dropdownLabel.on('click', () => {
				this.dropdownToggle();
			});
	}

	dropdownToggle() {
		if (this.dropdownState) {
			this.dropdownClose();
		} else {
			this.dropdownOpen();
		}
	}

	dropdownClose() {
		this.dropdownState = false;
		this.$dropdown.removeClass(this.dropdownOpenClass);
	}

	dropdownOpen() {
		this.dropdownState = true;
		this.$dropdown.addClass(this.dropdownOpenClass);

		if (this.$searchInput)
			this.$searchInput.focus();
	}

	documentClick(evt) {
		if (!$.contains(this.$dropdown.get(0), evt.target))
			this.dropdownClose();
	}

	dropDownItemsUpdate() {
		// remove all jQuery events to avoid memory leak
		this.$dropdownLabel.find('*').off();

		const $checked = this.filter.$checked;
		const $selected = this.filter.$selected;

		if ($checked && $checked.length) {
			this.$dropdownLabel.html('');

			const $items = $('<div class="jet-filter-items-dropdown__active"></div>');
			this.$dropdownLabel.append($items);

			$checked.each(index => {
				const $item = $checked.eq(index);

				$items.append(
					$(`<div class="jet-filter-items-dropdown__active__item">${$item.data('label')}<span class="jet-filter-items-dropdown__active__item__remove">×</span></div>`)
						.one('click', event => {
							event.stopPropagation();

							this.filter.reset($item.val());
							this.filter.emitFiterChange();
						})
				);
			});
		} else if ($selected && $selected.val()) {
			this.$dropdownLabel.html($selected.data('label'));
		} else {
			this.$dropdownLabel.html(this.dropdownPlaceholderText);
		}
	}

	dataUpdated() {
		if (this.$dropdown.length && this.$dropdownLabel.length)
			this.dropDownItemsUpdate();
	}

	toggleItemsVisibility() {
		const $visibleItems = this.$items.filter(index => {
			const $item = this.$items.eq(index),
				$input = $item.find('input');

			// ignore the item if it was hidden by the indexer as empty
			if ($item.hasClass('jet-filter-row-hide'))
				return false;

			// search value not found
			if (this.searchValue && $input.data('label').toString().toLowerCase().indexOf(this.searchValue) === -1) {
				$item.hide();

				return false;
			}

			$item.show();

			return true;
		});

		// MoreLess
		if (this.numberOfDisplayed) {
			if ($visibleItems.length > this.numberOfDisplayed) {
				if (!this.moreState) {
					// more than number of displayed
					for (let index = this.numberOfDisplayed; index < $visibleItems.length; index++) {
						$visibleItems.eq(index).hide();
					}
				}

				this.$moreless.show();
			} else {
				this.$moreless.hide();
			}
		}
	}
}