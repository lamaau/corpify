<script setup lang="ts">
import { ref } from "vue";
import EditForm from "./edit-form.vue";
import DeleteDialog from "./delete-dialog.vue";
import { Button } from "@/components/ui/button";
import { DotsHorizontalIcon } from "@radix-icons/vue";
import { Trash2Icon, PencilIcon } from "lucide-vue-next";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import DropdownItemButton from "@/components/dropdown-item-button.vue";
import {
    IAbilityAPIResponse,
    useAbilityQuery,
} from "@/composables/use-ability";

defineProps<{
    row: any;
}>();

const isOpen = ref<boolean>(false);
const { data: dataAbilities } = useAbilityQuery();
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
            <EditForm
                @update:open="isOpen = $event"
                :abilities="dataAbilities"
                :row="row"
            >
                <DropdownItemButton>
                    <PencilIcon class="h-4 w-4" /> Review
                </DropdownItemButton>
            </EditForm>
            <DeleteDialog @update:open="isOpen = $event" :row="row">
                <DropdownItemButton>
                    <Trash2Icon class="h-4 w-4" /> Delete
                </DropdownItemButton>
            </DeleteDialog>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
