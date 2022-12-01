<template>
	<div class="jet-ui_media"
		 :class="{
			'jet-ui_media--disabled': disabled,
		 }">
		<div v-if="attachment"
			 class="jet-ui_media-attachment"
			 @click="onPopupOpenClick">
			<div class="jet-ui_media-attachment-remove"
				 @click="onRemoveClick">
				<svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" ><path d="M13 4.00671L9.00671 8L13 11.9933L11.9933 13L8 9.00671L4.00671 13L3 11.9933L6.99329 8L3 4.00671L4.00671 3L8 6.99329L11.9933 3L13 4.00671Z" fill="#C92C2C" /></svg>
			</div>
			<img :src="getAttachmentUrl()"
				 alt="">
		</div>
		<Button v-else
				@click="onPopupOpenClick">
			{{attachmentButtonText}}
		</Button>
	</div>
</template>

<script>
import { defineComponent, ref, watch, onMounted } from "vue";
import Button from "./Button.vue";

export default defineComponent({
	name: "Media",

	components: {
		Button
	},

	props: {
		modelValue: { type: [Number, String], required: true },
		attachmentButtonText: { type: String, default: 'Choose an image' },
		popupTitleText: { type: String, default: 'Choose an image' },
		popupButtonText: { type: String, default: 'Choose an image' },
		disabled: { type: Boolean, default: false }
	},

	setup(props, context) {
		// Data
		const attachment = ref('');

		const wpMedia = wp.media({
			title: props.popupTitleText,
			button: { text: props.popupButtonText },
			library: { type: 'image' },
			multiple: false
		});

		// Lifecycles
		onMounted(() => {
			fetchAttachment();

			// Add wp media popup attachment selected handler
			wpMedia.on('open', onOpen).on('select', onSelect);
		});

		// Watchers
		watch(attachment, () => {
			const attachmentValue = attachment.value && attachment.value.id
				? attachment.value.id
				: '';

			if (attachmentValue === props.modelValue)
				return;

			context.emit('update:modelValue', attachmentValue);
		});

		// Methods
		const fetchAttachment = () => {
			if (!props.modelValue)
				return;

			wp.media.attachment(props.modelValue).fetch().then((data) => {
				attachment.value = data;
			});
		};

		const getAttachmentUrl = () => {
			if (!attachment.value)
				return '';

			if (attachment.value.sizes.thumbnail)
				return attachment.value.sizes.thumbnail.url;

			return attachment.value.url;
		};

		// Actions
		const onOpen = () => {
			if (!attachment.value)
				return;

			// Pre-select attached image
			wpMedia.state().get('selection').add(wp.media.attachment(attachment.value.id));
		};

		const onSelect = () => {
			// Attachment selected
			attachment.value = wpMedia.state().get('selection').first().toJSON();
		};

		const onPopupOpenClick = () => {
			wpMedia.open();
		};

		const onRemoveClick = (evt) => {
			evt.stopPropagation();

			attachment.value = '';
		};

		return {
			attachment,
			getAttachmentUrl,
			onPopupOpenClick,
			onRemoveClick
		};
	}
});
</script>

<style lang="scss">
@import "../scss/controls/media.scss";
</style>
