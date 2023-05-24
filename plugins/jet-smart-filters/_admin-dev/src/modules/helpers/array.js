export function is(arr) {
	return Array.isArray(arr);
};

export function isEmpty(arr) {
	return arr.length ? false : true;
};

export function findByPropertyValue(arr, prop, val) {
	return arr.find(
		item => item.hasOwnProperty(prop) && item[prop] == val
	);
};

export function findIndexByPropertyValue(arr, prop, val) {
	if (!is(arr))
		return false;

	return arr.findIndex(
		item => item.hasOwnProperty(prop) && item[prop] == val
	);
};

export default {
	is,
	isEmpty,
	findByPropertyValue,
	findIndexByPropertyValue
};