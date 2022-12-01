export default {
	isObject,
	notObject,
	objectSlice,
	clone,
	arrayMove,
	arrayRemoveByValue,
	arrayRemoveObjectByKey,
	mergeData,
	isNotEmpty,
	isEmpty,
	isEqual,
	someIsTrue,
	someIsFalse,
	allTrue,
	isValidUrl,
	isFunction,
	isNestingExist,
	setNesting,
	getNesting,
	getUrlParams,
	getProviderFilters,
	parseDate,
	convertDate,
	dateAddDay,
	dateAddMonth,
	dateAddYear,
	debounce
}

export function isObject(x) {
	return typeof x === 'object' && x !== null;
};

export function notObject(x) {
	return !isObject(x);
};

export function objectSlice(obj, key) {
	if (!obj.hasOwnProperty(key))
		return false;

	const keyValue = obj[key];
	delete obj[key];

	return keyValue;
};

export function clone(o) {
	let output, v, key;

	output = Array.isArray(o) ? [] : {};

	for (key in o) {
		v = o[key];
		output[key] = (typeof v === "object") ? clone(v) : v;
	}

	return output;
}

export function arrayMove(arr, startIndex, endIndex) {
	while (startIndex < 0) {
		startIndex += arr.length;
	}
	while (endIndex < 0) {
		endIndex += arr.length;
	}
	if (endIndex >= arr.length) {
		var k = endIndex - arr.length + 1;
		while (k--) {
			arr.push(undefined);
		}
	}

	arr.splice(endIndex, 0, arr.splice(startIndex, 1)[0]);

	return arr;
};

export function arrayRemoveByValue(array, val) {
	let index = array.indexOf(val);

	if (index > -1) {
		array.splice(index, 1);
	}
}

export function arrayRemoveObjectByKey(array, key, val) {
	let index = array.findIndex(o => o[key] === val);

	if (index > -1) {
		array.splice(index, 1);
	}

	return array;
}

export function mergeData() {
	const args = [...arguments];

	if (!args.length)
		return false;

	if (args.length === 1)
		return args[0];

	let outputData = [];

	args.forEach(arg => {
		outputData = outputData.concat(arg);
	});

	return [...new Set(outputData)];
}

export function isNotEmpty(obj) {
	switch (obj.constructor) {
		case Object:
			return Object.entries(obj).length ? true : false;
		case Array:
			return obj.length ? true : false;
	}

	return obj ? true : false;
}

export function isEmpty(obj) {
	return !isNotEmpty(obj);
}

export function someIsTrue(arr) {
	return arr.some(item => {
		return Boolean(item);
	});
}

export function someIsFalse(arr) {
	return arr.some(item => {
		return !Boolean(item);
	});
}

export function allTrue(arr) {
	return someIsFalse(arr) ? false : true;
}

export function isValidUrl(string) {
	try {
		new URL(string);
	} catch (_) {
		return false;
	}

	return true;
}

export function isFunction(variableToCheck) {
	return variableToCheck instanceof Function ? true : false;
}

export function isNestingExist(obj) {
	const nesting = Array.from(arguments).splice(1);
	let output = true;

	for (let key of nesting) {
		if (!obj[key]) {
			output = false
			break;
		}

		obj = obj[key];
	}

	return output;
}

export function setNesting(data, obj, nesting, props = {}) {
	if (!data)
		return;

	let iterationObj = obj;

	for (let index = 0; index < nesting.length; index++) {
		const iterationKey = nesting[index],
			isLast = index === nesting.length - 1 ? true : false;

		if (isLast) {
			if (iterationObj[iterationKey] && props.merge) {
				iterationObj[iterationKey] = mergeData(iterationObj[iterationKey], data);
			} else {
				iterationObj[iterationKey] = data;
			}
		} else {
			if (!iterationObj[iterationKey])
				iterationObj[iterationKey] = {};

			iterationObj = iterationObj[iterationKey];
		}
	}
}

