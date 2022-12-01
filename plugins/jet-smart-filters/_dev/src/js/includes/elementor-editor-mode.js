export default {
	initFilters() {
		const widgets = {
			'jet-smart-filters-checkboxes.default': this.checkboxes,
			'jet-smart-filters-radio.default': this.radio,
			'jet-smart-filters-range.default': this.range,
			'jet-smart-filters-date-range.default': this.dateRange,
			'jet-smart-filters-date-period.default': this.datePeriod
		};

		for (const widget in widgets) {
			const callback = widgets[widget];

			window.elementorFrontend.hooks.addAction('frontend/element_ready/' + widget, callback.bind(this));
		}
	},

	checkboxes($scope) {
		this.initFilter('CheckBoxes', $scope.find('.' + window.JetSmartFilters.filtersList.CheckBoxes));
	},

	radio($scope) {
		this.initFilter('Radio', $scope.find('.' + window.JetSmartFilters.filtersList.Radio));
	},

	range($scope) {
		this.initFilter('Range', $scope.find('.' + window.JetSmartFilters.filtersList.Range));
	},

	dateRange($scope) {
		this.initFilter('DateRange', $scope.find('.' + window.JetSmartFilters.filtersList.DateRange));
	},

	datePeriod($scope) {
		this.initFilter('DatePeriod', $scope.find('.' + window.JetSmartFilters.filtersList.DatePeriod));
	},

	initFilter(filterName, $selector) {
		if (!$selector.length)
			return;

		$selector.each(index => {
			const $item = $selector.eq(index);

			new window.JetSmartFilters.filters[filterName]($item);
		});
	}
};