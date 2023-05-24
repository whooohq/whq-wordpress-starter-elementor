<template>
	<div class="jet-ui_checkboxes-list"
		 :class="{
			'jet-ui_checkboxes-list--disabled': disabled,
		 }">
		<div class="jet-ui_checkboxes-list-item"
			 v-for="( option, index ) in optionsList"
			 :key="option.value">
			<div class="jet-ui_checkbox"
				 :class="{
					'jet-ui_checkbox--disabled': option.disabled || disabled,
					'jet-ui_checkbox--checked': option.checked,
				 }"
				 @click="onCheckboxClick(option)">
				<input class="jet-ui_checkbox-input"
					   type="checkbox"
					   :value="option.value"
					   :checked="option.checked"
					   :name="name"
					   :disabled="option.disabled || disabled">
				<div class="jet-ui_checkbox-marker">
					<svg v-if="option.checked" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 0H2C0.9 0 0 0.9 0 2V16C0 17.1 0.9 18 2 18H16C17.1 18 18 17.1 18 16V2C18 0.9 17.1 0 16 0ZM16 16H2V2H16V16ZM14.99 6L13.58 4.58L6.99 11.17L4.41 8.6L2.99 10.01L6.99 14L14.99 6Z" /></svg>
					<svg v-else width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 2V16H2V2H16ZM16 0H2C0.9 0 0 0.9 0 2V16C0 17.1 0.9 18 2 18H16C17.1 18 18 17.1 18 16V2C18 0.9 17.1 0 16 0Z"/></svg>
				</div>
				<div class="jet-ui_checkbox-label">
					{{option.label}}
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { defineComponent, computed } from "vue";

export default defineComponent({
	name: "Checkboxes",

	props: {
		modelValue: { type: Array, required: true },
		options: { type: Array, default: () => [] },
		name: { type: String, default: null },
		disabled: { type: Boolean, default: false }
	},

	setup(props, context) {
		// Computed data
		const optionsList = computed(() => {
			return props.options.map(({ ...option }) => {
				option.checked = isOptionChecked(option);

				return option;
			});
		});

		// Methods
		const isOptionChecked = (option) => {
			if (!props.modelValue)
				return false;

			return props.modelValue.includes(option.value);
		};

		// Actions
		const onCheckboxClick = (option) => {
			option.checked = !option.checked;

			const newModelValue = [];

			optionsList.value.forEach(option => {
				if (option.checked)
					newModelValue.push(option.value);
			});

			context.emit('update:modelValue', newModelValue);
		};

		return {
			optionsList,
			onCheckboxClick
		};
	}
});
</script>

<style lang="scss">
@import "../scss/controls/checkboxes.scss";
</style>
