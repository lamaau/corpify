<script setup lang="ts">
import { ref, onMounted, shallowRef } from "vue";
import { DataTable } from "@/components/datatable";
import { createColumns } from "./components/columns";
import CreateForm from "./components/create-form.vue";
import BasicPage from "@/components/global-layout/basic-page.vue";

const tableRef = shallowRef();
const columns = ref<ReturnType<typeof createColumns>>([]);

onMounted(() => {
    columns.value = createColumns(tableRef.value);
});
</script>

<template>
    <BasicPage title="Program" description="All of your program" sticky>
        <template #actions>
            <CreateForm :tableRef="tableRef" />
        </template>
        <DataTable ref="tableRef" url="/programs?withRelations=[features]" :columns="columns" />
    </BasicPage>
</template>
