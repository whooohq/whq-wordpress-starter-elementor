<template>
	<Repeater v-model="items"
			  :item="newItem"
			  :axis="axis"
			  :disabled="disabled">
		<template v-slot="{item, index}">
			<div class="jet-ui_controls-repeater-list">
				<div class="jet-ui_controls-repeater-list-item"
					 v-for="(control, controlKey) in getControls(item)"
					 :key="controlKey">
					<h5 class="jet-ui_controls-repeater-title"
						v-if="control.title"
						v-html="control.title" />
					<component class="jet-ui_controls-repeater-control"
							   :is="getControlName(control)"
							   :modelValue="getControlValue(control)"
							   v-bind="getControlAttrs(control)"
							   @update:modelValue="onControlUpdate($event, controlKey, index)" />
				</div>
			</div>
		</template>
	</Repeater>
</template>

<script>
import { defineComponent, computed } from "vue";
import Repeater from "../Repeater";
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

import controlsListServices from "../services/controls-list.js";

export default defineComponent({
	name: "ControlsRepeater",

	components: {
		Repeater,
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
		modelValue: { type: Array, required: true },
		fields: { type: Object, required: true },
		axis: { type: String, default: 'y' },
		label: { type: String, default: '' },
		add_label: { type: String, default: '+ Add' },
		disabled: { type: Boolean, default: false },
	},

	setup(props, context) {
		// Computed Data
		const items = computed({
			get: () => [...props.modelValue],
			set: (value) => context.emit('update:modelValue', value)
		});

		const newItem = computed(() => {
			const itemData = {};

			for (const fieldKey in props.fields)
				itemData[fieldKey] = props.fields[fieldKey].value || '';

			return itemData;
		});

		// Methods
		const getControls = (item) => {
			const controls = {};

			for (const controlKey in item) {
				if (!props.fields.hasOwnProperty(controlKey))
					continue;

				const control = { ...props.fields[controlKey] };

				if (item[controlKey])
					control.value = item[controlKey];

				if (!control.hidden)
					controls[controlKey] = controlsListServices.controlPreparation(control);
			}

			return controls;
		};

		const getControlName = (control) => controlsListServices.getControlName(control);

		// Actions
		const onControlUpdate = (value, key, index) => {
			const newItems = items.value;

			newItems[index][key] = value;
			items.value = newItems;

			context.emit('controlChanged', { index, key, value });
		};

		return {
			items,
			newItem,
			getControls,
			getControlName,
			getControlValue: controlsListServices.getControlValue,
			getControlAttrs: controlsListServices.getControlAttrs,
			onControlUpdate
		};
	}
});
</script>

<style lang="scss">
@import "../scss/controls/repeater.scss";
</style>
