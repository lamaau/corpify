import { RouteMeta } from "vue-router";
import PostRoutes from "./post";
import CategoryRoutes from "./category";

export default [
    {
        path: "/publication",
        meta: {
            title: "Publication",
        } as RouteMeta,
        children: [...PostRoutes, ...CategoryRoutes],
    },
];
