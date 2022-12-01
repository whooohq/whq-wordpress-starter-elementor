<template >
	<h3 class="jet-ui_type-selector-info-title">
		<div v-if="filterData.img"
			 class="jet-ui_type-selector-info-title-icon">
			<img :src="filterData.img" />
		</div>
		{{filterData.label}}
	</h3>
	<div class="jet-ui_type-selector-info-content"
		 v-html="filterData.info" />
</template>

<script>
import { defineComponent, computed } from "vue";
import popup from "@/services/popups.js";
import { useGetter } from "@/store/helper.js";

export default defineComponent({
	name: 'FilterInfo',

	setup(props, context) {
		const filterData = useGetter('filterTypes', false)[popup.data.value];

		return {
			filterData
		};
	}
});
</script>