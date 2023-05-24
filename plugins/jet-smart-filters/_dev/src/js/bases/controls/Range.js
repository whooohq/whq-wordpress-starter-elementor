import Filter from 'bases/Filter';

export default class RangeControl extends Filter {

	constructor($container, $filter, $sliderInputMin, $sliderInputMax, $sliderValuesMin, $sliderValuesMax, $sliderTrackRange, $rangeInputMin, $rangeInputMax, prefix, suffix) {
		super($filter, $container);

		this.$sliderInputMin = $sliderInputMin || this.$filter.find('.jet-range__slider__input--min');
		this.$sliderInputMax = $sliderInputMax || this.$filter.find('.jet-range__slider__input--max');
		this.$sliderValuesMin = $sliderValuesMin || this.$filter.find('.jet-range__values-min');
		this.$sliderValuesMax = $sliderValuesMax || this.$filter.find('.jet-range__values-max');
		this.$sliderTrackRange = $sliderTrackRange || this.$filter.find('.jet-range__slider__track__range');
		this.$rangeInputMin = $rangeInputMin || this.$filter.find('.jet-range__inputs__min');
		this.$rangeInputMax = $rangeInputMax || this.$filter.find('.jet-range__inputs__max');
		this.$inputs = this.$sliderInputMin.add(this.$sliderInputMax).add(this.$rangeInputMin).add(this.$rangeInputMax);
		this.minConstraint = parseFloat(this.$sliderInputMin.attr('min'));
		this.maxConstraint = parseFloat(this.$sliderInputMax.attr('max'));
		this.step = parseFloat(this.$sliderInputMax.attr('step'));
		this.minVal = parseFloat(this.$sliderInputMin.val());
		this.maxVal = parseFloat(this.$sliderInputMax.val());
		this.prefix = prefix || this.$filter.find('.jet-range__values-prefix').first().text() || false;
		this.suffix = suffix || this.$filter.find('.jet-range__values-suffix').first().text() || false;
		this.format = this.$filter.data('format') || {
			'thousands_sep': '',
			'decimal_sep': '',
			'decimal_num': 0,
		};

		this.initSlider();
		this.processData();
		this.initEvent();
		this.valuesUpdated();
	}

	initSlider() {
		this.$filter.on('mousemove touchstart', this.findClosestRange.bind(this));

		this.$sliderInputMin.on('input', (event) => {
			this.minVal = parseFloat(this.$sliderInputMin.val());
			this.valuesUpdated('min');
		});
		this.$sliderInputMax.on('input', () => {
			this.maxVal = parseFloat(this.$sliderInputMax.val());
			this.valuesUpdated('max');
		});

		if (this.$rangeInputMin.length)
			this.$rangeInputMin.on('input keydown blur', (event) => {
				this.minVal = this.inputNumberRangeValidation(parseFloat(this.$rangeInputMin.val())) || this.minConstraint;

				if (event.type === 'blur' || event.keyCode === 13)
					this.valuesUpdated('min');
			});
		if (this.$rangeInputMax.length)
			this.$rangeInputMax.on('input keydown blur', (event) => {
				this.maxVal = this.inputNumberRangeValidation(parseFloat(this.$rangeInputMax.val())) || this.maxConstraint;

				if (event.type === 'blur' || event.keyCode === 13)
					this.valuesUpdated('max');
			});
	}

	addFilterChangeEvent() {
		this.$inputs.on('change', () => {
			this.processData();
			this.emitFiterChange();
		});
	}

	removeChangeEvent() {
		this.$filter.off();
		this.$inputs.off();
	}

	processData() {
		if (this.$rangeInputMin.length)
			this.$rangeInputMin.val(this.minVal);
		if (this.$rangeInputMax.length)
			this.$rangeInputMax.val(this.maxVal);

		// Prevent of adding slider defaults
		if (this.minVal == this.minConstraint && this.maxVal == this.maxConstraint) {
			this.dataValue = false;
			return;
		}

		this.dataValue = this.minVal + '_' + this.maxVal;
	}

