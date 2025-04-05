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
import { ICarouseImagelList } from "@/composables/use-settings";
import { useFormMutation } from "@/composables/use-form-mutation";
import { QueryClient, useQueryClient } from "@tanstack/vue-query";

const isOpen = ref<boolean>(false);
const queryClient: QueryClient = useQueryClient();
const context = ref<string>("hero_carousel_image");

const props = defineProps<{
    item?: ICarouseImagelList;
    items: ICarouseImagelList[];
}>();

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

watch(isOpen, () => {
    if (props.item) {
        form.setValues({
            title: props.item.title,
            summary: props.item.summary,
            file: props.item.file,
        });
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
        onError: (error: any) => {
            const err = error.response.data.errors;
            if (err) {
                const index = !props.item
                    ? props.items.length
                    : props.items.findIndex((i) => i.id === props.item?.id);

                const prefix = `${context.value}.${index}`;

                const getError = (field: string) =>
                    err?.[`${prefix}.${field}`]?.[0];

                form.setErrors({
                    title: getError("title"),
                    summary: getError("summary"),
                    file: getError("file"),
                });
            }
        },
    },
);

const onSubmit = form.handleSubmit(async (data) => {
    const rawItems = isProxy(props.items) ? toRaw(props.items) : props.items;

    const formData = new FormData();
    formData.append("context", context.value);

    const filteredItems = props.item
        ? rawItems.filter((i) => i.id !== props.item?.id)
        : rawItems;

    const resultArray = [{ ...data }, ...filteredItems];

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
                <DialogTitle>
                    {{
                        item
                            ? "Update image carousel"
                            : "Add new image carousel"
                    }}
                </DialogTitle>
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
