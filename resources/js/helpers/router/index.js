import {createRouter, createWebHistory} from "vue-router";
import admin from "./admin";
import auth from "./auth";
import home from "./home";
import store from "../store";
import learn from "./learn";
import classes from "./class";
import manage from "./manage";

const error = [
    {
        path: "/:pathMatch(.*)*",
        name: "error",
        component: () => import("../../pages/error/404.vue"),
    }
];

const routes = [...home, ...admin,...manage, ...auth,...learn, ...classes,...error];

const router = createRouter({
    history: createWebHistory(),
    routes,
});
export default router;
// redirect to log in if not logged in and trying to access a restricted page
router.beforeEach((to, from, next) => {
    // home pages that don't require auth
    const publicPages = [
        "/auth/login",
        "/auth/register",
        "/auth/forgot-password",
        "/auth/reset-password",
    ];
    // check if current page is a home page
    const authRequired = !publicPages.includes(to.path);
    if (authRequired) {
        const loggedIn = localStorage.getItem("user");
        if (!loggedIn) {
            return next({
                path: "/auth/login",
            });
        } else {
            // check email verification
            if (!store.getters['account/isEmailVerified'] && !to.path.includes('auth')) {
                return next({
                    path: "/auth/verify",
                });
            }
            // check if user is admin
            if (to.path.includes('admin') && !store.getters['account/isAdmin']) {
                return next({
                    path: "/",
                });
            }

            // check if user is manager
            if (to.path.includes('manage') && !store.getters['account/isAdmin']) {
                return next({
                    path: "/",
                });
            }
        }
    }
    next();
});
