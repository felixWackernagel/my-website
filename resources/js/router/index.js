import { createRouter, createWebHistory } from 'vue-router';

import { useUserStore } from './../stores/user';

import homeAdminIndex from '../components/admin/home/index.vue';
import locationsAdminIndex from '../components/admin/locations/index.vue';
import quizzesAdminIndex from '../components/admin/quizzes/index.vue';

import homePageIndex from '../components/pages/home/index.vue';
import notFound from '../components/notFound.vue';
import login from '../components/auth/login.vue';

const routes = [
    {
        path: '/admin/home',
        name: 'adminHome',
        component: homeAdminIndex,
        meta: {
            requiresAuth: true,
            title: 'Admin Area'
        }
    },
    {
        path: '/admin/locations',
        name: 'adminLocations',
        component: locationsAdminIndex,
        meta: {
            requiresAuth: true,
            title: 'Kneipen'
        }
    },
    {
        path: '/admin/quizzes',
        name: 'adminQuizzes',
        component: quizzesAdminIndex,
        meta: {
            requiresAuth: true,
            title: 'Quizze'
        }
    },
    {
        path: '/',
        name: 'home',
        component: homePageIndex,
        meta: {
            requiresAuth: false,
            title: 'Home'
        }
    },
    {
        path: '/login',
        name: 'login',
        component: login,
        meta: {
            requiresAuth: false,
            title: 'Login'
        }
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'notFound',
        component: notFound,
        meta: {
            requiresAuth: false,
            title: 'Seite nicht gefunden'
        }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach( ( to, from ) => {
    document.title = to.meta.title;

    const store = useUserStore();

    if( to.meta.requiresAuth && !store.authorized ) {
        return {
            name: 'login'
        };
    }

    // stay on admin page when authorized
    if( !to.meta.requiresAuth && store.authorized ) {
        return { name: 'adminHome' };
    }
});

export default router;