<template>
	<div class="jet_table">
		<slot />
	</div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
	name: "TableWrapper"
});
</script>
