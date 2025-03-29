<script setup lang="ts">
import { createColumns } from "./table";
import { Button } from "@/components/ui/button";
import { ref, onMounted, shallowRef } from "vue";
import { DataTable } from "@/components/datatable";
import { Dialog, DialogContent, DialogTrigger } from "@/components/ui/dialog";

const tableRef = shallowRef();
const isDialogOpen = ref(false);
const columns = ref<ReturnType<typeof createColumns>>([]);

onMounted(() => {
  columns.value = createColumns(tableRef.value);
});
</script>

<template>
  <DataTable ref="tableRef" url="/programs" :columns="columns">
    <template #toolbar>
      <Dialog v-model:open="isDialogOpen">
        <DialogTrigger asChild>
          <Button size="sm" variant="default" @click="isDialogOpen = true">
            Tambah
          </Button>
        </DialogTrigger>
        <DialogContent>
          <div>dwkndkw</div>
        </DialogContent>
      </Dialog>
    </template>
  </DataTable>
</template>
