<template>
	<div class="jet_table-thead">
		<slot />
	</div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
	name: "THead"
});
</script>
