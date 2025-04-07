import { RouteMeta } from "vue-router";

export default [
    {
        path: "",
        name: "user.index",
        component: async () => await import("@/pages/user/user/index.vue"),
        meta: {
            title: "User",
        } as RouteMeta,
    },
];
