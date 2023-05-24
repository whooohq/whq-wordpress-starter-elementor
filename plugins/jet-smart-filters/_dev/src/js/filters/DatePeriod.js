import Filter from 'bases/Filter';
import { getNesting, isObject, convertDate, dateAddDay, debounce } from 'includes/utility';

export default class DatePeriod extends Filter {
	name = 'date-period';
	datepickerButtonSelector = '.jet-date-period__datepicker-button';
	datepickerInputSelector = '.jet-date-period__datepicker-input';
	prevPeriodButtonSelector = '.jet-date-period__prev';
	nextPeriodButtonSelector = '.jet-date-period__next';
	datepickerOpenedClass = 'jet-date-period-datepicker-opened';
	periodIsSetClass = 'jet-date-period-is-set';
	periodStartClass = 'jet-date-period-start';
	periodSeparatorClass = 'jet-date-period-separator';
	periodEndClass = 'jet-date-period-end';

	constructor($container) {
		const $filter = $container.find('.jet-date-period');

		super($filter, $container);

		this.datePeriod = [];

		this.id = this.$filter.closest('.elementor-widget-jet-smart-filters-date-period').data('id') || this.$filter.closest('.brxe-jet-smart-filters-date-period').attr('id');
		this.$datepickerBtn = $filter.find(this.datepickerButtonSelector);
		this.$prevPeriodBtn = $filter.find(this.prevPeriodButtonSelector);
		this.$nextPeriodBtn = $filter.find(this.nextPeriodButtonSelector);
		this.$datepickerInput = $filter.find(this.datepickerInputSelector);
		this.dateFormat = this.$datepickerInput.data('format');
		this.minDate = this.parseDate(this.$datepickerInput.data('mindate'));
		this.maxDate = this.parseDate(this.$datepickerInput.data('maxdate'));
		this.startEndDateEnabled = isObject(this.dateFormat) ? true : false;
		this.dateSeparator = this.startEndDateEnabled && this.dateFormat.separator ? ' ' + this.dateFormat.separator + ' ' : ' - ';
		this.periodType = this.$filter.data('period-type') || 'day';
		this.btnPlaceholder = this.$datepickerBtn.html();

		this.$datepickerInput.prop('type', 'text');

		this.debounceInitDatepickerWeekHover = debounce(this.initDatepickerWeekHover, 100);

		this.initDatepicker();
		this.initEvent();
		this.processData();
	}

	initDatepicker() {
		const datepickerOptions = {
			language: 'jsf',
			dateFormat: 'yy/m/d',
			autoClose: true,
			position: 'bottom left',
			offset: 0,
			view: 'days',
			minView: 'days',
			firstDay: Number(getNesting(JetSmartFilterSettings, 'misc', 'week_start'))
		};

		// min max date enabled
		if (this.minDate)
			datepickerOptions.minDate = this.minDate;

		if (this.maxDate)
			datepickerOptions.maxDate = this.maxDate;

		// add localization
		if (!$.fn.airDatepicker.language['jsf']) {
			const localizedText = getNesting(JetSmartFilterSettings, 'datePickerData');

			$.fn.airDatepicker.language['jsf'] = {
				days: localizedText.dayNames,
				daysShort: localizedText.dayNamesShort,
				daysMin: localizedText.dayNamesMin,
				months: localizedText.monthNames,
				monthsShort: localizedText.monthNamesShort,
				today: localizedText.currentText,
				clear: localizedText.closeText
			};
		}

		// datepicker on select
		datepickerOptions.onSelect = (formattedDate, date, inst) => {
			if (!date)
				return;

			let startDate = date,
				endDate;

			switch (this.periodType) {
				case 'week':
					const dayOffset = inst.opts.firstDay > date.getDay() ? inst.opts.firstDay - 7 : inst.opts.firstDay;

					startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + dayOffset);
					endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 6 + dayOffset);

					break;

				case 'month':
					endDate = new Date(date.getFullYear(), date.getMonth() + 1, 0);

					break;

				case 'year':
					endDate = new Date(date.getFullYear(), 11, 31);

					break;

				case 'range':
					if (!Array.isArray(date) || date.length < 2)
						return;

					startDate = date[0];
					endDate = date[1];

					if ((!this.minDate || this.minDate < startDate) && (!this.maxDate || this.maxDate > endDate))
						this.periodCustomRange = Math.round(Math.abs((endDate - startDate) / (24 * 60 * 60 * 1000)));

					break;

				default:
					this.$datepickerInput.val(convertDate(date)).trigger('change');

					return;
			}

			if (this.minDate && this.minDate > startDate)
				startDate = this.minDate;

			if (this.maxDate && this.maxDate < endDate)
				endDate = this.maxDate;

