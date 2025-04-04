import { RouteMeta } from "vue-router";

export default [
    {
        path: "site",
        children: [
            {
                path: "contact",
                name: "settings.site.contact",
                component: async () =>
                    await import("@/pages/settings/site/contact.vue"),
                meta: {
                    title: "Setting Site Contact",
                } as RouteMeta,
            },
            {
                path: "address",
                name: "settings.site.address",
                component: async () =>
                    await import("@/pages/settings/site/address.vue"),
                meta: {
                    title: "Setting Site Contact",
                } as RouteMeta,
            },
            {
                path: "social-media",
                name: "settings.site.social-media",
                component: async () =>
                    await import("@/pages/settings/site/social-media.vue"),
                meta: {
                    title: "Setting Site Contact",
                } as RouteMeta,
            },
            {
                path: "carousel-image",
                name: "settings.site.carousel-image",
                component: async () =>
                    await import("@/pages/settings/site/carousel-image.vue"),
                meta: {
                    title: "Setting Site Contact",
                } as RouteMeta,
            },
            {
                path: "carousel-text",
                name: "settings.site.carousel-text",
                component: async () =>
                    await import("@/pages/settings/site/carousel-text.vue"),
                meta: {
                    title: "Setting Site Contact",
                } as RouteMeta,
            },
        ],
    },
];
