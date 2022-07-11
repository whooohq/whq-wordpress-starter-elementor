import eventBus from 'includes/event-bus';
import templateParser from 'includes/template-parser';
import {
	isEmpty,
	getNesting
} from 'includes/utility';

export default class ActiveItems {
	activeItemsСollection = {};

	constructor ($activeItems, props = {}) {
		const {
			separateMultiple = false,
			templateName = false,
			listClass = 'active-list',
			labelClass = 'active-title',
			itemClass = 'active-item',
			clearClass = 'active-clear',
		} = props;

		this.$activeItemsContainer = $activeItems;
		this.$elementorWidget = this.$activeItemsContainer.closest('.hide-widget');
		this.separateMultiple = separateMultiple;
		this.templateName = templateName;
		this.listClass = listClass;
		this.labelClass = labelClass;
		this.itemClass = itemClass;
		this.clearClass = clearClass;
		this.provider = this.$activeItemsContainer.data('contentProvider');
		this.queryId = this.$activeItemsContainer.data('queryId').toString() || 'default';
		this.providerKey = this.provider + '/' + this.queryId;
		this.additionalProviders = this.$activeItemsContainer.data('additional-providers');
		this.allProviders = [this.providerKey];
		this.applyType = this.$activeItemsContainer.data('applyType') || 'ajax';
		this.filtersLabel = this.$activeItemsContainer.data('label');
		this.clearItemLabel = this.$activeItemsContainer.data('clearItemLabel');

		this.setAllProviders();

		// Event subscriptions
		eventBus.subscribe('activeItems/change', (activeItems, provider, queryId) => {
			if (!this.isCurrentProvider({ provider, queryId }))
				return;

			this.addToСollection(activeItems, provider + '/' + queryId);
			this.buildItems();
		});
		eventBus.subscribe('activeItems/rebuild', (provider, queryId) => {
			if (!this.isCurrentProvider({ provider, queryId }))
				return;

			this.buildItems();
		});
	}

	addToСollection(activeItems, providerKey) {
		let items = activeItems.filter(item => {
			return !item.isAdditional;
		});

		if (this.isThereHierarchicalFilters(items))
			items = this.groupHierarchicalFilters(items);

		this.activeItemsСollection[providerKey] = items;
	}

	buildItems() {
		this.$elementorWidget.removeClass('hide-widget');

		// remove all jQuery events to avoid memory leak
		this.$activeItemsContainer.find('*').off();

		const activeItems = this.activeItems;

		if (isEmpty(activeItems)) {
			this.$activeItemsContainer.html('');
			this.$elementorWidget.addClass('hide-widget');

			return;
		}

		const elList = document.createElement('div');
		elList.className = this.listClass;

		if (this.filtersLabel) {
			const elLabel = document.createElement('div');

			elLabel.className = this.labelClass;
			elLabel.innerHTML = this.filtersLabel;

			elList.appendChild(elLabel);
		}

		if (this.clearItemLabel) {
			elList.appendChild(this.buildItem({
				value: this.clearItemLabel,
				itemClass: this.clearClass,
				callback: () => { eventBus.publish('fiters/remove', this) }
			}));
		}

		activeItems.forEach(activeItem => {
			let item = null;

			if (Array.isArray(activeItem)) {
				item = this.groupedItem(activeItem);
			} else if (this.isSeparate(activeItem)) {
				item = this.separatedItems(activeItem);
			} else {
				item = this.regularItem(activeItem);
			}

			if (item)
				elList.appendChild(item);
		});

		this.$activeItemsContainer.html(elList);
	}

	buildItem(props) {
		const {
			value,
			label = false,
			itemClass = this.itemClass,
			template = this.itemTemplate,
			callback = () => { }
		} = props;

		let activeItemContent = value;

		if (template) {
			activeItemContent = templateParser(template, {
				$label: label,
				$value: value
			})
		}

		const elActiveItem = document.createElement('div');

		elActiveItem.className = itemClass;
		elActiveItem.innerHTML = activeItemContent;

		// add jQuery click event once
		$(elActiveItem).one('click', callback);

		return elActiveItem;
	}

