<script setup lang="ts">
import { ref, onMounted, shallowRef } from "vue";
import { DataTable } from "@/components/datatable";
import { createColumns } from "./components/columns";
import CreateForm from "./components/create-form.vue";
import { useAbilityQuery } from "@/composables/use-ability";
import BasicPage from "@/components/global-layout/basic-page.vue";

const tableRef = shallowRef();
const columns = ref<ReturnType<typeof createColumns>>([]);

const { data: dataAbilities } = useAbilityQuery();

onMounted(() => {
    columns.value = createColumns(tableRef.value);
});
</script>

<template>
    <BasicPage
        title="User Abilities"
        description="All of abilities displayed here"
        sticky
    >
        <template #actions>
            <CreateForm :abilities="dataAbilities" />
        </template>
        <DataTable ref="tableRef" url="/roles" :columns="columns" />
    </BasicPage>
</template>
