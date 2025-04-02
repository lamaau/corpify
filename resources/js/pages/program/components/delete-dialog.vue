<script setup lang="ts">
import { ref, watch } from "vue";
import fetcher from "@/lib/fetcher";
import { toast } from "@/components/ui/toast";
import { Button } from "@/components/ui/button";
import { TDetailPost } from "@/composables/use-post";
import { tableQueryKeys } from "@/enums/query-keys";
import { useMutation } from "@/composables/use-mutation";
import { QueryClient, useQueryClient } from "@tanstack/vue-query";
import {
    AlertDialog,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
} from "@/components/ui/alert-dialog";

const props = defineProps<{
    row: TDetailPost;
}>();

const isOpen = ref<boolean>(false);
const emit = defineEmits(["update:open"]);
const queryClient: QueryClient = useQueryClient();

const { isPending, mutate } = useMutation(
    async () => await fetcher.delete(`/programs/${props.row.id}`),
    {
        onSuccess: ({ message: description }: any) => {
            toast({ description });
            queryClient.invalidateQueries(
                tableQueryKeys.lists("/programs") as any,
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
