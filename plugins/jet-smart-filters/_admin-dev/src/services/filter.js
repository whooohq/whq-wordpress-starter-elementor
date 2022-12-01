import { ref, reactive, computed } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useGetters, useGetter, useActions } from "@/store/helper.js";
import { controlPreparation, parseOptions } from "@/modules/JetUI/services/controls-list.js";
import { clone, stringToBoolean } from "@/modules/helpers/utils.js";
import _object from "@/modules/helpers/object.js";
import _array from "@/modules/helpers/array.js";
import request from "@/services/request.js";
import popup from "@/services/popups.js";

const {
	filterID,
	filterTitle,
	filterSavedData,
	filterUnsavedData,
	filterSettings
} = useGetters(['filterID', 'filterTitle', 'filterSavedData', 'filterUnsavedData', 'filterSettings']);

const {
	updateIsPageLoading,
	updateIsFilterLoading,
	updateCurrentPage,
	updateFilterID,
	updateFilterTitle,
	updateFilterDate,
	updateFilterSavedData,
	updateFilterUnsavedData,
	updateFilterSettings,
	updateFiltersListArgs,
	updateTaxTermsOptions,
	updatePostsItemsOptions
} = useActions(['updateIsPageLoading', 'updateIsFilterLoading', 'updateCurrentPage', 'updateFilterID', 'updateFilterTitle', 'updateFilterDate', 'updateFilterSavedData', 'updateFilterUnsavedData', 'updateFilterSettings', 'updateFiltersListArgs', 'updateTaxTermsOptions', 'updatePostsItemsOptions']);

let router, route;

const excludeIncludeBackupValues = {};
const visualInputBackupValues = {};

export const filterType = ref(null);
export const updateAvailable = computed(() => filterTitle.value && !_object.isEmpty(filterUnsavedData.value) && _object.isEmpty(requiredNotFilled));

export function init() {
	// Init vue-router
	router = useRouter();
	route = useRoute();

	const routeID = route.params.id;

	clearStoreData();
	updateCurrentPage(route.name);
	updateFilterID(routeID);

	if (routeID === 'new') {
		updateIsPageLoading(false);
		updateSettings({});

		return;
	}

	requestData();
}

export function requestData(enablePageLoading = true) {
	if (enablePageLoading)
		updateIsPageLoading(true);

	request.getFilter(filterID.value)
		.then(response => {
			if (!response) {
				router.push('new');
			}

			updateData(response);
			updateSettings();
			checkSettings();
			updateIsPageLoading(false);
		});
}

export function updateData(data) {
	updateFilterSavedData(data);
}

export function addAdditionallyData(key, data) {
	const filtersListArgs = useGetter('filtersListArgs', false);

	filtersListArgs.additionally = {
		[key]: data
	};

	updateFiltersListArgs(filtersListArgs);
}

export function addAdditionallyUpdate(id = null, data = null) {
	if (id === null)
		id = filterID.value;

	if (data === null)
		data = filterUnsavedData.value;

	addAdditionallyData('update', { id, data });

	if (id === filterID.value)
		clearStoreData();
}

export function updateSettings(newValues = null) {
	const settings = clone(window.JetSmartFiltersAdminData.filter_settings);
	const savedData = newValues || useGetter('filterSavedData', false);

	for (const groupKey in settings) {
		for (const key in settings[groupKey].settings) {
			const control = controlPreparation(settings[groupKey].settings[key]);

			// Set response control value
			if (savedData.hasOwnProperty(key))
				switch (control.type) {
					case 'switcher':
						// Switcher control convert string to boolean
						control.value = stringToBoolean(savedData[key]);
						break;

					case 'repeater':
						let repeaterValue = savedData[key];

						if (!_array.is(repeaterValue))
							if (_object.is(repeaterValue)) {
								repeaterValue = _object.toArray(repeaterValue);
							} else {
								repeaterValue = [];
							}

						control.value = repeaterValue;
						break;

					default:
						control.value = savedData[key];
						break;
				}
		}
	}

	if (savedData.hasOwnProperty('title'))
		updateFilterTitle(savedData.title);

	if (savedData.hasOwnProperty('date'))
		updateFilterDate(savedData.date);

	updateFilterSettings(settings);
}

export function сhangeSetting(key, value) {
	const unsavedData = useGetter('filterUnsavedData', false);

	if (key === 'title')
		updateFilterTitle(value);

	if (key === 'date')
		updateFilterDate(filterSavedData.value.date);

	unsavedData[key] = value;

	if (filterSavedData.value.hasOwnProperty(key) && filterSavedData.value[key] === value)
		delete unsavedData[key];

	updateFilterUnsavedData(unsavedData);
	checkSettingChange(key);
};

