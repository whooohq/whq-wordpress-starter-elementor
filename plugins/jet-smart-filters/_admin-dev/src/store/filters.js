// State
const state = {
	columns: { title: 'Title', type: 'Type', source: 'Data Source', date: 'Date' },
	filteredColumns: {
		date: { type: 'date' },
		type: {
			type: 'checkboxes',
			options: [
				{ key: 'checkboxes', value: 'Checkboxes', checked: false },
				{ key: 'select', value: 'Select', checked: false },
				{ key: 'range', value: 'Range', checked: false },
				{ key: 'rating', value: 'Rating', checked: false }
			]
		}
	},
	filtersList: [],
	filtersListArgs: {
		pagination: {
			page: 1,
			totalPages: 1,
			count: 0,
			perPage: 20
		},
		search: '',
		type: '',
		source: '',
		sort: false
	},
	filterTypes: { ...window.JetSmartFiltersAdminData.filter_types },
	filterSources: { ...window.JetSmartFiltersAdminData.filter_sources },
	sortByList: { ...window.JetSmartFiltersAdminData.sort_by_list },
	quantity: {
		filters: 0,
		trash: 0,
	},
	isFiltersListLoading: true,
};

// Getters
const getters = {
	columns: state => { return state.columns; },
	filteredColumns: state => { return state.filteredColumns; },
	filtersList: state => { return state.filtersList; },
	filtersListArgs: state => { return state.filtersListArgs; },
	filterTypes: state => { return state.filterTypes; },
	filterSources: state => { return state.filterSources; },
	sortByList: state => { return state.sortByList; },
	quantity: state => { return state.quantity; },
	isFiltersListLoading: state => { return state.isFiltersListLoading; },
};

// Actions
const actions = {
	updateFiltersList: ({ commit }, filtersList) => {
		commit('updateState', {
			name: 'filtersList',
			data: filtersList
		});
	},

	updateFiltersListArgs: ({ commit }, filtersListArgs) => {
		commit('updateState', {
			name: 'filtersListArgs',
			data: filtersListArgs
		});
	},

	updateQuantity: ({ commit }, quantity) => {
		commit('updateState', {
			name: 'quantity',
			data: quantity
		});
	},

	updateIsFiltersListLoading: ({ commit }, value) => {
		commit('updateState', {
			name: 'isFiltersListLoading',
			data: value
		});
	},
};

export default {
	state,
	getters,
	actions
};