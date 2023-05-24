<template>
	<DropDownMenu :items="options"
				  :label="label"
				  :autoClose="autoClose"
				  v-slot="{itemData}">
		<Checkbox v-model="checked[itemData.key]"
				  :label="itemData.value"
				  @update:modelValue="onCheck" />
	</DropDownMenu>
</template>

<script>
import { defineComponent, ref, watchEffect } from "vue";
import DropDownMenu from "./DropDownMenu.vue";
import Checkbox from "../Checkbox.vue";
import _object from "@/modules/helpers/object.js";
import _array from "@/modules/helpers/array.js";

export default defineComponent({
	name: "DropDownCheckboxs",

	components: {
		Checkbox,
		DropDownMenu,
	},

	props: {
		options: { type: Array, required: true },
		label: { type: String, default: '' },
		autoClose: { type: Boolean, default: false },
	},

	setup(props, context) {
		const checked = ref({});

		watchEffect(() => {
			for (const option in props.options)
				if (option.checked)
					checked.value[option.key] = option.checked || false;
		});

		const onCheck = () => {
			const checkedValue = Object.keys(_object.filterByValue(checked.value, true));

			context.emit('onChange', !_array.isEmpty(checkedValue) ? checkedValue : false);
		};

		return {
			checked,
			onCheck
		};
	}
});
</script>