export function changeDate(newDate) {
	сhangeSetting('date', newDate);
	updateFilterDate(newDate);
};

export function saveData() {
	updateIsFilterLoading(true);

	request.updateFilter(filterID.value, filterUnsavedData.value)
		.then((newID) => {
			updateData(Object.assign(filterSavedData.value, filterUnsavedData.value));
			updateFilterUnsavedData({});
			updateIsFilterLoading(false);

			if (filterID.value === 'new' && newID) {
				updateFilterID(newID);

				router.push({
					name: 'filter',
					params: { id: newID }
				});
			}
		});
}

export function getSetting(key) {
	for (const groupKey in filterSettings.value)
		for (const settingKey in filterSettings.value[groupKey].settings)
			if (settingKey === key)
				return filterSettings.value[groupKey].settings[settingKey];

	return false;
}

export function getSettingValue(key) {
	const setting = getSetting(key);

	return setting ? setting.value : false;
}

export function clearStoreData() {
	updateFilterID(null);
	updateFilterTitle('');
	updateFilterDate('');
	updateFilterSavedData({});
	updateFilterUnsavedData({});
	updateFilterSettings({});
}

export function goToList() {
	router.push('/');
}

export function moveToTrash() {
	addAdditionallyData('move_to_trash', filterID.value);
	clearStoreData();

	goToList();
}

export function beforeRouteUpdate(to, from) {
	const fromId = from.params.id;
	const toId = to.params.id;

	if (fromId !== 'new') {
		clearStoreData();
		updateSettings({});

		if (toId === 'new')
			updateFilterID('new');
	}

	if (toId !== 'new') {
		updateFilterID(toId);
		requestData(fromId !== 'new');
	}
}

export function beforeRouteLeave(to, from, next) {
	if (!updateAvailable.value)
		return next();

	// Save changes popup
	popup.saveChanges(
		// Save
		() => {
			addAdditionallyUpdate();
			next();
		},
		// Don’t save
		() => {
			clearStoreData();
			next();
		}
	);
}

export function checkSettings() {
	filterType.value = getSettingValue('_filter_type');

	const dataSourceControl = getSetting('_data_source');
	const dataSource = dataSourceControl.value;

	/*
	 * Check tax terms options
	 */
	if (['checkboxes', 'select', 'radio'].includes(filterType.value))
		if (getSettingValue('_use_exclude_include')) {
			if (dataSource === 'taxonomies')
				updateSettingTaxTermsOptions('_data_exclude_include', getSettingValue('_source_taxonomy'));

			if (dataSource === 'posts')
				updateSettingPostsOptions('_data_exclude_include', getSettingValue('_source_post_type'));
		}

	/*
	 * Check color image controls visibility
	 */
	if ('color-image' === filterType.value) {
		switchVisualColorImageInput();
		switchVisualValueInput();

		if (dataSource === 'taxonomies')
			updateRepeaterSettingTaxTermsOptions('_source_color_image_input', 'selected_value', getSettingValue('_source_taxonomy'));

		if (dataSource === 'posts')
			updateRepeaterSettingPostsOptions('_source_color_image_input', 'selected_value', getSettingValue('_source_post_type'));
	}

	/*
	 * Color image hide custom fields source option
	 */
	const customFieldsOptionIndex = _array.findIndexByPropertyValue(dataSourceControl.options, 'value', 'custom_fields');

	if (customFieldsOptionIndex)
		if ('color-image' === filterType.value) {
			dataSourceControl.options[customFieldsOptionIndex].disabled = true;

			if (dataSource === 'custom_fields')
				dataSourceControl.value = '';
		} else {
			delete dataSourceControl.options[customFieldsOptionIndex].disabled;
		}
}

