import type { App } from "vue";
import { setupI18n } from "./i18n";
import { setupPinia } from "./pinia";
import { setupDayjs } from "./dayjs";
import { setupOgChart } from "./og-chart";
import { setupNProgress } from "./nprogress";
import { setupAutoAnimate } from "./auto-animate";
import { setupTanstackVueQuery } from "./tanstack-vue-query";

export function setupPlugins(app: App) {
    setupDayjs();
    setupI18n(app);
    setupPinia(app);
    setupNProgress();
    setupOgChart(app);
    setupAutoAnimate(app);
    setupTanstackVueQuery(app);
}
