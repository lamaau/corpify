<script setup lang="ts">
import { createColumns } from "./table";
import { ref, onMounted, shallowRef } from "vue";
import { DataTable } from "@/components/datatable";
import CreateForm from "./components/create-form.vue";

const tableRef = shallowRef();
const isDialogOpen = ref(false);
const columns = ref<ReturnType<typeof createColumns>>([]);

onMounted(() => {
    columns.value = createColumns(tableRef.value);
});
</script>

<template>
    <DataTable ref="tableRef" url="/galleries" :columns="columns">
        <template #toolbar>
            <CreateForm :isOpen="isDialogOpen" :tableRef="tableRef" />
        </template>
    </DataTable>
</template>
