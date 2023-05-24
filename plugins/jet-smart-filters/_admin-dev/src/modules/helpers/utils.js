export function clone(o) {
	if (typeof o !== 'object')
		return o;

	let output, v, key;

	output = Array.isArray(o) ? [] : {};

	for (key in o) {
		v = o[key];
		output[key] = (typeof v === 'object') ? clone(v) : v;
	}

	return output;
}

export function debounce(callback, wait, immediate = false) {
	let timeout = null;

	return function () {
		const callNow = immediate && !timeout;
		const next = () => callback.apply(this, arguments);

		clearTimeout(timeout);
		timeout = setTimeout(next, wait);

		if (callNow) {
			next();
		}
	};
}

export function stringToBoolean(string) {
	if (typeof string === 'boolean')
		return string;

	switch (string.toLowerCase().trim()) {
		case 'true':
		case 'yes':
		case '1':
			return true;

		case 'false':
		case 'no':
		case '0':
		case null:
			return false;

		default:
			return Boolean(string);
	}
}

export default {
	clone,
	debounce,
	stringToBoolean
};