import { ref } from "vue";
import type { NavGroup } from "@/components/app-sidebar/types";
import {
    BadgeHelp,
    Boxes,
    Bug,
    CreditCard,
    LayoutDashboard,
    ListTodo,
    Podcast,
    Settings,
    SquareUserRound,
    Users,
} from "lucide-vue-next";

export function useSidebar() {
    const navData = ref<NavGroup[]>();

    navData.value = [
        {
            title: "General",
            items: [
                {
                    title: "Dashboard",
                    url: "/dashboard",
                    icon: LayoutDashboard,
                },
                {
                    title: "Program",
                    url: "/programs",
                    icon: ListTodo,
                },
                {
                    title: "Gallery",
                    url: "/gallery",
                    icon: ListTodo,
                },
            ],
        },
        {
            title: "Feature",
            items: [
                {
                    title: "About",
                    icon: SquareUserRound,
                    items: [
                        { title: "Organization", url: "/about/organization" },
                    ],
                },
            ],
        },
        {
            title: "Other",
            items: [
                {
                    title: "Settings",
                    icon: Settings,
                    items: [
                        { title: "Account", url: "/settings" },
                        { title: "Application", url: "/settings/app" },
                        { title: "Appearance", url: "/settings/appearance" },
                    ],
                },
            ],
        },
    ];

    const otherPages = ref<NavGroup[]>([
        {
            title: "Other",
            items: [
                {
                    title: "Plans & Pricing",
                    icon: CreditCard,
                    url: "/billing",
                },
            ],
        },
    ]);

    return {
        navData,
        otherPages,
    };
}
