import { computed } from "vue";
import { useRouter, useRoute } from "vue-router";
import request from "@/services/request.js";
import popup from "@/services/popups.js";
import { useGetter, useGetters, useActions } from "@/store/helper.js";
import _array from "@/modules/helpers/array.js";

const {
	currentPage,
	filtersList
} = useGetters(['currentPage', 'filtersList']);

const {
	pluginSettings,
	filterTypes,
	filterSources
} = useGetters(['pluginSettings', 'filterTypes', 'filterSources'], false);

const {
	updateIsPageLoading,
	updateIsFiltersListLoading,
	updateCurrentPage,
	updateFiltersList,
	updateFiltersListArgs,
	updateQuantity
} = useActions(['updateIsFiltersListLoading', 'updateIsPageLoading', 'updateCurrentPage', 'updateFiltersList', 'updateFiltersListArgs', 'updateQuantity']);

let router, route;

export const isIndexerEnabled = pluginSettings.indexer_enabled;

export const isСhecked = computed(() => filtersList.value.some(item => item.checked));

export function init() {
	// Init vue-router
	router = useRouter();
	route = useRoute();

	const args = useGetter('filtersListArgs', false);

	if (['filters', 'trash'].includes(currentPage.value))
		args.pagination.page = 1;

	updateIsPageLoading(true);
	updateCurrentPage(route.name);
	updateFiltersList([]);
	updateList(args);
}

export function updateList(args = null) {
	if (args === null)
		args = useGetter('filtersListArgs', false);

	const requestArgs = {
		status: currentPage.value === 'trash' ? 'trash' : 'publish',
	};

	if (args.pagination) {
		if (args.pagination.page)
			requestArgs.page = args.pagination.page;

		if (args.pagination.perPage)
			requestArgs.per_page = args.pagination.perPage;
	}

	if (args.sort) {
		if (args.sort.orderBy)
			requestArgs.orderby = args.sort.orderBy;

		if (args.sort.order)
			requestArgs.order = args.sort.order;
	}

	if (args.additionally) {
		for (const key in args.additionally)
			requestArgs[key] = args.additionally[key];

		delete args.additionally;
	}

	for (const key in args)
		if (args[key] && !['pagination', 'sort'].includes(key))
			requestArgs[key] = args[key];

	updateIsFiltersListLoading(true);

	request.getFilters(requestArgs)
		.then(response => {
			updateFiltersList(response.filters);

			// Parse filters data
			response.filters.forEach(filter => {
				for (const key in filter)
					if (!filter[key])
						filter[key] = '—';

				if (filter.type && filterTypes[filter.type])
					filter.type = filterTypes[filter.type];

				if (filter.source && filterSources[filter.source])
					filter.source = filterSources[filter.source];
			});

			// update pagination data
			args.pagination.count = response.count;
			args.pagination.totalPages = Math.ceil(args.pagination.count / args.pagination.perPage);

			['restore', 'move_to_trash', 'delete'].forEach(prop => { delete args[prop]; });
			updateFiltersListArgs(args);

			// update quantity
			updateQuantity({
				filters: response.total_count,
				trash: response.total_trash_count,
			});

			updateIsPageLoading(false);
			updateIsFiltersListLoading(false);
		});
};

export function updateListArg(key, value, resetPagination = false) {
	const args = useGetter('filtersListArgs', false);

	// pagination
	if (key === 'pagination') {
		args.pagination.page = value;
		// perPage
	} else if (key === 'perPage') {
		args.pagination.perPage = value;

		if (args.pagination.perPage >= args.pagination.count)
			args.pagination.totalPages = 1;
		// default
	} else {
		args[key] = value;
	}

	if (resetPagination)
		args.pagination.page = 1;

	updateFiltersListArgs(args);
	updateList();
};

export function goToPage(pageName, params = false) {
	if (pageName === currentPage.value)
		return;

	const routerData = {
		name: pageName
	};

	if (params)
		routerData.params = params;

	updateCurrentPage(pageName);
	router.push(routerData);
}

export function toFilter(filterId) {
	goToPage('filter', { id: filterId });
}

export function doAction(action, value) {
	if (!value)
		return;

	const args = useGetter('filtersListArgs', false);

	args[action] = value;

	updateList(args);
}

export function quickEdit(id) {
	popup.quickEdit(id);
}

export function restore(id) {
	doAction('restore', id);
}

export function moveToTrash(id) {
	doAction('move_to_trash', id);
}

export function remove(id) {
	popup[id === 'all' ? 'emptyTrash' : 'deletePermanently'](
		() => { doAction('delete', id); }
	);
}

export function getById(id) {
	return _array.findByPropertyValue(filtersList.value, 'ID', id);
}

export function clearFiltering(update = false) {
	const newArgs = useGetter('filtersListArgs', false);

	newArgs.search = '';
	newArgs.type = '';
	newArgs.source = '';
	newArgs.sort = false;

	updateFiltersListArgs(newArgs);

	if (update === true)
		updateList();
}

export default {
	isIndexerEnabled,
	isСhecked,
	init,
	updateList,
	updateListArg,
	goToPage,
	toFilter,
	doAction,
	quickEdit,
	restore,
	moveToTrash,
	remove,
	getById,
	clearFiltering
};