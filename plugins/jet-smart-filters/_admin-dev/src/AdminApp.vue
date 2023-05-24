<template>
	<router-view></router-view>
	<Popups />
</template>

<script>
import { defineComponent } from "vue";
import Popups from "@/views/Popups";

export default defineComponent({
	name: "AdminApp",

	components: {
		Popups
	}
})
</script>

<style src="./scss/index.scss" lang="scss" />