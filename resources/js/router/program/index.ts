import { RouteMeta } from "vue-router";

export default [
  {
    path: "/programs",
    name: "programs.index",
    component: async () => await import("@/pages/programs/index.vue"),
    meta: {
      title: "Program",
    } as RouteMeta,
  },
  {
    path: "/programs/create",
    name: "programs.create",
    component: async () => await import("@/pages/programs/index.vue"),
    meta: {
      title: "Program",
    } as RouteMeta,
  },
];
