<script setup lang="ts">
import { watch, ref } from "vue";
import { pickBy, debounce } from "lodash";
import { Loader2Icon } from "lucide-vue-next";
import { Cross2Icon } from "@radix-icons/vue";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import DataTableViewOptions from "./DataTableViewOptions.vue";
// import DataTableFacetedFilter from "./DataTableFacetedFilter.vue";

const props = defineProps({
  table: { type: Object, required: true },
  toolbars: { type: Object, required: false },
});

const emit = defineEmits(["onSearch"]);

const server = ref({
  search: "",
});

const resetFilterAndSort = () => {
  Object.keys(server.value).forEach((key) => {
    server.value[key] = null;
  });
};

const onSearch = debounce((event) => {
  server.value.search = event.target.value;
}, 500);

watch(
  server,
  () => {
    let params = pickBy(server.value);
    emit("onSearch", server.value);
  },
  { deep: true }
);
</script>
<template>
  <div class="flex items-center justify-between gap-4">
    <div class="mb-4 gap-4 flex flex-1 items-center space-x-2">
      <div v-if="toolbars" class="relative">
        <Input
          v-if="toolbars.hasOwnProperty('search')"
          :placeholder="toolbars.search.placeholder"
          v-model="searchValue"
          type="search"
          @input="onSearch"
        />
        <div
          v-if="toolbars.search.loading"
          class="absolute inset-y-0 right-0 flex items-center pr-2"
        >
          <Loader2Icon class="h-4 w-4 animate-spin text-gray-400" />
        </div>
      </div>

      <!-- <DataTableFacetedFilter
                v-if="table.getColumn('priority')"
                :column="table.getColumn('priority')"
                title="Priority"
                :options="priorities"
            /> -->

      <Button v-show="''.length" variant="ghost" @click="resetFilterAndSort">
        Reset
        <Cross2Icon class="ml-2 h-4 w-4" />
      </Button>
    </div>

    <div class="mb-4 flex flex-row gap-4 items-center">
      <slot name="right-action" />
      <!-- <DataTableViewOptions :table="table" /> -->
    </div>
  </div>
</template>
