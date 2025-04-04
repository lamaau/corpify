<script setup lang="ts">
import { isProxy, ref, toRaw, watch } from "vue";
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
import {
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/components/ui/form";
import fetcher from "@/lib/fetcher";
import { useForm } from "vee-validate";
import { Input } from "@/components/ui/input";
import { toast } from "@/components/ui/toast";
import { galleryQueryKeys } from "@/enums/query-keys";
import type { ICarouselList } from "@/composables/use-settings";
import { useFormMutation } from "@/composables/use-form-mutation";
import { QueryClient, useQueryClient } from "@tanstack/vue-query";

const isOpen = ref<boolean>(false);
const queryClient: QueryClient = useQueryClient();
const context = ref<string>("hero_carousel_image");

const props = defineProps<{ items: ICarouselList[] }>();

const form = useForm({
    initialValues: {
        file: "",
        title: "",
        summary: "",
        context: context.value,
    },
});

watch(isOpen, (newIsOpen) => {
    if (!newIsOpen) {
        form.setFieldError("file", undefined);
    }
});

const { isPending, mutate } = useFormMutation(
    async (formData) => await fetcher.post(`settings/site`, formData),
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
    formData.append("context", context.value);

    const resultArray = [
        ...(isProxy(props.items) ? toRaw(props.items) : props.items),
        { ...data },
    ];

    resultArray.forEach((item, index) => {
        Object.entries(item).forEach(([key, value]) => {
            if (value) {
                formData.append(
                    `hero_carousel_image[${index}][${key}]`,
                    value as any,
                );
            }
        });
    });

    mutate(formData);
});
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger asChild>
            <slot />
        </DialogTrigger>
        <DialogContent class="min-w-max">
            <DialogHeader class="flex items-start">
                <DialogTitle>Add new carousel</DialogTitle>
                <DialogDescription>
                    Only images are allowed. Max image size: 5MB.
                </DialogDescription>
            </DialogHeader>

            <div class="flex flex-col space-y-4">
                <div class="flex flex-col gap-y-2">
                    <FormField v-slot="{ componentField }" name="title">
                        <FormItem>
                            <FormControl>
                                <FormLabel>Title</FormLabel>
                                <Input v-bind="componentField" />
                                <FormMessage />
                            </FormControl>
                        </FormItem>
                    </FormField>

                    <FormField v-slot="{ componentField }" name="summary">
                        <FormItem>
                            <FormControl>
                                <FormLabel>Summary</FormLabel>
                                <Textarea v-bind="componentField" />
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
                <Button :disabled="isPending" @click="onSubmit">
                    Submit
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
