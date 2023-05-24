// List of popups with removed checked attributes
const removedCheckedAttributePopups = [];

window.addEventListener('elementor/popup/hide', event => {
	const id = event.detail.id;
	const instance = event.detail.instance;

	// Remove checked attribute from filters in popup
	if (!removedCheckedAttributePopups.includes(id)) {
		removedCheckedAttributePopups.push(id);

		const $checkedItems = instance.$element.find('.jet-filter input[type="checkbox"][checked]');

		if (!$checkedItems.length)
			return;

		$checkedItems.removeAttr('checked');
		instance.elementHTML = instance.$element.prop('outerHTML');
	}
});