<script setup lang="ts">
import { ref } from "vue";
import {
    Dialog,
    DialogContent,
    DialogTrigger,
    DialogHeader,
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
                    :src="row.thumbnail"
                    :alt="row.title"
                    class="h-10 w-10 object-cover rounded"
                />
                <div class="text-left min-w-[25vh]">
                    <span>{{ row.title }}</span>
                </div>
            </button>
        </DialogTrigger>
        <DialogContent class="max-w-min">
            <DialogHeader class="flex items-start">
                <DialogTitle>{{ row.title }}</DialogTitle>
            </DialogHeader>

            <div>
                <img
                    :src="row.thumbnail"
                    :alt="row.title"
                    class="max-w-xl object-cover rounded-lg"
                />
            </div>
        </DialogContent>
    </Dialog>
</template>
