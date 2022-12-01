<template>
	<div class="jet_filters-list jet_filters-trash">
		<div class="jet_filters-list-header">
			<h1 class="jet_filters-list-heading">Smart Filters List</h1>
			<router-link class="jet_filters-list-add-new-btn"
						 to="/new"
						 v-slot="{ href, navigate }">Add New</router-link>
			<AdminModeSwitcher />
			<Button class="jet_filters-trash-restore-all"
					@click="onActionClick('restore')">
				<svg width="19" height="16" viewBox="0 0 19 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.8335 0.5C6.69183 0.5 3.3335 3.85833 3.3335 8H0.833496L4.16683 11.325L7.50016 8H5.00016C5.00016 4.775 7.6085 2.16667 10.8335 2.16667C14.0585 2.16667 16.6668 4.775 16.6668 8C16.6668 11.225 14.0585 13.8333 10.8335 13.8333C9.22516 13.8333 7.76683 13.175 6.71683 12.1167L5.5335 13.3C6.89183 14.6583 8.7585 15.5 10.8335 15.5C14.9752 15.5 18.3335 12.1417 18.3335 8C18.3335 3.85833 14.9752 0.5 10.8335 0.5ZM10.0002 4.66667V8.83333L13.5418 10.9333L14.1835 9.86667L11.2502 8.125V4.66667H10.0002Z" /></svg>
				Restore All
			</Button>
			<Button class="jet_filters-trash-delete-all"
					@click="onActionClick('remove')">
				<svg width="12" height="16" viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.999837 13.8333C0.999837 14.75 1.74984 15.5 2.6665 15.5H9.33317C10.2498 15.5 10.9998 14.75 10.9998 13.8333V3.83333H0.999837V13.8333ZM2.6665 5.5H9.33317V13.8333H2.6665V5.5ZM8.9165 1.33333L8.08317 0.5H3.9165L3.08317 1.33333H0.166504V3H11.8332V1.33333H8.9165Z" /></svg>
				Empty Trash
			</Button>
			<Submenu />
		</div>
		<Filtration />
		<Preloader :show="isPageLoading" />
		<template v-if="filtersList.length">
			<Navigation />
			<DataTable :rows="filtersList"
					   :columns="columns"
					   :hasCheckbox="true">
				<template #table-head="{columns}"
						  v-if="isСhecked">
					<ActiveHead />
				</template>
				<template #table-body-cell="{column, value, rowIndex}">
					<BodyCell :columnKey="column"
							  :value="value"
							  :rowIndex="rowIndex" />
				</template>
				<template #table-body-after-rows>
					<transition name="fade">
						<Preloader :show="isFiltersListLoading" />
					</transition>
				</template>
			</DataTable>
			<Navigation />
		</template>
		<NotFound v-else-if="!isPageLoading"
				  text="Trash is empty" />
	</div>
</template>

<script>
import { defineComponent, onMounted, computed } from "vue";
import { useGetters } from "@/store/helper.js";
import filters from "@/services/filters.js";
import DataTable from "@/modules/DataTable";
import JetUI from "@/modules/JetUI";
import Preloader from "@/components/Preloader.vue";
import { ActiveHead, BodyCell, Submenu, Filtration, Navigation, AdminModeSwitcher, IndexerButton, NotFound } from "./components";

export default defineComponent({
	name: 'Trash',

	components: {
		DataTable,
		Pagination: JetUI.Pagination,
		Button: JetUI.controls.Button,
		Preloader,
		ActiveHead,
		BodyCell,
		Submenu,
		Filtration,
		Navigation,
		AdminModeSwitcher,
		IndexerButton,
		NotFound
	},

	setup(props, context) {
		const {
			isPageLoading,
			isFiltersListLoading,
			columns,
			filtersList
		} = useGetters(['isPageLoading', 'isFiltersListLoading', 'columns', 'filtersList']);

		const settingsUrl = window.JetSmartFiltersAdminData.urls.admin + 'admin.php?page=jet-dashboard-settings-page&subpage=jet-smart-filters-general-settings';

		// Lifecycles
		onMounted(() => {
			filters.init();
		});

		// Actions
		const onActionClick = (action) => {
			filters[action]('all');
		};

		return {
			isPageLoading,
			isFiltersListLoading,
			filtersList,
			columns,
			isСhecked: filters.isСhecked,
			settingsUrl,
			onActionClick
		};
	}
})
</script>