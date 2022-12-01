<template>
	<div class="jet-ui_radio-list"
		 :class="{
			'jet-ui_radio-list--disabled': disabled,
		 }">
		<div class="jet-ui_radio-list-item"
			 v-for="( option, index ) in optionsList"
			 :key="option.value">
			<div class="jet-ui_radio"
				 :class="{
					'jet-ui_radio--disabled': option.disabled || disabled,
					'jet-ui_radio--checked': option.checked,
				 }"
				 @click="onRadioClick(option)">
				<input class="jet-ui_radio-input"
					   type="radio"
					   :value="option.value"
					   :checked="option.checked"
					   :name="name"
					   :disabled="option.disabled || disabled">
				<div class="jet-ui_radio-marker">
					<svg v-if="option.checked" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 0C4.48 0 0 4.48 0 10C0 15.52 4.48 20 10 20C15.52 20 20 15.52 20 10C20 4.48 15.52 0 10 0ZM10 18C5.58 18 2 14.42 2 10C2 5.58 5.58 2 10 2C14.42 2 18 5.58 18 10C18 14.42 14.42 18 10 18Z" fill="#007CBA"/><path d="M10 15C12.7614 15 15 12.7614 15 10C15 7.23858 12.7614 5 10 5C7.23858 5 5 7.23858 5 10C5 12.7614 7.23858 15 10 15Z" /></svg>
					<svg v-else width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 0C4.48 0 0 4.48 0 10C0 15.52 4.48 20 10 20C15.52 20 20 15.52 20 10C20 4.48 15.52 0 10 0ZM10 18C5.58 18 2 14.42 2 10C2 5.58 5.58 2 10 2C14.42 2 18 5.58 18 10C18 14.42 14.42 18 10 18Z" /></svg>
				</div>
				<div class="jet-ui_radio-label">
					{{option.label}}
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { defineComponent, computed } from "vue";

export default defineComponent({
	name: "Radio",

	props: {
		modelValue: { type: [String, Number], required: true },
		options: { type: Array, default: () => [] },
		deselect: { type: Boolean, default: false },
		name: { type: String, default: null },
		disabled: { type: Boolean, default: null }
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

			return props.modelValue === option.value;
		};

		const uncheckOptionsList = () => {
			optionsList.value.forEach(option => {
				delete option.checked;
			});
		};

		// Actions
		const onRadioClick = (option) => {
			const prevChecked = option.checked;

			uncheckOptionsList();

			option.checked = props.deselect
				? !prevChecked
				: true;

			if (option.checked !== prevChecked)
				context.emit('update:modelValue', option.checked ? option.value : '');
		};

		return {
			optionsList,
			onRadioClick
		};
	}
});
</script>

<style lang="scss">
@import "../scss/controls/radio.scss";
</style>
