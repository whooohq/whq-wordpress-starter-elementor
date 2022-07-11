import {
	getNesting
} from 'includes/utility';

export default class CustomProvider {
	constructor(filterGroup) {
		this.filterGroup = filterGroup;

		// jetEngine Calendar add current query to request
		$(document).on('jet-engine-request-calendar', () => {
			const currentRequest = getNesting(JetEngine, 'currentRequest');

			if (!currentRequest || this.filterGroup.provider !== 'jet-engine-calendar')
				return;

			currentRequest.query = this.filterGroup.currentQuery;
		});
	}
}