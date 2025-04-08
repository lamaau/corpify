<script setup lang="ts">
import fetcher from "@/lib/fetcher";
import { ref, onMounted, watch } from "vue";
import { toast } from "@/components/ui/toast";
import { Button } from "@/components/ui/button";
import { settingQueryKeys } from "@/enums/query-keys";
import { Separator } from "@/components/ui/separator";
import { config } from "@/plugins/echo-editor/config";
import { useMutation } from "@/composables/use-mutation";
import { useSettingsQuery } from "@/composables/use-settings";
import { QueryClient, useQueryClient } from "@tanstack/vue-query";

const context = ref<string>("about_history");
const queryClient: QueryClient = useQueryClient();
const { data: dataApp } = useSettingsQuery(context.value);

const bodyRef = ref<string>("");

onMounted(() => {
    config.setUp();

    if (dataApp.value) {
        bodyRef.value = dataApp.value.content;
    }
});

watch(dataApp, ({ content }) => {
    if (content) {
        bodyRef.value = content;
    }
});

const { isPending, mutate } = useMutation(
    async (data) => fetcher.post(`/settings/site`, data),
    {
        onSuccess: ({ message: description }: any) => {
            toast({ description });
            queryClient.invalidateQueries(
                settingQueryKeys.detail(context.value) as any,
            );
        },
    },
);

const onSubmit = async () => {
    mutate({
        context: context.value,
        content: bodyRef.value,
    });
};
</script>

<template>
    <div>
        <h3 class="text-lg font-medium">History</h3>
        <p class="text-sm text-muted-foreground">
            Update your organization history information. This information will
            be showing up at your site
        </p>
    </div>
    <Separator class="my-4" />
    <div class="space-y-6">
        <echo-editor
            :extensions="config.extensions"
            v-model="bodyRef"
            class="min-h-96"
        />

        <div class="flex justify-start">
            <Button type="button" :disabled="isPending" @click="onSubmit">
                Update History
            </Button>
        </div>
    </div>
</template>