	setData(newData) {
		this.reset();

		if (!newData)
			return;

		const data = newData.split('_');

		if (data[0]) {
			this.minVal = parseFloat(data[0]);
			this.$sliderInputMin.val(this.minVal);
		}
		if (data[1]) {
			this.maxVal = parseFloat(data[1]);
			this.$sliderInputMax.val(this.maxVal);
		}

		this.valuesUpdated();
		this.processData();
	}

	reset() {
		this.dataValue = false;
		this.minVal = this.minConstraint;
		this.maxVal = this.maxConstraint;
		this.$sliderInputMin.val(this.minVal);
		this.$sliderInputMax.val(this.maxVal);

		this.valuesUpdated();
		this.processData();
	}

	findClosestRange(event) {
		const bounds = event.target.getBoundingClientRect(),
			clientX = event.clientX || event.touches[0].clientX,
			x = clientX - bounds.left,
			width = parseFloat(this.$sliderInputMax.width()),
			minValue = parseFloat(this.$sliderInputMin.val()),
			maxValue = parseFloat(this.$sliderInputMax.val());

		const averageValue = (maxValue + minValue) / 2,
			hoverValue = this.isRTL
				? (this.minConstraint - this.maxConstraint) * (x / width) + this.maxConstraint
				: (this.maxConstraint - this.minConstraint) * (x / width) + this.minConstraint;

		if (hoverValue > averageValue) {
			this.swapInput('max');
		} else {
			this.swapInput('min');
		}
	}

	swapInput(inputType) {
		switch (inputType) {
			case 'min':
				this.$sliderInputMin.css('z-index', 21);
				this.$sliderInputMax.css('z-index', 20);

				break;

			case 'max':
				this.$sliderInputMin.css('z-index', 20);
				this.$sliderInputMax.css('z-index', 21);

				break;
		}
	}

	valuesUpdated(inputType = false) {
		switch (inputType) {
			case 'min':
				if (this.minVal > this.maxVal - this.step)
					this.minVal = this.maxVal - this.step;

				this.$sliderInputMin.val(this.minVal);
				this.$rangeInputMin.val(this.minVal);

				break;

			case 'max':
				if (this.maxVal < this.minVal + this.step)
					this.maxVal = this.minVal + this.step;

				this.$sliderInputMax.val(this.maxVal);
				this.$rangeInputMax.val(this.maxVal);

				break;
		}

		if (this.$sliderValuesMin.length)
			this.$sliderValuesMin.html(this.getFormattedData(this.minVal));
		if (this.$sliderValuesMax.length)
			this.$sliderValuesMax.html(this.getFormattedData(this.maxVal));

		const low = 100 * ((this.minVal - this.minConstraint) / (this.maxConstraint - this.minConstraint)),
			high = 100 * ((this.maxVal - this.minConstraint) / (this.maxConstraint - this.minConstraint));

		this.$sliderTrackRange.css({
			'--low': low + '%',
			'--high': high + '%'
		});
	}

	inputNumberRangeValidation(val) {
		if (val < this.minConstraint)
			return this.minConstraint;

		if (val > this.maxConstraint)
			return this.maxConstraint;

		return val;
	}

	getFormattedData(data) {
		var re = '\\d(?=(\\d{' + (3 || 3) + '})+' + (this.format.decimal_num > 0 ? '\\D' : '$') + ')',
			num = data.toFixed(Math.max(0, ~~this.format.decimal_num));

		return (this.format.decimal_sep ? num.replace('.', this.format.decimal_sep) : num).replace(new RegExp(re, 'g'), '$&' + (this.format.thousands_sep || ''));
	}

	get activeValue() {
		if (typeof this.dataValue === 'string') {
			const data = this.dataValue.split('_');
			let value = '';

			if (data[0]) {
				if (this.prefix)
					value += this.prefix;

				value += this.getFormattedData(parseFloat(data[0]));

				if (this.suffix)
					value += this.suffix;

				if (data[1])
					value += ' — ';
			}

			if (data[1]) {
				if (this.prefix)
					value += this.prefix;

				value += this.getFormattedData(parseFloat(data[1]));

				if (this.suffix)
					value += this.suffix;
			}

			return value;
		} else {
			return this.dataValue;
		}
	}
}
