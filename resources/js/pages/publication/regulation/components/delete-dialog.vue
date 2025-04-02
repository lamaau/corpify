<script setup lang="ts">
import { ref, watch } from "vue";
import fetcher from "@/lib/fetcher";
import { toast } from "@/components/ui/toast";
import { Button } from "@/components/ui/button";
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
import { IRowProps } from "../types";

const props = defineProps<IRowProps>();
const emit = defineEmits(["update:open"]);

const isOpen = ref<boolean>(false);
const queryClient: QueryClient = useQueryClient();

const { isPending, mutate } = useMutation(
    async () => await fetcher.delete(`/regulations/${props.row.id}`),
    {
        onSuccess: ({ message: description }: any) => {
            isOpen.value = false;
            toast({ description });
            queryClient.invalidateQueries(
                tableQueryKeys.lists("/regulations") as any,
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
            <slot />
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
