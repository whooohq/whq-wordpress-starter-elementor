<template>
	<div class="jet-ui_сolorpicker"
		 :class="{
			'jet-ui_сolorpicker--disabled': disabled,
		 }">
		<color-input v-model="color"
					 class="jet-ui_сolorpicker-color"
					 format="hex"
					 position="bottom right"
					 disable-text-inputs
					 disable-alpha
					 :disabled="disabled" />
		<input class="jet-ui_сolorpicker-text"
			   type="text"
			   :value="modelValue"
			   :name="name"
			   :disabled="disabled"
			   maxlength="7"
			   @input="onInput" />
	</div>
</template>

<script>
import { defineComponent, computed, ref } from "vue";
import ColorInput from "vue-color-input";

export default defineComponent({
	name: "Colorpicker",

	components: {
		ColorInput
	},

	props: {
		modelValue: { type: String, required: true },
		name: { type: String, default: null },
		disabled: { type: Boolean, default: false }
	},

	setup(props, context) {
		// Computed Data
		const color = computed({
			get: () => props.modelValue,
			set: (value) => context.emit('update:modelValue', value)
		});

		// Actions
		const onInput = (evt) => {
			color.value = evt.target.value;
		};

		return {
			color,
			onInput,
		};
	}
});
</script>

<style lang="scss">
@import "../scss/controls/colorpicker.scss";
</style>
