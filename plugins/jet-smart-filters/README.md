# JetSmartFilters

# ChangeLog

## 2.3.12
* ADD: reindex indexer DB table on plugin activate and update
* UPD: template parses special characters
* FIX: Permalink rewrite rules
* FIX: range filter with popup
* FIX: WPML WooCommerce multi currency price
* FIX: Date Range Filter datepicker current day
* FIX: Search filter RTL
* FIX: filter date period rtl scroll
* FIX: gutenberg console error
* FIX: indexer with current query args
* FIX: maps listing for Borlabs Cookies plugin
* FIX: additional filter settings input clears the 'X'
* FIX: show widget icon in elementor editor if filter not selected
* FIX: additional filter style search remove horizontal offset RTL
* FIX: additional filter style search remove horizontal offset RTL
* FIX: Checkbox styles for block editor

## 2.3.11
* UPD: replaced deprecated method _register_controls to register_controls
* FIX: CheckBoxes additional settings dropdown
* FIX: Search filter spinner spins infinitely on submission with 'AJAX on typing'
* FIX: ePro widgets after filtration
* FIX: duplication of sublevels of a hierarchical select
* FIX: woocommerce shortcode attribute on page reload
* FIX: check hierarchy current page
* FIX: Radio filter with motion effects sticky
* FIX: Date range filter query & placeholder on redirect
* FIX: Date period filter for popup
* FIX: Select filter alignment style
* FIX: EPro Posts skin 'Full Content' settings

## 2.3.10
* ADD: elementor pro popup support
* FIX: jet-woo-products-grid/list Use Current Query option on archive page
* FIX: air-datepicker conflict
* FIX: taxanomies parent terms indexer
* FIX: compatibility with Elementor Pro 3.6
* UPD: jet-elementor-extension framework

## 2.3.9
* ADD: Custom Query Variable option for taxonomies source
* ADD: `URL with filtered value` dynamic tag
* UPD: Better JetEngine compatibility
* FIX: Select filter style options
* FIX: WPML tax sub terms indexer
* FIX: Filter label notice

## 2.3.8
* ADD: allow to filter indexer data before writing into DB
* UPD: setIndexedData updating result manually
* FIX: grammatical error correction from HoriSontal to HoriZontal
* FIX: clear range filter input
* FIX: hierarchical chain
* FIX: sanitize widgets settings before passing for rendering
* FIX: indexer with duplicates

## 2.3.7
* ADD: indexer on get filters data request sql SET SESSION group_concat_max_len
* ADD: check is indexer enabled on 'index_filters' method

## 2.3.6
* SYS: renamed indexer method

## 2.3.5
* FIX: JetEngine with Use Custom Query on AJAX compatibility
* FIX: JetEngine lazy load compatibility

## 2.3.4
* FIX: Indexer for custom database table prefix

## 2.3.3
* UPD: Indexer refactoring
* ADD: Auto re-indexing option
* FIX: Alphabet filter
* FIX: Duplicate labels in the filter widget when displaying multiple filters
* FIX: Date Range with one blank field
* FIX: Date Period day type
* FIX: Rating filter clear
* FIX: Check Range filter if item max value 0
* FIX: Range filter if item max value 0
* FIX: Range filter with negatives values
* FIX: elementor editor icons from fa to eicon
* FIX: guten blocks in widgets areas error on refresh
* FIX: remove console.log

## 2.3.2
* ADD: multi sorting
* ADD: Sorting filter Reset Field Appearance control
* FIX: url with additional filters
* FIX: apply button filter for gutenberg
* FIX: Alphabet filter
* FIX: Date period filter events duplication
* FIX: Active tag filter visibility for Hello Elementor theme
* FIX: guten get_editor_script_depends
* FIX: Radio All option label when Group terms by parents
* FIX: Date Range with page reload in Safari
* FIX: hierarchical chaining for identical taxonomies
* FIX: Range filter WooCommerce min/max prices with gets params
* FIX: Hierarchical label
* FIX: jet-engine-calendar current request query

## 2.3.1
* ADD: Query Builder settings to store for JetWooBuilder Product Grid/List providers;
* FIX: Custom query arguments for Product List provider.

## 2.3.0
* ADD: Alphabet filter
* ADD: Multiple query variable separated by comma
* ADD: Radio, Visual, CheckRange filters add additional settings
* ADD: CCT Data Source
* FIX: Additional filter settings dropdown without search
* FIX: range input slider
* FIX: relation AND between filters with the same taxonomy
* FIX: elementor pro Archive Products customizer default product sorting options

