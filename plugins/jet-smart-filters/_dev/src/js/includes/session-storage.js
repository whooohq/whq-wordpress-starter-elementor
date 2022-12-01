export default {
	set(name, data) {
		if (typeof (Storage) === 'undefined')
			return;

		sessionStorage.setItem(name, JSON.stringify(data));
	},

	get(name, del = false) {
		if (typeof (Storage) === 'undefined')
			return false;

		const data = sessionStorage.getItem(name);

		if (!data)
			return false;

		if (del)
			sessionStorage.removeItem(name);

		return JSON.parse(data);
	}
}