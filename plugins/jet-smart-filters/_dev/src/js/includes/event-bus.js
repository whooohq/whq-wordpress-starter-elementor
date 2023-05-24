export default {
	channels: {},

	subscribe(channelName, listener, addToEnd = false) {
		if (!this.channels[channelName]) {
			this.channels[channelName] = [];
		}

		this.channels[channelName][addToEnd ? 'push' : 'unshift'](listener);
	},

	publish(channelName) {
		const channel = this.channels[channelName];

		if (!channel || !channel.length)
			return;

		channel.forEach(listener => listener(...Array.from(arguments).splice(1)));
	}
};