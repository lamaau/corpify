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
        <DialogContent class="max-w-[600px]">
            <DialogHeader class="flex items-start">
                <DialogTitle>{{ row.file.file_name }}</DialogTitle>
                <!-- <DialogDescription>
                    {{ row.summary }}
                </DialogDescription> -->
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
