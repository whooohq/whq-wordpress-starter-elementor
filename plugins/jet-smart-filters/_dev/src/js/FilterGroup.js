import AdditionalFilters from 'modules/AdditionalFilters';
import CustomProvider from 'modules/CustomProvider';
import Indexer from 'modules/Indexer';
import TabIndex from 'modules/TabIndex';
import eventBus from 'includes/event-bus';
import request from 'includes/request';
import preloader from 'includes/preloader';
import {
	isEmpty,
	isNotEmpty,
	isEqual,
	isObject,
	setNesting,
	getNesting,
	getUrlParams,
	getThirdPartyUrlParams,
	mergeData,
	applyAliases,
	debounce
} from 'includes/utility';

export default class FilterGroup {
	urlPrefix = 'jsf';
	activeItemsExceptions = ['sorting', 'pagination'];

	constructor(provider, queryId, filters = []) {
		this.provider = provider;
		this.queryId = queryId;
		this.filters = [];
		this.providerSelector = this.getProviderSelector();
		this.$provider = $(this.providerSelector);
		this.currentQuery = {};
		this.isAjaxLoading = false;

		// URL data
		this.urlType = getNesting(JetSmartFilterSettings, 'misc', 'url_type') || 'plain';
		this.baseUrl = getNesting(JetSmartFilterSettings, 'baseurl');
		this.baseUrlParams = getThirdPartyUrlParams();

		// modules
		this.additionalFilters = new AdditionalFilters(this);
		this.customProvider = new CustomProvider(this);

		// initialization incoming filters
		filters.forEach(filter => {
			this.addFilter(filter);
		});

		preloader.subscribe(this.providerSelector, {
			provider,
			queryId
		});

		this.debounceProcessFilters = debounce(this.processFilters, 100);

		// Event subscriptions
		eventBus.subscribe('fiter/change', filter => {
			if (!this.isCurrentProvider(filter))
				return;

			this.updateSameFilters(filter);
			this.filterChangeHandler(filter.applyType);
		}, true);
		eventBus.subscribe('fiters/apply', applyFilter => {
			if (!this.isCurrentProvider(applyFilter))
				return;

			this.applyFiltersHandler(applyFilter.applyType, applyFilter.redirect && applyFilter.redirectPath ? applyFilter.redirectPath : false, applyFilter.redirectInNewWindow);
		}, true);
		eventBus.subscribe('fiters/remove', removeFilter => {
			if (!this.isCurrentProvider(removeFilter))
				return;

			this.removeFiltersHandler(removeFilter.applyType);
		});
		eventBus.subscribe('pagination/change', paginationFilter => {
			if (!this.isCurrentProvider(paginationFilter))
				return;

			this.paginationСhangeHandler(paginationFilter.applyType, paginationFilter.topOffset);
		}, true);
		eventBus.subscribe('pagination/load-more', paginationFilter => {
			if (!this.isCurrentProvider(paginationFilter))
				return;

			this.paginationLoadMoreHandler();
		}, true);
	}

	// Filters initialization
	addFilter(newFilter) {
		// remove old duplicate
		this.filters = this.filters.filter(filter => newFilter.path !== filter.path);

		// filter add 
		newFilter.uniqueKey = this.getFilterUniqueKey(newFilter);

		// push new filter to the collection
		this.filters.push(newFilter);

		// Init filter modules
		this.initIndexer(newFilter);
		this.initTabIndex(newFilter);

		this.debounceProcessFilters();
	}

	processFilters() {
		if (!this.filters.length)
			return;

		// update current query
		this.currentQuery = this.query;

		// update filters with current data
		this.setFiltersData();

		// update additional filters
		this.additionalFilters.collectFilters();
	}

	// Reinit filters
	reinitFilters(filterNames = null) {
		if (filterNames && !Array.isArray(filterNames))
			filterNames = [filterNames];

		this.filters.forEach(filter => {
			if (filterNames && !filterNames.includes(filter.name))
				return;

			if (filter.reinit)
				filter.reinit();
		});
	}

