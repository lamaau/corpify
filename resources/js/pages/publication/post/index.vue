<script setup lang="ts">
import { PlusIcon } from "lucide-vue-next";
import { Button } from "@/components/ui/button";
import { ref, onMounted, shallowRef } from "vue";
import { DataTable } from "@/components/datatable";
import { createColumns } from "./components/columns";
import { usePostsStatusQuery } from "@/composables/use-constant";
import BasicPage from "@/components/global-layout/basic-page.vue";
import DataTableFacetedFilter from "@/components/datatable/DataTableFacetedFilter.vue";

const tableRef = shallowRef();
const columns = ref<ReturnType<typeof createColumns>>([]);
const { data: postStatusQuery } = usePostsStatusQuery();

onMounted(() => {
    columns.value = createColumns(tableRef);
});

const onFilterStatus = (selectedValues: Set<number>) => {
    tableRef.value.fetch({
        status: Array.from(selectedValues),
    });
};
</script>
<template>
    <BasicPage
        title="Publication"
        description="This show all of your publication"
        sticky
    >
        <template #actions>
            <Button @click="$router.push({ name: 'publication.post.create' })">
                Add New
                <PlusIcon />
            </Button>
        </template>
        <div class="w-[calc(100svw-2rem)] md:w-full overflow-x-auto">
            <DataTable ref="tableRef" url="/posts" :columns="columns">
                <template #left-toolbar="{ table }">
                    <DataTableFacetedFilter
                        v-if="postStatusQuery"
                        title="Status"
                        :options="postStatusQuery.data"
                        :column="table.getColumn('status')"
                        @onFilter="onFilterStatus"
                    />
                </template>
            </DataTable>
        </div>
    </BasicPage>
</template>
