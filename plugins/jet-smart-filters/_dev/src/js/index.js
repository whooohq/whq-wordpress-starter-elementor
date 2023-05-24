"use strict";

// Init JetSamrtFilters
import JSF from './filters-initializer';

// Includes
import editorMode from 'includes/editor-mode.js';

$(document).ready(function () {
	window.JetPlugins.init(false, JSF.filterNames.map(filterName => {
		return {
			block: 'jet-smart-filters/' + filterName,
			callback: $scope => {
				JSF.initFilter($scope);
			}
		};
	}));
});

// If elementor
$(window).on('elementor/frontend/init', function () {
	JSF.filterNames.forEach(filterName => {
		elementorFrontend.hooks.addAction('frontend/element_ready/jet-smart-filters-' + filterName + '.default', function ($scope) {
			if (elementorFrontend.isEditMode()) {
				// init filter in editor
				editorMode.initFilter(filterName, $scope);
			} else {
				// init filter
				const $filters = $scope.find('.jet-filter');

				if (!$filters.length)
					return;

				$filters.each(index => {
					JSF.initFilter($filters.eq(index));
				});
			}
		});
	});
});

// If bricks
window.JetSmartFiltersBricksInit = function () {
	// init filter in editor
	if (!window.bricksIsFrontend)
		editorMode.intiAllFilters();
};

// Extensions
import './extensions';