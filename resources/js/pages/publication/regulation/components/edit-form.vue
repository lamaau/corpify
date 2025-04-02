<script setup lang="ts">
import { ref, defineEmits, onMounted, watch } from "vue";
import { Button } from "@/components/ui/button";
import {
    Dialog,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
    DialogDescription,
    DialogContent,
} from "@/components/ui/dialog";
import {
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/components/ui/form";
import fetcher from "@/lib/fetcher";
import { useForm } from "vee-validate";
import { toast } from "@/components/ui/toast";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { tableQueryKeys } from "@/enums/query-keys";
import FileUpload from "@/components/file-upload.vue";
import { useFormMutation } from "@/composables/use-form-mutation";
import {
    InvalidateQueryFilters,
    QueryClient,
    useQueryClient,
} from "@tanstack/vue-query";
import PreviewPdf from "./preview-pdf.vue";
import { IRowProps } from "../types";

const props = defineProps<IRowProps>();
const emit = defineEmits(["update:open"]);

const isOpen = ref<boolean>(false);
const queryClient: QueryClient = useQueryClient();

const form = useForm({
    initialValues: {
        title: "",
        summary: "",
        file: "",
    },
});

const fillValues = () => {
    form.setValues({
        title: props.row.title,
        summary: props.row.summary,
    });
};

onMounted(fillValues);

const { mutate, isPending } = useFormMutation(
    async (formData) => fetcher.post(`/regulations/${props.row.id}`, formData),
    form,
    {
        // @ts-ignore
        onSuccess: ({ message: description }) => {
            isOpen.value = false;
            toast({ description });
            queryClient.invalidateQueries(
                tableQueryKeys.lists(
                    "/regulations",
                ) as InvalidateQueryFilters<unknown>,
            );
        },
    },
);

const onSubmit = form.handleSubmit(async (data) => {
    const formData = new FormData();
    formData.append("_method", "PUT");
    Object.entries(data).forEach(([key, value]) => {
        if (value) {
            formData.append(key, value as any);
        }
    });

    mutate(formData);
});

watch(isOpen, (newValue) => {
    emit("update:open", newValue);
});
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger asChild>
            <slot />
        </DialogTrigger>
        <DialogContent class="min-w-max">
            <DialogHeader class="flex items-start">
                <DialogTitle>Review regulation</DialogTitle>
                <DialogDescription>
                    You can update this regulation
                </DialogDescription>
            </DialogHeader>

            <div class="flex flex-col space-y-4">
                <div class="flex flex-col gap-y-4">
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

                    <div>
                        <FileUpload
                            label="Upload PDF"
                            :error="form.errors.value?.file"
                            :file="row.file"
                            :allowedMimes="['application/pdf']"
                            @file-selected="
                                (file) => {
                                    form.setFieldValue('file', file as any);
                                    form.setFieldError('file', undefined);
                                }
                            "
                        />
                        <PreviewPdf :row="row">
                            <Button variant="link" class="px-0 mt-1">
                                Preview
                            </Button>
                        </PreviewPdf>
                    </div>
                </div>
            </div>
            <DialogFooter>
                <Button @click="onSubmit" :disabled="isPending">
                    Submit
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
