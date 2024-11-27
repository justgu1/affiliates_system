import { createRouter, createWebHistory } from 'vue-router';
import { reactive } from 'vue';

// views
import Home from '@views/Home.vue';
import affiliateRegister from '@views/affiliate/Register.vue';
import affiliateLogin from '@views/affiliate/Login.vue';
import affiliateProfile from '@views/affiliate/Profile.vue';
import affiliateDashboard from '@views/affiliate/Dashboard.vue';
import Logout from '@views/Logout.vue';
import userLogin from '@views/user/Login.vue';
import userRegister from '@views/user/Register.vue';
import userProfile from '@views/user/Profile.vue';
import adminDashboard from '@views/admin/Dashboard.vue';
import adminAffiliates from '@views/admin/Affiliates.vue';
import adminUsers from '@views/admin/Users.vue';
import adminComissions from '@views/admin/Comissions.vue';
import api from '@utils/api.js';


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
    {
        path: '/logout',
        name: 'Logout',
        component: Logout,
        meta: { requiresAuth: true },
    },
    {
        path: '/affiliate/login',
        name: 'affiliate.Entrar',
        component: affiliateLogin,
        meta: { requiresAuth: false, role: 'affiliate' },
    },
    {
        path: '/affiliate/register',
        name: 'affiliate.Increver-se',
        component: affiliateRegister,
        meta: { requiresAuth: false, role: 'affiliate' },
    },
    {
        path: '/affiliate/dashboard',
        name: 'affiliate.Dashboard',
        component: affiliateDashboard,
        meta: { requiresAuth: true, role: 'affiliate' },
    },
    {
        path: '/affiliate/profile',
        name: 'affiliate.Profile',
        component: affiliateProfile,
        meta: { requiresAuth: true, role: 'affiliate' },
    },
    {
        path: '/user/login',
        name: 'user.Entrar',
        component: userLogin,
        meta: { requiresAuth: false, role: 'user' },
    },
    {
        path: '/user/register',
        name: 'user.Increver-se',
        component: userRegister,
        meta: { requiresAuth: false, role: 'user' },
    },
    {
        path: '/user/profile',
        name: 'user.Profile',
        component: userProfile,
        meta: { requiresAuth: true, role: 'user' },
    },
    {
        path: '/admin/dashboard',
        name: 'admin.Dashboard',
        component: adminDashboard,
        meta: { requiresAuth: true, role: 'user' },
    },
    {
        path: '/admin/users',
        name: 'admin.Usuários',
        component: adminUsers,
        meta: { requiresAuth: true, role: 'user' },
    },
    {
        path: '/admin/affiliates',
        name: 'admin.Afiliados',
        component: adminAffiliates,
        meta: { requiresAuth: true, role: 'user' },
    },
    {
        path: '/admin/comissions',
        name: 'admin.Comissões',
        component: adminComissions,
        meta: { requiresAuth: true, role: 'user' },
    },
];

const getRoutesByPrefix = (prefix) => {
    return routes.filter(route => route.path.startsWith(`/${prefix}`));
};

const getAffiliateRoutes = (Auth = false) => {
    if (Auth) {
        return getRoutesByPrefix('affiliate').filter(route =>
            route.path.includes('/login') ||
            route.path.includes('/register')
        );
    } else {
        return getRoutesByPrefix('affiliate').filter(route =>
            !route.path.includes('/login') &&
            !route.path.includes('/register') &&
            !route.path.includes('/profile')
        );
    }
};

const getUserRoutes = (Auth = false) => {
    if (Auth) {
        return getRoutesByPrefix('user').filter(route =>
            route.path.includes('/login') ||
            route.path.includes('/register')
        );
    } else {
        return getRoutesByPrefix('user').filter(route =>
            !route.path.includes('/login') &&
            !route.path.includes('/register') &&
            !route.path.includes('/profile')
        );
    }
};

const getAdminRoutes = (Auth = false) => {
    if (Auth) {
        return getRoutesByPrefix('user').filter(route =>
            route.path.includes('/login') ||
            route.path.includes('/register')
        );
    } else {
        return getRoutesByPrefix('admin');
    }
};

const getCommonRoutes = () => routes.filter(route => route.meta.common === true);

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    routeState.previousRoute = from;

    const ignoreRoutes = ['affiliate.Entrar', 'affiliate.Increver-se', 'user.Entrar', 'user.Increver-se', 'Home', 'Logout'];
    if (ignoreRoutes.includes(to.name)) {
        return next();
    }
    if (to.meta.requiresAuth) {
        const token = localStorage.getItem('user_token');
        const role = localStorage.getItem('user_role');
        if (token) {
            try {
                await api.get(`/verify`);
                return next();
            } catch (error) {
                localStorage.removeItem('user_token');
                localStorage.removeItem('user_role');
                localStorage.removeItem('user_name');
                localStorage.removeItem('user_email');
                localStorage.removeItem('user_isAdmin');
                return next({ name: 'Home', query: { redirect: to.fullPath } });
            }
        } else {
            return next({ name: 'Home', query: { redirect: to.fullPath } });
        }
    }
    next();
});

export { routeState, getAffiliateRoutes, getUserRoutes, getCommonRoutes, getAdminRoutes };
export default router;
