import Filter from 'bases/Filter';

export default class ButtonApply extends Filter {
	name = 'button-apply';

	constructor($container) {
		const $filter = $container.find('.apply-filters');

		super($filter, $container);

		this.$filter.find('.apply-filters__button').on('click', () => {
			this.emitFitersApply();
		})
	}
}