export function checkSettingChange(key) {
	if ('_filter_type' === key) {
		checkSettings();

		return;
	}

	/*
	 * Update exclude include select options for current taxonomy
	 */
	if (['_source_taxonomy', '_source_post_type', '_data_source', '_use_exclude_include'].includes(key)) {
		if (['checkboxes', 'select', 'radio'].includes(filterType.value) && getSettingValue('_use_exclude_include')) {
			const excludeIncludeSetting = getSetting('_data_exclude_include');
			const dataSource = getSettingValue('_data_source');

			if ('_source_taxonomy' === key || (dataSource === 'taxonomies' && ['_data_source', '_use_exclude_include'].includes(key))) {
				const newTaxName = getSettingValue('_source_taxonomy');

				excludeIncludeBackupValues[excludeIncludeSetting.currentTax] = excludeIncludeSetting.value;
				excludeIncludeSetting.value = excludeIncludeBackupValues[newTaxName] || '';
				сhangeSetting('_data_exclude_include', excludeIncludeSetting.value);
				updateSettingTaxTermsOptions('_data_exclude_include', newTaxName);
			}

			if ('_source_post_type' === key || (dataSource === 'posts' && ['_data_source', '_use_exclude_include'].includes(key))) {
				const newPostType = getSettingValue('_source_post_type');

				excludeIncludeBackupValues[excludeIncludeSetting.currentPostType] = excludeIncludeSetting.value;
				excludeIncludeSetting.value = excludeIncludeBackupValues[newPostType] || '';
				сhangeSetting('_data_exclude_include', excludeIncludeSetting.value);
				updateSettingPostsOptions('_data_exclude_include', newPostType);
			}
		}
	}

	/*
	 * Visual filter
	 */
	if ('color-image' === filterType.value) {
		const visualInputSetting = getSetting('_source_color_image_input');
		const dataSource = getSettingValue('_data_source');

		if (['_source_color_image_input', '_color_image_type'].includes(key))
			switchVisualColorImageInput();

		if (key === '_data_source')
			switchVisualValueInput();

		if (key === '_source_taxonomy' || (key === '_data_source' && dataSource === 'taxonomies')) {
			const newTaxName = getSettingValue('_source_taxonomy');

			visualInputBackupValues[visualInputSetting.currentTax] = [];
			visualInputSetting.value.forEach(value => visualInputBackupValues[visualInputSetting.currentTax].push(value.selected_value));
			for (let index = 0; index < visualInputSetting.value.length; index++)
				visualInputSetting.value[index].selected_value = visualInputBackupValues[newTaxName] && visualInputBackupValues[newTaxName][index]
					? visualInputBackupValues[newTaxName][index]
					: '';

			updateRepeaterSettingTaxTermsOptions('_source_color_image_input', 'selected_value', newTaxName);
		}

		if (key === '_source_post_type' || (key === '_data_source' && dataSource === 'posts')) {
			const newPostType = getSettingValue('_source_post_type');

			visualInputBackupValues[visualInputSetting.currentPostType] = [];
			visualInputSetting.value.forEach(value => visualInputBackupValues[visualInputSetting.currentPostType].push(value.selected_value));
			for (let index = 0; index < visualInputSetting.value.length; index++)
				visualInputSetting.value[index].selected_value = visualInputBackupValues[newPostType] && visualInputBackupValues[newPostType][index]
					? visualInputBackupValues[newPostType][index]
					: '';

			updateRepeaterSettingPostsOptions('_source_color_image_input', 'selected_value', newPostType);
		}
	}
}

export function updateSettingTaxTermsOptions(settingKey, taxName) {
	const taxTermsOptions = useGetter('taxTermsOptions', false);
	const setting = getSetting(settingKey);

	if (!setting)
		return;

	// Update current tax value
	setting.currentTax = taxName;

	if (taxTermsOptions.hasOwnProperty(taxName)) {
		setting.options = taxTermsOptions[taxName];

		return;
	}

	const placeholder = setting.placeholder;

	setting.placeholder = 'Loading...';

	request.getTaxonomyTerms(taxName)
		.then(response => {
			setting.options = parseOptions(response);
			setting.placeholder = placeholder;

			taxTermsOptions[taxName] = setting.options;
			updateTaxTermsOptions(taxTermsOptions);
		});
}

export function updateSettingPostsOptions(settingKey, postType) {
	const postsItemsOptions = useGetter('postsItemsOptions', false);
	const setting = getSetting(settingKey);

	if (!setting)
		return;

	// Update current tax value
	setting.currentPostType = postType;

	if (postsItemsOptions.hasOwnProperty(postType)) {
		setting.options = postsItemsOptions[postType];

		return;
	}

	const placeholder = setting.placeholder;

	setting.placeholder = 'Loading...';

	request.getPostsList(postType)
		.then(response => {
			setting.options = parseOptions(response);
			setting.placeholder = placeholder;

			postsItemsOptions[postType] = setting.options;
			updatePostsItemsOptions(postsItemsOptions);
		});
}