	regularItem(filter) {
		const value = filter.activeValue,
			label = filter.activeLabel;

		if (!value)
			return false;

		return this.buildItem({
			value,
			label,
			callback: () => { this.removeFilter(filter) }
		});
	}

	separatedItems(filter) {
		const items = document.createDocumentFragment();

		filter.data.forEach(itemValue => {
			const value = filter.getValueLabel(itemValue),
				label = filter.activeLabel;

			if (value)
				items.appendChild(this.buildItem({
					value,
					label,
					callback: () => { this.removeFilter(filter, itemValue) }
				}));
		});

		return items;
	}

	groupedItem(filtersGroup) {
		let value = '',
			label;

		filtersGroup.forEach(filter => {
			const filterValue = filter.activeValue,
				filterLabel = filter.activeLabel;

			if (filterValue) {
				if (value)
					value += ' > ';

				value += filterValue;
			}

			if (!label && filterLabel)
				label = filterLabel;
		});

		return this.buildItem({
			value,
			label,
			callback: () => { this.removeFilter(filtersGroup[0]) }
		});
	}

	removeFilter(filter, value = false) {
		// reset filter
		filter.reset(value);

		// filter cloning and overwrite properties
		const activeFilter = filter.copy;

		activeFilter.applyType = this.applyType;
		//activeFilter.additionalProviders = this.additionalProviders;

		// emit reset active filter
		eventBus.publish('fiter/change', activeFilter);
	}

	setAllProviders() {
		const additionalProviders = (this.additionalProviders && Array.isArray(this.additionalProviders) ? this.additionalProviders : []).map(additionalProvider => {
			const providerData = additionalProvider.split('/', 2),
				provider = providerData[0],
				queryId = providerData[1] || this.queryId;

			return provider + '/' + queryId;
		});

		this.allProviders = [...new Set([this.providerKey, ...additionalProviders])];
	}

	isSeparate(filter) {
		return this.separateMultiple && Array.isArray(filter.data) ? true : false;
	}

	isThereHierarchicalFilters(filters) {
		return filters.some(filter => {
			return filter.isHierarchy;
		})
	}

	isCurrentProvider(filter = { provider: false, queryId: false }) {
		const {
			provider = false,
			queryId = 'default'
		} = filter;

		if (!provider)
			return false;

		return this.allProviders.includes(provider + '/' + queryId) ? true : false;
	}

	get activeItems() {
		let allActiveItems = [];

		for (const providerKey in this.activeItemsСollection)
			allActiveItems = [...allActiveItems, ...this.activeItemsСollection[providerKey]];

		return allActiveItems;
	}

	get itemTemplate() {
		if (!this.templateName)
			return false;

		return getNesting(JetSmartFilterSettings, 'templates', this.templateName);
	}

	get containerElement() {
		if (!this.$activeItemsContainer)
			return false;

		if (!this.$activeItemsContainer.length)
			return false;

		return this.$activeItemsContainer.get(0);
	}

	// Additional methods
	groupHierarchicalFilters(filters) {
		const groupedFilters = [];

		while (filters.length) {
			let filtersGroup;
			const firstFilter = filters.shift(),
				firstFilterId = firstFilter.filterId;

			for (let i = 0; i < filters.length; i++) {
				if (filters[i].filterId === firstFilterId) {
					if (!filtersGroup)
						filtersGroup = [firstFilter];

					filtersGroup.push(...filters.splice(i, 1));
					i--;
				}
			}

			if (filtersGroup) {
				groupedFilters.push(filtersGroup);
			} else {
				groupedFilters.push(firstFilter);
			}
		}

		return groupedFilters;
	}
}