<script lang="ts" setup>
import { RefreshCcw } from 'lucide-vue-next'
</script>

<template>
  <UiTooltipProvider>
    <UiTooltip>
      <UiTooltipTrigger as-child>
        <UiButton variant="ghost" class="p-1">
          <RefreshCcw class="w-4 h-4" />
        </UiButton>
      </UiTooltipTrigger>
      <UiTooltipContent>
        <p>refresh</p>
      </UiTooltipContent>
    </UiTooltip>
  </UiTooltipProvider>
</template>

<style scoped>

</style>
