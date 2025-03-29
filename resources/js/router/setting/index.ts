import { RouteMeta } from "vue-router";

export default [
  {
    path: "/settings",
    name: "settings.index",
    component: async () => await import("@/pages/settings/index.vue"),
    meta: {
      title: "Setting",
    } as RouteMeta,
  },
];
