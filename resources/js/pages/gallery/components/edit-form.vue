<script setup lang="ts">
import { ref, watch } from "vue";
import { Button } from "@/components/ui/button";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/components/ui/dialog";
import { Textarea } from "@/components/ui/textarea";
import FileUpload from "@/components/file-upload.vue";
import { useMutation } from "@/composables/use-mutation";
import {
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/components/ui/form";
import fetcher from "@/lib/fetcher";
import { toast } from "@/components/ui/toast";
import { useForm } from "vee-validate";
import { cn } from "@/lib/utils";
import { Switch } from "@/components/ui/switch";
import { Label } from "@/components/ui/label";
import { useFormMutation } from "@/composables/use-form-mutation";
import { QueryClient, useQueryClient } from "@tanstack/vue-query";
import { galleryQueryKeys } from "@/enums/query-keys";
import type { IGallery } from "./types";
import { Trash2Icon } from "lucide-vue-next";

const props = defineProps<{
    row: IGallery;
}>();

const isOpen = ref<boolean>(false);
const queryClient: QueryClient = useQueryClient();

const form = useForm({
    initialValues: {
        file: "",
        featured: 0,
        caption: "",
    },
});

watch(isOpen, (newIsOpen) => {
    if (newIsOpen) {
        form.setValues({
            caption: props.row.caption,
            featured: props.row.featured,
        });
    } else {
        form.setFieldError("file", undefined);
    }
});

const { isPending, mutate } = useFormMutation(
    async (formData) =>
        await fetcher.post(`/galleries/${props.row.id}`, formData),
    form,
    {
        onSuccess: ({ message: description }: any) => {
            queryClient.invalidateQueries(galleryQueryKeys.all as any);
            toast({ description });
            isOpen.value = false;
        },
    },
);

const onSubmit = form.handleSubmit(async (data) => {
    const formData = new FormData();
    formData.append("_method", "PUT");
    Object.entries(data).forEach(([key, value]) => {
        if (value || typeof value === "number") {
            formData.append(key, value as any);
        }
    });

    mutate(formData);
});

const { isPending: isDeletePending, mutate: onDelete } = useMutation(
    async () => await fetcher.delete(`/galleries/${props.row.id}`),
    {
        onSuccess: ({ message: description }: any) => {
            queryClient.invalidateQueries(galleryQueryKeys.all as any);
            toast({ description });
            isOpen.value = false;
        },
    },
);
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger asChild>
            <slot />
        </DialogTrigger>
        <DialogContent class="min-w-max">
            <DialogHeader class="flex items-start">
                <DialogTitle>Review your image</DialogTitle>
                <DialogDescription>
                    Only images are allowed. Max file size: 5MB.
                </DialogDescription>
            </DialogHeader>

            <div class="flex flex-col space-y-4">
                <div class="flex flex-col gap-y-2">
                    <FormField v-slot="{ componentField }" name="caption">
                        <FormItem>
                            <FormControl>
                                <FormLabel>Caption</FormLabel>
                                <Textarea
                                    v-bind="componentField"
                                    :class="
                                        cn(
                                            form.errors.value?.caption &&
                                                'border border-destructive',
                                        )
                                    "
                                />
                                <FormMessage />
                            </FormControl>
                        </FormItem>
                    </FormField>
                    <FormField
                        v-slot="{ componentField, setValue, value }"
                        name="featured"
                    >
                        <FormItem>
                            <FormControl>
                                <FormLabel>Featured</FormLabel>
                                <div class="flex items-center space-x-2">
                                    <Switch
                                        id="airplane-mode"
                                        v-bind="componentField"
                                        @update:model-value="
                                            setValue($event ? 1 : 0)
                                        "
                                    />
                                    <Label for="airplane-mode">
                                        {{
                                            value
                                                ? "Yes, this featured"
                                                : "No, this not featured"
                                        }}
                                    </Label>
                                </div>
                                <FormMessage />
                            </FormControl>
                        </FormItem>
                    </FormField>
                </div>
                <FileUpload
                    label="Upload image"
                    :error="form.errors.value?.file"
                    @file-selected="
                        ($event) => {
                            form.setFieldError('file', undefined);
                            form.setFieldValue('file', $event as any);
                        }
                    "
                />
            </div>
            <DialogFooter>
                <Button
                    :disabled="isPending || isDeletePending"
                    size="icon"
                    variant="destructive"
                    @click="onDelete"
                >
                    <span class="sr-only">Delete</span>
                    <Trash2Icon />
                </Button>
                <Button
                    :disabled="isPending || isDeletePending"
                    @click="onSubmit"
                >
                    Submit
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
