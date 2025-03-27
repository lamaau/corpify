import type { Router } from "vue-router";
import { useAuth } from "@/composables/use-auth";
import { startProgress, stopProgress } from "@/utils/nprogress";

function setupCommonGuard(router: Router) {
  router.beforeEach(() => {
    startProgress();
    return true;
  });

  router.afterEach(() => {
    stopProgress();
    return true;
  });
}

function setupAuthGuard(router: Router) {
  router.beforeEach((to, from, next) => {
    const { isAuthenticated } = useAuth();

    // Redirect logic
    const publicPages = ["/login", "/register", "/forgot-password"];
    const authRequired = !publicPages.includes(to.path);

    if (authRequired && !isAuthenticated.value) {
      return next("/login");
    }

    if (publicPages.includes(to.path) && isAuthenticated.value) {
      return next("/dashboard");
    }

    next();
  });
}

export function createRouterGuard(router: Router) {
  setupCommonGuard(router);
  setupAuthGuard(router);
}
