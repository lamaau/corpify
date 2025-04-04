import { RouteMeta } from "vue-router";

export default [
    {
        path: "site",
        children: [
            {
                path: "contact",
                name: "settings.site.contact",
                component: async () =>
                    await import("@/pages/settings/site/contact/contact.vue"),
                meta: {
                    title: "Setting Site Contact",
                } as RouteMeta,
            },
            {
                path: "address",
                name: "settings.site.address",
                component: async () =>
                    await import("@/pages/settings/site/contact/address.vue"),
                meta: {
                    title: "Setting Site Contact",
                } as RouteMeta,
            },
            {
                path: "social-media",
                name: "settings.site.social-media",
                component: async () =>
                    await import(
                        "@/pages/settings/site/contact/social-media.vue"
                    ),
                meta: {
                    title: "Setting Site Contact",
                } as RouteMeta,
            },
        ],
    },
    {
        path: "site/carousel",
        name: "settings.carousel.index",
        component: async () =>
            await import("@/pages/settings/site/carousel/index.vue"),
        meta: {
            title: "Setting Site Carousel",
        } as RouteMeta,
    },
];
