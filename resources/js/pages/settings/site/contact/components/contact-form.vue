<script setup lang="ts">
import { onMounted, watch } from "vue";
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

const context: string = "contact";
const { data: dataContact } = useSettingsQuery(context);
const queryClient: QueryClient = useQueryClient();

const form = useForm({
    initialValues: {
        email: "",
        phone: "",
        context: "",
    },
});

const fillValues = (data: any) => {
    form.setValues({
        email: data.email,
        phone: data.phone,
        context: data.context,
    });
};

watch(dataContact, fillValues);

onMounted(() => {
    if (dataContact.value) {
        fillValues(dataContact.value);
    }
});

const { mutate, isPending } = useFormMutation(
    async (data) => fetcher.post(`/settings/site`, data),
    form,
    {
        onSuccess: ({ message: description }: any) => {
            toast({
                title: "Contact updated",
                description: description,
                variant: "default",
            });

            queryClient.invalidateQueries(
                settingQueryKeys.detail(context) as any,
            );
        },
    },
);

const onSubmit = form.handleSubmit((values) => mutate(values));
</script>

<template>
    <div>
        <h3 class="text-lg font-medium">Contact</h3>
        <p class="text-sm text-muted-foreground">
            Update your site contact information. This information will be used
            for site-related communications.
        </p>
    </div>
    <Separator class="my-4" />
    <div class="space-y-6">
        <FormField v-slot="{ componentField }" name="email">
            <FormItem>
                <FormLabel>Email Address</FormLabel>
                <FormControl>
                    <Input type="text" v-bind="componentField" />
                </FormControl>
                <FormDescription>
                    This is the email that will be displayed on your site.
                </FormDescription>
                <FormMessage />
            </FormItem>
        </FormField>

        <FormField v-slot="{ componentField }" name="phone">
            <FormItem>
                <FormLabel>Phone Number</FormLabel>
                <FormControl>
                    <Input type="text" v-bind="componentField" />
                </FormControl>
                <FormDescription>
                    This is the phone that will be displayed on your site.
                </FormDescription>
                <FormMessage />
            </FormItem>
        </FormField>

        <div class="flex justify-start">
            <Button type="button" :disabled="isPending" @click="onSubmit">
                Update contact
            </Button>
        </div>
    </div>
</template>
