// @ts-ignore
import routes from "./routes";
import { createRouterGuard } from "./guard";
import { createRouter, createWebHistory } from "vue-router";

console.log(routes);

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { left: 0, top: 0, behavior: "smooth" };
  },
});

createRouterGuard(router);

export default router;
