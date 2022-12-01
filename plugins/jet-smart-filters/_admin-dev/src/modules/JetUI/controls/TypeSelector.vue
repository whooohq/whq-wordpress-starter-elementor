<template>
	<div class="jet-ui_type-selector-list"
		 :class="{
			'jet-ui_type-selector-list--disabled': disabled,
		 }">
		<div class="jet-ui_type-selector-list-item"
			 v-for="( option, index ) in typesList"
			 :key="option.value">
			<div class="jet-ui_type-selector"
				 :class="{
					'jet-ui_type-selector--disabled': option.disabled || disabled,
					'jet-ui_type-selector--checked': option.checked,
				 }"
				 @click="onTypeSelectorClick(option)">
				<input class="jet-ui_type-selector-input"
					   type="radio"
					   :value="option.value"
					   :checked="option.checked"
					   :name="name"
					   :disabled="option.disabled || disabled">
				<svg class="jet-ui_type-selector-marker" width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.50016 9.50008L2.00016 6.00008L0.833496 7.16675L5.50016 11.8334L15.5002 1.83341L14.3335 0.666748L5.50016 9.50008Z" /></svg>
				<div v-if="option.data.info"
					 class="jet-ui_type-selector-info-button"
					 @click="onInfoClick($event, option.value)">
					<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.99984 0.666748C4.39984 0.666748 0.666504 4.40008 0.666504 9.00008C0.666504 13.6001 4.39984 17.3334 8.99984 17.3334C13.5998 17.3334 17.3332 13.6001 17.3332 9.00008C17.3332 4.40008 13.5998 0.666748 8.99984 0.666748ZM9.83317 13.1667H8.1665V8.16675H9.83317V13.1667ZM9.83317 6.50008H8.1665V4.83342H9.83317V6.50008Z" /></svg>
				</div>
				<img v-if="option.data.img"
					 class="jet-ui_type-selector-img"
					 :src="option.data.img">
				<div v-if="option.data.label"
					 class="jet-ui_type-selector-label">
					{{option.data.label}}
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { defineComponent, computed } from "vue";
import popup from "@/services/popups.js";

export default defineComponent({
	name: "TypeSelector",

	props: {
		modelValue: { type: [String, Number], required: true },
		options: { type: Array, default: () => [] },
		deselect: { type: Boolean, default: false },
		name: { type: String, default: null },
		disabled: { type: Boolean, default: null }
	},

	setup(props, context) {
		// Computed data
		const typesList = computed(() => {
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

		const uncheckTypesList = () => {
			typesList.value.forEach(option => {
				delete option.checked;
			});
		};

		// Actions
		const onTypeSelectorClick = (option) => {
			const prevChecked = option.checked;

			uncheckTypesList();

			option.checked = props.deselect
				? !prevChecked
				: true;

			if (option.checked !== prevChecked)
				context.emit('update:modelValue', option.checked ? option.value : '');
		};

		const onInfoClick = (evt, filterName) => {
			evt.stopPropagation();

			popup.filterInfo(filterName);
		};

		return {
			typesList,
			onTypeSelectorClick,
			onInfoClick
		};
	}
});
</script>

<style lang="scss">
@import "../scss/controls/type-selector.scss";
</style>
