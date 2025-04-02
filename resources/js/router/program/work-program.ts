import { RouteMeta } from "vue-router";

export default [
    {
        path: "/work-program",
        name: "work-program.index",
        component: async () => await import("@/pages/work-program/index.vue"),
        meta: {
            title: "Work Program",
        } as RouteMeta,
    },
    {
        path: "/work-program/create",
        name: "work-program.create",
        component: async () => await import("@/pages/work-program/create.vue"),
        meta: {
            title: "Create Work Program",
        } as RouteMeta,
    },
    {
        path: "/work-program/edit/:workProgramId",
        name: "work-program.edit",
        component: async () => await import("@/pages/work-program/edit.vue"),
        meta: {
            title: "Review Work Program",
        } as RouteMeta,
    },
];