## 2.2.3
* ADD: compatibility with new jetEngine features
* UPD: pagination filter provider top offset change max to 999
* UPD: pagination filter items gap
* UPD: checkbox decorator offsets
* FIX: Products cat & tag default taxonomy
* FIX: elementor Scheme_Typography

## 2.2.2
* UPD: Range Filter
* FIX: Grouped Filters styles
* FIX: Minor bugs

## 2.2.1
* UPD: Allow to rewrite indexer query args
* UPD: Rolled back hide elementor widget container if all items are hidden by indexer
* FIX: JetEngine glossaries compatibility
* FIX: Avoid letter-casing related errors when checking if DB table is exists
* FIX: ePro archive products default query
* FIX: ePro Archive Products sorting on page reload if sorting presets are set in the customizer
* FIX: Products loop

## 2.2.0
* ADD: URL Structure Settings (Plain/Permalink)
* ADD: JetTabs ajax load template compatibility
* ADD: Hamburger Panel ajax load template compatibility
* ADD: Hide elementor widget container if all items are hidden by indexer
* ADD: Date period datepicker button text
* ADD: ePro Posts skin full content support
* FIX: Visual filter options list value
* FIX: Checkbox filter MORE/LESS ignore the item if it was hidden by the indexer as empty
* FIX: remove strip slashes on searching
* FIX: check current control on ajax redirect
* FIX: avoid PHP notices
* FIX: bugs fixing


## 2.1.1
* ADD: Hide filter label if all items is hidden
* ADD: Localized data extra_props
* FIX: Filter select grouped filters styles
* FIX: Date period format placeholder
* FIX: Hierarchy filter with single tax
* FIX: Visual filter image empty error
* FIX: EPro Archive Products add tax_query to store query

## 2.1.0
* ADD: New filter Date Period
* ADD: Checkboxes Additional Settings:
	* Search
	* More/Less
	* Dropdown
	* Scroll
* ADD: Radio
	* Ability to add options all
	* Ability to deselect radio buttons
* ADD: Added the ability to change styles in Gutenberg ( **required plugin Jet Style Manager** )
<br/>Widgets that support styles:
	* Active Filters
	* Active Tags
	* Apply Button
	* Checkboxes
	* Check Range
	* Date Period
	* Date Range
	* Pagination
	* Radio
	* Range
	* Rating
	* Remove Filters
	* Search
	* Select
	* Sorting
	* Visual

## 2.0.6
* FIX: WordPress 5.6 compatibility

## 2.0.5
* UPD: jet dashboard to 2.0.4
* FIX: bugs fixing

## 2.0.4
* ADD: hide Elementor widgets: active filters, active tags and remove filters if not active
* ADD: hierarchical filter preloader class
* UPD: change indexer DB columns format
* UPD: jet dashboard to 2.0.0
* FIX: minor bugs

## 2.0.3
* ADD: JetWooBuilder 1.7.0 compatibility
* ADD: compatibility with upcoming jet-engine listing
* FIX: epro-archive widget for products posts

## 2.0.2
* ADD: 'Get from query meta key' callback for range filter
* UPD: wrapper action for jet-engine provider
* FIX: hierarchy filter with single taxonomy
* FIX: process listing grid with nested listing grid
* FIX: epro-archive widget default query tags and custom taxonomy

## 2.0.1
* ADD: jet-dashboard
* ADD: date format for date-range filter
* ADD: ajax content hooks for epro-products widget
* FIX: clearing select filter when returning to the filter page
* FIX: minor bugs

## 2.0.0
* ADD: added filter blocks for gutenberg
* FIX: ignoring a hidden filter in a general query
* FIX: range active items prefix and suffix
* FIX: hide active filter styles while there are no active filters
* FIX: indexer hide/disable items with disabled counter
* FIX: minor bugs

## 1.8.4
* ADD: additional providers repeater with provider and queryID
* ADD: ability to set negative values for range filter
* ADD: merge same query keys for filters with Exclude/Include option
* FIX: ePro Posts 'Open in new window' option
* FIX: clearing meta_query date on redirect
* FIX: term_taxonomy_id from term_id for hierarchy filter
* FIX: don't show the counter when the option is turned off while the indexer is on
* FIX: fix for duplicate pagination filters

## 1.8.3
* FIX: hierarchical select;
* FIX: indexer data key for manual input data source;
* FIX: pagination for Pro Product with query_id;

