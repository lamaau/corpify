import { RouteMeta } from "vue-router";

export default [
    {
        path: "/gallery",
        name: "gallery.index",
        component: async () => await import("@/pages/gallery/index.vue"),
        meta: {
            title: "Gallery",
        } as RouteMeta,
    },
];
