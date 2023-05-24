export function is(date) {
	return (new Date(date) !== "Invalid Date") && !isNaN(new Date(date));
};

export function isValid(date) {
	return date instanceof Date && !isNaN(date);
}

export function daysInMonth(month, year) {
	return new Date(year, month + 1, 0).getDate();
}

export default {
	is,
	isValid,
	daysInMonth
};