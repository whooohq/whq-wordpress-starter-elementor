<template>
	<UploadButton class="jet_filters-list-indexer-button"
				  :loading="loading"
				  text="Apply Indexer"
				  loadingText="Indexing"
				  loadedText="Indexed"
				  @click="onClick" />
</template>

<script>
import { defineComponent, ref } from "vue";
import request from "@/services/request.js";
import UploadButton from "@/modules/JetUI/controls/UploadButton.vue";

export default defineComponent({
	name: "IndexerButton",

	components: {
		UploadButton
	},

	setup(props, context) {
		const loading = ref(false);

		// Actions
		const onClick = () => {
			if (loading.value)
				return;

			loading.value = true;

			request.reindexFilters()
				.then(response => {
					loading.value = false;
				});
		};

		return {
			loading,
			onClick
		};
	}
});
</script>
