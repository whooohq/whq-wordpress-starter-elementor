<template>
	<div class="jet-ui_controls-list"
		 :class="{
			'jet-ui_controls-list--disabled': disabled,
		 }">
		<h2 class="jet-ui_controls-list-title"
			v-if="title">
			{{title}}
		</h2>
		<div class="jet-ui_controls-list-item"
			 v-for="(control, controlKey) in displayedControls"
			 :key="controlKey"
			 :name="controlKey"
			 :class="{
				'jet-ui_controls-list-item--fullwidth': control.fullwidth,
				'jet-ui_controls-list-item--required-not-filled': controlsRequiredNotFilled.includes(controlKey)
			 }">
			<div class="jet-ui_controls-list-item-meta">
				<h4 class="jet-ui_controls-list-item-title"
					v-if="control.title">
					{{control.title}}
				</h4>
				<div class="jet-ui_controls-list-item-description"
					 v-if="control.description"
					 v-html="control.description" />
			</div>
			<div class="jet-ui_controls-list-item-control">
				<component :is="getControlName(control)"
						   :modelValue="getControlValue(control)"
						   v-bind="getControlAttrs(control)"
						   @update:modelValue="onControlUpdate($event, controlKey)" />
				<div v-if="controlsRequiredNotFilled.includes(controlKey)"
					 class="jet-ui_controls-list-required-notification">
					Required field must be filled
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { defineComponent, computed, watch } from "vue";
import controls from "../controls";
import controlsListServices from "../services/controls-list.js";

export default defineComponent({
	name: "ControlsList",

	props: {
		modelValue: { type: Object, required: true },
		title: { type: String, default: null },
		disabled: { type: Boolean, default: false }
	},

	components: {
		...controls.collection
	},

	setup(props, context) {
		// Computed
		const controls = computed(() => controlsListServices.controlsVisibilityCheck(JSON.parse(JSON.stringify(props.modelValue))));
		const displayedControls = computed(() => controlsListServices.getVisibleСontrols(controls.value));

		// Required controls
		const controlsRequiredNotFilled = computed(() => controlsListServices.getControlsRequiredNotFilled(displayedControls.value));

		watch(controlsRequiredNotFilled, () => {
			context.emit('controlsRequiredNotFilledChange', controlsRequiredNotFilled.value);
		}, { immediate: true });

		// Methods
		const updateControls = () => {
			if (JSON.stringify(props.modelValue) === JSON.stringify(controls.value))
				return;

			context.emit('update:modelValue', controls.value);
		};

		const getControlName = (control) => controlsListServices.getControlName(control);

		// Actions
		const onControlUpdate = (value, key) => {
			controls.value[key].value = value;
			updateControls();

			context.emit('controlChanged', { key, value });
		};

		return {
			displayedControls,
			getControlName,
			getControlValue: controlsListServices.getControlValue,
			getControlAttrs: controlsListServices.getControlAttrs,
			getControlAttrs: controlsListServices.getControlAttrs,
			controlsRequiredNotFilled,
			isControlRequiredNotFilled: controlsListServices.isControlRequiredNotFilled,
			onControlUpdate
		};
	}
});
</script>

<style lang="scss">
@import "../scss/controls-list.scss";
</style>
