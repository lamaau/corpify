import { RouteMeta } from "vue-router";

export default [
    {
        path: "/logs/visitor",
        meta: {
            title: "Visitor Logs",
        } as RouteMeta,
        component: async () => await import("@/pages/visitor/logs/index.vue"),
    },
];
