<template >
	<teleport v-if="isShow"
			  to="body"
			  :disabled="true">
		<div class="jet-popup"
			 :class="class"
			 role="dialog"
			 aria-modal="true"
			 aria-label="Jet popup">
			<div class="jet-popup-backing"
				 @click="onBackingClick"></div>
			<div class="jet-popup-body">
				<div class="jet-popup-body-header">
					<div class="jet-popup-body-header-close"
						 @click="onCloseClick">
						<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 1.41L12.59 0L7 5.59L1.41 0L0 1.41L5.59 7L0 12.59L1.41 14L7 8.41L12.59 14L14 12.59L8.41 7L14 1.41Z" /></svg>
					</div>
				</div>
				<div class="jet-popup-body-content">
					<slot />
				</div>
			</div>
		</div>
	</teleport>
</template>

<script>
import { defineComponent, computed } from "vue";

export default defineComponent({
	name: 'Popup',

	props: {
		modelValue: { type: Boolean, required: true },
		class: { type: String, default: '' }
	},

	setup(props, context) {
		const isShow = computed({
			get: () => props.modelValue,
			set: (value) => context.emit('update:modelValue', value)
		});

		// Methods
		const close = () => {
			isShow.value = false;
		};

		// Actions
		const onCloseClick = () => {
			close();
		};

		const onBackingClick = () => {
			close();
		};

		return {
			isShow,
			onCloseClick,
			onBackingClick
		};
	}
});
</script>

<style lang="scss">
@import "./scss/popup.scss";
</style>
