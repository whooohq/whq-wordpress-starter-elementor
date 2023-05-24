export function is(obj) {
	return typeof obj === 'object' && !Array.isArray(obj) && obj !== null;
};

export function isEmpty(obj) {
	return Object.entries(obj).length ? false : true;
};

export function filterByValue(obj, value) {
	const filteredObject = {};

	for (const key in obj) {
		const keyValue = obj[key];

		if (keyValue === value)
			filteredObject[key] = keyValue;
	}

	return filteredObject;
}

export function toArray(obj) {
	return Object.entries(obj).map(entry => entry[1]);
}

export function toArrayOfObjects(obj, key = 'key', value = 'value') {
	return Object.entries(obj).map(entry => ({
		[key]: entry[0],
		[value]: entry[1]
	}));
}

export default {
	is,
	isEmpty,
	filterByValue,
	toArray,
	toArrayOfObjects
};