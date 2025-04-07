<script setup lang="ts">
import { ref } from "vue";
import {
    Dialog,
    DialogContent,
    DialogTrigger,
    DialogHeader,
    DialogDescription,
} from "@/components/ui/dialog";

defineProps<{
    row: any;
}>();

const isOpen = ref<boolean>(false);
</script>
<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger asChild>
            <button class="w-full flex flex-row gap-x-2">
                <img
                    :src="row.profile.avatar"
                    :alt="row.profile.name"
                    class="h-10 w-10 object-cover rounded"
                />
                <div class="text-left flex flex-col">
                    <span>{{ row.profile.name }}</span>
                    <span class="text-muted-foreground">{{ row.email }}</span>
                </div>
            </button>
        </DialogTrigger>
        <DialogContent class="min-w-max">
            <DialogHeader class="flex items-start">
                <DialogTitle>{{ row.profile.name }}</DialogTitle>
                <DialogDescription>{{ row.email }}</DialogDescription>
            </DialogHeader>

            <div>
                <img
                    :src="row.profile.avatar"
                    :alt="row.profile.name"
                    class="w-full h-full max-w-xl object-cover rounded-lg"
                />
            </div>
        </DialogContent>
    </Dialog>
</template>
