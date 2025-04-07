<script setup lang="ts">
import { ref, onMounted, watch } from "vue";
import fetcher from "@/lib/fetcher";
import { useForm } from "vee-validate";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { Separator } from "@/components/ui/separator";
import { useSettingsQuery } from "@/composables/use-settings";
import { useFormMutation } from "@/composables/use-form-mutation";
import {
    FormControl,
    FormDescription,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/components/ui/form";
import { toast } from "@/components/ui/toast";
import { QueryClient, useQueryClient } from "@tanstack/vue-query";
import { settingQueryKeys } from "@/enums/query-keys";
import FileUpload from "@/components/file-upload.vue";
import { Textarea } from "@/components/ui/textarea";

const context = ref<string>("app");
const { data: dataApp } = useSettingsQuery(context.value);
const queryClient: QueryClient = useQueryClient();

const form = useForm({
    initialValues: {
        app_name: "",
        app_alias_name: "",
        app_description: "",
        file: null,
        context: context.value,
    },
});

const fillValues = (data: any) => {
    form.setValues({
        app_name: data.app_name,
        app_alias_name: data.app_alias_name,
        app_description: data.app_description,
        file: data.file,
        context: context.value,
    });
};

watch(dataApp, fillValues);

onMounted(() => {
    if (dataApp.value) {
        fillValues(dataApp.value);
    }
});

const { mutate, isPending } = useFormMutation(
    async (formData) => fetcher.post(`/settings/site`, formData),
    form,
    {
        onSuccess: ({ message: description }: any) => {
            toast({
                title: "App updated",
                description: description,
                variant: "default",
            });

            queryClient.invalidateQueries(
                settingQueryKeys.detail(context.value) as any,
            );
        },
    },
);

const onSubmit = form.handleSubmit((data) => {
    const formData = new FormData();
    Object.entries(data).forEach(([key, value]) => {
        if (value) {
            formData.append(key, value as any);
        }
    });

    console.log(formData);

    mutate(formData);
});
</script>

<template>
    <div>
        <h3 class="text-lg font-medium">App</h3>
        <p class="text-sm text-muted-foreground">
            Update your site app information. This information will be used for
            site-related communications.
        </p>
    </div>
    <Separator class="my-4" />
    <div class="space-y-6">
        <FormField v-slot="{ componentField }" name="app_name">
            <FormItem>
                <FormLabel>App Name</FormLabel>
                <FormControl>
                    <Input type="text" v-bind="componentField" />
                </FormControl>
                <FormDescription>
                    This is the app name that will be displayed on your site.
                </FormDescription>
                <FormMessage />
            </FormItem>
        </FormField>

        <FormField v-slot="{ componentField }" name="app_alias_name">
            <FormItem>
                <FormLabel>App Alias Name</FormLabel>
                <FormControl>
                    <Input type="text" v-bind="componentField" />
                </FormControl>
                <FormDescription>
                    This is the app alias name that will be displayed on your
                    site.
                </FormDescription>
                <FormMessage />
            </FormItem>
        </FormField>

        <FormField v-slot="{ componentField }" name="app_description">
            <FormItem>
                <FormLabel>App Description</FormLabel>
                <FormControl>
                    <Textarea type="text" v-bind="componentField" />
                </FormControl>
                <FormDescription>
                    This is the description that will be displayed on your site.
                </FormDescription>
                <FormMessage />
            </FormItem>
        </FormField>

        <div>
            <FileUpload
                label="App Logo"
                :error="form.errors.value?.file"
                :file="dataApp?.file"
                @file-selected="
                    ($event) => {
                        form.setFieldValue('file', $event as any);
                        form.setFieldError('file', undefined);
                    }
                "
            />
            <span class="text-sm text-muted-foreground block mt-2">
                This is the app logo that will be displayed on your site.
            </span>
        </div>

        <div class="flex justify-start">
            <Button type="button" :disabled="isPending" @click="onSubmit">
                Update App
            </Button>
        </div>
    </div>
</template>
