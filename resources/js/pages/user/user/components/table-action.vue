<script setup lang="ts">
import { ref } from "vue";
import AbilityForm from "./ability-form.vue";
import { Button } from "@/components/ui/button";
import { DotsHorizontalIcon } from "@radix-icons/vue";
import { LockIcon } from "lucide-vue-next";
import DropdownItemButton from "@/components/dropdown-item-button.vue";
import {
    DropdownMenu,
    DropdownMenuItem,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";

const props = defineProps<{
    row: any;
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
        <DropdownMenuContent align="start" class="min-w-max">
            <DropdownMenuItem asChild>
                <AbilityForm @update:open="isOpen = $event" :row="row">
                    <DropdownItemButton>
                        <LockIcon class="h-4 w-4" /> Ability
                    </DropdownItemButton>
                </AbilityForm>
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
