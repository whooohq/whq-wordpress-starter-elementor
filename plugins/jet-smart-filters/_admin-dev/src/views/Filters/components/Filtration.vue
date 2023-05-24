<template>
	<div class="jet_filters-list-filtration">
		<div class="jet_filters-list-filtration-search">
			<div class="jet_filters-list-filtration-control-label">Search</div>
			<Text class="jet_filters-list-filtration-control"
				  :class="{ 'jet_filters-list-filtration-control--active': search }"
				  :modelValue="search"
				  placeholder="Enter name"
				  :clearEnabled="true"
				  @update:modelValue="onSearchChange" />
		</div>
		<div class="jet_filters-list-filtration-by-type">
			<div class="jet_filters-list-filtration-control-label">Filter by type</div>
			<Select class="jet_filters-list-filtration-control"
					:class="{ 'jet_filters-list-filtration-control--active': filterType }"
					:modelValue="filterType"
					placeholder="Select"
					:options="filterTypesOptions"
					:clearEnabled="true"
					:deselect="true"
					@update:modelValue="onTypeChange" />
		</div>
		<div class="jet_filters-list-filtration-by-source">
			<div class="jet_filters-list-filtration-control-label">Filter by data source</div>
			<Select class="jet_filters-list-filtration-control"
					:class="{ 'jet_filters-list-filtration-control--active': filterSource }"
					:modelValue="filterSource"
					placeholder="Select"
					:options="filterSourcesOptions"
					:clearEnabled="true"
					:deselect="true"
					@update:modelValue="onSourceChange" />
		</div>
		<div class="jet_filters-list-filtration-sort-by">
			<div class="jet_filters-list-filtration-control-label">Sort by</div>
			<Select class="jet_filters-list-filtration-control"
					:class="{ 'jet_filters-list-filtration-control--active': sortBy }"
					:modelValue="sortBy"
					placeholder="Select"
					:options="sortByOptions"
					:clearEnabled="true"
					:deselect="true"
					@update:modelValue="onSortByChange" />
		</div>
		<Button class="jet_filters-list-filtration-clear-btn"
				text="Clear Filters"
				@click="onFiltrationClearClick" />
	</div>
</template>

<script>
import { defineComponent, computed } from "vue";
import { useGetter, useGetters } from "@/store/helper.js";
import filters from "@/services/filters.js";
import JetUI from "@/modules/JetUI";
import _object from "@/modules/helpers/object.js";

export default defineComponent({
	name: "Filtration",

	components: {
		Text: JetUI.controls.Text,
		Select: JetUI.controls.Select,
		Button: JetUI.controls.Button
	},

	setup(props, context) {
		const filtersListArgs = useGetter('filtersListArgs');

		const {
			filterTypes,
			filterSources,
			sortByList
		} = useGetters(['filtersListArgs', 'filterTypes', 'filterSources', 'sortByList'], false);

		const search = computed(() => filtersListArgs.value.search);

		const filterType = computed(() => filtersListArgs.value.type);
		const filterTypesOptions = Object.entries(filterTypes).map(entry => ({
			value: entry[0],
			label: entry[1].label
		}));

		const filterSource = computed(() => filtersListArgs.value.source);
		const filterSourcesOptions = _object.toArrayOfObjects(filterSources, 'value', 'label');

		const sortBy = computed(() => {
			if (!filtersListArgs.value.sort || !filtersListArgs.value.sort.orderBy || !filtersListArgs.value.sort.order)
				return '';

			return filtersListArgs.value.sort.orderBy + '_' + filtersListArgs.value.sort.order;
		});
		const sortByOptions = _object.toArrayOfObjects(sortByList, 'value', 'label');

		// Actions
		const onSearchChange = (newSearchValue) => {
			filters.updateListArg('search', newSearchValue, true);
		};

		const onTypeChange = (newTypeValue) => {
			filters.updateListArg('type', newTypeValue, true);
		};

		const onSourceChange = (newSourceValue) => {
			filters.updateListArg('source', newSourceValue, true);
		};

		const onSortByChange = (newSortByValue) => {
			let sortByValue = false;

			if (newSortByValue) {
				const sortByData = newSortByValue.split('_', 2);

				sortByValue = {
					orderBy: sortByData[0],
					order: sortByData[1],
				};
			}

			filters.updateListArg('sort', sortByValue, true);
		};

		const onFiltrationClearClick = () => {
			if (!search.value && !filterType.value && !filterSource.value && !sortBy.value)
				return;

			filtersListArgs.value.pagination.page = 1;
			filtersListArgs.value.search = '';
			filtersListArgs.value.type = '';
			filtersListArgs.value.source = '';
			filtersListArgs.value.sort = false;

			filters.updateList();
		};

		return {
			search,
			filterType,
			filterTypesOptions,
			filterSource,
			filterSourcesOptions,
			sortBy,
			sortByOptions,
			onSearchChange,
			onTypeChange,
			onSourceChange,
			onSortByChange,
			onFiltrationClearClick
		};
	}
});
</script>
