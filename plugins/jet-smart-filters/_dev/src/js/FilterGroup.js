import AdditionalFilters from 'modules/AdditionalFilters';
import CustomProvider from 'modules/CustomProvider';
import Indexer from 'modules/Indexer';
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
	removeAllDefaultUrlParams,
	mergeData
} from 'includes/utility';

export default class FilterGroup {
	urlPrefix = 'jet-smart-filters';
	activeItemsExceptions = ['sorting', 'pagination'];

	constructor(provider, queryId, filters, queryData = false) {
		this.provider = provider;
		this.queryId = queryId;
		this.filters = filters;
		this.providerSelector = this.getProviderSelector();
		this.$provider = $(this.providerSelector);

		this.currentQuery = Object.assign(this.query, this.urlParams, queryData);

		// Init modules
		this.additionalFilters = new AdditionalFilters(this);
		this.customProvider = new CustomProvider(this);
		this.initIndexer();

		this.urlType = getNesting(JetSmartFilterSettings, 'misc', 'url_type') || 'plain';
		this.baseUrl = window.location.pathname.replace(/jsf\/.*?$/, '');
		this.baseUrlParams = removeAllDefaultUrlParams(window.location.search);

		// Event subscriptions
		eventBus.subscribe('fiter/change', filter => {
			if (!this.isCurrentProvider(filter))
				return;

			this.filterChangeHandler(filter.applyType);
		});
		eventBus.subscribe('fiters/apply', applyFilter => {
			if (!this.isCurrentProvider(applyFilter))
				return;

			this.applyFiltersHandler(applyFilter.applyType, applyFilter.redirect && applyFilter.redirectPath ? applyFilter.redirectPath : false, applyFilter.redirectInNewWindow);
		});
		eventBus.subscribe('fiters/remove', removeFilter => {
			if (!this.isCurrentProvider(removeFilter))
				return;

			this.removeFiltersHandler(removeFilter.applyType);
		});
		eventBus.subscribe('pagination/change', paginationFilter => {
			if (!this.isCurrentProvider(paginationFilter))
				return;

			this.paginationСhangeHandler(paginationFilter.applyType, paginationFilter.topOffset);
		});

		preloader.subscribe(this.providerSelector, {
			provider,
			queryId
		});

		// After initialization
		setTimeout(() => {
			// update filters with current data
			this.setFiltersData();

			this.currentQuery = this.query;
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

	paginationСhangeHandler(applyType, topOffset = 0) {
		this.apply(applyType);

		// scroll to provider
		if (applyType !== 'reload')
			$('html, body').stop().animate({ scrollTop: this.$provider.offset().top - topOffset }, 500);
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

		document.location = this.baseUrl + url || this.baseUrl;
	}

	doAjax() {
		const query = this.query;

		if (isEqual(query, this.currentQuery))
			return;

		this.currentQuery = query;
		this.updateUrl();
		this.ajaxRequest(response => {
			this.ajaxRequestCompleted(response);
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
		eventBus.publish('ajaxFilters/start-loading', this.provider, this.queryId);
	}

	endAjaxLoading() {
		eventBus.publish('ajaxFilters/end-loading', this.provider, this.queryId);
	}

	ajaxRequestCompleted(response) {
		//update the provider selector if for some reason it is null
		if (!this.$provider.length)
			this.$provider = $(this.providerSelector);

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
			this.renderResult(response.content);
		}

		// update provider data
		if (response.is_data) {
			this.$provider.trigger('jet-filter-data-updated', response);
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
				.closest('.elementor-widget-jet-engine-maps-listing,.jet-map-listing-block')
				.trigger('jet-filter-custom-content-render', response);
		}

		eventBus.publish('ajaxFilters/updated', this.provider, this.queryId);
	}

	renderResult(result) {
		if (!this.$provider.length)
			return;

		if ('insert' === this.providerSelectorData.action) {
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

		// emit rendered event
		eventBus.publish('provider/content-rendered', this.provider, this.$provider);
		// for backward compatibility with other plugins
		$(document).trigger('jet-filter-content-rendered', [this.$provider, this, this.provider, this.queryId]);
	}

	setFiltersData(data = this.currentQuery) {
		this.filters.forEach(filter => {
			if (filter.isHierarchy && (filter.singleTax || data['hc']))
				return;

			const key = filter.queryKey,
				value = data[key];

			if (value)
				if (!filter.isHierarchy) {
					if (filter.setData)
						filter.setData(value);
				} else {
					filter.dataValue = value;
				}
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
				history.replaceState(null, null, this.baseUrl + url);
		} else {
			history.replaceState(null, null, this.baseUrl);
		}
	}

	getUrl(allFilters = false) {
		const urlData = {};

		this.filters.forEach(filter => {
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
				url = 'jsf/' + providerName + '/';

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
					url = this.baseUrlParams + '&jsf=' + providerName;
				} else {
					url = '?jsf=' + providerName;
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
	initIndexer() {
		const indexedClass = 'jet-filter-indexed';

		this.filters.forEach(filter => {
			if (filter.$container && filter.$container.hasClass(indexedClass)) {
				// Init Indexer Class
				filter.indexer = new Indexer(filter);
			}
		});
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

		this.filters.forEach(filter => {
			if (filter.disabled)
				return;

			const data = filter.data,
				key = filter.queryKey;

			if (!data || !key)
				return;

			if (query[key] && filter.mergeSameQueryKeys) {
				query[key] = mergeData(query[key], data, 'operator_AND');
			} else {
				if (filter.isHierarchy && filter.hierarchicalСhain)
					query['hc'] = filter.hierarchicalСhain;

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
		return this.filters.filter(filter => {
			return filter.data && filter.reset && !filter.disabled && !this.activeItemsExceptions.includes(filter.name);
		});
	}

	get hierarchyFilters() {
		const hierarchyFilters = {};

		this.filters.forEach(filter => {
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

		this.filters.forEach(filter => {
			if (filter.indexer)
				indexingFilters.push(filter.filterId);
		});

		if (!indexingFilters.length)
			return false;

		return JSON.stringify([...new Set(indexingFilters)]);
	}
}