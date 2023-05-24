<template>
	<DropDown :label="label"
			  ref="DropDown">
		<div class="jet_dropdown-menu-item"
			 v-for="(item, index) of items"
			 :key="index"
			 @click="onClick(item, index)">
			<slot :itemData="item"
				  :itemIndex="index">
				{{item}}
			</slot>
		</div>
	</DropDown>
</template>

<script>
import { defineComponent, ref } from "vue";
import DropDown from "./DropDown.vue";

export default defineComponent({
	name: "DropDownMenu",

	components: {
		DropDown
	},

	props: {
		items: { type: Array, default: () => [] },
		label: { type: String, default: '' },
		autoClose: { type: Boolean, default: false },
	},

	setup(props, context) {
		const DropDown = ref(null);

		// Events
		const onClick = (item, index) => {
			context.emit('itemClick', item, index);

			if (props.autoClose)
				DropDown.value.opened = false;
		};

		return {
			DropDown,
			onClick
		};
	}
});
</script>