export function getNesting(obj) {
	const nesting = Array.from(arguments).splice(1);
	let isNestingExist = true;

	for (let key of nesting) {
		if (!obj[key]) {
			isNestingExist = false
			break;
		}

		obj = obj[key];
	}

	return isNestingExist ? obj : false;
}

export function isEqual(value, other) {
	let type = Object.prototype.toString.call(value);

	if (type !== Object.prototype.toString.call(other)) {
		return false;
	}

	if (['[object Array]', '[object Object]'].indexOf(type) < 0) {
		return false;
	}

	let valueLen = type === '[object Array]' ? value.length : Object.keys(value).length,
		otherLen = type === '[object Array]' ? other.length : Object.keys(other).length;

	if (valueLen !== otherLen) {
		return false;
	}

	let compare = function (item1, item2) {
		let itemType = Object.prototype.toString.call(item1);

		if (['[object Array]', '[object Object]'].indexOf(itemType) >= 0) {
			if (!isEqual(item1, item2)) {
				return false;
			}
		} else {
			if (itemType !== Object.prototype.toString.call(item2)) {
				return false;
			}

			if (itemType === '[object Function]') {
				if (item1.toString() !== item2.toString()) {
					return false;
				}
			} else {
				if (item1 !== item2) {
					return false;
				}
			}
		}
	};

	if (type === '[object Array]') {
		for (let i = 0; i < valueLen; i++) {
			if (compare(value[i], other[i]) === false) {
				return false;
			}
		}
	} else {
		for (let key in value) {
			if (value.hasOwnProperty(key)) {
				if (compare(value[key], other[key]) === false) {
					return false;
				}
			}
		}
	}

	return true;
};

export function getProviderFilters(provider, queryId = 'default') {
	return getNesting(JetSmartFilters, 'filterGroups', provider + '/' + queryId, 'filters') || [];
}

export function getUrlParams() {
	const search = decodeURIComponent(window.location.search),
		hashes = search.slice(search.indexOf('?') + 1).split('&'),
		params = {};

	hashes.map(hash => {
		const [key, val] = hash.split('=');
		params[key] = val;
	})

	return params;
}

export function removeAllDefaultUrlParams(url) {
	const defaultParams = [
		'jsf=',
		'tax=',
		'meta=',
		'date=',
		'sort=',
		'alphabet=',
		'_s=',
		'pagenum=',
		// backward compatibility
		'jet-smart-filters=',
		'jet_paged=',
		'search=',
		'_tax_query_',
		'_meta_query_',
		'_date_query_',
		'_sort_',
		'__s_',
	];

	defaultParams.forEach(param => {
		const regex = new RegExp('[\?&]' + param + '[^&]+', 'g');

		url = url.replace(regex, '');
	});

	return url.replace(/^&/, '?').replace(/[\?&]$/, '');
}

export function parseDate(dateString, dateFormat = 'mm/dd/yy') {
	const output = {
		date: $.datepicker.parseDate(dateFormat, dateString),
		value: ''
	}

	output.value = convertDate(output.date) || '';

	return output;
}

export function convertDate(date) {
	if (!date || !date.getTime())
		return false;

	return date.getFullYear() + '.' + (date.getMonth() + 1) + '.' + date.getDate();
}

export function dateAddDay(date, days = 1) {
	date.setDate(date.getDate() + days);

	return date;
}

export function dateAddMonth(date, months = 1) {
	const d = date.getDate();

	date.setMonth(date.getMonth() + months);

	if (date.getDate() != d) {
		date.setDate(0);
	}

	return date;
}

export function dateAddYear(date, years = 1) {
	date.setFullYear(date.getFullYear() + years);

	return date;
}

export function debounce(callback, wait, immediate = false) {
	let timeout = null

	return function () {
		const callNow = immediate && !timeout
		const next = () => callback.apply(this, arguments)

		clearTimeout(timeout)
		timeout = setTimeout(next, wait)

		if (callNow) {
			next()
		}
	}
}