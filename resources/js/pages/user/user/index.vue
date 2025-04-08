<script setup lang="ts">
import { ref, onMounted, shallowRef } from "vue";
import { DataTable } from "@/components/datatable";
import { createColumns } from "./components/columns";
import BasicPage from "@/components/global-layout/basic-page.vue";

const tableRef = shallowRef();
const columns = ref<ReturnType<typeof createColumns>>([]);

onMounted(() => {
    columns.value = createColumns(tableRef.value);
});
</script>

<template>
    <BasicPage title="Users" description="All of users displayed here" sticky>
        <DataTable
            ref="tableRef"
            url="/users?withRelations=[profile,roles]"
            :columns="columns"
        />
    </BasicPage>
</template>
