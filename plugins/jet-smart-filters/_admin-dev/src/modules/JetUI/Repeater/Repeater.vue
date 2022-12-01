<template>
	<div class="jet-ui_repeater"
		 :class="{
			'jet-ui_repeater--disabled': disabled,
		 }">
		<SlickList class="jet-ui_repeater-items"
				   v-model="items"
				   :axis="axis"
				   :useDragHandle="true">
			<SlickItem class="jet-ui_repeater-item"
					   v-for="(item, index) in items"
					   :index="index"
					   :key="index">
				<div class="jet-ui_repeater-item-heading">
					<div class="jet-ui_repeater-item-handle"
					     v-handle>
						<svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg"><line x1="2" y1="3" x2="14" y2="3" stroke-width="2"></line><line x1="2" y1="11" x2="14" y2="11" stroke-width="2"></line><line x1="2" y1="7" x2="14" y2="7" stroke-width="2"></line></svg>
					</div>
					<div class="jet-ui_repeater-item-clone"
						 @click="onCloneItemClick(index)">
						<svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.87846 0.5H1.69664C0.946644 0.5 0.333008 1.11364 0.333008 1.86364V11.4091H1.69664V1.86364H9.87846V0.5ZM11.9239 3.22727H4.42392C3.67392 3.22727 3.06028 3.84091 3.06028 4.59091V14.1364C3.06028 14.8864 3.67392 15.5 4.42392 15.5H11.9239C12.6739 15.5 13.2876 14.8864 13.2876 14.1364V4.59091C13.2876 3.84091 12.6739 3.22727 11.9239 3.22727ZM11.9239 14.1364H4.42392V4.59091H11.9239V14.1364Z"/></svg>
					</div>
					<div class="jet-ui_repeater-item-remove"
						 @click="onRemoveItemClick(index)">
						<svg width="12" height="16" viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.00033 13.8333C1.00033 14.75 1.75033 15.5 2.66699 15.5H9.33366C10.2503 15.5 11.0003 14.75 11.0003 13.8333V3.83333H1.00033V13.8333ZM2.66699 5.5H9.33366V13.8333H2.66699V5.5ZM8.91699 1.33333L8.08366 0.5H3.91699L3.08366 1.33333H0.166992V3H11.8337V1.33333H8.91699Z"/></svg>
					</div>
				</div>
				<div class="jet-ui_repeater-item-content">
					<slot :item="item"
						  :index="index" />
				</div>
			</SlickItem>
		</SlickList>
		<div class="jet-ui_repeater-actions">
			<button class="jet-ui_repeater-add-buttom"
					@click="onAddItemClick">
				<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.8332 6.83317H6.83317V11.8332H5.1665V6.83317H0.166504V5.1665H5.1665V0.166504H6.83317V5.1665H11.8332V6.83317Z" fill="#007CBA"/></svg>
				{{addLabel}}
			</button>
		</div>
	</div>
</template>

<script>
import { defineComponent, computed } from "vue";
import { SlickList, SlickItem, HandleDirective } from "@/modules/SlickSort/index.js";

export default defineComponent({
	name: "Repeater",

	components: {
		SlickItem,
		SlickList,
	},

	directives: {
		handle: HandleDirective
	},

	props: {
		modelValue: { type: Array, required: true },
		item: { type: [Object, Array], default: null },
		axis: { type: String, default: 'y' },
		addLabel: { type: String, default: 'Add New' },
		disabled: { type: Boolean, default: false }
	},

	setup(props, context) {
		// Computed Data
		const items = computed({
			get: () => [...props.modelValue],
			set: (value) => context.emit('update:modelValue', value)
		});

		// Actions
		const onAddItemClick = () => {
			if (props.item) {
				const newItems = items.value;

				newItems.push(props.item);
				items.value = newItems;
			}

			context.emit('addItem');
		};

		const onCloneItemClick = (itemIndex) => {
			const newItems = items.value;

			newItems.splice(itemIndex + 1, 0, newItems[itemIndex]);
			items.value = newItems;

			context.emit('cloneItem');
		};

		const onRemoveItemClick = (itemIndex) => {
			const newItems = items.value;

			newItems.splice(itemIndex, 1);
			items.value = newItems;

			context.emit('removeItem');
		};

		return {
			items,
			onAddItemClick,
			onCloneItemClick,
			onRemoveItemClick
		};
	}
});
</script>

<style lang="scss">
@import "../scss/repeater.scss";
</style>
