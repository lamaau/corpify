import { RouteMeta } from "vue-router";

export default [
    {
        path: "og",
        children: [
            {
                path: "history",
                name: "settings.history.history",
                component: async () =>
                    await import("@/pages/settings/og/history.vue"),
                meta: {
                    title: "Setting Site",
                } as RouteMeta,
            },
            {
                path: "vision",
                name: "settings.vision.vision",
                component: async () =>
                    await import("@/pages/settings/og/vision.vue"),
                meta: {
                    title: "Setting Site",
                } as RouteMeta,
            },
        ],
    },
];
