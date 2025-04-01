<script setup lang="ts">
import { defineProps, defineEmits } from "vue";
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
import { Label } from "@/components/ui/label";
import FileUpload from "@/components/file-upload.vue";
import {
    FormControl,
    FormField,
    FormItem,
    FormMessage,
} from "@/components/ui/form";
import fetcher, { handleFormSubmit } from "@/lib/fetcher";
import { toast } from "@/components/ui/toast";
import { useForm } from "vee-validate";
import { cn } from "@/lib/utils";

const emit = defineEmits(["reload"]);
const props = defineProps<{ row: any }>();

const open = defineModel({
    default: false,
});

const { isSubmitting, ...form } = useForm({
    initialValues: {
        caption: props.row.caption,
        file: "",
    },
});

const handleFile = (file: File | null) => {
    form.setFieldValue("file", file as any);
};

const onSubmit = handleFormSubmit(form, async (data) => {
    const formData = new FormData();
    formData.append("_method", "PUT");
    formData.append("caption", data.caption);
    if (data.file) {
        formData.append("file", data.file);
    }
    const { message } = await fetcher.post(
        `/galleries/${props.row.id}`,
        formData,
    );

    emit("reload");

    toast({
        description: message,
    });
});
</script>

<template>
    <Dialog v-model:open="open">
        <DialogTrigger asChild>
            <slot />
        </DialogTrigger>

        <DialogContent class="max-w-2xl">
            <DialogHeader class="flex items-start">
                <DialogTitle>Upload your file</DialogTitle>
                <DialogDescription>
                    Only images are allowed. Max file size: 5MB.
                </DialogDescription>
            </DialogHeader>

            <div class="flex flex-col space-y-4">
                <div class="flex flex-col gap-y-2">
                    <FormField v-slot="{ componentField }" name="caption">
                        <FormItem>
                            <FormControl>
                                <Label>Caption</Label>
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
                </div>
                <FileUpload
                    label="Upload image"
                    :file="row.file?.file_url"
                    @file-selected="handleFile"
                    :error="form.errors.value?.file"
                />
            </div>
            <DialogFooter>
                <Button :disabled="isSubmitting" @click="onSubmit">
                    Submit
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
