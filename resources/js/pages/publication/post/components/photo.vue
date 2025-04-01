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
                    :src="row.thumbnail"
                    :alt="row.title"
                    class="h-14 w-14 object-cover rounded"
                />
            </button>
        </DialogTrigger>
        <DialogContent class="max-w-4xl">
            <DialogHeader class="flex items-start">
                <DialogTitle>{{ row.title }}</DialogTitle>
                <DialogDescription>
                    {{ row.created_at }}
                </DialogDescription>
            </DialogHeader>

            <div>
                <img
                    :src="row.thumbnail"
                    :alt="row.title"
                    class="h-full w-full object-cover rounded-lg"
                />
            </div>
        </DialogContent>
    </Dialog>
</template>
