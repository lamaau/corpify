<script setup lang="ts">
import { onMounted, ref } from "vue";
import { useQuery } from "@tanstack/vue-query";
import { valueUpdater } from "@/lib/utils";
import DataTableToolbar from "./DataTableToolbar.vue";
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
import fetcher from "@/lib/fetcher";
import { tableQueryKeys } from "@/enums/query-keys";

const props = defineProps({
    columns: { type: Object, required: true },
    url: { type: String, required: true },
});

const sorting = ref([]);
const rowSelection = ref({});
const columnFilters = ref([]);
const columnVisibility = ref({});
const queryParams = ref({});

// Create a function to fetch data that will be used by the query
const fetchData = async (params: Record<string, any> = {}) => {
    // Remove empty parameters
    const filteredParams = Object.fromEntries(
        Object.entries(params).filter(([_, value]) => value !== ""),
    );

    const { data: apiResponse } = await fetcher.get(props.url, {
        params: Object.keys(filteredParams).length ? filteredParams : undefined,
    });

    return apiResponse;
};

const { data, isLoading, refetch } = useQuery({
    queryKey: tableQueryKeys.lists(props.url),
    queryFn: () => fetchData(queryParams.value),
});

const reload = () => {
    refetch();
};

const fetch = async (params: Record<string, any> = {}) => {
    queryParams.value = params;
    return refetch();
};

onMounted(() => {
    // Initial fetch is handled by the useQuery hook automatically
});

defineExpose({
    reload,
    fetch,
});

const handleQueryChanged = (params: any) => {
    fetch(params);
};

const handleOnSearch = (query: any) => {
    fetch(query);
};

const table = useVueTable({
    manualPagination: true,
    // Use optional chaining to prevent errors when data is not yet loaded
    get pageCount() {
        return data.value?.to ?? 0;
    },
    get data() {
        return data.value?.data ?? [];
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
    <div class="flex-1 overflow-auto px-4 py-4">
        <DataTableToolbar
            :table="table"
            :search="{ loading: isLoading }"
            @on-search="handleOnSearch"
        >
            <template #left-toolbar>
                <slot name="left-toolbar" :table="table" />
            </template>
        </DataTableToolbar>

        <template v-if="data">
            <div class="rounded-md border mb-4">
                <Table>
                    <TableHeader>
                        <TableRow
                            v-for="headerGroup in table.getHeaderGroups()"
                            :key="headerGroup.id"
                        >
                            <TableHead
                                v-for="header in headerGroup.headers"
                                :key="header.id"
                            >
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
                                <TableCell
                                    v-for="cell in row.getVisibleCells()"
                                    :key="cell.id"
                                >
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
                                <span v-if="isLoading">Loading data...</span>
                                <span v-else>Nothing found to display</span>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <DataTablePagination
                v-if="data"
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
