<script setup lang="ts">
import ContactForm from "./components/contact-form.vue";
import SettingsLayout from "./components/settings-layout.vue";
</script>

<template>
    <SettingsLayout
        title="Contact"
        description="Set the contact person of your site."
    >
        <ContactForm />
    </SettingsLayout>
</template>
