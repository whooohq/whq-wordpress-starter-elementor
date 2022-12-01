// Remove checked attribute from filters in popup
const processedPopups = [];

jQuery(document).on('elementor/popup/hide', (event, id, instance) => {
	if (processedPopups.includes(id))
		return;

	processedPopups.push(id);

	const $checkedItems = instance.$element.find('.jet-filter input[type="checkbox"][checked]');

	if (!$checkedItems.length)
		return;

	$checkedItems.removeAttr('checked');
	instance.elementHTML = instance.$element.prop('outerHTML');
});