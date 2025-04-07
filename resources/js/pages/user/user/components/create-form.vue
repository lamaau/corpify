<script setup lang="ts">
import { ref } from "vue";
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
import { PlusIcon } from "lucide-vue-next";

const isDialogOpen = ref(false);

const { isSubmitting, ...form } = useForm({
    initialValues: {
        caption: "",
        file: "",
    },
});

const handleFile = (file: File | null) => {
    form.setFieldValue("file", file as any);
};

const onSubmit = handleFormSubmit(form, async (data) => {
    const formData = new FormData();
    formData.append("caption", data.caption);
    if (data.file) {
        formData.append("file", data.file);
    }
    const { message } = await fetcher.postForm("/galleries", formData);
    isDialogOpen.value = false;

    toast({
        description: message,
    });
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
