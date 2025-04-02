<script setup lang="ts">
import DeleteDialog from "./delete-dialog.vue";
import { Button } from "@/components/ui/button";
import { DotsHorizontalIcon } from "@radix-icons/vue";
import { PencilIcon, Trash2Icon } from "lucide-vue-next";
import {
    DropdownMenu,
    DropdownMenuItem,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { ref } from "vue";

defineProps<{
    row: any;
}>();

const isOpen = ref(false);
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
            <DropdownMenuItem
                @click="
                    $router.push({
                        name: 'work-program.edit',
                        params: {
                            workProgramId: row.id,
                        },
                    })
                "
            >
                <PencilIcon class="h-4 w-4 mr-2" /> Review
            </DropdownMenuItem>
            <DeleteDialog @update:open="isOpen = $event" :row="row">
                <DropdownMenuItem as-child>
                    <Button
                        variant="ghost"
                        class="h-8 w-full flex justify-start"
                    >
                        <Trash2Icon class="h-4 w-4 mr-2" /> Delete
                    </Button>
                </DropdownMenuItem>
            </DeleteDialog>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
