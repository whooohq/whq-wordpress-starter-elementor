import SelectControl from 'bases/controls/Select';
import eventBus from 'includes/event-bus';

export default class SelectHierarchical {
	name = 'select-hierarchical';

	filters = [];

	constructor($container) {
		const $filters = $container.find('.jet-select');

		if (!$filters.length)
			return;

		$filters.each(index => {
			const $filter = $filters.eq(index),
				filter = new SelectControl($container, $filter);

			filter.$container = $container;
			filter.isHierarchy = true;
			filter.depth = index;
			filter.singleTax = filter.$filter.data('singleTax');

			this.filters.push(filter);

			// overwrite processData method
			filter.processData = () => {
				this.hierarchicalFilterProcessData(filter);
			};
		});

		this.isHierarchy = true;
		this.indexer = $container.hasClass('jet-filter-indexed');
		this.lastFilter = this.filters[this.filters.length - 1];
		this.filterId = this.lastFilter.filterId;
		this.isReloadType = this.lastFilter.isReloadType;

		// if reload type
		if (this.isReloadType) {
			this.filters.forEach(filter => {
				filter.$applyButton.off();

				// add filters change event
				filter.$select.on('change', () => {
					filter.processData();
					this.getNextHierarchyLevels(filter);
				});
			});

			this.lastFilter.$applyButton.on('click', () => {
				this.lastFilter.emitFiterChange();
			});
		}

		// Event subscriptions
		eventBus.subscribe('fiter/change', filter => {
			if (filter.filterId === this.filterId)
				this.getNextHierarchyLevels(filter);
		});
		eventBus.subscribe('fiters/remove', removeFilter => {
			if (!this.lastFilter.isCurrentProvider(removeFilter))
				return;

			this.clearHierarchyLevels();
		});
		eventBus.subscribe('hierarchyFilters/update', filters => {
			if (filters[this.filterId])
				this.updateHierarchyLevels(filters[this.filterId]);
		});
	}

	getHierarchicalСhain(filter) {
		const hc = [];

		for (let index = 0; index < filter.depth; index++)
			if (this.filters[index].queryKey === filter.queryKey)
				hc.push(this.filters[index].data);

		return hc;
	}

	hierarchicalFilterProcessData(filter) {
		filter.dataValue = filter.$selected.val();

		// get hierarchical chain if same taxonomies
		if (filter.depth) {
			const hierarchicalСhain = this.getHierarchicalСhain(filter);

			if (hierarchicalСhain.length)
				filter.hierarchicalСhain = hierarchicalСhain.join();
		}
	}

	getNextHierarchyLevels(filter) {
		const depth = filter.depth + 1,
			values = [];

		if (!depth)
			return;

		for (let i = depth; i < this.filters.length; i++) {
			this.filters[i].reset();
			this.filters[i].showPreloader();
		}

		for (let i = 0; i < depth; i++) {
			const currFilter = this.filters[i];

			values.push({
				value: currFilter.data,
				tax: currFilter.queryVar,
			});
		}

		this.ajaxRequest({ values, depth });
	}

	updateHierarchyLevels(filters) {
		const values = [];

		filters.forEach(filter => {
			if (filter.dataValue)
				values.push({
					value: filter.data,
					tax: filter.queryVar,
				});
		});

		this.ajaxRequest({ values }, () => {
			filters.forEach(filter => {
				filter.setData(filter.data);
			});

			const firstFilter = filters[0];
			if (firstFilter)
				eventBus.publish('activeItems/rebuild', firstFilter.provider, firstFilter.queryId);
		});
	}

	clearHierarchyLevels() {
		const [, ...hierarchyLevels] = this.filters;

		hierarchyLevels.forEach(hierarchyLevel => {
			hierarchyLevel.$select.find('option').each((index, item) => {
				if (index === 0)
					return;

				const $option = $(item);

				$option.remove();
			});
		});
	}

	ajaxRequest(data, callback) {
		const {
			values,
			depth = false,
			indexer = this.indexer
		} = data;

		const requestData = {
			action: 'jet_smart_filters_get_hierarchy_level',
			filter_id: this.filterId,
			values
		};

		if (depth)
			requestData.depth = depth;
		if (indexer)
			requestData.indexer = indexer;

		$.ajax({
			url: JetSmartFilterSettings.ajaxurl,
			type: 'POST',
			dataType: 'json',
			data: requestData,
		}).done(response => {
			for (let i = 1; i < this.count; i++) {
				const currFilter = this.filters[i],
					newControlContent = $(response.data['level_' + i]).find('select').html();

				if (newControlContent) {
					currFilter.$select.html(newControlContent);
					this.updateFilterIndexer(currFilter);
				}
			}

			if (typeof callback === 'function')
				callback();

			eventBus.publish('hierarchyFilters/levelsUpdated', this.filterId);
		}).always(() => {
			this.filters.forEach(filter => {
				filter.hidePreloader();
			});
		});
	}

	updateFilterIndexer(filter) {
		if (!filter.indexer)
			return;

		// if "Apply Type" selected "Page reload"
		const isApplyTypeReload = filter.isReload;

		// if "Change Counters" selected "Never"
		const isChangeCountersNever = filter.indexer.changeCounte === 'never' ? true : false;

		if (isApplyTypeReload || isChangeCountersNever)
			filter.indexer.set();
	}

	// Getters
	get count() {
		return this.filters.length;
	}
}