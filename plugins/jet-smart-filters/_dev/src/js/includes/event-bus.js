export default {
	channels: {},

	subscribe(channelName, listener) {
		if (!this.channels[channelName]) {
			this.channels[channelName] = [];
		}
		this.channels[channelName].push(listener);
	},

	publish(channelName) {
		const channel = this.channels[channelName]
		if (!channel || !channel.length) {
			return;
		}

		channel.forEach(listener => listener(...Array.from(arguments).splice(1)));
	}
}