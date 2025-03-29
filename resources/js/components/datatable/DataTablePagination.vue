<script setup lang="ts">
import { ref, watch } from "vue";
import { pickBy } from "lodash";
import {
  ChevronLeftIcon,
  ChevronRightIcon,
  DoubleArrowLeftIcon,
  DoubleArrowRightIcon,
} from "@radix-icons/vue";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Button } from "@/components/ui/button";

const props = defineProps({
  table: { type: Object },
  total: { type: Number },
  from: { type: Number },
  to: { type: Number },
  currentPage: { type: Number },
  lastPage: { type: Number },
});

const pageSizes = [10, 20, 30, 40, 50];
const emit = defineEmits(["queryChanged"]);

const server = ref({
  page: 1,
  per_page: 10,
});

const onChangePageSize = (value) => {
  server.value.per_page = value;
};

watch(
  server,
  ({ per_page }) => {
    props.table.setPageSize(per_page);
    let params = pickBy(server.value);

    emit("queryChanged", params);
  },
  { deep: true }
);
</script>

<template>
  <div class="flex items-center justify-between">
    <!-- <div class="md:inline hidden flex-1 text-sm text-muted-foreground">
            {{ table.getFilteredSelectedRowModel().rows.length }} dari
            {{ table.getFilteredRowModel().rows.length }} baris dipilih.
        </div> -->
    <div class="flex items-center w-full justify-between">
      <div class="flex items-center md:space-x-2 space-x-0">
        <p class="md:inline hidden text-sm font-medium">Per halaman</p>
        <Select
          :model-value="`${server.per_page}`"
          @update:model-value="onChangePageSize"
        >
          <SelectTrigger class="h-8 w-[70px]">
            <SelectValue :placeholder="server.per_page" />
          </SelectTrigger>
          <SelectContent side="top">
            <SelectItem
              v-for="pageSize in pageSizes"
              :key="pageSize"
              :value="`${pageSize}`"
            >
              {{ pageSize }}
            </SelectItem>
          </SelectContent>
        </Select>
      </div>
      <div
        class="w-[200px] items-center justify-center text-sm font-medium md:flex hidden"
      >
        Dari {{ from }} ke {{ to }} dari {{ total }}
      </div>
      <div class="flex items-center space-x-2">
        <Button
          variant="outline"
          class="hidden h-8 w-8 p-0 lg:flex"
          :disabled="currentPage <= 1"
          @click="server.page = 1"
        >
          <span class="sr-only">Go to first page</span>
          <DoubleArrowLeftIcon class="h-4 w-4" />
        </Button>
        <Button
          variant="outline"
          class="h-8 w-8 p-0"
          :disabled="currentPage <= 1"
          @click="server.page--"
        >
          <span class="sr-only">Go to previous page</span>
          <ChevronLeftIcon class="h-4 w-4" />
        </Button>
        <Button
          variant="outline"
          class="h-8 w-8 p-0"
          :disabled="currentPage >= lastPage"
          @click="server.page++"
        >
          <span class="sr-only">Go to next page</span>
          <ChevronRightIcon class="h-4 w-4" />
        </Button>
        <Button
          variant="outline"
          class="hidden h-8 w-8 p-0 lg:flex"
          :disabled="currentPage >= lastPage"
          @click="server.page = lastPage"
        >
          <span class="sr-only">Go to last page</span>
          <DoubleArrowRightIcon class="h-4 w-4" />
        </Button>
      </div>
    </div>
  </div>
</template>
