import { NavigationGuard } from 'vue-router'
import { checkLoggedin, currentUser } from "~/deps/service.js";

const middleware: NavigationGuard = async (to, from) => {
    if (to.path.startsWith("/auth")) {
        return;
    }

    await checkLoggedin();
    const user = currentUser.loggedin;

    if (!user) {
        return navigateTo({
            path: "/auth",
            query: {
                redirect: to.fullPath,
            },
        });
    }
};

export default middleware;