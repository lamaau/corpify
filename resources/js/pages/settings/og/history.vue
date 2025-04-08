<script setup lang="ts">
import HistoryForm from "./components/history-form.vue";
import SettingsLayout from "./components/settings-layout.vue";
</script>

<template>
    <SettingsLayout
        title="About us"
        description="Set the about us of your site."
    >
        <HistoryForm />
    </SettingsLayout>
</template>
