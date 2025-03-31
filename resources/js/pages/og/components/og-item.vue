<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import {
    ContextMenu,
    ContextMenuItem,
    ContextMenuContent,
    ContextMenuTrigger,
    ContextMenuSeparator,
} from "@/components/ui/context-menu";
import OgDelete from "./og-delete.vue";
import { Trash2Icon } from "lucide-vue-next";
import OgProfile from "./og-profile.vue";
import OgNew from "./og-new.vue";

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
                <div class="flex flex-col justify-center items-center gap-y-4">
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
            </button>
        </ContextMenuTrigger>
        <ContextMenuContent>
            <ContextMenuItem asChild as="template">
                <OgProfile v-bind="props">
                    <button
                        class="px-2 py-1.5 text-sm inline-flex items-center text-primary w-full cursor-default rounded-sm select-none hover:bg-accent"
                    >
                        Preview
                    </button>
                </OgProfile>
            </ContextMenuItem>
            <ContextMenuItem asChild as="template">
                <OgNew v-bind="props">
                    <button
                        class="px-2 py-1.5 text-sm inline-flex items-center text-primary w-full cursor-default rounded-sm select-none hover:bg-accent"
                    >
                        New Child
                    </button>
                </OgNew>
            </ContextMenuItem>
            <ContextMenuSeparator />
            <ContextMenuItem asChild as="template">
                <OgDelete>
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
