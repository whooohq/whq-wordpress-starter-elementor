<template>
	<div class="jet-ui_advanced-input"
		 :class="{
			'jet-ui_advanced-input-dropdown-opened': dropdownOpened,
			'jet-ui_advanced-input--disabled': disabled,
		 }">
		<input class="jet-ui_advanced-input-text"
			   type="text"
			   :value="modelValue"
			   :placeholder="placeholder"
			   :name="name"
			   :disabled="disabled"
			   @input="onInput" />
		<div v-if="options.length"
			 class="jet-ui_advanced-input-dropdown-button"
			 @click="onDropdownButtonClick">
			<svg v-if="dropdownOpened" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"></rect><g><path d="M14.95 6.46L11.41 10l3.54 3.54-1.41 1.41L10 11.42l-3.53 3.53-1.42-1.42L8.58 10 5.05 6.47l1.42-1.42L10 8.58l3.54-3.53z"></path></g></svg>
			<template v-else>
				<svg viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"></rect><g><path d="M14 10c2.2 0 4-1.8 4-4s-1.8-4-4-4-4 1.8-4 4 1.8 4 4 4zm-1-5V3h2v2h2v2h-2v2h-2V7h-2V5h2zM9 6c0-1.6.8-3 2-4h-1c-3.9 0-7 .9-7 2 0 1 2.6 1.8 6 2zm1 9c-3.9 0-7-.9-7-2v3c0 1.1 3.1 2 7 2s7-.9 7-2v-3c0 1.1-3.1 2-7 2zm2.8-4.2c-.9.1-1.9.2-2.8.2-3.9 0-7-.9-7-2v3c0 1.1 3.1 2 7 2s7-.9 7-2v-2c-.9.7-1.9 1-3 1-.4 0-.8-.1-1.2-.2zM10 10h1c-1-.7-1.7-1.8-1.9-3C5.7 6.9 3 6 3 5v3c0 1.1 3.1 2 7 2z"></path></g></svg>
			</template>
		</div>
		<div class="jet-ui_advanced-input-dropdown-body"
			 ref="dropdownAreaElement">
			<template v-if="!advancedData.fields">
				<ul v-if="options.length"
					class="jet-ui_advanced-input-dropdown-options">
					<li v-for="( option, index ) in options"
						:key="option.value"
						class="jet-ui_advanced-input-dropdown-option"
						@click="onOptionClick(option)">
						<span class="jet-ui_advanced-input-dropdown-option-mark">≫</span>
						{{ option.label }}
					</li>
				</ul>
			</template>
			<template v-else>
				<div class="jet-ui_advanced-input-dropdown-form">
					<div class="jet-ui_advanced-input-dropdown-form-header">
						<h4 v-if="advancedData.form_title"
							class="jet-ui_advanced-input-dropdown-form-title">
							{{advancedData.form_title}}
						</h4>
						<button class="jet-ui_advanced-input-dropdown-form-back"
								@click="onDropdownFormBackClick">Back</button>
					</div>
					<div class="jet-ui_advanced-input-dropdown-form-item"
						 v-for="(control, controlKey) in advancedData.fields"
						 :key="controlKey">
						<h5 class="jet-ui_advanced-input-dropdown-form-item-title"
							v-if="control.title"
							v-html="control.title" />
						<div class="jet-ui_advanced-input-dropdown-form-item-control">
							<component :is="getFormControlName(control)"
									   v-model="advancedData.fields[controlKey].value"
									   v-bind="getFormControlAttrs(control)"
									   @update:modelValue="onFormControlUpdate" />
						</div>
					</div>
				</div>
			</template>
		</div>
	</div>
</template>

<script>
import { defineComponent, ref } from "vue";
import Checkboxes from './Checkboxes.vue';
import Radio from './Radio.vue';
import Select from './Select.vue';
import Text from './Text.vue';
import Textarea from './Textarea.vue';
import Switcher from './Switcher.vue';
import Number from './Number.vue';
import Colorpicker from './Colorpicker.vue';
import Media from './Media.vue';
import HTML from './HTML.vue';

import useDropDown from "../composables/dropdown.js";
import controlsListServices from "../services/controls-list.js";

import { clone } from "@/modules/helpers/utils.js";

export default defineComponent({
	name: "AdvancedInput",

	components: {
		Checkboxes,
		Radio,
		Select,
		Text,
		Textarea,
		Switcher,
		Number,
		Colorpicker,
		Media,
		HTML
	},

	props: {
		modelValue: { type: [String, Object], required: true },
		placeholder: { type: String, default: null },
		options: { type: Array, default: () => [] },
		name: { type: String, default: null },
		disabled: { type: Boolean, default: false }
	},

	setup(props, context) {
		// Data
		const advancedData = ref({});

		const dropdownAreaElement = ref(null);
		const dropdown = useDropDown({
			areaElement: dropdownAreaElement,
			onClose: () => { advancedData.value = {}; }
		});

		// Methods
		const getFormControlName = (control) => controlsListServices.getControlName(control);

		const getSeparator = () => advancedData.value.separator || ':';

		const getAdvancedFormValue = (fields = null) => {
			if (!fields && advancedData.value.fields)
				fields = advancedData.value.fields;

			let formValue = '';

			for (const fieldKey in fields)
				formValue += getSeparator() + fields[fieldKey].value;

			return formValue;
		};

		const changeAdvancedFormValue = (value) => {
			setTimeout(() => {
				advancedData.value.fields = value;
			});
		};

		const clearValue = () => {
			advancedData.value = {};
			context.emit('update:modelValue', '');
		};

		// Actions
		const onInput = (evt) => {
			context.emit('update:modelValue', evt.target.value);
		};

		const onDropdownButtonClick = (evt) => {
			evt.stopPropagation();
			dropdown.switchState();
		};

		const onOptionClick = (option) => {
			clearValue();

			let value = option.value;

			if (option.hasOwnProperty('fields')) {
				const fields = controlsListServices.controlsPreparation(clone(option.fields));

				advancedData.value.key = option.value;
				advancedData.value.form_title = option.label;
				if (option.form_title)
					advancedData.value.form_title = option.form_title;
				if (option.separator)
					advancedData.value.separator = option.separator;

				if (props.modelValue.includes(getSeparator())) {
					const parsedValueData = props.modelValue.split(getSeparator());

					if (parsedValueData.shift() === value)
						for (const fieldKey in fields)
							fields[fieldKey].value = parsedValueData.shift();
				}

				value += getAdvancedFormValue(fields);

				changeAdvancedFormValue(fields);
			}

			context.emit('update:modelValue', value);
		};

		const onDropdownFormBackClick = () => {
			changeAdvancedFormValue(null);
		};

		const onFormControlUpdate = () => {
			const value = advancedData.value.key + getAdvancedFormValue();

			context.emit('update:modelValue', value);
		};

		return {
			dropdownOpened: dropdown.opened,
			advancedData,
			dropdownAreaElement,
			getFormControlName,
			getFormControlAttrs: controlsListServices.getControlAttrs,
			onInput,
			onDropdownButtonClick,
			onOptionClick,
			onDropdownFormBackClick,
			onFormControlUpdate
		};
	}
});
</script>

<style lang="scss">
@import "../scss/controls/advanced-input.scss";
</style>
