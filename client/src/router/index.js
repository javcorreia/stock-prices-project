import {createRouter, createWebHistory} from 'vue-router'
import HomeView from '../views/HomeView.vue'
import RegisterView from '../views/RegisterView.vue'
import LoginView from '../views/LoginView.vue'
import HistoryView from '../views/HistoryView.vue'
import {isJwtValid} from '../auth.js'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'home',
            component: HomeView,
            meta: {requiresAuth: true},
        },
        {
            path: '/register',
            name: 'register',
            component: RegisterView,
            meta: {guest: true},
        },
        {
            path: '/login',
            name: 'login',
            component: LoginView,
            meta: {guest: true},
        },
        {
            path: '/history',
            name: 'history',
            component: HistoryView,
            meta: {requiresAuth: true},
        },
    ],
})

router.beforeEach((to, from, next) => {
    const jwt = sessionStorage.getItem('token')
    const isLoggedIn = isJwtValid(jwt)

    if (to.meta.requiresAuth && !isLoggedIn) {
        next({name: 'login'})
    } else if (to.meta.guest && isLoggedIn) {
        next({name: 'home'})
    } else {
        next()
    }
})

export default router
