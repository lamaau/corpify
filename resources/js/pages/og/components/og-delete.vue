<script setup lang="ts">
import {
    AlertDialog,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from "@/components/ui/alert-dialog";
import { toast } from "@/components/ui/toast";
import { Button } from "@/components/ui/button";
import { useOg } from "@/composables/use-og";
import { watch } from "vue";

const props = defineProps<{
    item: any;
}>();

const { deleteOg } = useOg();
const { mutate: onDelete, data, isPending } = deleteOg(props.item.id);

watch(data, ({ message }) => {
    toast({
        description: message,
    });
});
</script>

<template>
    <AlertDialog>
        <AlertDialogTrigger as-child>
            <slot />
        </AlertDialogTrigger>
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>Are you absolutely sure?</AlertDialogTitle>
                <AlertDialogDescription>
                    This will delete all of children node and can't be refert
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel asChild>
                    <Button variant="ghost" :disableld="isPending">
                        Cancel
                    </Button>
                </AlertDialogCancel>
                <Button
                    :disabled="isPending"
                    class="destructive"
                    @click="onDelete"
                >
                    Delete
                </Button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
