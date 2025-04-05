import { type RouteMeta } from "vue-router";
import ProgramRoutes from "./program";
import SettingRoutes from "./setting";
import GalleryRoutes from "./gallery";
import AboutRoutes from "./about";
import PublicationRoutes from "./publication";
import LogsRoutes from "./logs";

interface IRouteMeta {
    title: string;
}

export default [
    {
        path: "/",
        redirect: "/login",
    },
    {
        path: "/login",
        name: "login",
        component: async () => await import("../pages/auth/sign-in.vue"),
        meta: {
            title: "Login",
        } as RouteMeta & IRouteMeta,
    },
    {
        path: "/dashboard",
        component: async () => await import("../layouts/default.vue"),
        meta: {
            title: "HPPI",
        },
        children: [
            {
                path: "",
                name: "dashboard",
                component: async () =>
                    await import("@/pages/dashboard/index.vue"),
                meta: {
                    title: "Dashboard",
                } as RouteMeta & IRouteMeta,
            },
            ...ProgramRoutes,
            ...SettingRoutes,
            ...GalleryRoutes,
            ...AboutRoutes,
            ...PublicationRoutes,
            ...LogsRoutes,
        ],
    },
];
