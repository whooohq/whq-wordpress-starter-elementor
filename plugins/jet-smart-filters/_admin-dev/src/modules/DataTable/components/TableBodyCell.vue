<template>
	<div class="jet_table-tbody-td">
		<slot />
	</div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
	name: "TableBodyCell"
});
</script>
