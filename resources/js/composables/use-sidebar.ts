import { ref } from "vue";
import type { NavGroup } from "@/components/app-sidebar/types";
import {
    ListTodo,
    CreditCard,
    LayoutDashboard,
    SettingsIcon,
    ChartColumnIcon,
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
            ],
        },
        {
            title: "Publication",
            items: [
                {
                    title: "Post",
                    icon: ListTodo,
                    url: "/publication/post",
                },
                {
                    title: "Regulation",
                    icon: ListTodo,
                    url: "/publication/regulation",
                },
            ],
        },
        {
            title: "Program",
            items: [
                {
                    title: "Program",
                    icon: ListTodo,
                    url: "/program",
                },
                {
                    title: "Work Program",
                    icon: ListTodo,
                    url: "/work-program",
                },
            ],
        },
        {
            title: "User",
            items: [
                {
                    title: "User",
                    url: "/user",
                    icon: ListTodo,
                },
                {
                    title: "User Ability",
                    icon: ListTodo,
                    url: "/ability",
                },
            ],
        },
        {
            title: "Features",
            items: [
                {
                    title: "Gallery",
                    url: "/gallery",
                    icon: ListTodo,
                },
                {
                    title: "Organization",
                    icon: ListTodo,
                    url: "/about/organization",
                },
            ],
        },
        {
            title: "Other",
            items: [
                {
                    title: "Settings",
                    icon: SettingsIcon,
                    items: [
                        {
                            title: "Site",
                            url: "/settings/site/app",
                        },
                        {
                            title: "About us",
                            url: "/settings/og/history",
                        },
                    ],
                },
                {
                    title: "Visitor Logs",
                    url: "/logs/visitor",
                    icon: ChartColumnIcon,
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
