<template >
	<h3 class="jet-popup-title">{{texts.title}}</h3>
	<div class="jet-popup-form">
		<div class="jet-popup-form-title">
			<div class="jet-popup-form-label">{{texts.form.title}}:</div>
			<div class="jet-popup-form-control">
				<Text v-model="title" />
			</div>
		</div>
		<div class="jet-popup-form-date">
			<div class="jet-popup-form-label">{{texts.form.date}}:</div>
			<div class="jet-popup-form-control">
				<DateInput :date="date"
						   @onChange="onDateChange" />
			</div>
		</div>
	</div>
	<div class="jet-popup-actions">
		<UploadButton class="jet-popup-actions-apply"
					  :loading="isFiltersListLoading"
					  :disabled="updateDisabled"
					  text="Save"
					  loadingText="Saving"
					  loadedText="Saved"
					  @click="onApplyClick" />
		<Button class="jet-popup-actions-cancel"
				text="Cancel"
				@click="onCancelClick" />
	</div>
</template>

<script>
import { defineComponent, ref, reactive, computed } from "vue";
import { useGetter } from "@/store/helper.js";
import controls from "@/modules/JetUI/controls";
import _object from "@/modules/helpers/object.js";
import filters from "@/services/filters.js";
import filter from "@/services/filter.js";
import popup from "@/services/popups.js";

export default defineComponent({
	name: 'QuickEdit',

	components: {
		Button: controls.Button,
		Text: controls.Text,
		DateInput: controls.DateInput,
		UploadButton: controls.UploadButton,
	},

	setup(props, context) {
		const texts = {
			title: "Quick edit",
			form: {
				title: 'Title',
				date: 'Date',
			}
		};

		const filterId = popup.data.value;
		const filterData = filters.getById(filterId);
		const title = ref(filterData.title);
		const date = ref(filterData.date);
		const filterSavedData = reactive({
			title: filterData.title,
			date: filterData.date
		});
		const isDateValid = ref(true);
		const isFiltersListLoading = useGetter('isFiltersListLoading');

		const unsavedData = computed(() => {
			const data = {};

			if (title.value !== filterSavedData.title)
				data.title = title.value;

			if (isDateValid.value && date.value !== filterSavedData.date)
				data.date = date.value;

			return data;
		});

		const updateDisabled = computed(() => !isDateValid.value || _object.isEmpty(unsavedData.value));

		// Actions
		const onDateChange = (newDate) => {
			if (newDate) {
				isDateValid.value = true;
				date.value = newDate;
			} else {
				isDateValid.value = false;
			}
		};

		const onApplyClick = () => {
			filter.addAdditionallyUpdate(filterId, unsavedData.value);
			filters.updateList();

			filterSavedData.title = title.value;
			filterSavedData.date = date.value;
		};

		const onCancelClick = () => {
			popup.cancel();
		};

		return {
			texts,
			title,
			date,
			updateDisabled,
			isFiltersListLoading,
			onDateChange,
			onApplyClick,
			onCancelClick
		};
	}
});
</script>