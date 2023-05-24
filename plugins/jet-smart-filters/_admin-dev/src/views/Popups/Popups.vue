<template >
	<Popup :modelValue="isShow"
		   :class="className"
		   @update:modelValue="onCancel">
		<component :is="component" />
	</Popup>
</template>

<script>
import { defineComponent, computed } from "vue";
import popup from "@/services/popups.js";
import Popup from "@/modules/Popup";
import Dialog from "./components/Dialog.vue";
import QuickEdit from "./components/QuickEdit.vue";
import FilterInfo from "./components/FilterInfo.vue";

export default defineComponent({
	name: 'Popups',

	components: {
		Popup,
		Dialog,
		QuickEdit,
		FilterInfo
	},

	setup(props, context) {
		const isShow = computed(() => popup.isShow.value);

		const className = computed(() => {
			let className = '';

			if ('deletePermanently' === popup.type.value)
				className = 'jet-popup--delete-permanently';

			if ('emptyTrash' === popup.type.value)
				className = 'jet-popup--empty-trash';

			if ('quickEdit' === popup.type.value)
				className = 'jet-popup--quick-edit';

			if ('saveChanges' === popup.type.value)
				className = 'jet-popup--save-changes';

			if ('filterInfo' === popup.type.value)
				className = 'jet-popup--filter-info';

			return className;
		});

		const component = computed(() => {
			let componentName = false;

			if (['deletePermanently', 'emptyTrash', 'saveChanges'].includes(popup.type.value))
				componentName = 'Dialog';

			if ('quickEdit' === popup.type.value)
				componentName = 'QuickEdit';

			if ('filterInfo' === popup.type.value)
				componentName = 'FilterInfo';

			return componentName;
		});

		// Actions
		const onCancel = () => {
			popup.close();
		};

		return {
			isShow,
			className,
			component,
			onCancel
		};
	}
});
</script>
