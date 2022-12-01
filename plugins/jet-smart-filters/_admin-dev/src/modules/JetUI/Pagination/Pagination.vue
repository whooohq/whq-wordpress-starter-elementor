<template>
	<div class="jet-ui_pagination"
		 v-if="show">
		<ul class="jet-ui_pagination-items">
			<li class="jet-ui_pagination-item"
				v-if="withNextPrev">
				<div class="jet-ui_pagination-prev"
					 :class="{'jet-ui_disabled': disablePrev}"
					 @click="!disablePrev ? prevPage() : ''">
					<svg width="8" height="11" viewBox="0 0 8 11" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.0874 9.07085L3.27074 5.24585L7.0874 1.42085L5.9124 0.24585L0.912402 5.24585L5.9124 10.2458L7.0874 9.07085Z" /></svg>
					<span class="jet-ui_pagination-prev-text"
						  v-if="prevText">{{ prevText }}</span>
				</div>
			</li>
			<li class="jet-ui_pagination-item"
				v-for="(n, index) in pages"
				:key="index"
				:class="{active: n.active}">
				<div :class="{'jet-ui_pagination-page-link': !n.disable, 'jet-ui_pagination-gap': n.disable}"
					 @click="!n.disable && !n.active ? pageClick(n) : ''">{{ n.value }}</div>
			</li>
			<li class="jet-ui_pagination-item"
				v-if="withNextPrev">
				<div class="jet-ui_pagination-next"
					 :class="{'jet-ui_disabled': disableNext}"
					 @click="!disableNext ? nextPage() : ''">
					<span class="jet-ui_pagination-next-text"
						  v-if="nextText">{{ nextText }}</span>
					<svg width="8" height="11" viewBox="0 0 8 11" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.912598 1.42085L4.72926 5.24585L0.912598 9.07085L2.0876 10.2458L7.0876 5.24585L2.0876 0.24585L0.912598 1.42085Z" /></svg>
				</div>
			</li>
		</ul>
	</div>
</template>

<script>
import { defineComponent, ref, computed, watch } from "vue";

export default defineComponent({
	name: "Pagination",

	props: {
		startPage: { type: Number, default: 1, },
		totalPages: { type: Number, required: true },
		pageRange: { type: Number, default: 3, },
		withNextPrev: { type: Boolean, default: false },
		nextText: { type: String, default: '', },
		prevText: { type: String, default: '', },
	},

	setup(props, context) {
		const show = ref(true);
		const currentPage = ref(props.startPage);
		const selectedPages = ref([]);
		const disableNext = ref(false);
		const disablePrev = ref(false);

		const pages = computed(() => {
			let pages = [];

			if (!props.pageRange) {
				fillInterval(pages, 1, props.totalPages);
			}

			if (props.pageRange) {
				if (props.totalPages <= (props.pageRange * 2 + 2)) {
					fillInterval(pages, 1, props.totalPages);
				} else {
					if (currentPage.value < props.pageRange + 2) {
						fillInterval(pages, 1, props.pageRange * 2 + 1);
						addItem(pages, '...', true);
						addItem(pages, props.totalPages);
					} else if (currentPage.value > (props.totalPages - (props.pageRange + 1))) {
						addItem(pages, 1);
						addItem(pages, '...', true);
						fillInterval(pages, (props.totalPages - (props.pageRange * 2)), props.totalPages);
					} else {
						addItem(pages, 1);
						if (currentPage.value > props.pageRange + 2) {
							addItem(pages, '...', true);
						}
						fillInterval(pages, currentPage.value - props.pageRange, currentPage.value + props.pageRange);
						if (currentPage.value < (props.totalPages - (props.pageRange + 1))) {
							addItem(pages, '...', true);
						}
						addItem(pages, props.totalPages);
					}
				}
			}

			return pages;
		});

		// Methods
		const pageClick = (n) => {
			currentPage.value = n.value;
			emitChanges();
		};

		const nextPage = () => {
			currentPage.value++;
			emitChanges();
		};

		const prevPage = () => {
			if (!selectedPages.value.length) {
				currentPage.value--;
			} else {
				currentPage.value = selectedPages.value[0] - 1;
			}
			emitChanges();
		};

		const emitChanges = () => {
			context.emit('change', currentPage.value);
			clearSelected();
		};

		const prevNextCheck = () => {
			disablePrev.value = currentPage.value === 1 || selectedPages.value[0] === 1 ? true : false;
			disableNext.value = currentPage.value === props.totalPages ? true : false;
		};

		const clearSelected = () => {
			selectedPages.value = [];
			prevNextCheck();
		};

		const fillInterval = (array, start, end) => {
			for (let index = start; index <= end; index++) {
				addItem(array, index);
			}
		};

		const addItem = (array, index, disable = false) => {
			array.push({
				value: index,
				active: ifItemActive(index),
				disable: disable,
			});
		};

		const ifItemActive = (index) => {
			let active = false;

			if (currentPage.value === index) {
				active = true;
			} else {
				if (selectedPages.value.length)
					active = selectedPages.value.includes(index);
			}

			return active;
		};

		// Watchers
		watch(() => props.totalPages, (totalPages, prevTotalPage) => {
			if (props.totalPages <= 1) {
				show.value = false;
			} else {
				show.value = true;
				prevNextCheck();
			}
		}, {
			immediate: true
		});

		watch(() => props.startPage, (startPage, prevStartPage) => {
			currentPage.value = startPage;
			prevNextCheck();
		});

		return {
			show,
			pages,
			disableNext,
			disablePrev,
			prevPage,
			nextPage,
			pageClick
		};
	}
});
</script>

<style lang="scss">
@import "../scss/pagination.scss";
</style>
