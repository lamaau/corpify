<script setup lang="ts">
import Toaster from "@/components/ui/toast/Toaster.vue";
</script>

<template>
  <div class="flex-1">
    <Toaster />
    <VueQueryDevtools />
    <router-view v-slot="{ Component, route }">
      <component :is="Component" :key="route" />
    </router-view>
  </div>
</template>
