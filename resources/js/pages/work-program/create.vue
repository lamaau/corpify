<script setup lang="ts">
import fetcher from "@/lib/fetcher";
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useForm } from "vee-validate";
import { Input } from "@/components/ui/input";
import { toast } from "@/components/ui/toast";
import Combobox from "@/components/select.vue";
import { Button } from "@/components/ui/button";
import FileUpload from "@/components/file-upload.vue";
import { config } from "@/plugins/echo-editor/config";
import { useFormMutation } from "@/composables/use-form-mutation";
import BasicPage from "@/components/global-layout/basic-page.vue";
import {
    FormField,
    FormItem,
    FormMessage,
    FormControl,
    FormLabel,
} from "@/components/ui/form";
import {
    Card,
    CardTitle,
    CardHeader,
    CardContent,
    CardDescription,
} from "@/components/ui/card";
import { Textarea } from "@/components/ui/textarea";

const router = useRouter();

const bodyRef = ref<string>("");

const form = useForm();

const handleFile = (file: File | null) => {
    form.setFieldValue("file", file as any);
};

onMounted(() => {
    config.setUp();
});

const { isPending, mutate } = useFormMutation(
    async (formData) => await fetcher.post("/work-programs", formData),
    form,
    {
        onSuccess: ({ message: description }: any) => {
            toast({ description });
            router.push({ name: "work-program.index" });
        },
    },
);

const onSubmit = form.handleSubmit(async (data) => {
    const formData = new FormData();

    const newData = {
        ...data,
        body: bodyRef.value,
        status: data.status?.value,
        program_id: data.program_id?.id ?? null,
    };

    Object.entries(newData).forEach(([key, value]) => {
        if (value) {
            formData.append(key, value as any);
        }
    });

    mutate(formData);
});
</script>

<template>
    <BasicPage
        title="Add new work program"
        description="You can create new work program here"
    >
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2">
                <echo-editor
                    :extensions="config.extensions"
                    v-model="bodyRef"
                    class="min-h-96"
                />
            </div>

            <div class="lg:col-span-1">
                <Card>
                    <CardHeader>
                        <CardTitle>Activity Details</CardTitle>
                        <CardDescription>
                            Configure your activity information
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <FormField v-slot="{ componentField }" name="title">
                            <FormItem>
                                <FormLabel>Title</FormLabel>
                                <FormControl class="flex flex-col gap-y-2">
                                    <Input v-bind="componentField" />
                                    <FormMessage />
                                </FormControl>
                            </FormItem>
                        </FormField>

                        <FormField v-slot="{ componentField }" name="summary">
                            <FormItem>
                                <FormLabel>Summary</FormLabel>
                                <FormControl class="flex flex-col gap-y-2">
                                    <Textarea v-bind="componentField" />
                                    <FormMessage />
                                </FormControl>
                            </FormItem>
                        </FormField>

                        <FileUpload
                            label="Thumbnail"
                            @file-selected="handleFile"
                            :error="form.errors.value?.file"
                        />

                        <FormField
                            v-slot="{ componentField }"
                            name="program_id"
                        >
                            <FormItem>
                                <FormLabel>Program</FormLabel>
                                <FormControl>
                                    <Combobox
                                        v-bind="componentField"
                                        api-url="/programs"
                                        id-key="id"
                                        name-key="name"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <FormField v-slot="{ componentField }" name="status">
                            <FormItem>
                                <FormLabel>Publish Status</FormLabel>
                                <FormControl>
                                    <Combobox
                                        v-bind="componentField"
                                        api-url="/constant/posts"
                                        id-key="value"
                                        name-key="name"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <div class="flex justify-end mt-4">
                            <Button @click="onSubmit" :disabled="isPending">
                                Submit Publication
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </BasicPage>
</template>
