import { RouteMeta } from "vue-router";

export default [
    {
        path: "post",
        name: "publication.post.index",
        component: async () =>
            await import("@/pages/publication/post/index.vue"),
        meta: {
            title: "Publication",
        } as RouteMeta,
    },
    {
        path: "post/create",
        name: "publication.post.create",
        component: async () =>
            await import("@/pages/publication/post/create.vue"),
        meta: {
            title: "Create Publication",
        } as RouteMeta,
    },
    {
        path: "post/edit/:postId",
        name: "publication.post.edit",
        component: async () =>
            await import("@/pages/publication/post/edit.vue"),
        meta: {
            title: "Review Publication",
        } as RouteMeta,
    },
];
