<script setup lang="ts">
import { h, computed } from "vue";
import { useRoute } from "vue-router";
import {
    ChevronsUpDown,
    StoreIcon,
    GlobeIcon,
    PhoneCallIcon,
} from "lucide-vue-next";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";

const route = useRoute();
const currentPath = computed(() => route.path);
const activeClass = "text-primary font-semibold bg-primary/5";

const links = [
    {
        path: "/settings/site/contact",
        label: "Contact",
        icon: h(PhoneCallIcon),
    },
    {
        path: "/settings/site/address",
        label: "Address",
        icon: h(StoreIcon),
    },
    {
        path: "/settings/site/social-media",
        label: "Social Media",
        icon: h(GlobeIcon),
    },
];

const currentLink = computed(() =>
    links.find((link) => link.path === currentPath.value),
);
</script>

<template>
    <nav class="flex flex-col gap-2">
        <router-link
            v-for="link in links"
            :key="link.path"
            :to="link.path"
            class="items-center hidden px-2 py-1 rounded-md lg:flex hover:bg-primary/5"
            :class="link.path === currentPath ? activeClass : ''"
        >
            <component :is="link.icon" class="w-4 h-4 mr-1" />
            <span>{{ link.label }}</span>
        </router-link>

        <DropdownMenu class="lg:hidden">
            <DropdownMenuTrigger>
                <div
                    class="flex items-center w-full px-2 py-2.5 border rounded-md lg:hidden hover:bg-primary/5 border-primary/4"
                >
                    <component :is="currentLink?.icon" class="w-4 h-4 mr-1" />
                    <span>{{ currentLink?.label }}</span>
                    <ChevronsUpDown class="w-4 h-4 ml-auto" />
                </div>
            </DropdownMenuTrigger>
            <DropdownMenuContent class="w-full" align="start">
                <DropdownMenuItem
                    v-for="link in links"
                    :key="link.path"
                    @click="$router.push(link.path)"
                >
                    <component :is="link.icon" class="w-4 h-4 mr-1" />
                    {{ link.label }}
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    </nav>
</template>

<style scoped></style>
