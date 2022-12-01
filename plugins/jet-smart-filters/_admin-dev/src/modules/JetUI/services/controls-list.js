import _object from "@/modules/helpers/object.js";

export const controlsComponentsList = {
	checkboxes: 'Checkboxes',
	radio: 'Radio',
	select: 'Select',
	text: 'Text',
	textarea: 'Textarea',
	switcher: 'Switcher',
	number: 'Number',
	colorpicker: 'Colorpicker',
	media: 'Media',
	repeater: 'Repeater',
	advanced_input: 'AdvancedInput',
	type_selector: 'TypeSelector',
	html: 'HTML'
};

export function controlsVisibilityCheck(controls) {
	let controlsVisibilityWasChanged;
	let iterationLimiter = 0;

	do {
		controlsVisibilityWasChanged = false;
		iterationLimiter++;

		for (const controlKey in controls) {
			const control = controls[controlKey];

			if (!control.conditions || (control.hasOwnProperty('hidden') && !control.hidden))
				continue;

			let isHidden = false;

			for (let conditionControlKey in control.conditions) {
				const condition = control.conditions[conditionControlKey];

				// If not equal operator
				let negation = false;
				if (conditionControlKey.slice(-1) === '!') {
					negation = true;
					conditionControlKey = conditionControlKey.slice(0, -1);
				}

				const conditionControl = controls[conditionControlKey];

				if (conditionControl && !conditionControl.hidden) {
					if (Array.isArray(condition)) {
						if (!condition.includes(conditionControl.value))
							isHidden = true;
					} else {
						if (condition !== 'is_visible' && condition !== conditionControl.value)
							isHidden = true;
					}
				} else {
					if (condition !== 'is_hidden')
						isHidden = true;
				}

				if (negation)
					isHidden = !isHidden;

				if (isHidden)
					break;
			}

			if (Boolean(control.hidden) !== isHidden) {
				if (isHidden)
					control.hidden = true;

				if (!isHidden)
					delete control.hidden;

				controlsVisibilityWasChanged = true;
			};
		}

		if (iterationLimiter > 30) {
			controlsVisibilityWasChanged = false;
			console.error('!!! Iteration limit !!!');
		}
	} while (controlsVisibilityWasChanged);

	return controls;
};

export function getVisibleСontrols(controls) {
	const output = {};

	for (const controlKey in controls)
		if (!controls[controlKey].hidden)
			output[controlKey] = controls[controlKey];

	return output;
}

export function getControlName(control) {
	if (!control.type) {
		console.error('Сontrol type not specified', control);

		return null;
	}

	if (!controlsComponentsList[control.type]) {
		console.error('"' + control.type + '" is a non-existent type of control');

		return null;
	}

	return controlsComponentsList[control.type];
};

export function getControlValue(control) {
	return control.value;
};

export function getControlAttrs(control) {
	const attrs = {};

	for (const attrKey in control) {
		if (['type', 'title', 'description', 'value', 'fullwidth', 'conditions'].includes(attrKey))
			continue;

		attrs[attrKey] = control[attrKey];
	}

	return attrs;
};

export function getControlsRequiredNotFilled(controls) {
	const controlsRequiredNotFilled = [];

	for (const key in controls)
		if (isControlRequiredNotFilled(controls[key]))
			controlsRequiredNotFilled.push(key);

	return controlsRequiredNotFilled;
}

export function isControlRequiredNotFilled(control) {
	if (!control.hasOwnProperty('required'))
		return false;

	return !getControlValue(control);
}

export function controlsPreparation(incomingControls) {
	const controls = {};

	for (const controlKey in incomingControls)
		controls[controlKey] = controlPreparation(incomingControls[controlKey]);

	return controls;
}

export function controlPreparation(control) {
	setControlDefaultValue(control);
	parseControlOptions(control);

	return control;
}

export function setControlDefaultValue(control) {
	if (control.hasOwnProperty('value'))
		return;

	switch (control.type) {
		case 'text':
		case 'textarea':
		case 'select':
		case 'number':
		case 'media':
		case 'colorpicker':
		case 'advanced_input':
		case 'type_selector':
			control.value = '';
			break;

		case 'checkboxes':
		case 'repeater':
			control.value = [];
			break;

		case 'switcher':
			control.value = false;
			break;

		default:
			control.value = null;
			break;
	}
};

export function parseControlOptions(control) {
	if (!control.hasOwnProperty('options') || !_object.is(control.options))
		return;

	control.options = parseOptions(control.options);
}

export function parseOptions(options) {
	const parsedOptions = [];

	for (const optionKey in options) {
		const option = options[optionKey];
		const parsedOption = {
			value: optionKey
		};

		parsedOption[typeof option === 'object' ? 'data' : 'label'] = option;
		parsedOptions.push(parsedOption);
	}

	return parsedOptions;
}

export default {
	controlsComponentsList,
	controlsVisibilityCheck,
	getVisibleСontrols,
	getControlName,
	getControlValue,
	getControlAttrs,
	getControlsRequiredNotFilled,
	isControlRequiredNotFilled,
	controlsPreparation,
	controlPreparation,
	setControlDefaultValue,
	parseControlOptions,
	parseOptions,
};