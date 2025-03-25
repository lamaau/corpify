import { type RouteMeta } from "vue-router";

interface IRouteMeta {
  title: string;
  guest?: boolean;
  auth?: boolean;
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
      guest: true,
    } as RouteMeta & IRouteMeta,
  },
  {
    path: "/dashboard",
    component: async () => await import("../layouts/default.vue"),
    meta: {
      title: "HPPI",
      auth: true,
    },
    children: [
      {
        path: "",
        name: "dashboard",
        component: async () => await import("../pages/dashboard/index.vue"),
        meta: {
          title: "Dashboard",
        } as RouteMeta & IRouteMeta,
      },
    ],
  },
];
