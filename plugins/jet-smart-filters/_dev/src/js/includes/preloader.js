import eventBus from 'includes/event-bus';

const preloader = {
	init() {
		this.subscribers = [];

		// Event subscriptions
		eventBus.subscribe('ajaxFilters/start-loading', (provider, queryId) => {
			this.action(this.currentElements(provider, queryId), 'show');
		});
		eventBus.subscribe('ajaxFilters/end-loading', (provider, queryId) => {
			this.action(this.currentElements(provider, queryId), 'hide');
		});
	},

	subscribe(target, props) {
		const {
			provider = false,
			queryId = 'default',
			preloaderClass = 'jet-filters-loading'
		} = props;

		if (!provider)
			return;

		this.subscribers.push({
			target,
			provider,
			queryId,
			preloaderClass
		});
	},

	action(elements, action) {
		elements.forEach(element => {
			const {
				target,
				preloaderClass
			} = element;

			const $el = target instanceof jQuery ? target : $(target);

			switch (action) {
				case 'show':
					$el.addClass(preloaderClass);
					break;

				case 'hide':
					$el.removeClass(preloaderClass);

					break;
			}
		});
	},

	currentElements(provider, queryId) {
		return this.subscribers.filter(element => {
			return element.provider === provider && element.queryId === queryId;
		});
	},
}

export default preloader;