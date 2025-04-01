<script setup lang="ts">
import { watch, ref } from "vue";
import { pickBy, debounce } from "lodash";
import { Table } from "@tanstack/vue-table";
import { Loader2Icon } from "lucide-vue-next";
import { Cross2Icon } from "@radix-icons/vue";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import DataTableViewOptions from "./DataTableViewOptions.vue";

type TSearch = {
    loading: boolean;
};

defineProps<{
    table: Table<any>;
    search: TSearch;
}>();

const emit = defineEmits(["onSearch"]);

const server = ref({
    search: "",
});

const resetFilterAndSort = () => {
    Object.keys(server.value).forEach((key) => {
        // @ts-ignore
        server.value[key] = null;
    });
};

const onSearch = debounce((event) => {
    server.value.search = event.target.value;
}, 500);

watch(
    server,
    () => {
        pickBy(server.value);
        emit("onSearch", server.value);
    },
    { deep: true },
);
</script>
<template>
    <div class="flex items-center justify-between">
        <div class="mb-4 gap-4 flex flex-1 items-center">
            <div class="relative">
                <Input
                    class="h-8 w-[150px] lg:w-[250px]"
                    placeholder="Search here.."
                    type="search"
                    @input="onSearch"
                />
                <div
                    v-if="search.loading"
                    class="absolute inset-y-0 right-0 flex items-center pr-2"
                >
                    <Loader2Icon class="h-4 w-4 animate-spin text-gray-400" />
                </div>
            </div>

            <!-- slot -->
            <slot name="left-toolbar" />
        </div>

        <div class="mb-4 flex flex-row gap-4 items-center">
            <slot name="right-toolbar" />
            <DataTableViewOptions :table="table" />
        </div>
    </div>
</template>
