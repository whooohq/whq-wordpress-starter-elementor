<template>
	<div class="jet_filters-list-active-head">
		<div class="jet_filters-list-active-head-checkbox">
			<Checkbox v-model="allChecked" />
		</div>
		<div class="jet_filters-list-active-head-selected">
			<span class="jet_filters-list-active-head-selected-count">Selected: {{selectedCount}}</span>
		</div>
		<div class="jet_filters-list-active-head-actions">
			<template v-if="currentPage === 'trash'">
				<Button class="jet_filters-list-active-head-actions-restore"
						@click="onActionClick('restore')">
					<svg width="19" height="16" viewBox="0 0 19 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.8335 0.5C6.69183 0.5 3.3335 3.85833 3.3335 8H0.833496L4.16683 11.325L7.50016 8H5.00016C5.00016 4.775 7.6085 2.16667 10.8335 2.16667C14.0585 2.16667 16.6668 4.775 16.6668 8C16.6668 11.225 14.0585 13.8333 10.8335 13.8333C9.22516 13.8333 7.76683 13.175 6.71683 12.1167L5.5335 13.3C6.89183 14.6583 8.7585 15.5 10.8335 15.5C14.9752 15.5 18.3335 12.1417 18.3335 8C18.3335 3.85833 14.9752 0.5 10.8335 0.5ZM10.0002 4.66667V8.83333L13.5418 10.9333L14.1835 9.86667L11.2502 8.125V4.66667H10.0002Z" /></svg>
					Restore
				</Button>
				<Button class="jet_filters-list-active-head-actions-delete-permanently"
						@click="onActionClick('remove')">
					<svg width="12" height="16" viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.999837 13.8333C0.999837 14.75 1.74984 15.5 2.6665 15.5H9.33317C10.2498 15.5 10.9998 14.75 10.9998 13.8333V3.83333H0.999837V13.8333ZM2.6665 5.5H9.33317V13.8333H2.6665V5.5ZM8.9165 1.33333L8.08317 0.5H3.9165L3.08317 1.33333H0.166504V3H11.8332V1.33333H8.9165Z" /></svg>
					Delete permanently
				</Button>
			</template>
			<template v-else>
				<Button class="jet_filters-list-active-head-actions-remove"
						@click="onActionClick('moveToTrash')">
					<svg width="12" height="16" viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.00033 13.8333C1.00033 14.75 1.75033 15.5 2.66699 15.5H9.33366C10.2503 15.5 11.0003 14.75 11.0003 13.8333V3.83333H1.00033V13.8333ZM2.66699 5.5H9.33366V13.8333H2.66699V5.5ZM8.91699 1.33333L8.08366 0.5H3.91699L3.08366 1.33333H0.166992V3H11.8337V1.33333H8.91699Z" /></svg>
					Move to trash
				</Button>
			</template>
		</div>
	</div>
</template>

<script>
import { defineComponent, inject, computed } from "vue";
import { useGetters } from "@/store/helper.js";
import filters from "@/services/filters.js";
import Button from "@/modules/JetUI/controls/Button.vue";
import Checkbox from "@/modules/DataTable/components/Checkbox.vue";

export default defineComponent({
	name: "ActiveHead",

	components: {
		Checkbox,
		Button
	},

	setup() {
		const {
			currentPage,
			filtersList,
		} = useGetters(['currentPage', 'filtersList']);

		const dataTableProvider = inject('DataTableProvider');

		// Computed data
		const selected = computed(
			() => filtersList.value.filter(item => item.checked)
		);

		const selectedCount = computed(
			() => selected.value.length
		);

		// Methods
		const onClearClick = () => {
			filtersList.value.forEach(item =>
				delete item.checked
			);
		};

		const onActionClick = (action) => {
			if (!selectedCount.value)
				return;

			filters[action](selected.value.map(item => parseInt(item.ID)));
		};

		return {
			currentPage,
			filtersList,
			selectedCount,
			allChecked: dataTableProvider.allChecked,
			onClearClick,
			onActionClick
		};
	}
});
</script>
