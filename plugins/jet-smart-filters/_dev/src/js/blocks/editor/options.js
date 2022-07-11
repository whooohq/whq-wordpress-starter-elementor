import {
	getNesting
} from 'includes/utility';

const { __ } = wp.i18n;

export const applyType = [{
	label: __('AJAX'),
	value: 'ajax'
},
{
	label: __('Page reload'),
	value: 'reload'
},
{
	label: __('Mixed'),
	value: 'mixed'
}];

export const applyOn = [{
	label: __('Value change'),
	value: 'value'
},
{
	label: __('Click on apply button'),
	value: 'submit'
}];

export const ratingIcons = [{
	label: __('Star'),
	value: 'fa fa-star'
},
{
	label: __('Star o'),
	value: 'fa fa-star-o'
},
{
	label: __('Star half'),
	value: 'fa fa-star-half'
},
{
	label: __('Star half o'),
	value: 'fa fa-star-half-o'
}];

export const sortingOrder = [{
	label: __('ASC'),
	value: 'ASC'
},
{
	label: __('DESC'),
	value: 'DESC'
}];

export const indexerShowItemsRule = [{
	label: __('Show'),
	value: 'show'
},
{
	label: __('Hide'),
	value: 'hide'
},
{
	label: __('Disable'),
	value: 'disable'
}];

export const indexerChangeItemsRule = [{
	label: __('Always'),
	value: 'always'
},
{
	label: __('Never'),
	value: 'never'
},
{
	label: __('Other Filters Changed'),
	value: 'other_changed'
}];

function getFiltersOptions(type) {
	return getDataOptions(getNesting(JetSmartFilterBlocksData, 'filters', type))
};

function getDataOptions(data) {
	const options = [];

	for (const key in data) {
		options.push({
			value: key,
			label: data[key]
		});
	}

	return options;
}

export default {
	filters: getFiltersOptions,
	providers: getDataOptions(getNesting(JetSmartFilterBlocksData, 'providers')),
	applyType,
	applyOn,
	ratingIcons,
	indexerShowItemsRule,
	indexerChangeItemsRule,
	sortingOrder,
	sortingOrderby: getDataOptions(getNesting(JetSmartFilterBlocksData, 'sorting_orderby')),
	imageSizes: getDataOptions(getNesting(JetSmartFilterBlocksData, 'image_sizes'))
}