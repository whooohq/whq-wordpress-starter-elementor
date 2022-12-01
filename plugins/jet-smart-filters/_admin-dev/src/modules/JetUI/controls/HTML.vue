<template>
	<div class="jet-ui_html"
		 :class="{
			'jet-ui_html--disabled': disabled,
		 }"
		 v-html="html" />
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
	name: "Text",

	props: {
		html: { type: String, required: true },
		disabled: { type: Boolean, default: false }
	},
});
</script>

<style lang="scss">
@import "../scss/controls/html.scss";
</style>
