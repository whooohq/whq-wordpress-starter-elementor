<template>
	<div class="jet_dropdown"
		 :class="{ 'jet_opened': opened }">
		<div class="jet_dropdown-button"
			 @click="opened = !opened">
			{{ label }}
		</div>
		<div class="jet_dropdown-panel">
			<slot />
		</div>
	</div>
</template>

<script>
import { defineComponent, ref, getCurrentInstance, onMounted, onUnmounted } from "vue";

export default defineComponent({
	name: "DropDown",

	props: {
		label: { type: String, default: '' }
	},

	setup(props, context) {
		const instance = getCurrentInstance();
		const opened = ref(false);

		// Lifecycles
		onMounted(() => {
			document.addEventListener('click', documentClick);
		});

		onUnmounted(() => {
			document.removeEventListener('click', documentClick);
		});

		const documentClick = (evt) => {
			if (!opened.value)
				return;

			if ((instance.ctx.$el !== evt.target) && !instance.ctx.$el.contains(evt.target))
				opened.value = false;
		};

		return {
			opened
		};
	}
});
</script>
