import { createMemoryHistory, createRouter, createWebHistory } from "vue-router";
import { currentUser, checkLoggedin } from "@deps/service.js";

import Auth from "@mobile/Auth.vue";
import Login from "@mobile/Login.vue";
import Forgot from "@mobile/Forgot.vue";
import Home from "@mobile/Home.vue";
import Install from "@mobile/Install.vue";
import Profile from "@mobile/Profile.vue";
import Notif from "@mobile/Notif.vue";
import Form from "@mobile/Form.vue";
import Manual from "@mobile/Manual.vue";
import Detail from "@mobile/Detail.vue";
import Faq from "@mobile/Faq.vue";
import Validate from "@mobile/Validate.vue";

const routes = [
    { path: "/", component: Validate, meta: { requiresAuth: true } },
    { path: "/home", component: Home, meta: { requiresAuth: true } },
    { path: "/install", component: Install, meta: { requiresAuth: false } },
    { path: "/auth", component: Auth, meta: { requiresAuth: false } },
    { path: "/login", component: Login, meta: { requiresAuth: false } },
    { path: "/forgot", component: Forgot, meta: { requiresAuth: false } },
    { path: "/detail/:id", component: Detail, meta: { requiresAuth: true } },
    { path: "/form", component: Form, meta: { requiresAuth: true } },
    { path: "/manual", component: Manual, meta: { requiresAuth: true } },
    { path: "/notif", component: Notif, meta: { requiresAuth: true } },
    { path: "/profile", component: Profile, meta: { requiresAuth: true } },
    { path: "/faq", component: Faq, meta: { requiresAuth: true } },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to) => {
    let user = currentUser;
    if (currentUser?.loggedin === false) {
        user = await checkLoggedin();
    }
    if (to.meta.requiresAuth) {
        if (user?.loggedin === false) {
            return {
                path: "/auth",
                query: {
                    redirect: to.fullPath,
                },
            };
        }
    }
});

export { router };
