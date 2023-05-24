<template>
	<div class="jet_table-tbody">
		<slot />
	</div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
	name: "TBody"
})
</script>
