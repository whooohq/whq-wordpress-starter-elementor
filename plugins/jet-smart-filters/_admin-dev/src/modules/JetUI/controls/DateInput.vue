<template>
	<div class="jet-ui_date-input"
		 :class="{
			'jet-ui_date-input--disabled': disabled,
		 }">
		<Select class="jet-ui_date-input-month"
				:class="incorrectFieldClass('month')"
				:modelValue="month"
				:options="monthsList"
				placeholder="m"
				@update:modelValue="onChange('month', $event)" />
		<Number class="jet-ui_date-input-day"
				:class="incorrectFieldClass('day')"
				:modelValue="day"
				:maxLength="2"
				:selectOnFocus="true"
				placeholder="dd"
				@update:modelValue="onChange('day', $event)" />
		<span class="jet-ui_date-input-comma">,</span>
		<Number class="jet-ui_date-input-year"
				:class="incorrectFieldClass('year')"
				:modelValue="year"
				:maxLength="4"
				:selectOnFocus="true"
				placeholder="yyyy"
				@update:modelValue="onChange('year', $event)" />
		<span class="jet-ui_date-input-at">at</span>
		<Number class="jet-ui_date-input-hours"
				:class="incorrectFieldClass('hours')"
				:modelValue="hours"
				:maxLength="2"
				:selectOnFocus="true"
				placeholder="H"
				@update:modelValue="onChange('hours', $event)" />
		<span class="jet-ui_date-input-colon">:</span>
		<Number class="jet-ui_date-input-minute"
				:class="incorrectFieldClass('minute')"
				:modelValue="minute"
				:maxLength="2"
				:selectOnFocus="true"
				placeholder="M"
				@update:modelValue="onChange('minute', $event)" />
	</div>
</template>

<script>
import { defineComponent, ref, computed, watch } from "vue";
import _date from "@/modules/helpers/date.js";
import Select from './Select.vue';
import Number from './Number.vue';

export default defineComponent({
	name: "DateInput",

	components: {
		Select,
		Number
	},

	props: {
		date: { type: String, required: true },
		monthsList: { type: Array, default: () => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] },
		disabled: { type: Boolean, default: false }
	},

	setup(props, context) {
		const monthsList = computed(() => {
			const options = [];

			for (let index = 0; index < props.monthsList.length; index++) {
				options.push({
					value: index,
					label: props.monthsList[index]
				});
			}

			return options;
		});

		const date = computed(() => new Date(props.date));

		const year = ref('');
		const month = ref('');
		const day = ref('');
		const hours = ref('');
		const minute = ref('');

		const incorrectFields = ref([]);

		// Watchers
		watch(date, () => {
			if (!_date.isValid(date.value)) {
				incorrectFields.value = ['year', 'month', 'day', 'hours', 'minute'];
				return;
			}

			incorrectFields.value = [];

			year.value = date.value.getFullYear();
			month.value = date.value.getMonth();
			day.value = date.value.getDate();
			hours.value = date.value.getHours();
			minute.value = date.value.getMinutes();
		},
			{ immediate: true }
		);

		// Methods
		const incorrectFieldClass = (fieldName) => {
			return incorrectFields.value.includes(fieldName)
				? 'jet-ui_date-input--incorrect'
				: '';
		};

		const isMonthDayExist = (day, month, year) => {
			return day >= 1 && day <= _date.daysInMonth(month, year);
		};

		// Actions
		const onChange = (type, value) => {
			incorrectFields.value = [];

			switch (type) {
				case 'year':
					if (value === '' || value < 1900)
						incorrectFields.value.push('year');

					if (!isMonthDayExist(day.value, month.value, value))
						incorrectFields.value.push('day');

					year.value = value;

					break;

				case 'month':
					if (value === '' || !(value >= 0 && value < monthsList.value.length))
						incorrectFields.value.push('month');

					if (!isMonthDayExist(day.value, value, year.value))
						incorrectFields.value.push('day');

					month.value = value;

					break;

				case 'day':
					if (value === '' || !isMonthDayExist(value, month.value, year.value))
						incorrectFields.value.push('day');

					day.value = value;

					break;

				case 'hours':
					if (value === '' || !(value >= 0 && value <= 23))
						incorrectFields.value.push('hours');

					hours.value = value;

					break;

				case 'minute':
					if (value === '' || !(value >= 0 && value <= 59))
						incorrectFields.value.push('minute');

					minute.value = value;

					break;
			}

			let newDate = false;

			if (!incorrectFields.value.length)
				newDate =
					year.value + '-' + ('0' + (month.value + 1)).slice(-2) + '-' + day.value
					+ ' ' +
					('0' + hours.value).slice(-2) + ':' + ('0' + minute.value).slice(-2) + ':' + ('0' + date.value.getSeconds()).slice(-2);

			context.emit('onChange', newDate);
		};

		return {
			monthsList,
			date,
			year,
			month,
			day,
			hours,
			minute,
			incorrectFieldClass,
			onChange
		};
	}
});
</script>

<style lang="scss">
@import "../scss/controls/date-input.scss";
</style>
