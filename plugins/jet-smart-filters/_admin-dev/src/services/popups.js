import { ref } from "vue";

const isShow = ref(false);
const type = ref('');
const data = ref(false);

let onApply = () => { },
	onCancel = () => { };

export function apply() {
	onApply();
	close();
};

export function cancel() {
	onCancel();
	close();
};

export function open(popupType, popupData = {}) {
	if (popupData.hasOwnProperty('data'))
		data.value = popupData.data;

	if (popupData.onApply)
		onApply = popupData.onApply;

	if (popupData.onCancel)
		onCancel = popupData.onCancel;

	isShow.value = true;
	type.value = popupType;
}

export function close() {
	isShow.value = false;
	type.value = '';
	data.value = false;
	onApply = onCancel = () => { };
}

export function quickEdit(filterId, applyCB = false, cancelCB = false) {
	open('quickEdit', { data: filterId, onApply: applyCB, onCancel: cancelCB });
}

export function deletePermanently(applyCB = false, cancelCB = false) {
	open('deletePermanently', { onApply: applyCB, onCancel: cancelCB });
}

export function emptyTrash(applyCB = false, cancelCB = false) {
	open('emptyTrash', { onApply: applyCB, onCancel: cancelCB });
}

export function saveChanges(applyCB = false, cancelCB = false) {
	open('saveChanges', { onApply: applyCB, onCancel: cancelCB });
}

export function filterInfo(filterName) {
	open('filterInfo', { data: filterName });
}

export default {
	isShow,
	type,
	data,
	apply,
	cancel,
	open,
	close,
	quickEdit,
	deletePermanently,
	emptyTrash,
	saveChanges,
	filterInfo
};