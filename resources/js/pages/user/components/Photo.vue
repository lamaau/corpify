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
            <button>
                <img
                    :src="row.file.file_url"
                    :alt="row.file.name"
                    class="h-14 w-14 object-cover rounded"
                />
            </button>
        </DialogTrigger>
        <DialogContent class="max-w-4xl">
            <DialogHeader class="flex items-start">
                <DialogTitle>{{ row.caption }}</DialogTitle>
                <DialogDescription>
                    <div class="flex flex-col">
                        <span>{{ row.file.file_name }}</span>
                        <span>{{ row.file.file_mime_type }}</span>
                        <span>{{ row.file.file_size }}</span>
                    </div>
                </DialogDescription>
            </DialogHeader>

            <div>
                <img
                    :src="row.file.file_url"
                    :alt="row.file.name"
                    class="h-full w-full object-cover rounded-lg"
                />
            </div>
        </DialogContent>
    </Dialog>
</template>
