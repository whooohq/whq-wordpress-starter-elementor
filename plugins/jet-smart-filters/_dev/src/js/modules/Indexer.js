import eventBus from 'includes/event-bus';
import {
	isEqual,
	getNesting
} from 'includes/utility';

export default class Indexer {
	rowSelector = '.jet-filter-row';
	counterSelector = '.jet-filters-counter';

	constructor(filter) {
		this.filter = filter;

		this.currentIndexerData = this.indexerData;
		this.isCounter = this.filter.$container.data('showCounter') === 'yes' ? true : false;
		this.indexerRule = this.filter.$container.data('indexerRule');
		this.changeCounte = this.filter.$container.data('changeCounter');

		if (!this.isCounter && this.indexerRule === 'show')
			return;

		this.set();

		// flag which displays updated only the current filter or not
		// needed to implement the option "Change Counters -> Other Filters Changed"
		let onlyCurrentFilterChanged = true;

		eventBus.subscribe('fiter/change', filter => {
			if (filter.filterId != this.filter.filterId)
				onlyCurrentFilterChanged = false;
		});
		eventBus.subscribe('ajaxFilters/updated', (provider, queryId) => {
			if (!this.filter.isCurrentProvider({ provider, queryId }))
				return;

			if ('other_changed' === this.changeCounte && onlyCurrentFilterChanged)
				return;

			// reset flag
			onlyCurrentFilterChanged = true;

			this.update();
		});

		eventBus.subscribe('fiters/remove', removeFilter => {
			if (!this.filter.isCurrentProvider(removeFilter))
				return;

			// set flag
			onlyCurrentFilterChanged = false;
		});

		eventBus.subscribe('hierarchyFilters/levelsUpdated', filterId => {
			if (this.filter.filterId !== filterId)
				return;

			this.set();
		});
	}

	set() {
		const $items = this.$items;
		let itemsCount = $items.length,
			hiddenItemsCount = 0;

		$items.each(index => {
			let $item = $items.eq(index);
			const counts = this.currentIndexerData[$item.val()] || 0;

			if (!$item.val()) {
				hiddenItemsCount++;
				return;
			}

			if (this.isCounter) {
				switch ($item.prop('tagName')) {
					case 'INPUT':
						$item = $item.closest(this.rowSelector);
						$item.find(this.counterSelector + ' .value').text(counts);

						break;

					case 'OPTION':
						if ($item.attr('loading-item') !== '' && '' !== $item.attr('value')) {
							$item.text($item.data('label') + ' ' + $item.data('counter-prefix') + counts + $item.data('counter-suffix'));
						}

						break;
				}
			} else {
				if ($item.prop('tagName') === 'INPUT')
					$item = $item.closest(this.rowSelector);
			}

			if (['hide', 'disable'].includes(this.indexerRule)) {
				if (0 === counts) {
					$item.addClass('jet-filter-row-' + this.indexerRule);

					if ($item.prop('tagName') === 'OPTION' && this.indexerRule === 'hide' && !$item.parent('span.jet-filter-row-hide').length && $item.val())
						$item.wrap('<span class="jet-filter-row-hide" />');

					if ($item.prop('tagName') === 'OPTION' && this.indexerRule === 'disable')
						$item.attr('disabled', true);
				} else {
					$item.removeClass('jet-filter-row-' + this.indexerRule);

					if ($item.prop('tagName') === 'OPTION' && this.indexerRule === 'hide' && $item.parent('span.jet-filter-row-hide').length)
						$item.unwrap();

					if ($item.prop('tagName') === 'OPTION' && this.indexerRule === 'disable')
						$item.removeAttr('disabled');
				}

				if ('hide' === this.indexerRule && 0 === counts) {
					hiddenItemsCount++;
				}
			}
		});

		if ('hide' === this.indexerRule) {
			if (!this.filter.isHierarchy || (this.filter.isHierarchy && this.filter.depth === 0)) {
				if (hiddenItemsCount >= itemsCount) {
					this.filter.$container.hide();
					this.filter.$applyButton.hide();
				} else {
					this.filter.$container.show();
					this.filter.$applyButton.show();
				}
			} else {
				if (hiddenItemsCount >= itemsCount) {
					this.filter.$filter.hide();
				} else {
					this.filter.$filter.show();
				}
			}
		}

		this.updateFilter();
	}

	update() {
		const indexerData = this.indexerData;

		if (isEqual(indexerData, this.currentIndexerData)) {
			return;
		} else {
			this.currentIndexerData = indexerData;
		}

		if ('never' === this.changeCounte)
			this.isCounter = false;

		this.set();
	}

	updateFilter() {
		if (this.filter.additionalFilterSettings)
			this.filter.additionalFilterSettings.toggleItemsVisibility();
	}

	get $items() {
		return this.filter.$filter.find('input, option');
	}

	get indexerData() {
		const data = getNesting(JetSmartFilterSettings, 'jetFiltersIndexedData');
		let output = {};

		for (const provider in data) {
			if (provider !== this.filter.provider + '/' + this.filter.queryId)
				continue;

			for (const type in data[provider]) {
				if (type !== this.filter.queryType)
					continue;

				for (const itemName in data[provider][type]) {
					if (itemName !== this.filter.queryVar)
						continue;

					for (const itemKey in data[provider][type][itemName])
						output[itemKey] = data[provider][type][itemName][itemKey];
				}
			}
		}

		return output;
	}
}