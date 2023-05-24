<template>
	<div class="jet-ui_switcher"
		 :class="{
			'jet-ui_switcher--on': value,
			'jet-ui_switcher--off': !value,
			'jet-ui_switcher--disabled': disabled,
		 }"
		 @click="onClick">
		<input type="hidden"
			   class="jet-ui_switcher-input"
			   :value="value"
			   :name="name"
			   :checked="value">
		<div class="jet-ui_switcher-panel"></div>
		<div class="jet-ui_switcher-trigger"></div>
	</div>
</template>

<script>
import { defineComponent, computed } from "vue";
import { stringToBoolean } from "@/modules/helpers/utils.js";

export default defineComponent({
	name: "Switcher",

	props: {
		modelValue: { type: [Boolean, String], required: true },
		name: { type: String, default: null },
		disabled: { type: Boolean, default: false }
	},

	setup(props, context) {
		const value = computed(() => stringToBoolean(props.modelValue));

		// Actions
		const onClick = () => {
			context.emit('update:modelValue', !value.value);
		};

		return {
			value,
			onClick
		};
	}
});
</script>

<style lang="scss">
@import "../scss/controls/switcher.scss";
</style>
