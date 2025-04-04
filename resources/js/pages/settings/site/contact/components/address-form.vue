<script setup lang="ts">
import { onMounted, watch } from "vue";
import fetcher from "@/lib/fetcher";
import { useForm } from "vee-validate";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { Textarea } from "@/components/ui/textarea";
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

const context: string = "address";
const { data: dataAddress } = useSettingsQuery(context);
const queryClient: QueryClient = useQueryClient();

const form = useForm({
    initialValues: {
        building_name: "",
        address_line_1: "",
        address_line_2: "",
        context: context,
    },
});

const fillValues = (data: any) => {
    form.setValues({
        building_name: data.building_name,
        address_line_1: data.address_line_1,
        address_line_2: data.address_line_2,
        context: data.context ?? context,
    });
};

watch(dataAddress, fillValues);

onMounted(() => {
    if (dataAddress.value) {
        fillValues(dataAddress.value);
    }
});

const { mutate, isPending } = useFormMutation(
    async (data) => await fetcher.post(`/settings/site`, data),
    form,
    {
        onSuccess: ({ message: description }: any) => {
            toast({
                title: "Address updated",
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
        <h3 class="text-lg font-medium">Address</h3>
        <p class="text-sm text-muted-foreground">
            Update your site address information. This information will be used
            for site-related communications.
        </p>
    </div>
    <Separator class="my-4" />
    <div class="space-y-6">
        <FormField v-slot="{ componentField }" name="building_name">
            <FormItem>
                <FormLabel>Building Name</FormLabel>
                <FormControl>
                    <Input type="text" v-bind="componentField" />
                </FormControl>
                <FormDescription>
                    This is the building name that will be displayed on your
                    site.
                </FormDescription>
                <FormMessage />
            </FormItem>
        </FormField>

        <FormField v-slot="{ componentField }" name="address_line_1">
            <FormItem>
                <FormLabel>Address Line 1</FormLabel>
                <FormControl>
                    <Textarea type="text" v-bind="componentField" />
                </FormControl>
                <FormDescription>
                    This is the road name that will be displayed on your site.
                </FormDescription>
                <FormMessage />
            </FormItem>
        </FormField>
        <FormField v-slot="{ componentField }" name="address_line_2">
            <FormItem>
                <FormLabel>Address Line 2</FormLabel>
                <FormControl>
                    <Textarea type="text" v-bind="componentField" />
                </FormControl>
                <FormDescription>
                    This is the road name that will be displayed on your site.
                </FormDescription>
                <FormMessage />
            </FormItem>
        </FormField>

        <div class="flex justify-start">
            <Button type="button" :disabled="isPending" @click="onSubmit">
                Update address
            </Button>
        </div>
    </div>
</template>
