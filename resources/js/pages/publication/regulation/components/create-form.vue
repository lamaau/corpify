<script setup lang="ts">
import { ref } from "vue";
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
import { PlusIcon } from "lucide-vue-next";
import { toast } from "@/components/ui/toast";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { tableQueryKeys } from "@/enums/query-keys";
import FileUpload from "@/components/file-upload.vue";
import { useFormMutation } from "@/composables/use-form-mutation";
import { InvalidateQueryFilters, useQueryClient } from "@tanstack/vue-query";

const emit = defineEmits(["update:isOpen"]);

const isDialogOpen = ref<boolean>(false);
const queryClient = useQueryClient();

const form = useForm({
    initialValues: {
        title: "",
        summary: "",
        file: "",
    },
});

const { mutate, isPending } = useFormMutation(
    async (formData) => fetcher.post("/regulations", formData),
    form,
    {
        // @ts-ignore
        onSuccess: ({ message: description }) => {
            isDialogOpen.value = false;
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
    Object.entries(data).forEach(([key, value]) => {
        if (value) {
            formData.append(key, value as any);
        }
    });

    mutate(formData);
});
</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger asChild>
            <Button @click="isDialogOpen = true">
                Add New
                <PlusIcon />
            </Button>
        </DialogTrigger>
        <DialogContent class="min-w-max">
            <DialogHeader class="flex items-start">
                <DialogTitle>Add new regulation</DialogTitle>
                <DialogDescription>
                    Upload regulation from your organization
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

                    <FileUpload
                        label="Upload PDF"
                        :error="form.errors.value?.file"
                        :allowedMimes="['application/pdf']"
                        @file-selected="
                            (file) => {
                                form.setFieldValue('file', file as any);
                                form.setFieldError('file', undefined);
                            }
                        "
                    />
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
