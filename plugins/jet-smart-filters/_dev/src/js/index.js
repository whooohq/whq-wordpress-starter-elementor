import filtersInitializer from './filters-initializer';

// Includes
import elementorEditorMode from 'includes/elementor-editor-mode';

"use strict";

//JetSmartFilters
window.JetSmartFilters = filtersInitializer;

// Init filters
$(document).ready(function () {
	window.JetSmartFilters.initializeFilters();
});

// If elementor
$(window).on('elementor/frontend/init', function () {
	// edit mode filters init
	if (elementorFrontend.isEditMode())
		elementorEditorMode.initFilters();
});

// Reinit filters events
$(window)
	.on('jet-popup/render-content/ajax/success', function (evt, popup) {
		window.JetSmartFilters.initializeFiltersInContainer($('#jet-popup-' + popup.popup_id));
	})
	.on('jet-tabs/ajax-load-template/after', function (evt, props) {
		window.JetSmartFilters.initializeFiltersInContainer(props.contentHolder);
	})
	.on('jet-blocks/ajax-load-template/after', function (evt, props) {
		window.JetSmartFilters.initializeFiltersInContainer(props.contentHolder);
	});

// Elementor pro popup
$(document).on('elementor/popup/show', (event, id, instance) => {
	window.JetSmartFilters.initializeFiltersInContainer(instance.$element);
});