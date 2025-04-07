import { RouteMeta } from "vue-router";
import UserRoutes from "./user";
import AbilityRoutes from "./ability";

export default [
    {
        path: "/user",
        meta: {
            title: "Users",
        } as RouteMeta,
        children: [...UserRoutes],
    },
    {
        path: "/ability",
        meta: {
            title: "Ability",
        } as RouteMeta,
        children: [...AbilityRoutes],
    },
];
