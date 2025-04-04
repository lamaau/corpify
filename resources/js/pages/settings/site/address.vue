<script setup lang="ts">
import AddressForm from "./components/address-form.vue";
import SettingsLayout from "./components/settings-layout.vue";
</script>

<template>
    <SettingsLayout title="Address" description="Set the address of your site.">
        <AddressForm />
    </SettingsLayout>
</template>
