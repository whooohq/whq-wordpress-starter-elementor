<template>
	<div class="jet-ui_text"
		 :class="{
			'jet-ui_text--disabled': disabled,
		 }">
		<!-- Clear -->
		<div v-if="modelValue && clearEnabled"
			 class="jet-ui_text-clear"
			 @mousedown="onClearClick" />
		<input class="jet-ui_text-input"
			   type="text"
			   :value="modelValue"
			   :placeholder="placeholder"
			   :name="name"
			   :disabled="disabled"
			   @input="onInput" />
	</div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
	name: "Text",

	props: {
		modelValue: { type: String, required: true },
		placeholder: { type: String, default: null },
		clearEnabled: { type: Boolean, default: false },
		name: { type: String, default: null },
		autocomplete: { type: String, default: null },
		disabled: { type: Boolean, default: false }
	},

	setup(props, context) {
		// Actions
		const onInput = (evt) => {
			context.emit('update:modelValue', evt.target.value);
		};

		const onClearClick = () => {
			context.emit('update:modelValue', '');
		};

		return {
			onInput,
			onClearClick
		};
	}
});
</script>

<style lang="scss">
@import "../scss/controls/text.scss";
</style>
