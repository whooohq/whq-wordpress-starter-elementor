<template>
	<div class="jet_filter">
		<div class="jet_filter-title-wrapper">
			<h1 class="jet_filter-title">Edit Filter</h1>
			<Button class="jet_filter-back-to-list"
					text="Back to Listing"
					@click="onBackToList" />
		</div>
		<Preloader :show="isPageLoading" />
		<div v-if="!isPageLoading"
			 class="jet_filter-content">
			<div class="jet_filter-settings">
				<ControlsList v-for="(data, key) in filterSettings"
							  :key="key"
							  :class="'jet_filter-section-' + key"
							  v-model="data.settings"
							  :title="data.label"
							  @controlChanged="onChange($event.key, $event.value)"
							  @controlsRequiredNotFilledChange="onRequiredNotFilledChange($event, key)" />
			</div>
			<div class="jet_filter-sidebar">
				<div class="jet_filter-publishing">
					<h3 class="jet_filter-publishing-title">Publish</h3>
					<div v-if="!isFilterNew"
						 class="jet_filter-publishing-date">
						<Date :modelValue="filterDate"
							  @update:modelValue="onFilterDateChange" />
					</div>
					<div class="jet_filter-publishing-actions">
						<Button v-if="!isFilterNew"
								class="jet_filter-remove"
								@click="onMoveToTrash">
							<svg width="12" height="16" viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.00033 13.8333C1.00033 14.75 1.75033 15.5 2.66699 15.5H9.33366C10.2503 15.5 11.0003 14.75 11.0003 13.8333V3.83333H1.00033V13.8333ZM2.66699 5.5H9.33366V13.8333H2.66699V5.5ZM8.91699 1.33333L8.08366 0.5H3.91699L3.08366 1.33333H0.166992V3H11.8337V1.33333H8.91699Z" /></svg>
							Move to trash
						</Button>
						<UploadButton class="jet_filter-update"
									  :loading="isFilterLoading"
									  :disabled="!updateAvailable"
									  text="Update"
									  loadingText="Updating"
									  loadedText="Updated"
									  @click="onUpdate" />
					</div>
				</div>
				<div class="jet_filter-help-block">
					<h3 class="jet_filter-help-block-title">{{helpBlockData.title}}</h3>
					<div class="jet_filter-help-block-list">
						<a class="jet_filter-help-block-list-item"
						   v-for="item in helpBlockData.list"
						   :href="item.link"
						   target="_blank" >
							<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.00033 0.666748C4.40033 0.666748 0.666992 4.40008 0.666992 9.00008C0.666992 13.6001 4.40033 17.3334 9.00033 17.3334C13.6003 17.3334 17.3337 13.6001 17.3337 9.00008C17.3337 4.40008 13.6003 0.666748 9.00033 0.666748ZM9.83366 14.8334H8.16699V13.1667H9.83366V14.8334ZM11.5587 8.37508L10.8087 9.14175C10.2087 9.75008 9.83366 10.2501 9.83366 11.5001H8.16699V11.0834C8.16699 10.1667 8.54199 9.33342 9.14199 8.72508L10.1753 7.67508C10.4837 7.37508 10.667 6.95842 10.667 6.50008C10.667 5.58342 9.91699 4.83342 9.00033 4.83342C8.08366 4.83342 7.33366 5.58342 7.33366 6.50008H5.66699C5.66699 4.65841 7.15866 3.16675 9.00033 3.16675C10.842 3.16675 12.3337 4.65841 12.3337 6.50008C12.3337 7.23342 12.0337 7.90008 11.5587 8.37508Z" /></svg>
							{{item.label}}
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { defineComponent, computed, onMounted } from "vue";
import { onBeforeRouteUpdate, onBeforeRouteLeave } from 'vue-router';
import { useGetters } from "@/store/helper.js";
import filter from "@/services/filter.js";
import JetUI from "@/modules/JetUI";
import Preloader from "@/components/Preloader.vue";

export default defineComponent({
	name: 'Filter',

	components: {
		ControlsList: JetUI.ControlsList,
		Button: JetUI.controls.Button,
		Date: JetUI.controls.Date,
		UploadButton: JetUI.controls.UploadButton,
		Preloader
	},

	setup(props, context) {
		const {
			filterTitle,
			filterDate,
			filterSettings,
			isPageLoading,
			isFilterLoading,
			isFilterNew
		} = useGetters(['filterTitle', 'filterDate', 'filterSettings', 'isPageLoading', 'isFilterLoading', 'isFilterNew']);

		// Lifecycles
		onMounted(() => {
			filter.init();
		});

		onBeforeRouteUpdate(filter.beforeRouteUpdate);
		onBeforeRouteLeave(filter.beforeRouteLeave);

		// Actions
		const onBackToList = () => {
			filter.goToList();
		};

		const onChange = (key, value) => {
			filter.сhangeSetting(key, value);
		};

		const onUpdate = () => {
			filter.saveData();
		};

		const onMoveToTrash = () => {
			filter.moveToTrash();
		};

		const onFilterDateChange = (newDate) => {
			filter.changeDate(newDate);
		};


		return {
			filterSettings,
			filterTitle,
			filterDate,
			updateAvailable: filter.updateAvailable,
			helpBlockData: filter.helpBlockData,
			isPageLoading,
			isFilterLoading,
			isFilterNew,
			onBackToList,
			onChange,
			onUpdate,
			onMoveToTrash,
			onFilterDateChange,
			onRequiredNotFilledChange: filter.changeRequiredNotFilled
		};
	}
})
</script>