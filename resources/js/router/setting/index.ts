import { RouteMeta } from "vue-router";

export default [
    {
        path: "/settings",
        name: "settings.index",
        component: async () => await import("@/pages/settings/index.vue"),
        meta: {
            title: "Setting Profile",
        } as RouteMeta,
    },
    {
        path: "/settings/app",
        name: "settings.app",
        component: async () => await import("@/pages/settings/app.vue"),
        meta: {
            title: "Setting Application",
        } as RouteMeta,
    },
    {
        path: "/settings/appearance",
        name: "settings.appearance",
        component: async () => await import("@/pages/settings/appearance.vue"),
        meta: {
            title: "Setting Appearance",
        } as RouteMeta,
    },
];
