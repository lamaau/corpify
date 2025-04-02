<script setup lang="ts">
import { ref } from "vue";
import EditForm from "./edit-form.vue";
import DeleteDialog from "./delete-dialog.vue";
import { Button } from "@/components/ui/button";
import { DotsHorizontalIcon } from "@radix-icons/vue";
import { PencilIcon, Trash2Icon } from "lucide-vue-next";
import DropdownItemButton from "@/components/dropdown-item-button.vue";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { TDetailPost } from "@/composables/use-post";

defineProps<{
    row: TDetailPost;
}>();

const isOpen = ref<boolean>(false);
</script>

<template>
    <DropdownMenu v-model:open="isOpen">
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" class="flex h-8 w-8 p-0">
                <DotsHorizontalIcon class="h-4 w-4" />
                <span class="sr-only">Open menu</span>
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="start" class="w-min">
            <EditForm @update:open="isOpen = $event" :row="row">
                <DropdownItemButton>
                    <PencilIcon class="h-4 w-4 mr-1" /> Review
                </DropdownItemButton>
            </EditForm>
            <DeleteDialog @update:open="isOpen = $event" :row="row">
                <DropdownItemButton>
                    <Trash2Icon class="h-4 w-4 mr-1" /> Delete
                </DropdownItemButton>
            </DeleteDialog>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
