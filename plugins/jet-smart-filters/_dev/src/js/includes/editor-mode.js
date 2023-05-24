export default {
	initFilter: function (filterName, $scope = $('body')) {
		switch (filterName) {
			case 'checkboxes':
				init('CheckBoxes');
				break;

			case 'check-range':
				init('CheckRange');
				break;

			case 'radio':
				init('Radio');
				break;

			case 'color-image':
				init('Visual');
				break;

			case 'range':
				init('Range');
				break;

			case 'date-range':
				init('DateRange');
				break;

			case 'date-period':
				init('DatePeriod');
				break;
		}

		function init(filterKey) {
			const $filters = $scope.find('.' + window.JetSmartFilters.filtersList[filterKey]);

			if (!$filters.length)
				return;

			$filters.each(index => {
				new window.JetSmartFilters.filters[filterKey]($filters.eq(index));
			});
		}
	},

	intiAllFilters: function ($scope = $('body')) {
		window.JetSmartFilters.filterNames.forEach(filterName => {
			this.initFilter(filterName, $scope);
		});
	}
};