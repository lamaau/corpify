<script setup lang="ts">
import OgNew from "./og-new.vue";
import OgRoot from "./og-root.vue";
import OgDelete from "./og-delete.vue";
import OgProfile from "./og-profile.vue";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { PlusIcon, UserIcon, Trash2Icon, PencilIcon } from "lucide-vue-next";
import {
    ContextMenu,
    ContextMenuItem,
    ContextMenuContent,
    ContextMenuTrigger,
    ContextMenuSeparator,
} from "@/components/ui/context-menu";

const props = defineProps<{
    open: boolean;
    item: any;
    children: any;
}>();
</script>
<template>
    <ContextMenu>
        <ContextMenuTrigger>
            <button
                class="flex w-64 p-4 rounded-lg border transition-all duration-200 hover:shadow-md justify-center items-center cursor-default"
                :class="[
                    open
                        ? 'bg-card border-primary shadow-sm'
                        : 'bg-card/80 border hover:border-border',
                ]"
            >
                <div
                    v-if="item.user"
                    class="flex flex-col justify-center items-center gap-y-4"
                >
                    <Avatar class="w-16 h-16">
                        <AvatarImage
                            :src="item.user.avatar"
                            :alt="item.user.name"
                        />
                        <AvatarFallback>CN</AvatarFallback>
                    </Avatar>
                    <div class="flex-col">
                        <div>{{ item.user.name }}</div>
                        <div>{{ item.position.name }}</div>
                    </div>
                </div>
                <div v-else>
                    <h1>{{ item.category.name }}</h1>
                    <span class="text-sm">{{ item.category.summary }}</span>
                </div>
            </button>
        </ContextMenuTrigger>
        <ContextMenuContent class="w-min">
            <ContextMenuItem asChild as="template">
                <OgProfile v-if="item.parentId" v-bind="props">
                    <button
                        class="px-2 py-1.5 text-sm inline-flex items-center text-primary w-full cursor-default rounded-sm select-none hover:bg-accent"
                    >
                        <UserIcon :size="16" class="mr-1.5" />
                        Review
                    </button>
                </OgProfile>
                <OgRoot v-else :item="props.item">
                    <button
                        class="px-2 py-1.5 text-sm inline-flex items-center text-primary w-full cursor-default rounded-sm select-none hover:bg-accent"
                    >
                        <PencilIcon :size="14" class="mr-1.5" />
                        Review
                    </button>
                </OgRoot>
            </ContextMenuItem>
            <ContextMenuItem asChild as="template">
                <OgNew v-bind="props">
                    <button
                        class="px-2 py-1.5 text-sm inline-flex items-center text-primary w-full cursor-default rounded-sm select-none hover:bg-accent"
                    >
                        <PlusIcon :size="16" class="mr-1.5" />
                        New Child
                    </button>
                </OgNew>
            </ContextMenuItem>
            <ContextMenuSeparator />
            <ContextMenuItem asChild as="template">
                <OgDelete :item>
                    <button
                        class="px-2 py-1.5 inline-flex text-sm items-center text-primary w-full cursor-default rounded-sm select-none hover:bg-destructive hover:text-background"
                    >
                        <Trash2Icon :size="16" class="mr-1.5" />
                        <span class="text-sm">Delete</span>
                    </button>
                </OgDelete>
            </ContextMenuItem>
        </ContextMenuContent>
    </ContextMenu>
</template>
