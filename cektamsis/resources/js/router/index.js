import { createRouter, createWebHistory } from 'vue-router';
import Fitur from '../pages/Fitur.vue';
import Kontak from '../pages/Kontak.vue';
import Tentang from '../pages/Tentang.vue';
import Welcome from '../pages/Welcome.vue';

const routes = [
    { path: '/', name: 'welcome', component: Welcome },
    { path: '/tentang', name: 'tentang', component: Tentang },
    { path: '/fitur', name: 'fitur', component: Fitur },
    { path: '/kontak', name: 'kontak', component: Kontak },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
