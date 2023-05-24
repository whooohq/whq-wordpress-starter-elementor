<template>
	<div class="jet-ui_textarea"
		 :class="{
			'jet-ui_textarea--disabled': disabled,
		 }">
		<textarea class="jet-ui_textarea-input"
				  :value="modelValue"
				  :rows="rows"
				  :placeholder="placeholder"
				  :disabled="disabled"
				  @input="onInput" />
	</div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
	name: "Textarea",

	props: {
		modelValue: { type: String, required: true },
		rows: { type: Number, default: 4 },
		placeholder: { type: String, default: null },
		disabled: { type: Boolean, default: false }
	},

	setup(props, context) {
		const onInput = (evt) => {
			context.emit('update:modelValue', evt.target.value);
		};

		return {
			onInput
		};
	}
});
</script>

<style lang="scss">
@import "../scss/controls/textarea.scss";
</style>
