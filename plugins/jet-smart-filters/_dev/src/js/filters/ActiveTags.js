import ActiveItems from 'bases/ActiveItems';

export default class ActiveTags extends ActiveItems {
	name = 'active-tags';

	constructor($activeTags) {
		const props = {
			separateMultiple: true,
			templateName: 'active_tag',
			listClass: 'jet-active-tags__list',
			labelClass: 'jet-active-tags__title',
			itemClass: 'jet-active-tag',
			clearClass: 'jet-active-tag jet-active-tag--clear'
		};

		super($activeTags, props);
	}
}