export function updateRepeaterSettingTaxTermsOptions(settingKey, fieldKey, taxName) {
	const taxTermsOptions = useGetter('taxTermsOptions', false);
	const repeater = getSetting(settingKey);

	if (!repeater || !repeater.fields.hasOwnProperty(fieldKey))
		return;

	// Update current tax value
	repeater.currentTax = taxName;

	const placeholder = repeater.fields[fieldKey].placeholder;

	repeater.fields[fieldKey].placeholder = 'Loading...';
	repeater.fields[fieldKey].options = [];

	if (taxTermsOptions.hasOwnProperty(taxName)) {
		setOptions(taxTermsOptions[taxName]);
	} else {
		request.getTaxonomyTerms(taxName)
			.then(response => {
				taxTermsOptions[taxName] = parseOptions(response);
				updateTaxTermsOptions(taxTermsOptions);

				setOptions(taxTermsOptions[taxName]);
			});
	}

	function setOptions(options) {
		repeater.fields[fieldKey].placeholder = placeholder;
		repeater.fields[fieldKey].options = options;
	}
}

export function updateRepeaterSettingPostsOptions(settingKey, fieldKey, postType) {
	const postsItemsOptions = useGetter('postsItemsOptions', false);
	const repeater = getSetting(settingKey);

	if (!repeater || !repeater.fields.hasOwnProperty(fieldKey))
		return;

	// Update current post type value
	repeater.currentPostType = postType;

	const placeholder = repeater.fields[fieldKey].placeholder;

	repeater.fields[fieldKey].placeholder = 'Loading...';
	repeater.fields[fieldKey].options = [];

	if (postsItemsOptions.hasOwnProperty(postType)) {
		setOptions(postsItemsOptions[postType]);
	} else {
		request.getPostsList(postType)
			.then(response => {
				postsItemsOptions[postType] = parseOptions(response);
				updatePostsItemsOptions(postsItemsOptions);

				setOptions(postsItemsOptions[postType]);
			});
	}

	function setOptions(options) {
		repeater.fields[fieldKey].placeholder = placeholder;
		repeater.fields[fieldKey].options = options;
	}
}

export function switchVisualColorImageInput(newSourceType = null) {
	if (newSourceType === null)
		newSourceType = getSettingValue('_color_image_type');

	const inputSetting = getSetting('_source_color_image_input');

	if (!inputSetting || !['color', 'image'].includes(newSourceType))
		return;

	inputSetting.sourceType = newSourceType;

	delete inputSetting.fields.source_image.hidden;
	delete inputSetting.fields.source_color.hidden;

	if (inputSetting.sourceType === 'color')
		inputSetting.fields.source_image.hidden = true;

	if (inputSetting.sourceType === 'image')
		inputSetting.fields.source_color.hidden = true;
}

export function switchVisualValueInput(newDataSource = null) {
	if (newDataSource === null)
		newDataSource = getSettingValue('_data_source');

	const inputSetting = getSetting('_source_color_image_input');

	if (!inputSetting)
		return;

	inputSetting.dataSource = newDataSource;

	delete inputSetting.fields.value.hidden;
	delete inputSetting.fields.selected_value.hidden;

	if (['taxonomies', 'posts'].includes(inputSetting.dataSource)) {
		inputSetting.fields.value.hidden = true;
	} else {
		inputSetting.fields.selected_value.hidden = true;
	}
}

// Required controls
export const requiredNotFilled = reactive({});

export const changeRequiredNotFilled = (notFilled, key) => {
	if (!_array.isEmpty(notFilled)) {
		requiredNotFilled[key] = notFilled;
	} else {
		delete requiredNotFilled[key];
	}
};

// Help Block
export const helpBlockData = computed(() => {
	const data = useGetter('helpBlockData', false);
	const outputData = {
		title: data.title,
		list: data.general
	};

	if (data.special.hasOwnProperty(filterType.value))
		outputData.list = [...data.special[filterType.value], ...data.general];

	return outputData;
});

export default {
	filterType,
	updateAvailable,
	init,
	requestData,
	updateData,
	addAdditionallyData,
	addAdditionallyUpdate,
	updateSettings,
	сhangeSetting,
	changeDate,
	saveData,
	getSetting,
	getSettingValue,
	clearStoreData,
	goToList,
	moveToTrash,
	beforeRouteUpdate,
	beforeRouteLeave,
	checkSettings,
	checkSettingChange,
	updateSettingTaxTermsOptions,
	updateRepeaterSettingTaxTermsOptions,
	updateRepeaterSettingPostsOptions,
	switchVisualColorImageInput,
	switchVisualValueInput,
	requiredNotFilled,
	changeRequiredNotFilled,
	helpBlockData
};