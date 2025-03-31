import { RouteMeta } from "vue-router";

export default [
    {
        path: "organization",
        name: "organization.index",
        component: async () => await import("@/pages/og/index.vue"),
        meta: {
            title: "organization",
        } as RouteMeta,
    },
];
