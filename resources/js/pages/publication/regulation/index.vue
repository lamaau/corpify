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
    <BasicPage title="Regulation" description="All of your regulation" sticky>
        <template #actions>
            <CreateForm :tableRef="tableRef" />
        </template>
        <DataTable ref="tableRef" url="/regulations" :columns="columns" />
    </BasicPage>
</template>
