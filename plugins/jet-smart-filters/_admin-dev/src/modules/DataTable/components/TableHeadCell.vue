<template>
	<div class="jet_table-thead-th">
		<slot />
	</div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
	name: "TableHeadCell"
});
</script>
