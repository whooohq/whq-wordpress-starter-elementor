import { ref, getCurrentInstance, onMounted, onUnmounted } from 'vue';

export default function useDropDown(props = {}) {
	const instance = getCurrentInstance();
	const opened = ref(false);

	// Lifecycles
	onMounted(() => {
		if (props.areaElement)
			document.addEventListener('click', documentClick);
	});

	onUnmounted(() => {
		if (props.areaElement)
			document.removeEventListener('click', documentClick);
	});

	// Methods
	const open = () => {
		opened.value = true;

		if (typeof props.onOpen === 'function')
			props.onOpen();
	};

	const close = () => {
		opened.value = false;

		if (typeof props.onClose === 'function')
			props.onClose();
	};

	const switchState = () => {
		if (!opened.value) {
			open();
		} else {
			close();
		}
	};

	const documentClick = (evt) => {
		if (!opened.value)
			return;

		if ((props.areaElement.value !== evt.target) && !props.areaElement.value.contains(evt.target))
			close();
	};

	return {
		opened,
		open,
		close,
		switchState
	};
}