## 1.8.2
* ADD: allow using numbers in "query id" fields;
* FIX: hierarchical filters workflow with additional providers;
* FIX: filters workflow with the products loop widget;
* FIX: hide filters items in the Safari browser;
* FIX: minor bugs;

## 1.8.1
* FIX: redirect path url;
* FIX: provider widget query ID;
* FIX: reset field appearance;

## 1.8.0
* UPD: front-end code refactoring;
* ADD: allow to choose additional provider for filters;
* ADD: show empty terms for checkboxes, select, radio and visual filters;

## 1.7.2
* ADD: compatibility the Indexer with WPML plugin;
* FIX: applying Indexer functionality for page reload filters;
* FIX: compatibility the Indexer with JetPopup plugin;
* FIX: Checkbox, Check Range, Radio filters horizontal layout style controls;
* FIX: hierarchy levels options list on redirect;
* FIX: various minor fixes.

## 1.7.1
* ADD: Allow to get options for select, radio and checkboxes from custom field data (for JetEngine or ACF);
* FIX: Various fixes.

## 1.7.0
* ADD: Sorting widget;
* ADD: Support for Elementor Pro Portfolio widget;
* ADD: comparison operator for select and radio filters;
* ADD: Search Filter widget add apply on typing option;
* ADD: Relational operator for checkbox filter;
* ADD: Active Tags filter;
* ADD: New aply type for filters;
* UPD: Style options for checkbox, check range, radio, visual filters;
* FIX: Minor bugs.

## 1.6.2
* FIX: grouped filters styles
* FIX: better JetEngine compatibility
* FIX: hide grouped filters when indexer empty

## 1.6.1
* UPD: grouped filters styles
* FIX: various fixes

## 1.6.0
* ADD: allow to make redirect from filters to results page
* ADD: Hiearachical filters
* FIX: Various fixes

## 1.5.1
* FIX: Default query args in jet woo products grid widget

## 1.5.0
* ADD: Indexer functionality for checkboxes, check range, select, visual and radio filter types
* UPD: Hide remove all filters button if no active filters
* UPD: Filters Icons
* FIX: Various fixes

## 1.4.2
* FIX: Hot Fixes

## 1.4.1
* ADD: Need helps links to widgets
* ADD: Placeholders for inputs in Date Range Filter

## 1.4.0
* ADD: Visual filter
* ADD: Include/Exclude functionality
* ADD: Remove all filters button widget
* ADD: Inline layout options for radio, checkboxes, check-range filters
* ADD: Better compatibility with WPML and WooCommerce Multilingual plugins
* ADD: %woocommerce_currency_symbol%  macros for range filter prefix and suffix options;
* FIX: Various fixes.
* ADD: Changelog;

## 1.3.2
* ADD: Compatibility with checkbox meta field created with Jet Engine - https://github.com/CrocoBlock/suggestions/issues/163;
* FIX: Merge default query args with current query args;

## 1.3.1
* ADD: Compatibility with WooCommerce Multilingual plugin;
* FIX: Bug with woocommerce archive provider in astra theme;
* FIX: Issue CrocoBlock/suggestions#186;
* FIX: Merging query args with default query args;
* UPD: Compatibility with JetEngine 1.4.0;
* FIX: Various fixes.

## 1.3.0
* ADD: Rating widget;
* ADD: Support for Elementor Pro Products widget;
* ADD: Support for Elementor Pro Archive Products widget;
* ADD: Apply search filter on enter press action
* FIX: Various fixes.

## 1.2.1
* ADD: Allow to filter query before filters applied;
* UPD: Better Compatibility with Elementor Pro;
* FIX: Templates select for JetWooBuilder widgets;
* FIX: Various fixes.


## 1.2.0
* ADD: Separate widget for Apply button;
* ADD: Support for Elementor Pro Posts widget;
* ADD: Support for Elementor Pro Archive widget;
* UPD: New options for Pagination widget;
* FIX: Various fixes.

## 1.1.0.1
* FIX: Large numbers comparing

## 1.1.0
* ADD: RU localization;
* ADD: allow to edit or disable prev/next controls in Pagination widget;
* ADD: allow to set step, number format and suffix for range and check range filters;
* UPD: allow to search by meta field in search filter;
* UPD: run Elementor ready triggers after apply filters;
* UPD: allow to filter same query variable by multiple filters.

## 1.0.0
* Initial release
