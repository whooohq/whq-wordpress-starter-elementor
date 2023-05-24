<template>
	<button class="jet-ui_button"
			:class="{
				['jet-ui_button--' + type]: type !== 'primary',
				['jet-ui_button--' + size]: size !== 'default',
				'jet-ui_button--disabled': disabled,
			}"
			:disabled="disabled">
		<slot>{{text}}</slot>
	</button>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
	name: "Button",

	props: {
		text: { type: String, default: null },
		type: { type: String, default: 'primary', validator: val => ['primary', 'secondary', 'transparent'].includes(val) },
		size: { type: String, default: 'default', validator: val => ['default', 'large', 'small'].includes(val) },
		disabled: { type: Boolean, default: false }
	}
});
</script>

<style lang="scss">
@import "../scss/controls/button.scss";
</style>
