import { RouteMeta } from "vue-router";
import OrganizationRoutes from "./organization";

export default [
    {
        path: "/about",
        meta: {
            title: "About",
        } as RouteMeta,
        children: [...OrganizationRoutes],
    },
];
