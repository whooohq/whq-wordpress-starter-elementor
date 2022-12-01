<template>
	<template v-if="columnKey === 'title'">
		<template v-if="currentPage === 'trash'">
			<div class="jet_filters-list-row-title">{{value}}</div>
			<div class="jet_filters-list-row-actions">
				<Button class="jet_filters-list-row-actions-restore"
						text="Restore"
						@click="onActionClick('restore')" />
				<span class="jet_filters-list-row-actions-separator" />
				<Button class="jet_filters-list-row-actions-delete-permanently"
						text="Delete permanently"
						@click="onActionClick('remove')" />
			</div>
		</template>
		<template v-else>
			<router-link class="jet_filters-list-row-title"
						 :to="`/${id}`"
						 v-slot="{ href, navigate }">{{value}}</router-link>
			<div class="jet_filters-list-row-actions">
				<router-link class="jet_filters-list-row-edit"
							 :to="`/${id}`"
							 v-slot="{ href, navigate }">Edit</router-link>
				<span class="jet_filters-list-row-actions-separator" />
				<Button class="jet_filters-list-row-quick-edit"
						text="Quick edit"
						@click="onActionClick('quickEdit')" />
				<span class="jet_filters-list-row-actions-separator" />
				<Button class="jet_filters-list-row-remove"
						text="Move to trash"
						@click="onActionClick('moveToTrash')" />
			</div>
		</template>
	</template>
	<template v-else-if="columnKey === 'type'">
		<div class="jet_filters-list-row-type">
			<img :src="value.img">
			{{value.label}}
		</div>
	</template>
	<template v-else>
		{{value}}
	</template>
</template>

<script>
import { defineComponent, computed } from "vue";
import { useGetters } from "@/store/helper.js";
import filters from "@/services/filters.js";
import Button from "@/modules/JetUI/controls/Button.vue";

export default defineComponent({
	name: "BodyCell",

	props: {
		columnKey: { type: String, required: true },
		value: { required: true },
		rowIndex: { type: Number, required: true }
	},

	components: {
		Button
	},

	setup(props, context) {
		const {
			currentPage,
			filtersList
		} = useGetters(['currentPage', 'filtersList']);

		// Computeable
		const id = computed(() => parseInt(filtersList.value[props.rowIndex].ID));
		const value = computed(() => props.value);

		// Actions
		const onEditClick = () => {
			filters.toFilter(id.value);
		};

		const onActionClick = (action) => {
			filters[action](id.value);
		};

		return {
			currentPage,
			id,
			value,
			onEditClick,
			onActionClick,
		};
	}
});
</script>
