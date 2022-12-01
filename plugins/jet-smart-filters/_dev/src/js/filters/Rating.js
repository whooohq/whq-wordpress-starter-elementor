import RatingControl from 'bases/controls/Rating';

export default class Rating extends RatingControl {
	name = 'rating';

	constructor ($container) {
		const $filter = $container.find('.jet-rating');

		super($container, $filter);
	}
}