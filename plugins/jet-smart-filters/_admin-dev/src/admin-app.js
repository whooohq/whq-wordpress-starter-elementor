import { createApp } from 'vue';
import router from './router';
import AdminApp from './AdminApp.vue';

const JSf_admin_app = createApp(AdminApp);

JSf_admin_app.use(router);
JSf_admin_app.mount('#jet-smart-filters-admin-app');