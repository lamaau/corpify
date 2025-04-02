import { RouteMeta } from "vue-router";

export default [
    {
        path: "regulation",
        name: "publication.regulation.index",
        component: async () =>
            await import("@/pages/publication/regulation/index.vue"),
        meta: {
            title: "Regulation",
        } as RouteMeta,
    },
];
