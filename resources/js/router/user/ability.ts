import { RouteMeta } from "vue-router";

export default [
    {
        path: "",
        name: "ability.index",
        component: async () => await import("@/pages/user/ability/index.vue"),
        meta: {
            title: "Ability",
        } as RouteMeta,
    },
];