	// Events Handlers
	filterChangeHandler(applyType) {
		this.resetFiltersByName('pagination');
		this.apply(applyType);
	}

	applyFiltersHandler(applyType, redirectPath = false, redirectInNewWindow = false) {
		this.resetFiltersByName('pagination');
		this.updateFiltersData();

		if (redirectPath) {
			this.doRedirect(applyType, redirectPath, redirectInNewWindow);
		} else {
			this.apply(applyType);
		}
	}

	removeFiltersHandler(applyType) {
		this.resetFiltersByName('pagination');
		this.resetFilters();
		this.apply(applyType);
	}

	paginationСhangeHandler(applyType, topOffset = false) {
		this.apply(applyType);

		// scroll to provider
		if (applyType !== 'reload' && (topOffset || topOffset === 0))
			$('html, body').stop().animate({ scrollTop: this.$provider.offset().top - topOffset }, 500);
	}

	paginationLoadMoreHandler() {
		this.doAjax({ loadMore: true });
	}

	// Actions
	apply(applyType = 'ajax') {
		this.emitActiveItems();

		if (applyType === 'reload') {
			this.doReload();
		} else {
			this.doAjax();
		}
	}

	doRedirect(applyType, redirectPath, redirectInNewWindow = false) {
		if (applyType === 'reload') {
			request.redirectWithGET(this.getUrl(true), redirectPath, redirectInNewWindow);
		} else {
			const params = {
				[this.urlPrefix]: this.providerKey,
				...this.query
			};

			request.redirectWithPOST(params, redirectPath, redirectInNewWindow);
		}
	}

	doReload() {
		const url = this.getUrl(true);
		let newLocation = this.baseUrl;

		if (url)
			newLocation = applyAliases(this.baseUrl + url);

		document.location = newLocation;
	}

	doAjax(externalProps = {}) {
		const query = this.query;

		this.$provider = $(this.providerSelector);

		if (!this.isProviderExist || isEqual(query, this.currentQuery))
			return;

		this.currentQuery = query;
		this.updateUrl();
		this.ajaxRequest(response => {
			this.ajaxRequestCompleted({
				...response,
				...externalProps
			});
		});
	}

	ajaxRequest(callback, query = this.query) {
		this.startAjaxLoading();

		request.ajax({
			query: query,
			provider: this.provider,
			queryId: this.queryId,
			indexingFilters: this.indexingFilters
		}).then(response => {
			callback(response);
			this.endAjaxLoading();
		}).catch(error => {
			if (!error)
				return;

			console.error(error);
			this.endAjaxLoading();
		});
	}

	startAjaxLoading() {
		this.isAjaxLoading = true;
		eventBus.publish('ajaxFilters/start-loading', this.provider, this.queryId);
	}

	endAjaxLoading() {
		this.isAjaxLoading = false;
		eventBus.publish('ajaxFilters/end-loading', this.provider, this.queryId);
	}

	ajaxRequestCompleted(response) {
		// update pagination props
		if (response.pagination && getNesting(JetSmartFilterSettings, 'props', this.provider, this.queryId)) {
			window.JetSmartFilterSettings.props[this.provider][this.queryId] = {
				...response.pagination
			};
		}

		// update indexed data
		if (response.jetFiltersIndexedData && getNesting(JetSmartFilterSettings, 'jetFiltersIndexedData', this.providerKey)) {
			window.JetSmartFilterSettings.jetFiltersIndexedData[this.providerKey] = response.jetFiltersIndexedData[this.providerKey];
		}

		// update provider content
		if (response.content) {
			this.renderResult(response.content, response.loadMore || false);
		}

		// update provider data
		if (response.is_data) {
			this.$provider.trigger('jet-filter-data-updated', [response, this]);
		}

		// update fragments
		if (response.fragments) {
			for (const selector in response.fragments) {
				const $el = jQuery(selector);

				if ($el.length) {
					$el.html(response.fragments[selector]);
				}
			}
		}

		// backward compatibility for jet-engine-maps
		if (this.provider) {
			this.$provider
				.closest('.elementor-widget-jet-engine-maps-listing,.jet-map-listing-block,.brxe-jet-engine-maps-listing')
				.trigger('jet-filter-custom-content-render', response);
		}

		eventBus.publish('ajaxFilters/updated', this.provider, this.queryId);
	}

