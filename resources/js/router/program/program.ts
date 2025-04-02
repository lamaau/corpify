import { RouteMeta } from "vue-router";

export default [
    {
        path: "/program",
        name: "program.index",
        component: async () => await import("@/pages/program/index.vue"),
        meta: {
            title: "Program",
        } as RouteMeta,
    },
    {
        path: "/program/create",
        name: "program.create",
        component: async () => await import("@/pages/program/index.vue"),
        meta: {
            title: "Program",
        } as RouteMeta,
    },
];
