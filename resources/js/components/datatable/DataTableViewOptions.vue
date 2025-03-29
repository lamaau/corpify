<script setup lang="ts">
import { computed } from "vue";
import { Button } from "@/components/ui/button";
import { MixerHorizontalIcon } from "@radix-icons/vue";
import {
  DropdownMenu,
  DropdownMenuTrigger,
  DropdownMenuContent,
  DropdownMenuCheckboxItem,
} from "@/components/ui/dropdown-menu";

const props = defineProps({
  table: { type: Object, required: true },
});

const columns = computed(() =>
  props.table
    .getAllColumns()
    .filter(
      (column: any) =>
        typeof column.accessorFn !== "undefined" && column.getCanHide()
    )
);
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger as-child>
      <Button variant="outline" size="sm" class="ml-auto hidden h-8 lg:flex">
        <MixerHorizontalIcon class="h-4 w-4" />
        <span class="sr-only">View Column</span>
      </Button>
    </DropdownMenuTrigger>
    <DropdownMenuContent align="end" class="w-[150px]">
      <DropdownMenuCheckboxItem
        v-for="column in columns"
        :key="column.id"
        class="capitalize"
        :checked="column.getIsVisible()"
        @update:checked="(value) => column.toggleVisibility(!!value)"
      >
        {{ column.id }}
      </DropdownMenuCheckboxItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
