<template>
	<div class="jet-ui_number"
		 :class="{
			'jet-ui_number--disabled': disabled,
		 }">
		<input class="jet-ui_number-input"
			   type="number"
			   :value="modelValue"
			   :min="min"
			   :max="max"
			   :placeholder="placeholder"
			   :name="name"
			   :disabled="disabled"
			   @focus="onFocus"
			   @input="onInput" />
	</div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
	name: "Number",

	props: {
		modelValue: { type: [Number, String], required: true },
		max: { type: Number, default: null },
		min: { type: Number, default: null },
		maxLength: { type: Number, default: null },
		placeholder: { type: String, default: null },
		selectOnFocus: { type: Boolean, default: false },
		name: { type: String, default: null },
		disabled: { type: Boolean, default: false }
	},

	setup(props, context) {
		const onFocus = (evt) => {
			if (props.selectOnFocus)
				evt.target.select();
		};

		const onInput = (evt) => {
			let newValue = evt.target.value;

			if (props.maxLength && newValue.length > props.maxLength)
				newValue = newValue.slice(0, props.maxLength);

			if (props.min && Number(newValue) < props.min)
				newValue = props.min;

			if (props.max && Number(newValue) > props.max)
				newValue = props.max;

			context.emit('update:modelValue', newValue !== '' ? Number(newValue) : '');

			if (newValue)
				evt.target.value = newValue;
		};

		return {
			onFocus,
			onInput
		};
	}
});
</script>

<style lang="scss">
@import "../scss/controls/number.scss";
</style>
