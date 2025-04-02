import { ref } from "vue";
import type { NavGroup } from "@/components/app-sidebar/types";
import {
    ListTodo,
    Settings,
    CreditCard,
    LayoutDashboard,
} from "lucide-vue-next";

export function useSidebar() {
    const navData = ref<NavGroup[]>();

    navData.value = [
        {
            title: "General",
            items: [
                {
                    title: "Overview",
                    url: "/overview",
                    icon: ListTodo,
                },
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