	renderResult(result, append = false) {
		if (!this.$provider.length)
			return;

		// update the provider selector if for some reason it doesn't actually exist on the page
		if (!$(document).find(this.$provider).length)
			this.$provider = $(this.providerSelector);

		if (append) {
			let $container = this.$provider;

			// .not 
			if (this.providerSelectorData.list)
				$container = $container.find(this.providerSelectorData.list)
					.not(this.providerSelectorData.list + ' ' + this.providerSelectorData.list);

			$container.append(
				$(result).find(this.providerSelectorData.item)
					.not(this.providerSelectorData.item + ' ' + this.providerSelectorData.item)
			);
		} else if ('insert' === this.providerSelectorData.action) {
			this.$provider.html(result);
		} else {
			this.$provider.replaceWith(result);
			this.$provider = $(this.providerSelector);
		}

		// trigger elementor widgets
		if (window.elementorFrontend) {
			switch (this.provider) {
				case 'jet-engine':
					window.elementorFrontend.hooks.doAction('frontend/element_ready/jet-listing-grid.default', this.$provider, $);
					break;

				case 'epro-portfolio':
					window.elementorFrontend.hooks.doAction('frontend/element_ready/portfolio.default', this.$provider, $);
					break;
			}

			this.$provider.find('[data-element_type]').each((index, item) => {
				const $this = $(item);
				let elementType = $this.data('element_type');

				if ('widget' === elementType) {
					elementType = $this.data('widget_type');
					window.elementorFrontend.hooks.doAction('frontend/element_ready/widget', $this, $);
				}

				window.elementorFrontend.hooks.doAction('frontend/element_ready/global', $this, $);
				window.elementorFrontend.hooks.doAction('frontend/element_ready/' + elementType, $this, $);
			});
		}

		if (window.JetPlugins) {
			window.JetPlugins.init(this.$provider);
			if (this.$provider.closest('[data-is-block*="/"]').length) {
				window.JetPlugins.initBlock(this.$provider.closest('[data-is-block*="/"]')[0], true);
			}
		}

		// emit rendered event
		eventBus.publish('provider/content-rendered', this.provider, this.$provider);
		// for backward compatibility with other plugins
		$(document).trigger('jet-filter-content-rendered', [this.$provider, this, this.provider, this.queryId]);
	}

	setFiltersData(data = Object.assign(this.currentQuery, this.urlParams)) {
		this.filters.forEach(filter => {
			if (filter.isHierarchy || filter.disabled)
				return;

			const key = filter.queryKey,
				value = data[key];

			if (value && filter.setData)
				filter.setData(value);

		});

		this.emitActiveItems();
		this.emitHierarchyFiltersUpdate();
	}

	updateFiltersData() {
		this.filters.forEach(filter => {
			if (filter.processData)
				filter.processData();
		});
	}

	resetFilters() {
		this.filters.forEach(filter => {
			if (filter.reset)
				filter.reset();
		});
	}

	updateSameFilters(changedFilter) {
		this.getSameFilters(changedFilter).forEach(filter => {
			if (changedFilter.data === filter.data)
				return;

			if (filter.setData)
				filter.setData(changedFilter.data);
		});
	}

	getFiltersByName(name) {
		return this.filters.filter(filter => {
			return filter.name === name;
		});
	}

	resetFiltersByName(name) {
		const filters = this.getFiltersByName(name);

		filters.forEach(filter => {
			if (filter.reset)
				filter.reset();
		});
	}

	// Url methods
	updateUrl() {
		const filteringApplied = this.filters.some(filter => {
			if (filter.data)
				return true;
		});

		if (filteringApplied) {
			const url = this.getUrl();

			if (url)
				history.replaceState(null, null, applyAliases(this.baseUrl + url));
		} else {
			history.replaceState(null, null, this.baseUrl + this.baseUrlParams);
		}
	}

