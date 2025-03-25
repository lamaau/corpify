<script setup lang="ts">
import type { TSort } from './types'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import { ArrowDownAZ, ArrowDownZA, SlidersHorizontal } from 'lucide-vue-next'

const emits = defineEmits<{
  (e: 'update:sort', payload: TSort): void
}>()

const sort = defineModel<TSort>({ default: 'asc' })
watch(sort, (newValue) => {
  emits('update:sort', newValue!)
})
</script>

<template>
  <Select v-model:model-value="sort">
    <SelectTrigger class="w-16">
      <SelectValue>
        <SlidersHorizontal :size="16" />
      </SelectValue>
    </SelectTrigger>
    <SelectContent align="end">
      <SelectItem value="asc">
        <div class="flex items-center gap-4">
          <ArrowDownAZ :size="16" />
          <span>Ascending</span>
        </div>
      </SelectItem>
      <SelectItem value="desc">
        <div class="flex items-center gap-4">
          <ArrowDownZA :size="16" />
          <span>Descending</span>
        </div>
      </SelectItem>
    </SelectContent>
  </Select>
</template>

<style scoped>
</style>
