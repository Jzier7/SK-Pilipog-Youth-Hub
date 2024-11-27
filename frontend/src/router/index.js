import { route } from 'quasar/wrappers';
import { createRouter, createWebHistory } from 'vue-router';
import routes from './routes';

export default route(function () {
    const Router = createRouter({
        history: createWebHistory(process.env.VUE_ROUTER_BASE),
        routes,
        scrollBehavior: () => ({ left: 0, top: 0 }),
    });

    Router.beforeEach((to, from, next) => {
        if (to.meta.middlewares) {
            const middlewares = to.meta.middlewares;
            const context = { to, from, next };

            const runMiddleware = (index) => {
                if (index < middlewares.length) {
                    middlewares[index]({
                        ...context,
                        next: (redirect) => {
                            if (redirect) {
                                return next(redirect);
                            }
                            runMiddleware(index + 1);
                        },
                    });
                } else {
                    next();
                }
            };

            runMiddleware(0);
        } else {
            next();
        }
    });

    return Router;
});