			this.$datepickerInput.val(convertDate(startDate) + '-' + convertDate(endDate)).trigger('change');
		};

		// datepicker on show
		datepickerOptions.onShow = inst => {
			if (this.id)
				inst.$datepicker.addClass('jet-date-period-' + this.id);

			this.$filter.addClass(this.datepickerOpenedClass);
			inst.$datepicker.addClass('jet-date-period-' + this.periodType);
		};

		// datepicker on hide
		datepickerOptions.onHide = inst => {
			if (this.id)
				inst.$datepicker.removeClass('jet-date-period-' + this.id);

			this.$filter.removeClass(this.datepickerOpenedClass);
			inst.$datepicker.removeClass('jet-date-period-' + this.periodType);
		};

		// datepicker on render cell
		datepickerOptions.onRenderCell = (date, cellType) => {
			// set active week period and add week hover
			if (this.periodType === 'week' && cellType === 'day') {
				this.debounceInitDatepickerWeekHover();

				if (this.isDateInRange(date)) {
					let classes = '-week-selected-';

					if (this.isDateFirstInRange(date))
						classes += ' -week-start-selected-';
					if (this.isDateLastInRange(date))
						classes += ' -week-end-selected-';

					return {
						classes
					};
				}
			}
		};

		if (this.periodType === 'month') {
			datepickerOptions.view = 'months';
			datepickerOptions.minView = 'months';
		}
		if (this.periodType === 'year') {
			datepickerOptions.view = 'years';
			datepickerOptions.minView = 'years';
		}

		if (this.periodType === 'range')
			datepickerOptions.range = true;

		/* if (this.isRTL)
			datepickerOptions.position = 'bottom right'; */

		// init air datepicker
		this.$datepicker = this.$datepickerInput.airDatepicker(datepickerOptions);
		this.datepicker = this.$datepicker.data('datepicker');

		// clear events to avoid duplication
		this.$datepickerBtn.off('click');
		this.$prevPeriodBtn.off('click');
		this.$nextPeriodBtn.off('click');
		this.$nextPeriodBtn.off('click');
		this.$datepickerInput.off('change');

		// init events
		this.$datepickerBtn.on('click', () => {
			this.datepicker.show();
		});

		this.$prevPeriodBtn.on('click', () => {
			this.prevPeriod();
		});

		this.$nextPeriodBtn.on('click', () => {
			this.nextPeriod();
		});

		this.$datepickerInput.on('change', () => {
			this.processData();
		});
	}

	addFilterChangeEvent() {
		this.$prevPeriodBtn.on('click', () => {
			this.emitFiterChange();
		});

		this.$nextPeriodBtn.on('click', () => {
			this.emitFiterChange();
		});

		this.$datepickerInput.on('change', () => {
			this.emitFiterChange();
		});
	}

	removeChangeEvent() {
		this.$datepickerBtn.off();
		this.$prevPeriodBtn.off();
		this.$nextPeriodBtn.off();
		this.$datepickerInput.off();
	}

	processData() {
		this.setPeriod();
		this.dataValue = this.$datepickerInput.val() || false;
	}

	setData(newData) {
		this.$datepickerInput.val(newData);
		this.processData();

		if (!this.datePeriod.length)
			return;

		const newDate = this.periodType === 'range' && this.datePeriod.length === 2
			? [this.datePeriod[0].date, this.datePeriod[1].date]
			: this.datePeriod[0].date;

		this.datepicker.selectDate(newDate);
	}

	reset() {
		this.$datepickerInput.val('');
		this.processData();
		this.datepicker.clear();
		this.datepicker.date = new Date();
	}

	get activeValue() {
		const periodStartDate = getNesting(this.datePeriod, '0', 'date'),
			periodStartFormatted = periodStartDate ? this.getFormattedDate(periodStartDate, 'start') : false,
			periodEndDate = this.startEndDateEnabled ? getNesting(this.datePeriod, '1', 'date') : false,
			periodEndFormatted = periodEndDate ? this.getFormattedDate(periodEndDate, 'end') : false;

		if (periodStartFormatted && periodEndFormatted) {
			return periodStartFormatted + this.dateSeparator + periodEndFormatted;
		} else {
			return periodStartFormatted;
		}
	}

	setPeriod() {
		const inputValue = this.$datepickerInput.val(),
			datesArray = [];

		if (inputValue)
			inputValue.split('-', 2).forEach(dateValue => {
				datesArray.push(dateValue);
			});

		this.datePeriod = [];

		datesArray.forEach(dateValue => {
			const date = new Date(dateValue.replaceAll('.', '/'));

			if (!(date instanceof Date))
				return;

			this.datePeriod.push({
				date,
				value: dateValue
			});
		});

		this.renderPeriod();
	}

	prevPeriod() {
		const periodStart = this.datePeriod[0] || false;

		if (!periodStart || (this.minDate && this.minDate >= periodStart.date))
			return;

		const newPeriodEnd = dateAddDay(periodStart.date, -1);
		let newPeriodStart = false;

		if (this.periodType === 'week') {
			newPeriodStart = dateAddDay(newPeriodEnd, -6);
		} else if (this.periodType === 'month') {
			newPeriodStart = new Date(newPeriodEnd.getFullYear(), newPeriodEnd.getMonth(), 1);
		} else if (this.periodType === 'year') {
			newPeriodStart = new Date(newPeriodEnd.getFullYear(), 0, 1);
		} else if (this.periodType === 'range') {
			newPeriodStart = dateAddDay(new Date(newPeriodEnd.getTime()), - this.periodCustomRange);
		}

		if (this.minDate && newPeriodStart < this.minDate)
			newPeriodStart = this.minDate;

		this.datepicker.selectDate(this.periodType === 'range' ? [newPeriodStart, newPeriodEnd] : newPeriodStart);
	}

	nextPeriod() {
		const periodEnd = this.datePeriod[1] || this.datePeriod[0] || false;

		if (!periodEnd || (this.maxDate && this.maxDate <= periodEnd.date))
			return;

		const newPeriodStart = dateAddDay(periodEnd.date);
		let newPeriodEnd = false;

		if (this.periodType === 'week') {
			newPeriodEnd = dateAddDay(new Date(newPeriodStart.getTime()), 6);
		} else if (this.periodType === 'month') {
			newPeriodEnd = new Date(newPeriodStart.getFullYear(), newPeriodStart.getMonth() + 1, 0);
		} else if (this.periodType === 'year') {
			newPeriodEnd = new Date(newPeriodStart.getFullYear(), 11, 31);
		} else if (this.periodType === 'range') {
			newPeriodEnd = dateAddDay(new Date(newPeriodStart.getTime()), this.periodCustomRange);
		}

		if (this.maxDate && newPeriodEnd > this.maxDate)
			newPeriodEnd = this.maxDate;

		this.datepicker.selectDate(this.periodType === 'range' ? [newPeriodStart, newPeriodEnd] : newPeriodStart);
	}

	renderPeriod() {
		if (!this.datePeriod.length) {
			this.$filter.removeClass(this.periodIsSetClass);
			this.$datepickerBtn.html(this.btnPlaceholder);

			return;
		}

		const periodStartDate = getNesting(this.datePeriod, '0', 'date'),
			periodStartHtml = periodStartDate ? `<div class="${this.periodStartClass}">${this.getFormattedDate(periodStartDate, 'start')}</div>` : '',
			periodEndDate = this.startEndDateEnabled ? getNesting(this.datePeriod, '1', 'date') : false,
			periodEndHtml = periodEndDate ? `<div class="${this.periodEndClass}">${this.getFormattedDate(periodEndDate, 'end')}</div>` : '',
			periodSeparatorHtml = periodStartDate && periodEndDate ? `<div class="${this.periodSeparatorClass}">${this.dateSeparator}</div>` : '';

		this.$filter.addClass(this.periodIsSetClass);
		this.$datepickerBtn.html(periodStartHtml + periodSeparatorHtml + periodEndHtml);
	}

	getFormattedDate(date, position = false) {
		let format = 'mm/dd/yy';

		if (this.dateFormat) {
			if (this.startEndDateEnabled) {
				if ((position === 'start' || !position) && this.dateFormat.start) format = this.dateFormat.start;
				if (position === 'end' && this.dateFormat.end) format = this.dateFormat.end;
			} else {
				format = this.dateFormat;
			}
		}

		return this.datepicker.formatDate(format, date);
	}

	parseDate(date) {
		if (!date)
			return false;

		if (date === 'today') {
			date = new Date();
		} else {
			date = new Date(date);
		}

		if (isNaN(date))
			return false;

		date.setHours(0, 0, 0, 0);

		return date;
	}

	isDateInRange(date) {
		if (!(date instanceof Date) || this.datePeriod.length < 2)
			return false;

		const datestamp = date.getTime(),
			startDatestamp = this.datePeriod[0].date.getTime(),
			endDatestamp = this.datePeriod[1].date.getTime();

		return datestamp >= startDatestamp && datestamp <= endDatestamp ? true : false;
	}

	isDateFirstInRange(date) {
		if (!(date instanceof Date) || !this.datePeriod[0])
			return false;

		const datestamp = date.getTime(),
			startDatestamp = this.datePeriod[0].date.getTime();

		return datestamp === startDatestamp ? true : false;
	}

	isDateLastInRange(date) {
		if (!(date instanceof Date) || !this.datePeriod[1])
			return false;

		const datestamp = date.getTime(),
			endDatestamp = this.datePeriod[1].date.getTime();

		return datestamp === endDatestamp ? true : false;
	}

	initDatepickerWeekHover() {
		const $days = this.datepicker.$content.find('.datepicker--cells-days .datepicker--cell-day');
		let weekHoveredDays = [];

		$days.off().on({
			mouseenter: evt => {
				const dayNumber = [...evt.target.parentNode.children].indexOf(evt.target);

				if (dayNumber < 0)
					return;

				for (let index = 0; index < 7; index++) {
					const $day = $days.eq(index + Math.floor(dayNumber / 7) * 7);

					$day.addClass('-week-hover-');
					if (index === 0) $day.addClass('-week-start-hover-');
					if (index === 6) $day.addClass('-week-end-hover-');

					weekHoveredDays.push($day);
				}
			},
			mouseleave: evt => {
				weekHoveredDays.forEach($day => {
					$day.removeClass('-week-hover- -week-start-hover- -week-end-hover-');
				});

				weekHoveredDays = [];
			}
		});
	}
}