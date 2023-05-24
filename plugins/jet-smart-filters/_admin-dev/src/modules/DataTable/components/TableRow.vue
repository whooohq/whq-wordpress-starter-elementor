<template>
	<div class="jet_table-row"
		 :class="{ 'jet_table-row-checked': checked }">
		<slot />
	</div>
</template>

<script>
import { defineComponent, ref } from "vue";

export default defineComponent({
	name: "TableRow",

	props: {
		checked: { type: Boolean, default: false }
	}
});
</script>
