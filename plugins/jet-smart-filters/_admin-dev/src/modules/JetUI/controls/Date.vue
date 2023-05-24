<template>
	<div class="jet-ui_date"
		 :class="{
			'jet-ui_date--disabled': disabled,
		 }">
		<div class="jet-ui_date-edit"
			 v-if="editMode">
			<DateInput class="jet-ui_date-edit-input"
					   :date="modelValue"
					   :monthsList="monthsList"
					   @onChange="onDateChange" />
			<div class="jet-ui_date-edit-actions">
				<Button class="jet-ui_date-edit-actions-cancel-button"
						type="transparent"
						text="Cancel"
						@click="onEditCancelClick" />
				<Button class="jet-ui_date-edit-actions-apply-button"
						:disabled="isApplyButtonDisabled"
						text="Apply"
						@click="onEditApplyClick" />
			</div>
		</div>
		<div class="jet-ui_date-value"
			 v-else>
			<slot :date="date"
				  :isDateValid="isDateValid"
				  :year="year"
				  :month="month"
				  :day="day"
				  :hours="hours"
				  :minute="minute">
				<template v-if="isDateValid">
					<span class="jet-ui_date-value-label">
						<svg width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.1667 9.99992H8V14.1666H12.1667V9.99992ZM11.3333 0.833252V2.49992H4.66667V0.833252H3V2.49992H2.16667C1.24167 2.49992 0.508333 3.24992 0.508333 4.16659L0.5 15.8333C0.5 16.7499 1.24167 17.4999 2.16667 17.4999H13.8333C14.75 17.4999 15.5 16.7499 15.5 15.8333V4.16659C15.5 3.24992 14.75 2.49992 13.8333 2.49992H13V0.833252H11.3333ZM13.8333 15.8333H2.16667V6.66658H13.8333V15.8333Z" /></svg>
						Published on:
					</span>
					<span class="jet-ui_date-value-month">{{month}}</span>
					<span class="jet-ui_date-value-day">{{day}}</span>,
					<span class="jet-ui_date-value-year">{{year}}</span> at
					<span class="jet-ui_date-value-hours">{{hours}}</span>
					<span class="jet-ui_date-value-colon">:</span>
					<span class="jet-ui_date-value-minute">{{minute}}</span>
				</template>
			</slot>
			<Button class="jet-ui_date-edit-button"
					type="transparent"
					@click="onEditClick">Edit</Button>
		</div>
	</div>
</template>

<script>
import { defineComponent, ref, computed, watch } from "vue";
import _date from "@/modules/helpers/date.js";
import DateInput from './DateInput.vue';
import Button from './Button.vue';

export default defineComponent({
	name: "Date",

	components: {
		DateInput,
		Button
	},

	props: {
		modelValue: { type: String, required: true },
		monthsList: { type: Array, default: () => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] },
		disabled: { type: Boolean, default: false }
	},

	setup(props, context) {
		const date = computed(() => new Date(props.modelValue));

		const editMode = ref(false);
		const tempDate = ref(false);
		const isDateValid = ref(false);
		const isApplyButtonDisabled = computed(() => !tempDate.value || tempDate.value === props.modelValue);

		const year = ref('');
		const month = ref('');
		const day = ref('');
		const hours = ref('');
		const minute = ref('');

		// Watchers
		watch(date, () => {
			isDateValid.value = _date.isValid(date.value);

			year.value = isDateValid.value ? date.value.getFullYear() : '';
			month.value = isDateValid.value ? props.monthsList[date.value.getMonth()] : '';
			day.value = isDateValid.value ? date.value.getDate() : '';
			hours.value = isDateValid.value ? ('0' + date.value.getHours()).slice(-2) : '';
			minute.value = isDateValid.value ? ('0' + date.value.getMinutes()).slice(-2) : '';
		},
			{ immediate: true }
		);

		// Actions
		const onEditClick = () => {
			editMode.value = true;
		};

		const onDateChange = (newDate) => {
			tempDate.value = newDate;
		};

		const onEditCancelClick = () => {
			tempDate.value = false;
			editMode.value = false;
		};

		const onEditApplyClick = () => {
			context.emit('update:modelValue', tempDate.value);

			tempDate.value = false;
			editMode.value = false;
		};

		return {
			date,
			editMode,
			isDateValid,
			isApplyButtonDisabled,
			year,
			month,
			day,
			hours,
			minute,
			onEditClick,
			onDateChange,
			onEditCancelClick,
			onEditApplyClick
		};
	}
});
</script>

<style lang="scss">
@import "../scss/controls/date.scss";
</style>
