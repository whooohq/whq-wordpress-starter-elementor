<template>
	<DropDown :label="label">
		<div class="jet_dropdown-data-range">
			<div class="jet_dropdown-data-range-from">
				<label>From:</label>
				<input type="date"
					   name="data-range-from"
					   v-model="fromDate"
					   :max="toDate"
					   @input="onChange">
			</div>
			<div class="jet_dropdown-data-range-to">
				<label>To:</label>
				<input type="date"
					   name="data-range-to"
					   v-model="toDate"
					   :min="fromDate"
					   @input="onChange">
			</div>
		</div>
	</DropDown>
</template>

<script>
import { defineComponent, ref } from "vue";
import DropDown from "./DropDown.vue";
import _object from "@/modules/helpers/object.js";

export default defineComponent({
	name: "DropDownMenu",

	components: {
		DropDown
	},

	props: {
		label: { type: String, default: '' },
		from: { type: String, default: '' },
		to: { type: String, default: '' }
	},

	setup(props, context) {
		const fromDate = ref(props.from);
		const toDate = ref(props.to);

		// Events
		const onChange = () => {
			const dateRange = {};

			if (fromDate.value)
				dateRange.from = fromDate.value;

			if (toDate.value)
				dateRange.to = toDate.value;

			context.emit('onChange', !_object.isEmpty(dateRange) ? dateRange : false);
		};

		return {
			fromDate,
			toDate,
			onChange
		};
	}
});
</script>
