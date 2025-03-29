<script setup lang="ts">
import { valueUpdater } from "@/lib/utils";
import { onMounted, ref, useSlots } from "vue";
import DataTablePagination from "./DataTablePagination.vue";
import { FlexRender, useVueTable, getCoreRowModel } from "@tanstack/vue-table";
import {
  Table,
  TableRow,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
} from "@/components/ui/table";
import DataTableToolbar from "./DataTableToolbar.vue";
import fetcher from "@/lib/fetcher";

const slots = useSlots();

const props = defineProps({
  columns: { type: Object, required: true },
  url: { type: String, required: true },
});

const data = ref({});
const sorting = ref([]);
const loading = ref(false);
const rowSelection = ref({});
const columnFilters = ref([]);
const columnVisibility = ref({});

const fetch = async (params: any = {}) => {
  loading.value = true;

  try {
    const { data: apiResponse } = await fetcher.get(props.url, {
      params: params,
    });

    data.value = apiResponse;

    return await apiResponse;
  } catch (error) {
    console.log(error);
  } finally {
    loading.value = false;
  }
};

const reload = () => {
  fetch();
};

onMounted(() => {
  fetch();
});

defineExpose({
  reload,
});

const handleQueryChanged = (params: any) => {
  fetch(params);
};

const handleOnSearch = (query: any) => {
  fetch(query);
};

const table = useVueTable({
  manualPagination: true,
  pageCount: data.value.to ?? 0,
  get data() {
    return data.value.data ?? [];
  },
  get columns() {
    return props.columns as any;
  },
  state: {
    get sorting() {
      return sorting.value;
    },
    get columnFilters() {
      return columnFilters.value;
    },
    get columnVisibility() {
      return columnVisibility.value;
    },
    get rowSelection() {
      return rowSelection.value;
    },
  },
  enableRowSelection: true,
  onSortingChange: (updaterOrValue) => valueUpdater(updaterOrValue, sorting),
  onColumnFiltersChange: (updaterOrValue) =>
    valueUpdater(updaterOrValue, columnFilters),
  onColumnVisibilityChange: (updaterOrValue) => {
    valueUpdater(updaterOrValue, columnVisibility);
  },
  onRowSelectionChange: (updaterOrValue) =>
    valueUpdater(updaterOrValue, rowSelection),
  getCoreRowModel: getCoreRowModel(),
});
</script>
<template>
  <div class="flex-1 overflow-auto">
    <div
      class="inline-flex items-center w-full pt-4"
      :class="{
        'justify-between': slots.toolbar,
        'justify-end': !slots.toolbar,
      }"
    >
      <slot name="toolbar" />
      <DataTableToolbar
        :table
        :toolbars="{
          search: {
            loading: loading,
            placeholder: 'Cari disini.',
          },
        }"
        @on-search="handleOnSearch"
      />
    </div>

    <template v-if="Object.keys(data).length">
      <div class="rounded-md border mb-4">
        <Table>
          <TableHeader>
            <TableRow
              v-for="headerGroup in table.getHeaderGroups()"
              :key="headerGroup.id"
            >
              <TableHead v-for="header in headerGroup.headers" :key="header.id">
                <FlexRender
                  v-if="!header.isPlaceholder"
                  :render="header.column.columnDef.header"
                  :props="header.getContext()"
                />
              </TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <template v-if="table.getRowModel().rows?.length">
              <TableRow
                v-for="row in table.getRowModel().rows"
                :key="row.id"
                :data-state="row.getIsSelected() && 'selected'"
              >
                <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                  <FlexRender
                    :render="cell.column.columnDef.cell"
                    :props="cell.getContext()"
                  />
                </TableCell>
              </TableRow>
            </template>

            <TableRow v-else>
              <TableCell
                :colspan="columns.length"
                class="h-24 text-center text-muted-foreground"
              >
                Tidak ada hasil untuk ditampilkan.
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </div>

      <DataTablePagination
        :table="table"
        :total="data.total"
        :from="data.from"
        :to="data.to"
        :last-page="data.last_page"
        :current-page="data.current_page"
        @query-changed="handleQueryChanged"
      />
    </template>
  </div>
</template>
