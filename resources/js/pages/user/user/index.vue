<script setup lang="ts">
import { createColumns } from "./components/columns";
import { ref, onMounted, shallowRef } from "vue";
import { DataTable } from "@/components/datatable";
import BasicPage from "@/components/global-layout/basic-page.vue";
import CreateForm from "./components/create-form.vue";

const tableRef = shallowRef();
const columns = ref<ReturnType<typeof createColumns>>([]);

onMounted(() => {
    columns.value = createColumns(tableRef.value);
});
</script>

<template>
    <BasicPage title="Users" description="All of users displayed here" sticky>
        <template #actions>
            <CreateForm />
        </template>
        <DataTable ref="tableRef" url="/users" :columns="columns" />
    </BasicPage>
</template>
