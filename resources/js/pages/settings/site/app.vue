<script setup lang="ts">
import AppForm from "./components/app-form.vue";
import SettingsLayout from "./components/settings-layout.vue";
</script>

<template>
    <SettingsLayout
        title="App"
        description="Set the general app config of your site."
    >
        <AppForm />
    </SettingsLayout>
</template>