	getUrl(allFilters = false) {
		const urlData = {};

		this.uniqueFilters.forEach(filter => {
			if (!(allFilters || filter.isMixed || filter.isReload))
				return;

			let data = filter.data;

			if (!data)
				return;

			let queryType = filter.queryType,
				queryVar = filter.queryVar;

			switch (queryType) {
				case 'tax_query':
					queryType = 'tax';

					break;

				case 'meta_query':
					queryType = 'meta';

					break;

				case 'date_query':
					queryType = 'date';
					queryVar = false;
					data = data.replaceAll('/', '-');

					break;

				case 'sort':
					const sortData = JSON.parse(data);

					queryVar = false;
					data = '';

					for (const sortKey in sortData) {
						data += sortKey + ':' + sortData[sortKey] + ';';
					}

					data = data.replace(/;\s*$/, '');

					break;

				case '_s':
					//queryType = 'search';
					queryVar = false;

					break;
			}

			switch (filter.name) {
				case 'range':
					queryVar += '!range';

					break;

				case 'check-range':
					queryVar += '!check-range';

					break;

				case 'date-range':
				case 'date-period':
					if (queryType === 'meta')
						queryVar += '!date';

					break;

				case 'pagination':
					queryType = 'pagenum';

					break;

				case 'search':
					if (filter.queryType === 'meta_query') {
						queryType = '_s';
						queryVar = false;
						data += '!meta=' + filter.queryVar;
					}

					break;


				default:
					if (filter.queryVarSuffix)
						queryVar += '!' + filter.queryVarSuffix;

					break;
			}

			const nesting = [queryType];

			if (queryVar)
				nesting.push(queryVar);

			if (filter.mergeSameQueryKeys && getNesting(urlData, ...nesting))
				data = mergeData(data, 'operator_AND');

			if (filter.isHierarchy && filter.hierarchicalСhain)
				data += 'hc' + filter.hierarchicalСhain;

			setNesting(data, urlData, nesting, { merge: filter.mergeSameQueryKeys });
		});

		if (isEmpty(urlData))
			return this.baseUrlParams || '';

		let url = '',
			providerName = this.provider;

		if (this.queryId && this.queryId !== 'default')
			providerName += ':' + this.queryId;

		switch (this.urlType) {
			case 'permalink':
				url = this.urlPrefix + '/' + providerName + '/';

				// replace _s on search
				if ('_s' in urlData) {
					urlData.search = urlData._s;
					delete urlData._s;
				}

				for (const queryTypeKey in urlData) {
					const queryTypeValue = urlData[queryTypeKey];

					url += queryTypeKey + '/';

					if (!isObject(queryTypeValue)) {
						url += encodeURIComponent(queryTypeValue) + '/';
					} else {
						if (Array.isArray(queryTypeValue)) {
							url += encodeURIComponent(queryTypeValue.join()) + '/';
						} else {
							for (const queryVarKey in queryTypeValue) {
								const queryVarValue = encodeURIComponent(queryTypeValue[queryVarKey]);

								url += queryVarKey + ':' + queryVarValue + ';';
							}
						}

						url = url.replace(/;\s*$/, '/');
					}
				}

				if (this.baseUrlParams)
					url += this.baseUrlParams;

				break;

			default:
				if (this.baseUrlParams) {
					url = this.baseUrlParams + '&' + this.urlPrefix + '=' + providerName;
				} else {
					url = '?' + this.urlPrefix + '=' + providerName;
				}

				for (const queryTypeKey in urlData) {
					const queryTypeValue = urlData[queryTypeKey];

					url += '&' + queryTypeKey + '=';

					if (!isObject(queryTypeValue)) {
						url += encodeURIComponent(queryTypeValue);
					} else {
						if (Array.isArray(queryTypeValue)) {
							url += encodeURIComponent(queryTypeValue.join());
						} else {
							for (const queryVarKey in queryTypeValue) {
								const queryVarValue = encodeURIComponent(queryTypeValue[queryVarKey]);

								url += queryVarKey + ':' + queryVarValue + ';';
							}
						}

						url = url.replace(/;\s*$/, '');
					}
				}

				break;
		}

		return url;
	}

