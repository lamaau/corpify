<script setup lang="ts">
import SettingsLayout from "./components/settings-layout.vue";
import CarouselImageForm from "./components/carousel-image-form.vue";
</script>

<template>
    <SettingsLayout
        title="Hero Image Carousel"
        description="Set the social media of your site."
    >
        <CarouselImageForm />
    </SettingsLayout>
</template>
