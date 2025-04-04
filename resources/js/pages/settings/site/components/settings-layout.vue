<script setup lang="ts">
import SettingsAside from "./settings-aside.vue";
import Page from "@/components/global-layout/basic-page.vue";

defineProps<{
    title: string;
    description: string;
}>();
</script>

<template>
    <Page :title="title" :description="description" sticky>
        <main class="grid grid-cols-1 lg:grid-cols-[200px_1fr] gap-4 w-full">
            <SettingsAside />

            <section>
                <slot />
            </section>
        </main>
    </Page>
</template>
