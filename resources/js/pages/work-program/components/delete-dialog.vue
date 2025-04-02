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
import { Button } from "@/components/ui/button";
import { toast } from "@/components/ui/toast";
import { useMutation } from "@/composables/use-mutation";
import { TDetailPost } from "@/composables/use-post";
import { tableQueryKeys } from "@/enums/query-keys";
import fetcher from "@/lib/fetcher";
import { useQueryClient } from "@tanstack/vue-query";
import { ref, watch } from "vue";

defineProps<{
    row: TDetailPost;
}>();

const emit = defineEmits(["update:open"]);

const isOpen = ref<boolean>(false);
const queryClient = useQueryClient();

const { isPending, mutate } = useMutation(
    async (id) => await fetcher.delete(`/work-programs/${id}`),
    {
        onSuccess: ({ message: description }: any) => {
            toast({ description });
            queryClient.invalidateQueries(
                tableQueryKeys.lists("/work-programs") as any,
            );
        },
    },
);

watch(isOpen, (newValue) => {
    emit("update:open", newValue);
});
</script>

<template>
    <AlertDialog v-model:open="isOpen">
        <AlertDialogTrigger as-child>
            <slot :isOpen />
        </AlertDialogTrigger>
        <AlertDialogContent class="w-max">
            <AlertDialogHeader>
                <AlertDialogTitle>Are you absolutely sure?</AlertDialogTitle>
                <AlertDialogDescription>
                    This will delete the post permanently, you will loss all of
                    them
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel asChild>
                    <Button :disabled="isPending" variant="ghost">
                        Cancel
                    </Button>
                </AlertDialogCancel>
                <Button
                    variant="destructive"
                    :disabled="isPending"
                    @click="
                        () => {
                            mutate(row.id);
                            isOpen = false;
                        }
                    "
                >
                    Delete
                </Button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
