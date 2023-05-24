<template>
	<div class="jet_filters-list-not-found">
		{{text}}
	</div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
	name: "NotFound",

	props: {
		text: { type: String, default: 'Not found' }
	},
});
</script>
