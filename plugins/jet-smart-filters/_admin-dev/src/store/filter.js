// State
const state = {
	filterID: null,
	filterTitle: '',
	filterDate: '',
	filterSavedData: {},
	filterUnsavedData: {},
	filterSettings: {},
	helpBlockData: window.JetSmartFiltersAdminData.help_block,
	isFilterLoading: false,
};

// Getters
const getters = {
	filterID: state => { return state.filterID; },
	filterTitle: state => { return state.filterTitle; },
	filterDate: state => { return state.filterDate; },
	filterSavedData: state => { return state.filterSavedData; },
	filterUnsavedData: state => { return state.filterUnsavedData; },
	filterSettings: state => { return state.filterSettings; },
	helpBlockData: state => { return state.helpBlockData; },
	isFilterLoading: state => { return state.isFilterLoading; },
	isFilterNew: state => { return state.filterID === 'new'; },
};

// Actions
const actions = {
	updateFilterID: ({ commit }, filterID) => {
		commit('updateState', {
			name: 'filterID',
			data: filterID
		});
	},
	updateFilterTitle: ({ commit }, filterTitle) => {
		commit('updateState', {
			name: 'filterTitle',
			data: filterTitle
		});
	},
	updateFilterDate: ({ commit }, filterDate) => {
		commit('updateState', {
			name: 'filterDate',
			data: filterDate
		});
	},
	updateFilterSavedData: ({ commit }, filterSavedData) => {
		commit('updateState', {
			name: 'filterSavedData',
			data: filterSavedData
		});
	},
	updateFilterUnsavedData: ({ commit }, filterUnsavedData) => {
		commit('updateState', {
			name: 'filterUnsavedData',
			data: filterUnsavedData
		});
	},
	updateFilterSettings: ({ commit }, filterSettings) => {
		commit('updateState', {
			name: 'filterSettings',
			data: filterSettings
		});
	},
	updateIsFilterLoading: ({ commit }, value) => {
		commit('updateState', {
			name: 'isFilterLoading',
			data: value
		});
	},
};

export default {
	state,
	getters,
	actions
};