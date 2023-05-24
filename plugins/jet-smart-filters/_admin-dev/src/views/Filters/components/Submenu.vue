<template>
	<div class="jet_filters-list-subnav">
		<div v-for="item in itemsList"
			 :key="item.name"
			 :class="[
				'jet_filters-list-subnav-' + item.name,
				{'jet_filters-list-subnav-active': isItemActive(item.name)}
			 ]"
			 @click="onItemClick(item.name)">
			{{item.label}} <span>({{quantity[item.name]}})</span>
		</div>
	</div>
</template>

<script>
import { defineComponent } from "vue";
import { useGetters } from "@/store/helper.js";
import filters from "@/services/filters.js";

export default defineComponent({
	name: "Submenu",

	setup(props, context) {
		const itemsList = [
			{
				name: 'filters',
				label: 'All filters'
			},
			{
				name: 'trash',
				label: 'Trash'
			}
		];

		const {
			currentPage,
			quantity
		} = useGetters(['currentPage', 'quantity']);

		// Methods
		const isItemActive = (itemName) => itemName === currentPage.value;

		// Actions
		const onItemClick = (itemName) => {
			filters.clearFiltering();
			filters.goToPage(itemName);
		};

		return {
			itemsList,
			isItemActive,
			quantity,
			onItemClick
		};
	}
});
</script>
