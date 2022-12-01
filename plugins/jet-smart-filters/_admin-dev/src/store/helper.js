import { computed } from "vue";
import store from "./store";
import { clone } from "@/modules/helpers/utils.js";

export function useStates(arr) {
	const keypair = arr.map(s => [s, store.state[s]]);

	return Object.fromEntries(keypair);
}

export function useState(name) {
	return store.state[name];
}

export function useGetters(arr, isComputed = true) {
	const keypair = arr.map(g => [g,
		isComputed
			? computed(() => store.getters[g])
			: clone(store.getters[g])
	]);

	return Object.fromEntries(keypair);
}

export function useGetter(name, isComputed = true) {
	return isComputed
		? computed(() => store.getters[name])
		: clone(store.getters[name]);
}

export function useMutations(arr) {
	const keypair = arr.map(m => [m, input => store.commit(m, input)]);

	return Object.fromEntries(keypair);
}

export function useMutation(name) {
	return input => store.commit(name, input);
}

export function useActions(arr) {
	const keypair = arr.map(m => [m, input => store.dispatch(m, input)]);

	return Object.fromEntries(keypair);
}

export function useAction(name) {
	return input => store.dispatch(name, input);
}