<template >
	<h3 class="jet-popup-title">{{texts[type].title}}</h3>
	<p class="jet-popup-text">{{texts[type].text}}</p>
	<div class="jet-popup-actions">
		<Button class="jet-popup-actions-cancel"
				:text="texts[type].cancel"
				@click="onCancelClick" />
		<Button class="jet-popup-actions-apply"
				:text="texts[type].apply"
				@click="onApplyClick" />
	</div>
</template>

<script>
import { defineComponent, computed } from "vue";
import Button from "@/modules/JetUI/controls/Button.vue";
import popup from "@/services/popups.js";

export default defineComponent({
	name: 'Dialog',

	components: {
		Button
	},

	setup(props, context) {
		const texts = {
			deletePermanently: {
				title: 'Delete permanently?',
				text: 'Are you sure you want to delete this filter forever? You can’t undo this action.',
				apply: 'Delete permanently',
				cancel: 'Cancel'
			},
			emptyTrash: {
				title: 'Empty trash?',
				text: 'Are you sure you want to delete permanently all filters in the trash? You can’t undo this action.',
				apply: 'Empty trash',
				cancel: 'Cancel'
			},
			saveChanges: {
				title: 'Save changes?',
				text: 'Are you sure you want to go to listing without saving changes?',
				apply: 'Save',
				cancel: 'Don’t save'
			},
		};

		const type = computed(() => popup.type.value);

		const onApplyClick = () => {
			popup.apply();
		};

		const onCancelClick = () => {
			popup.cancel();
		};

		return {
			texts,
			type,
			onApplyClick,
			onCancelClick
		};
	}
});
</script>