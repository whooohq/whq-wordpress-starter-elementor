<template>
	<div class="jet_filters-list-navigation">
		<div class="jet_filters-list-navigation-info">
			Showing results {{info.from}} - {{info.to}} of {{info.count}}
		</div>
		<Pagination class="jet_filters-list-navigation-pagination"
					:startPage="data.page"
					:totalPages="data.totalPages"
					:withNextPrev="true"
					@change="onPagaChange" />
		<div class="jet_filters-list-navigation-perpage">
			Per page
			<Number :modelValue="data.perPage"
					:min="1"
					:max="100"
					:selectOnFocus="true"
					@update:modelValue="onPerPageChange" />
		</div>
	</div>
</template>

<script>
import { defineComponent, computed } from "vue";
import { useGetter } from "@/store/helper.js";
import filters from "@/services/filters.js";
import JetUI from "@/modules/JetUI";

export default defineComponent({
	name: "Navigation",

	components: {
		Pagination: JetUI.Pagination,
		Number: JetUI.controls.Number
	},

	setup(props, context) {
		const filtersListArgs = useGetter('filtersListArgs');

		// Computed data
		const data = computed(() => filtersListArgs.value.pagination);

		const info = computed(() => {
			const page = data.value.page,
				perPage = data.value.perPage,
				totalPages = data.value.totalPages,
				count = data.value.count,
				from = (page - 1) * perPage + 1,
				to = page < totalPages ? from - 1 + perPage : count;

			return {
				page,
				perPage,
				totalPages,
				count,
				from,
				to,
			};
		});

		// Actions
		const onPagaChange = (newPage) => {
			filters.updateListArg('pagination', newPage);
		};

		const onPerPageChange = (newPerPage) => {
			filters.updateListArg('perPage', newPerPage, true);
		};

		return {
			data,
			info,
			onPagaChange,
			onPerPageChange
		};
	}
});
</script>
