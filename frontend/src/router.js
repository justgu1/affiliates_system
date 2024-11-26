import { createRouter, createWebHistory } from 'vue-router';
import { reactive } from 'vue';

// views
import Home from '@views/Home.vue';

const routeState = reactive({
    previousRoute: null,
});

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
        meta: { requiresAuth: false },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export { routeState };
export default router;