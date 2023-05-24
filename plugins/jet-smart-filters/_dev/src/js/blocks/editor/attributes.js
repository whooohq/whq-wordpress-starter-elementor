const { __ } = wp.i18n;

export default {
	// General
	filter_id: {
		type: 'number',
		default: 0,
	},
	content_provider: {
		type: 'string',
		default: 'not-selected',
	},
	apply_type: {
		type: 'string',
		default: 'ajax',
	},
	apply_on: {
		type: 'string',
		default: 'value',
	},
	apply_button: {
		type: 'boolean',
		default: false,
	},
	hide_apply_button: {
		type: 'boolean',
		default: false,
	},
	apply_button_text: {
		type: 'string',
		default: __('Apply filter'),
	},
	apply_redirect: {
		type: 'boolean',
		default: false,
	},
	redirect_path: {
		type: 'string',
		default: '',
	},
	remove_filters_text: {
		type: 'string',
		default: __('Remove filters'),
	},
	show_label: {
		type: 'boolean',
		default: false,
	},
	filters_label: {
		type: 'string',
		default: __('Active filters:'),
	},
	typing_min_letters_count: {
		type: 'number',
		default: 3,
	},
	tags_label: {
		type: 'string',
		default: __('Active tags:'),
	},
	clear_item: {
		type: 'boolean',
		default: true,
	},
	clear_item_label: {
		type: 'string',
		default: __('Clear'),
	},
	rating_icon: {
		type: 'string',
		default: 'fa fa-star',
	},
	sorting_label: {
		type: 'string',
		default: '',
	},
	sorting_placeholder: {
		type: 'string',
		default: __('Sort...'),
	},
	sorting_list: {
		type: 'array',
		default: [{
			title: __('By title from lowest to highest'),
			orderby: 'title',
			order: 'ASC'
		},
		{
			title: __('By title from highest to lowest'),
			orderby: 'title',
			order: 'DESC'
		},
		{
			title: __('By date from lowest to highest'),
			orderby: 'date',
			order: 'ASC'
		},
		{
			title: __('By date from highest to lowest'),
			orderby: 'date',
			order: 'DESC'
		}],
	},
	query_id: {
		type: 'string',
		default: '',
	},
	// Indexer
	apply_indexer: {
		type: 'boolean',
		default: false,
	},
	show_counter: {
		type: 'boolean',
		default: false,
	},
	counter_prefix: {
		type: 'string',
		default: '(',
	},
	counter_suffix: {
		type: 'string',
		default: ')',
	},
	show_items_rule: {
		type: 'string',
		default: 'show',
	},
	change_items_rule: {
		type: 'string',
		default: 'always',
	},
	// Filter Options
	show_items_label: {
		type: 'boolean',
		default: true,
	},
	filter_image_size: {
		type: 'string',
		default: 'full',
	},
	// Pagination Controls
	enable_items: {
		type: 'boolean',
		default: true,
	},
	pages_center_offset: {
		type: 'number',
		default: 0,
	},
	pages_end_offset: {
		type: 'number',
		default: 0,
	},
	enable_prev_next: {
		type: 'boolean',
		default: true,
	},
	prev_text: {
		type: 'string',
		default: __('Prev Text'),
	},
	next_text: {
		type: 'string',
		default: __('Next Text'),
	},
	enable_load_more: {
		type: 'boolean',
		default: false,
	},
	load_more_text: {
		type: 'string',
		default: __('Load More'),
	},
	autoscroll: {
		type: 'boolean',
		default: true,
	},
	provider_top_offset: {
		type: 'number',
		default: 0,
	},
	// Additional Settings
	search_enabled: {
		type: 'boolean',
		default: false,
	},
	search_placeholder: {
		type: 'string',
		default: __('Search...'),
	},
	moreless_enabled: {
		type: 'boolean',
		default: false,
	},
	less_items_count: {
		type: 'number',
		default: 5,
	},
	more_text: {
		type: 'string',
		default: __('More'),
	},
	less_text: {
		type: 'string',
		default: __('Less'),
	},
	dropdown_enabled: {
		type: 'boolean',
		default: false,
	},
	dropdown_placeholder: {
		type: 'string',
		default: __('Select some options'),
	},
	scroll_enabled: {
		type: 'boolean',
		default: false,
	},
	scroll_height: {
		type: 'number',
		default: 290,
	},
};