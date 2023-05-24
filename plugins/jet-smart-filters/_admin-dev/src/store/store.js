import { createStore } from "vuex";

import filters from "./filters.js";
import filter from "./filter.js";

// State
const state = {
	pluginSettings: window.JetSmartFiltersAdminData.plugin_settings,
	isPageLoading: true,
	currentPage: '',
	taxTermsOptions: {},
	postsItemsOptions: {},
	...filters.state,
	...filter.state
};

// Getters
const getters = {
	pluginSettings: state => { return state.pluginSettings; },
	isPageLoading: state => { return state.isPageLoading; },
	currentPage: state => { return state.currentPage; },
	taxTermsOptions: state => { return state.taxTermsOptions; },
	postsItemsOptions: state => { return state.postsItemsOptions; },
	...filters.getters,
	...filter.getters
};

// Mutations
const mutations = {
	updateState: (state, { name, data }) => {
		state[name] = data;
	}
};

// Actions
const actions = {
	updateIsPageLoading: ({ commit }, value) => {
		commit('updateState', {
			name: 'isPageLoading',
			data: value
		});
	},
	updateCurrentPage: ({ commit }, newPage) => {
		commit('updateState', {
			name: 'currentPage',
			data: newPage
		});
	},
	updateTaxTermsOptions: ({ commit }, newTaxTermsOptions) => {
		commit('updateState', {
			name: 'taxTermsOptions',
			data: newTaxTermsOptions
		});
	},
	updatePostsItemsOptions: ({ commit }, newPostsItemsOptions) => {
		commit('updateState', {
			name: 'postsItemsOptions',
			data: newPostsItemsOptions
		});
	},
	...filters.actions,
	...filter.actions
};

// Store
const store = createStore({
	state,
	getters,
	mutations,
	actions
});

export default store;