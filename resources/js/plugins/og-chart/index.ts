import type { App } from "vue";

// @ts-ignore
import { Vue3OrgChartPlugin } from "vue3-org-chart";
import "vue3-org-chart/dist/style.css";

export function setupOgChart(app: App) {
    app.use(Vue3OrgChartPlugin);
}
