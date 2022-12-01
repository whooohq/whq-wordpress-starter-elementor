import Filter from 'bases/Filter';
import eventBus from 'includes/event-bus';
import preloader from 'includes/preloader';
import templateParser from 'includes/template-parser';
import {
	getNesting
} from 'includes/utility';

export default class Pagination extends Filter {
	name = 'pagination';

	paginationListClass = 'jet-filters-pagination';
	paginationItemClass = 'jet-filters-pagination__item';
	paginationCurrentClass = 'jet-filters-pagination__current';
	paginationDisabledClass = 'jet-filters-pagination__disabled';
	navClass = 'prev-next';
	prevClass = 'prev';
	nextClass = 'next';

	constructor($filter) {
		super($filter);

		this.pageIndex = this.pageProp;
		this.dataValue = this.pageIndex;
		this.pagesCount = this.maxNumPagesProp;
		this.controls = this.$filter.data('controls');
		this.isNav = this.controls.nav || false;
		this.prevText = this.controls.prev;
		this.nextText = this.controls.next;
		this.midSize = this.controls.pages_mid_size || 0;
		this.endSize = this.controls.pages_end_size || 0;

		if (undefined !== this.controls.provider_top_offset)
			this.topOffset = this.controls.provider_top_offset || 0;

		this.buildPagination();

		// Event subscriptions
		preloader.subscribe($filter, {
			provider: this.provider,
			queryId: this.queryId
		});
		eventBus.subscribe('ajaxFilters/end-loading', (provider, queryId) => {
			if (!this.isCurrentProvider({ provider, queryId }))
				return;

			this.update();
		});
		// Change data value for duplicate pagination filters
		eventBus.subscribe('pagination/change', paginationFilter => {
			if (!this.isCurrentProvider(paginationFilter))
				return;

			if (paginationFilter.data !== this.data)
				this.dataValue = paginationFilter.data;
		});
	}

	buildPagination() {
		if (this.pagesCount < 2) {
			this.$filter.html('');

			return;
		}

		// remove all jQuery events to avoid memory leak
		this.$filter.find('*').off('click');

		const paginationItemTemplate = getNesting(JetSmartFilterSettings, 'templates', 'pagination_item');

		const elList = document.createElement('div');
		elList.className = this.paginationListClass;

		let isPrevItemDots = false;

		for (let i = 1; i <= this.pagesCount; i++) {
			const showDots = this.midSize !== 0 ?
				(this.endSize < i && i < this.pageIndex - this.midSize) || (this.endSize <= (this.pagesCount - i) && i > this.pageIndex + this.midSize) :
				false;

			if (showDots) {
				if (!isPrevItemDots) {
					elList.appendChild(this.buildDotsItem());
					isPrevItemDots = true;
				}
			} else {
				elList.appendChild(this.buildPaginationItem('numeral', i, this.onPaginationItemClick.bind(this), paginationItemTemplate));
				isPrevItemDots = false;
			}
		}

		if (this.isNav) {
			if (this.pageIndex > 1)
				elList.insertBefore(this.buildPaginationItem('prev', this.prevText, this.onPaginationItemClick.bind(this), paginationItemTemplate), elList.firstChild);

			if (this.pageIndex < this.pagesCount)
				elList.appendChild(this.buildPaginationItem('next', this.nextText, this.onPaginationItemClick.bind(this), paginationItemTemplate));
		}

		this.$filter.html(elList);

		this.setCurrentItem();

		// Emit pagination items build event
		eventBus.publish('pagination/itemsBuilt', this);
	}

	buildPaginationItem(type, value, clickCallBack, template = false) {
		let itemContent = value;

		if (template) {
			itemContent = templateParser(template, {
				$value: value
			});
		}

		const elPaginationItem = document.createElement('div');

		elPaginationItem.className = this.paginationItemClass;
		elPaginationItem.innerHTML = itemContent;

		if (getNesting(JetSmartFilterSettings, 'plugin_settings', 'use_tabindex') === 'true')
			elPaginationItem.tabIndex = 0;

		if (type === 'prev' || type === 'next') {
			elPaginationItem.dataset.value = type;
			elPaginationItem.classList.add(this.navClass);
			elPaginationItem.classList.add(this[type + 'Class']);
		} else {
			elPaginationItem.dataset.value = value;
		}

		// add jQuery click event
		$(elPaginationItem).on('click', clickCallBack);

		return elPaginationItem;
	}

	buildDotsItem() {
		const elDotsItem = document.createElement('div');

		elDotsItem.className = this.paginationItemClass;
		elDotsItem.innerHTML = getNesting(JetSmartFilterSettings, 'templates', 'pagination_item_dots') || '';

		return elDotsItem;
	}

	onPaginationItemClick(evt) {
		const $item = $(evt.currentTarget);
		let value = $item.data('value');

		switch (value) {
			case 'prev':
				if (this.pageIndex > 1) {
					value = this.pageIndex - 1;
				} else {
					value = 1;
				}

				break;

			case 'next':
				if (this.pageIndex < this.pagesCount) {
					value = this.pageIndex + 1;
				} else {
					value = this.pagesCount;
				}

				break;
		}

		if (this.pageIndex !== value) {
			// emit pagination change
			this.dataValue = value;
			eventBus.publish('pagination/change', this);
		}
	}

	setCurrentItem() {
		if (!this.pageIndex)
			return;

		const $container = this.$filter.find('.' + this.paginationListClass);

		$container.children().removeClass(this.paginationCurrentClass);
		$container.find("[data-value='" + this.pageIndex + "']").addClass(this.paginationCurrentClass);
	}

	update() {
		const currentPagesCount = this.maxNumPagesProp,
			currentDataValue = this.pageProp;

		if (currentPagesCount === this.pagesCount && currentDataValue === this.pageIndex)
			return;

		this.pagesCount = currentPagesCount;
		this.pageIndex = currentDataValue;

		this.buildPagination();
	}

	// Getters
	get data() {
		return this.dataValue && this.dataValue > 1 ? this.dataValue : false;
	}

	get pageProp() {
		const page = Number(getNesting(JetSmartFilterSettings, 'props', this.provider, this.queryId, 'page'));

		return page || 1;
	}

	get maxNumPagesProp() {
		const maxNumPages = Number(getNesting(JetSmartFilterSettings, 'props', this.provider, this.queryId, 'max_num_pages'));

		return maxNumPages || 0;
	}

	get queryKey() {
		return 'jet_paged';
	}
}