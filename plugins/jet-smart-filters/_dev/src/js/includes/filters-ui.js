import { getNesting, parseDate } from 'includes/utility';

const filtersUI = {
	datePicker: props => {
		const {
			$input,
			id = false,
			datepickerOptions = false
		} = props,
			weekStart = getNesting(JetSmartFilterSettings, 'misc', 'week_start') || 1,
			texts = getNesting(JetSmartFilterSettings, 'datePickerData'),
			defaultOptions = {
				dateFormat: 'mm/dd/yy',
				closeText: texts.closeText,
				prevText: texts.prevText,
				nextText: texts.nextText,
				currentText: texts.currentText,
				monthNames: texts.monthNames,
				monthNamesShort: texts.monthNamesShort,
				dayNames: texts.dayNames,
				dayNamesShort: texts.dayNamesShort,
				dayNamesMin: texts.dayNamesMin,
				weekHeader: texts.weekHeader,
				firstDay: parseInt(weekStart, 10),
				beforeShow: function (textbox, instance) {
					if (id) {
						const $calendar = instance.dpDiv;

						$calendar.addClass('jet-smart-filters-datepicker-' + id);
					}
				}
			};

		return $input.datepicker(datepickerOptions ? Object.assign(defaultOptions, datepickerOptions) : defaultOptions);
	},

	dateRange: {
		inputSelector: '.jet-date-range__input',
		submitSelector: '.jet-date-range__submit',
		fromSelector: '.jet-date-range__from',
		toSelector: '.jet-date-range__to',
		init: props => {
			const {
				id = false,
				$container = false,
				$dateRangeInput = $dateRangeInput || $container.find(filtersUI.dateRange.inputSelector),
				$dateRangeFrom = $dateRangeFrom || $container.find(filtersUI.dateRange.fromSelector),
				$dateRangeTo = $dateRangeTo || $container.find(filtersUI.dateRange.toSelector)
			} = props,
				dateFormat = $dateRangeInput.data('date-format') || 'mm/dd/yy';

			const from = filtersUI.datePicker({
				$input: $dateRangeFrom,
				id,
				datepickerOptions: {
					//defaultDate: '+1w',
					dateFormat
				}
			}).on('change', () => {
				const fromDate = parseDate($dateRangeFrom.val(), dateFormat),
					toDate = parseDate($dateRangeTo.val(), dateFormat);

				if (fromDate.value || toDate.value) {
					$dateRangeInput.val(fromDate.value + '-' + toDate.value);
				} else {
					$dateRangeInput.val('');
				}

				to.datepicker('option', 'minDate', fromDate.date);
			});

			const to = filtersUI.datePicker({
				$input: $dateRangeTo,
				id,
				datepickerOptions: {
					//defaultDate: '+1w',
					dateFormat
				}
			}).on('change', () => {
				const fromDate = parseDate($dateRangeFrom.val(), dateFormat),
					toDate = parseDate($dateRangeTo.val(), dateFormat);

				if (fromDate.value || toDate.value) {
					$dateRangeInput.val(fromDate.value + '-' + toDate.value);
				} else {
					$dateRangeInput.val('');
				}

				from.datepicker('option', 'maxDate', toDate.date);
			});
		}
	}
};

export default filtersUI;
