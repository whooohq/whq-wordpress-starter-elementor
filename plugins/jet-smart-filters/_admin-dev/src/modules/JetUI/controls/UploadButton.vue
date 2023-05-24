<template>
	<button class="jet-ui_upload-button"
			:class="[{
				['state-' + state]: state,
				'jet-ui_upload-button--disabled': disabled
			}]"
			:disabled="disabled">
		<template v-if="state === 'loading'">
			<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.8757 4.08611L7.59134 0V2.757C3.88136 3.19704 1 6.21447 1 9.8785C1 13.5425 3.87194 16.56 7.59134 17V15.1859C4.91714 14.7549 2.88324 12.5457 2.88324 9.8785C2.88324 7.21131 4.91714 5.00211 7.59134 4.57105V8.08241L11.8757 4.08611ZM16 8.98045C15.8399 7.73217 15.322 6.52879 14.4746 5.48706L13.1375 6.76228C13.646 7.43582 13.9661 8.19915 14.0979 8.98045H16ZM9.47458 15.177V16.991C10.7834 16.8384 12.0546 16.3534 13.1469 15.5452L11.791 14.252C11.0847 14.7369 10.2938 15.0512 9.47458 15.177ZM13.1375 13.0037L14.4746 14.2699C15.322 13.2282 15.8399 12.0248 16 10.7765H14.0979C13.9661 11.5578 13.646 12.3212 13.1375 13.0037Z" /></svg>
			{{loadingText}}
		</template>
		<template v-else-if="state === 'loaded'">
			<svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.49992 9.50008L1.99992 6.00008L0.833252 7.16675L5.49992 11.8334L15.4999 1.83341L14.3333 0.666748L5.49992 9.50008Z" /></svg>
			{{loadedText}}
		</template>
		<template v-else>
			{{text}}
		</template>
	</button>
</template>

<script>
import { defineComponent, ref, watch } from "vue";

export default defineComponent({
	name: "UploadButton",

	props: {
		text: { type: String, required: true },
		loading: { type: Boolean, default: false },
		loadingText: { type: String, default: 'Loading' },
		loadedText: { type: String, default: 'Loaded' },
		disabled: { type: Boolean, default: false }
	},

	setup(props, context) {
		const state = ref(false);
		let timeout;

		watch(() => props.loading, () => {
			clearTimeout(timeout);

			if (props.loading) {
				state.value = 'loading';
			} else {
				state.value = 'loaded';

				timeout = setTimeout(() => {
					state.value = false;
				}, 1500);
			}
		});

		return {
			state
		};
	}
});
</script>

<style lang="scss">
@import "../scss/controls/upload-button.scss";
</style>
