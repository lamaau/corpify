<script setup lang="ts">
import fetcher from "@/lib/fetcher";
import { useRouter } from "vue-router";
import { useForm } from "vee-validate";
import { ref, onMounted, watch } from "vue";
import { Input } from "@/components/ui/input";
import { toast } from "@/components/ui/toast";
import Combobox from "@/components/select.vue";
import { Label } from "@/components/ui/label";
import { Button } from "@/components/ui/button";
import { postQueryKeys } from "@/enums/query-keys";
import { useQueryClient } from "@tanstack/vue-query";
import { usePostQuery } from "@/composables/use-post";
import { config } from "@/plugins/echo-editor/config";
import FileUpload from "@/components/file-upload.vue";
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
const queryClient = useQueryClient();

const bodyRef = ref<string>("");
const thumbnailPreview = ref<string | undefined>();

const { postId } = router.currentRoute.value.params as { postId: string };

const { useShowQuery } = usePostQuery();
const { data: detailPostQuery, isLoading } = useShowQuery(postId);

const form = useForm();

const { isPending, mutate } = useFormMutation(
    async (formData) => await fetcher.post(`/posts/${postId}`, formData),
    form,
    {
        onSuccess: ({ message: description }: any) => {
            queryClient.invalidateQueries(postQueryKeys.detail(postId) as any);

            toast({ description });
            router.push({
                name: "publication.post.index",
            });
        },
    },
);

const fillValues = () => {
    if (detailPostQuery.value) {
        bodyRef.value = detailPostQuery.value.body;
        thumbnailPreview.value = detailPostQuery.value.thumbnail;

        form.setValues({
            title: detailPostQuery.value.title,
            summary: detailPostQuery.value.summary,
            status: {
                value: detailPostQuery.value.status.value,
                name: detailPostQuery.value.status.name,
            },
            post_category_id: {
                id: detailPostQuery.value.category.id,
                category_name: detailPostQuery.value.category.category_name,
            },
        });
    }
};

onMounted(() => {
    config.setUp();
    fillValues();
});

watch([detailPostQuery, isLoading], fillValues, { deep: true });

const handleFile = (file: File | null) => {
    form.setFieldValue("file", file as any);
};

const onSubmit = form.handleSubmit(async (data) => {
    const formData = new FormData();

    formData.append("_method", "PUT");

    const newData = {
        ...data,
        body: bodyRef.value,
        status: data.status?.value,
        post_category_id: data.post_category_id?.id ?? null,
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
        title="Review publication"
        description="Review publication and make some changes here if you want"
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
                                <FormControl class="flex flex-col gap-y-2">
                                    <Label>Title</Label>
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
                            :file="thumbnailPreview"
                            @file-selected="handleFile"
                        />

                        <FormField
                            v-slot="{ componentField }"
                            name="post_category_id"
                        >
                            <FormItem>
                                <FormLabel>Category</FormLabel>
                                <FormControl>
                                    <Combobox
                                        v-bind="componentField"
                                        api-url="/post-categories"
                                        id-key="id"
                                        name-key="category_name"
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