	// module initialization
	initIndexer(filter) {
		const indexedClass = 'jet-filter-indexed';

		if (filter.indexer || !filter.$container || !filter.$container.hasClass(indexedClass))
			return;

		// Init Indexer Class
		filter.indexer = new Indexer(filter);
	}

	initTabIndex(filter) {
		const use_tabindex = getNesting(JetSmartFilterSettings, 'plugin_settings', 'use_tabindex');

		if (filter.tabindex || use_tabindex !== 'true')
			return;

		filter.tabindex = new TabIndex(filter);
	}

	// emitters
	emitActiveItems() {
		eventBus.publish('activeItems/change', this.activeItems, this.provider, this.queryId);
	}

	emitHierarchyFiltersUpdate() {
		eventBus.publish('hierarchyFilters/update', this.hierarchyFilters);
	}

	isCurrentProvider(filter) {
		return filter.provider === this.provider && filter.queryId === this.queryId ? true : false;
	}

	// Additional methods
	getProviderSelector() {
		const delimiter = this.providerSelectorData.inDepth ? ' ' : '';

		return 'default' === this.queryId ? this.providerSelectorData.selector : this.providerSelectorData.idPrefix + this.queryId + delimiter + this.providerSelectorData.selector;
	}

	// Getters
	get query() {
		const query = {};

		this.uniqueFilters.forEach(filter => {
			const data = filter.data,
				key = filter.queryKey;

			if (!data || !key)
				return;

			if (query[key] && filter.mergeSameQueryKeys) {
				query[key] = mergeData(query[key], data, 'operator_AND');
			} else {
				if (filter.isHierarchy && filter.hierarchicalСhain)
					query['hc_' + filter.queryVar] = filter.hierarchicalСhain;

				query[key] = data;
			}
		});

		return query;
	}

	get providerKey() {
		return this.provider + '/' + this.queryId;
	}

	get providerSelectorData() {
		return getNesting(JetSmartFilterSettings, 'selectors', this.provider);
	}

	get urlParams() {
		const urlParams = getUrlParams();

		if (urlParams[this.urlPrefix] !== this.providerKey)
			return false;

		delete urlParams[this.urlPrefix];

		return urlParams;
	}

	get activeItems() {
		const activeItems = [];

		this.uniqueFilters.forEach(filter => {
			if (!filter.data || !filter.reset || this.activeItemsExceptions.includes(filter.name))
				return;

			activeItems.push(filter);
		});

		return activeItems;
	}

	get hierarchyFilters() {
		const hierarchyFilters = {};

		this.uniqueFilters.forEach(filter => {
			if (filter.isHierarchy) {
				if (!hierarchyFilters[filter.filterId])
					hierarchyFilters[filter.filterId] = [];

				hierarchyFilters[filter.filterId].push(filter);
			}
		});

		return isNotEmpty(hierarchyFilters) ? hierarchyFilters : false;
	}

	get indexingFilters() {
		const indexingFilters = [];

		this.uniqueFilters.forEach(filter => {
			if (filter.indexer)
				indexingFilters.push(filter.filterId);
		});

		if (!indexingFilters.length)
			return false;

		return JSON.stringify(indexingFilters);
	}

	get isProviderExist() {
		return this.$provider.length
			? true
			: false;
	}

	// methods for filter uniqueness
	getFilterUniqueKey(filter) {
		let uniqueKey = filter.name;

		if (filter.isHierarchy)
			uniqueKey += '/hierarchical-depth-' + filter.depth;

		['provider', 'queryId', 'queryKey'].forEach(key => {
			if (filter[key])
				uniqueKey += '/' + filter[key];
		});

		return uniqueKey;
	}

	get uniqueFilters() {
		return [...new Map(this.filters.map(filter => [filter.uniqueKey, filter])).values()];
	}

	getSameFilters(searchFilter) {
		return this.filters.filter(filter => searchFilter.uniqueKey === filter.uniqueKey);
	}
}