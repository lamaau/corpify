import { RouteMeta } from "vue-router";

export default [
    {
        path: "category",
        name: "publication.category.index",
        component: async () =>
            await import("@/pages/publication/category/index.vue"),
        meta: {
            title: "Publication",
        } as RouteMeta,
    },
    {
        path: "category/create",
        name: "publication.category.create",
        component: async () =>
            await import("@/pages/publication/category/create.vue"),
        meta: {
            title: "Create Publication",
        } as RouteMeta,
    },
    {
        path: "category/edit/:categoryId",
        name: "publication.category.edit",
        component: async () =>
            await import("@/pages/publication/category/edit.vue"),
        meta: {
            title: "Review Publication",
        } as RouteMeta,
    },
];
