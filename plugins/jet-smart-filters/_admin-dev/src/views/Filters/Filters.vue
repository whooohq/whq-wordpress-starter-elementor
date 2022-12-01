<template>
	<div class="jet_filters-list">
		<div class="jet_filters-list-header">
			<h1 class="jet_filters-list-heading">Smart Filters List</h1>
			<router-link class="jet_filters-list-add-new-btn"
						 to="/new"
						 v-slot="{ href, navigate }">Add New</router-link>
			<AdminModeSwitcher />
			<a class="jet_filters-list-settings-link"
			   :href="settingsUrl">
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.9502 8.78333C13.9835 8.53333 14.0002 8.275 14.0002 8C14.0002 7.73333 13.9835 7.46667 13.9418 7.21667L15.6335 5.9C15.7835 5.78333 15.8252 5.55833 15.7335 5.39167L14.1335 2.625C14.0335 2.44167 13.8252 2.38333 13.6418 2.44167L11.6502 3.24167C11.2335 2.925 10.7918 2.65833 10.3002 2.45833L10.0002 0.341667C9.96684 0.141667 9.80017 0 9.60017 0H6.40017C6.20017 0 6.04184 0.141667 6.00851 0.341667L5.70851 2.45833C5.21684 2.65833 4.76684 2.93333 4.35851 3.24167L2.36684 2.44167C2.18351 2.375 1.97517 2.44167 1.87517 2.625L0.283506 5.39167C0.183506 5.56667 0.216839 5.78333 0.383506 5.9L2.07517 7.21667C2.03351 7.46667 2.00017 7.74167 2.00017 8C2.00017 8.25833 2.01684 8.53333 2.05851 8.78333L0.366839 10.1C0.216839 10.2167 0.175173 10.4417 0.266839 10.6083L1.86684 13.375C1.96684 13.5583 2.17517 13.6167 2.35851 13.5583L4.35017 12.7583C4.76684 13.075 5.20851 13.3417 5.70017 13.5417L6.00017 15.6583C6.04184 15.8583 6.20017 16 6.40017 16H9.60017C9.80017 16 9.96684 15.8583 9.99184 15.6583L10.2918 13.5417C10.7835 13.3417 11.2335 13.075 11.6418 12.7583L13.6335 13.5583C13.8168 13.625 14.0252 13.5583 14.1252 13.375L15.7252 10.6083C15.8252 10.425 15.7835 10.2167 15.6252 10.1L13.9502 8.78333ZM8.00017 11C6.35017 11 5.00017 9.65 5.00017 8C5.00017 6.35 6.35017 5 8.00017 5C9.65017 5 11.0002 6.35 11.0002 8C11.0002 9.65 9.65017 11 8.00017 11Z" /></svg>
				Settings
			</a>
			<IndexerButton v-if="isIndexerEnabled" />
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
				  text="No filters found" />
	</div>
</template>

<script>
import { defineComponent, onMounted } from "vue";
import { useGetters } from "@/store/helper.js";
import filters from "@/services/filters.js";
import DataTable from "@/modules/DataTable";
import JetUI from "@/modules/JetUI";
import Preloader from "@/components/Preloader.vue";
import { ActiveHead, BodyCell, Submenu, Filtration, Navigation, AdminModeSwitcher, IndexerButton, NotFound } from "./components";

export default defineComponent({
	name: 'Filters',

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
		/* const onAddNewClick = () => {
			filters.toFilter('new');
		}; */

		return {
			isPageLoading,
			isFiltersListLoading,
			filtersList,
			columns,
			isIndexerEnabled: filters.isIndexerEnabled,
			isСhecked: filters.isСhecked,
			settingsUrl
		};
	}
})
</script>