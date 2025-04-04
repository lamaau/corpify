<script setup lang="ts">
import { onMounted, watch } from "vue";
import fetcher from "@/lib/fetcher";
import { useForm, useFieldArray } from "vee-validate";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { Separator } from "@/components/ui/separator";
import IconPicker from "@/components/icon-picker.vue";
import { useSettingsQuery } from "@/composables/use-settings";
import { useFormMutation } from "@/composables/use-form-mutation";
import {
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/components/ui/form";
import { toast } from "@/components/ui/toast";
import { QueryClient, useQueryClient } from "@tanstack/vue-query";
import { settingQueryKeys } from "@/enums/query-keys";
import { PlusCircle, Trash2Icon } from "lucide-vue-next";

const context: string = "social_media";
const { data: dataSocialMedia } = useSettingsQuery(context);
const queryClient: QueryClient = useQueryClient();

const createEmptyEntry = () => {
    return { icon: "", name: "", link: "" };
};

const form = useForm({
    initialValues: {
        context: context,
        social_media: [createEmptyEntry()],
    },
});

// Use the useFieldArray hook to manage the array of fields
const { fields, push, remove, replace } = useFieldArray("social_media");

const fillValues = (data: any) => {
    if (
        data &&
        Array.isArray(data.social_media) &&
        data.social_media.length > 0
    ) {
        // Use replace to update the field array
        replace(JSON.parse(JSON.stringify(data.social_media)));
    } else {
        // If no data, reset to a single empty entry
        replace([createEmptyEntry()]);
    }

    // Set the context value
    form.setFieldValue("context", context);
};

watch(dataSocialMedia, fillValues);

onMounted(() => {
    if (dataSocialMedia.value) {
        fillValues(dataSocialMedia.value);
    }
});

const { mutate, isPending } = useFormMutation(
    async (data) => await fetcher.post(`/settings/site`, data),
    form,
    {
        onSuccess: ({ message: description }: any) => {
            toast({
                title: "Social Media updated",
                description: description,
                variant: "default",
            });

            queryClient.invalidateQueries(
                settingQueryKeys.detail(context) as any,
            );
        },
    },
);

const addSocialMedia = () => {
    push(createEmptyEntry());
};

const removeSocialMedia = (index: number) => {
    // Only remove if there's more than one entry
    if (fields.value.length > 1) {
        remove(index);
    } else {
        // If it's the last entry, clear it instead of removing
        form.setFieldValue(`social_media.0.icon`, "");
        form.setFieldValue(`social_media.0.name`, "");
        form.setFieldValue(`social_media.0.link`, "");
    }
};

const onSubmit = form.handleSubmit((values) => mutate(values));
</script>

<template>
    <div>
        <h3 class="text-lg font-medium">Social Media</h3>
        <p class="text-sm text-muted-foreground">
            Manage your site's social media links. These links will be displayed
            on your site.
        </p>
    </div>
    <Separator class="my-4" />
    <div class="space-y-6">
        <div v-for="(field, index) in fields" :key="field.key">
            <div class="grid md:grid-cols-4 gap-4 items-center">
                <!-- Icon Field -->
                <FormField
                    v-slot="{ componentField }"
                    :name="`social_media.${index}.icon`"
                    class="flex-1"
                >
                    <FormItem>
                        <FormLabel>Icon</FormLabel>
                        <FormControl>
                            <IconPicker v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <!-- Name Field -->
                <FormField
                    v-slot="{ componentField }"
                    :name="`social_media.${index}.name`"
                    class="flex-1"
                >
                    <FormItem>
                        <FormLabel>Name</FormLabel>
                        <FormControl>
                            <Input type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <!-- Link Field -->
                <FormField
                    v-slot="{ componentField }"
                    :name="`social_media.${index}.link`"
                    class="flex-2"
                >
                    <FormItem>
                        <FormLabel>Link</FormLabel>
                        <FormControl>
                            <Input type="url" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <!-- Delete Button -->
                <Button
                    type="button"
                    variant="ghost"
                    size="icon"
                    @click="removeSocialMedia(index)"
                    class="mt-8 hover:bg-destructive hover:text-destructive-foreground"
                >
                    <Trash2Icon class="h-4 w-4" />
                </Button>
            </div>
        </div>

        <Button
            type="button"
            variant="outline"
            @click="addSocialMedia"
            class="flex items-center gap-2"
        >
            <PlusCircle class="h-4 w-4" />
            Add More
        </Button>

        <div class="flex justify-start mt-6">
            <Button type="button" :disabled="isPending" @click="onSubmit">
                Update Social Media
            </Button>
        </div>
    </div>
</template>
