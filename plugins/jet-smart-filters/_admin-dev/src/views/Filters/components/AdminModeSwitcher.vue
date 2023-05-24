<template>
	<UploadButton class="jet_filters-list-admin-mode-switcher"
				  :loading="loading"
				  text="Switch to Classic View"
				  loadingText="Switching"
				  loadedText="Switched"
				  @click="onClick" />
</template>

<script>
import { defineComponent, ref } from "vue";
import request from "@/services/request.js";
import UploadButton from "@/modules/JetUI/controls/UploadButton.vue";

export default defineComponent({
	name: "AdminModeSwitcher",

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

			request.adminModeSwitch('classic')
				.then(response => {
					loading.value = false;

					window.location = window.JetSmartFiltersAdminData.urls.admin + 'edit.php?post_type=jet-smart-filters';
				});
		};

		return {
			loading,
			onClick
		};
	}
});
</script>
