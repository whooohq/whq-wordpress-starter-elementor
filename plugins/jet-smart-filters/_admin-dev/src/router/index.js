import { createRouter, createWebHashHistory } from 'vue-router';
import { Filters, Trash, Filter } from '../views';

const routes = [
	{
		path: '/',
		name: 'filters',
		component: Filters
	},
	{
		path: '/trash',
		name: 'trash',
		component: Trash
	},
	{
		path: '/:id',
		name: 'filter',
		component: Filter
	}
];

const router = createRouter({
	history: createWebHashHistory(),
	routes
});

export default router;
