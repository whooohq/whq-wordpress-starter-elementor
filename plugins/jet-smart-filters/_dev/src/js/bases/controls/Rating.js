import Filter from 'bases/Filter';

export default class RatingControl extends Filter {
	starsRatingSelector = '.jet-rating-star__input';

	constructor($container, $filter, $starsRating) {
		super($filter, $container);

		this.$starsRating = $starsRating || $filter.find(this.starsRatingSelector);

		this.processData();
		this.initEvent();
	}

	initEvent() {
		this.$starsRating.off('click');

		this.$starsRating.on('click', (evt) => {
			const $starItem = $(evt.target);

			if ($starItem.hasClass('is-checked')) {
				this.$starsRating.prop('checked', false).removeClass('is-checked');
			} else {
				this.$starsRating.removeClass('is-checked');
				$starItem.addClass('is-checked');
			}
		});

		if (!this.isReloadType) {
			this.$starsRating.on('click', (evt) => {
				this.processData();
				this.emitFiterChange();
			});
		} else {
			this.addApplyEvent();
		}
	}

	removeChangeEvent() {
		this.$starsRating.off();
	}

	processData() {
		this.dataValue = this.$checked.val() || false;
	}

	setData(newData) {
		this.$checked.removeClass('is-checked');
		this.$starsRating.filter('[value="' + newData + '"]').addClass('is-checked');

		this.processData();
	}

	reset() {
		this.dataValue = false;
		this.$starsRating.prop('checked', false).removeClass('is-checked');
	}

	get activeValue() {
		const activeValue = this.dataValue || '0',
			total = this.$starsRating.length;

		return activeValue + '/' + total;
	}

	get $checked() {
		return this.$starsRating.filter('.is-checked');
	}
}