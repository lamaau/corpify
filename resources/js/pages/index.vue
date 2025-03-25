<script setup lang="ts">
import Loading from '@/components/loading.vue'
import { useRouter } from 'vue-router'

const router = useRouter()
router.push({ name: 'dashboard' })
</script>

<template>
  <div class="flex items-center justify-center w-screen h-screen">
    <Loading />
  </div>
</template>

<route lang="yaml">
  meta:
    layout: blank
</route>
