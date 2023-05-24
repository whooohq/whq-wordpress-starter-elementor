<template>
	<div class="jet-ui_select"
		 :class="{
			'jet-ui_select--multiple': multiple,
			'jet-ui_select--active': isOpen,
			'jet-ui_select--disabled': disabled
		 }"
		 ref="select">
		<!-- Panel -->
		<div class="jet-ui_select-panel"
			 @click="onSelectClick">
			<!-- Value -->
			<div class="jet-ui_select-value"
				 v-if="selected">
				<!-- Multiple value -->
				<template v-if="multiple">
					<div v-for="( tag, index ) in selected"
						 :key="tag.value"
						 class="jet-ui_select-tag"
						 @click="onTagClick(tag, $event)">
						{{tag.label}}
					</div>
				</template>
				<!-- Single value -->
				<template v-else>
					{{selected.label}}
				</template>
			</div>
			<!-- Placeholder -->
			<div class="jet-ui_select-placeholder"
				 v-else>
				{{placeholder}}
			</div>
			<!-- Clear -->
			<div v-if="selected && clearEnabled"
				 class="jet-ui_select-clear"
				 @click="onClearClick" />
			<!-- Caret -->
			<div class="jet-ui_select-caret" />
		</div>
		<!-- Dropdown -->
		<div class="jet-ui_select-dropdown">
			<div v-if="searchEnabled"
				 class="jet-ui_select-options-search">
				<input class="jet-ui_select-options-search-input"
					   type="search"
					   placeholder="Search..."
					   v-model="optionsSearch">
				<div v-if="optionsSearch"
					 class="jet-ui_select-options-search-clear"
					 @click="onOptionsSearchClearClick" />
			</div>
			<slot name="beforelist"></slot>
			<!-- Options -->
			<ul v-if="optionsList.length"
				class="jet-ui_select-options">
				<li v-for="( option, index ) in optionsList"
					:key="option.value"
					class="jet-ui_select-option"
					:class="{'jet-ui_select-option--selected': option.selected}"
					@click="onOptionClick(option)">
					{{ option.label }}
				</li>
			</ul>
			<slot v-else
				  name="nooptions">
				<div class="jet-ui_select-nooptions"
					 v-html="noOptionsText" />
			</slot>
			<slot name="afterlist"></slot>
		</div>
	</div>
</template>

<script>
import { defineComponent, ref, computed } from "vue";
import useDropDown from "../composables/dropdown.js";
import _array from "@/modules/helpers/array.js";

export default defineComponent({
	name: "Select",

	emits: [
		'update:modelValue', 'change', 'clear',
		'open', 'close',
	],

	props: {
		modelValue: { type: [String, Number, Array], required: true },
		multiple: { type: Boolean, default: false },
		options: { type: Array, default: () => [] },
		placeholder: { type: String, default: '' },
		search: { type: [Boolean, Number], default: 20 },
		clearEnabled: { type: Boolean, default: false },
		noOptionsText: { type: String, default: 'The list is empty' },
		deselect: { type: Boolean, default: false },
		disabled: { type: Boolean, default: false }
	},

	setup(props, context) {
		// Data
		const select = ref('');
		const optionsSearch = ref('');

		const dropdown = useDropDown({
			areaElement: select,
			onOpen: () => { context.emit('open'); },
			onClose: () => { context.emit('close'); }
		});

		// Computed data
		const optionsList = computed(() => {
			return props.options.map(({ ...option }) => {
				option.selected = isOptionSelected(option);

				return option;
			}).filter(option => {
				if (option.disabled)
					return false;

				if (optionsSearch.value)
					return option.label.toLowerCase().includes(optionsSearch.value.toLowerCase());

				return true;
			});
		});

		const selected = computed(() => {
			if (props.multiple && Array.isArray(props.modelValue)) {
				const selectedData = [];

				props.modelValue.forEach(item => {
					const selectedOption = _array.findByPropertyValue(props.options, 'value', item);

					if (selectedOption)
						selectedData.push(selectedOption);
				});

				return selectedData.length
					? selectedData
					: false;
			} else {
				return _array.findByPropertyValue(props.options, 'value', props.modelValue);
			}
		});

		const searchEnabled = computed(() => {
			if (!props.search)
				return false;

			return props.options.length >= props.search;
		});

		// Methods
		const updateOption = (option) => {
			let newModelValue = '';

			if (props.multiple) {
				const newValue = option.value;

				newModelValue = props.modelValue && Array.isArray(props.modelValue)
					? [...props.modelValue]
					: [];

				const newValueIndex = newModelValue.indexOf(newValue);

				if (newValueIndex === -1) {
					newModelValue.push(newValue);
				} else {
					newModelValue.splice(newValueIndex, 1);
				}
			} else {
				newModelValue = props.modelValue !== option.value
					? option.value
					: '';
			}

			context.emit('update:modelValue', newModelValue);
		};

		const isOptionSelected = (option) => {
			return props.multiple
				? props.modelValue.includes(option.value)
				: props.modelValue === option.value;
		};

		// Actions
		const onSelectClick = () => {
			dropdown.switchState();
		};

		const onClearClick = (evt) => {
			evt.stopPropagation();

			context.emit('update:modelValue', '');
			dropdown.close();
		};

		const onTagClick = (option, evt) => {
			evt.stopPropagation();

			updateOption(option);
		};

		const onOptionClick = (option) => {
			if (!props.deselect && !props.multiple && option.selected)
				return;

			updateOption(option);

			if (!props.multiple)
				dropdown.close();
		};

		const onOptionsSearchClearClick = (evt) => {
			evt.stopPropagation();

			optionsSearch.value = '';
		};

		return {
			select,
			optionsList,
			selected,
			isOpen: dropdown.opened,
			optionsSearch,
			searchEnabled,
			onSelectClick,
			onClearClick,
			onTagClick,
			onOptionClick,
			onOptionsSearchClearClick
		};
	}
});
</script>

<style lang="scss">
@import "../scss/controls/select.scss";
</style>