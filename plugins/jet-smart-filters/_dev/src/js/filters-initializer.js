// Main Class
import FilterGroup from './FilterGroup';

// Filters Сlasses
import filters from 'filters';

// Includes
import filtersUI from 'includes/filters-ui';
import eventBus from 'includes/event-bus';
import eproCompat from 'includes/epro-compat';
import preloader from 'includes/preloader';
import {
	getNesting,
} from 'includes/utility';

// Data
const JSF = {
	filtersList: {
		CheckBoxes: 'jet-smart-filters-checkboxes',
		CheckRange: 'jet-smart-filters-check-range',
		Select: 'jet-smart-filters-select',
		SelectHierarchical: 'jet-smart-filters-hierarchy',
		Range: 'jet-smart-filters-range',
		DateRange: 'jet-smart-filters-date-range',
		DatePeriod: 'jet-smart-filters-date-period',
		Radio: 'jet-smart-filters-radio',
		Rating: 'jet-smart-filters-rating',
		Visual: 'jet-smart-filters-color-image',
		Alphabet: 'jet-smart-filters-alphabet',
		Search: 'jet-smart-filters-search',
		Sorting: 'jet-smart-filters-sorting',
		ButtonApply: 'jet-smart-filters-apply-button',
		ButtonRemove: 'jet-smart-filters-remove-filters',
		Pagination: 'jet-smart-filters-pagination',
		ActiveFilters: 'jet-smart-filters-active',
		ActiveTags: 'jet-smart-filters-active-tags'
	},
	filterClass,
	filters,
	filterNames: [],
	filterGroups: {},
	initFilter,
	reinitFilters,
	findFilters,
	filtersUI,
	setIndexedData,
	events: eventBus
};

const additionalFiltersExceptions = ['ActiveFilters', 'ActiveTags', 'ButtonRemove'];

//JetSmartFilters
window.JetSmartFilters = JSF;

// Init filters
$(document).ready(function () {
	// before initialization
	const beforeInitEvent = new Event('jet-smart-filters/before-init');
	document.dispatchEvent(beforeInitEvent);

	// if elementor
	if (window.elementorFrontend) {
		// initialize elementor PRO widgets post rendered processing
		eproCompat.addSubscribers();
	}

	preloader.init();

	// initialization
	// search and init filters
	const $filters = JSF.findFilters();
	$filters.each(index => {
		const $filter = $filters.eq(index);

		JSF.initFilter($filter);
	});

	// after initialization
	const initedEvent = new Event('jet-smart-filters/inited');
	document.dispatchEvent(initedEvent);
});

// Methods
function initFilter($filter) {
	if ($filter.is('[jsf-filter]'))
		return;

	// mark the filter with an attribute that it has been initialized
	$filter.attr('jsf-filter', '');

	let filterName = null;

	for (const key in JSF.filtersList) {
		if ($filter.hasClass(JSF.filtersList[key]))
			filterName = key;
	}

	if (!filterName)
		return;

	const filter = new JSF.filters[filterName]($filter);

	if (filter.isHierarchy) {
		filter.filters.forEach(hierarchyFilter => {
			pushFilterToGroup(hierarchyFilter);
		});
	} else {
		pushFilterToGroup(filter);
	}

	// Additional Filters
	const additionalFilters = $filter.data('additional-providers') || $filter.find('[data-additional-providers]').data('additional-providers');

	if (!additionalFilters || additionalFiltersExceptions.includes(filterName))
		return;

	additionalFilters.forEach(additionalFilter => {
		const additionalFilterData = additionalFilter.split('/', 2),
			additionalProvider = additionalFilterData[0],
			additionalQueryId = additionalFilterData[1] || filter.queryId;

		if (filter.isHierarchy) {
			filter.filters.forEach(hierarchyFilter => {
				pushFilterToGroup(createAdditionalFilter(additionalProvider, additionalQueryId, hierarchyFilter));
			});
		} else {
			pushFilterToGroup(createAdditionalFilter(additionalProvider, additionalQueryId, filter));
		}
	});
};

function reinitFilters(filterNames = null) {
	if (filterNames && !Array.isArray(filterNames))
		filterNames = [filterNames];

	for (const groupKey in JSF.filterGroups)
		JSF.filterGroups[groupKey].reinitFilters(filterNames);
}

function pushFilterToGroup(filter) {
	if (!filter.provider || !filter.queryId)
		return;

	const provider = filter.provider;
	const queryId = filter.queryId;
	const filtersGroup = getFiltersGroup(provider, queryId);

	filtersGroup.addFilter(filter);
}

function getFiltersGroup(provider, queryId) {
	const groupKey = provider + '/' + queryId;

	if (!JSF.filterGroups[groupKey])
		JSF.filterGroups[groupKey] = new FilterGroup(provider, queryId);

	return JSF.filterGroups[groupKey];
}

function findFilters(container = $('html')) {
	return $('.' + Object.values(JSF.filtersList).join(', .'), container);
}

function filterClass(filterName) {
	for (const key in JSF.filtersList)
		if ('jet-smart-filters-' + filterName === JSF.filtersList[key])
			return key;
}

function setIndexedData(provider, query = {}) {
	if (!JSF.filterGroups[provider] || !JSF.filterGroups[provider].indexingFilters)
		return;

	const ajaxURL = getNesting(JetSmartFilterSettings, 'ajaxurl'),
		requestData = {
			action: 'jet_smart_filters_get_indexed_data',
			provider,
			query_args: query,
			indexing_filters: JSF.filterGroups[provider].indexingFilters
		};

	$.ajax({
		url: ajaxURL,
		type: 'POST',
		dataType: 'json',
		data: requestData,
	}).done(function (response) {
		if (!response.data)
			return;

		if (!window.JetSmartFilterSettings.jetFiltersIndexedData)
			window.JetSmartFilterSettings.jetFiltersIndexedData = {};

		if (!window.JetSmartFilterSettings.jetFiltersIndexedData[provider])
			window.JetSmartFilterSettings.jetFiltersIndexedData[provider] = {};

		// update indexed data
		window.JetSmartFilterSettings.jetFiltersIndexedData[provider] = response.data;

		if (!JSF.filterGroups[provider])
			return;

		JSF.filterGroups[provider].filters.forEach(filter => {
			if (!filter.indexer)
				return;

			filter.indexer.update();
		});
	});
}

function createAdditionalFilter(additionalProvider, additionalQueryId, filter) {
	return {
		isAdditional: true,
		name: filter.name,
		path: filter.path,
		provider: additionalProvider,
		queryId: additionalQueryId,
		filterId: filter.filterId,
		queryKey: filter.queryKey,
		data: filter.data,
		reset: function () {
			this.data = false;
		}
	};
}

// filling array with names
for (const key in JSF.filtersList) {
	const filter = JSF.filtersList[key];

	JSF.filterNames.push(filter.replace('jet-smart-filters-', ''));
}

export default JSF;