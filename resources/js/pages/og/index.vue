<script setup lang="ts">
import { onMounted, ref, watch, nextTick } from "vue";
import { useOg } from "@/composables/use-og";
import OgItem from "./components/og-item.vue";
import { Button } from "@/components/ui/button";
import Page from "@/components/global-layout/basic-page.vue";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import OgPositionCategoryForm from "./components/og-position-category-form.vue";
import {
    PlusIcon,
    ZoomInIcon,
    ZoomOutIcon,
    Maximize2Icon,
    Minimize2Icon,
    ScanSearchIcon,
    ChevronDownIcon,
    ChevronUpIcon,
} from "lucide-vue-next";
import fetcher from "@/lib/fetcher";

const keyRef = ref<number>(0);
const vocApi = ref<any>(undefined);
const currentTab = ref<string | undefined>(undefined);

// TanStack query approach with deep cloning to ensure reactivity
const { fetchOg } = useOg();
const { data: queryData, isLoading } = fetchOg();

// Create a separate reactive ref to store a deep copy of the data
const data = ref<any>({});

// Function to deep clone the data to break reactivity references
const updateDataFromQuery = () => {
    if (queryData.value && Object.keys(queryData.value).length > 0) {
        // Use JSON parse/stringify for deep cloning to break reactive chains that might cause issues
        data.value = JSON.parse(JSON.stringify(queryData.value));
    }
};

// Direct fetch approach (for testing/comparison)
const _fetch = async () => {
    try {
        const { data: apiResponse } = await fetcher.get("/og");
        // Store directly without going through TanStack
        data.value = apiResponse;
        refreshChart();
    } catch (error) {
        console.error("Failed to fetch OG data:", error);
    }
};

const initVue3OrgChart = ({ api }: any) => {
    vocApi.value = api;
};

const refreshChart = async () => {
    // Force component re-render by incrementing key
    keyRef.value++;

    // Wait for the next tick to ensure data has been updated
    await nextTick();

    if (data.value && Object.keys(data.value).length > 0) {
        // If current tab doesn't exist in the new data, set to first tab
        if (
            !currentTab.value ||
            !Object.keys(data.value).includes(currentTab.value)
        ) {
            currentTab.value = Object.keys(data.value)[0];
        }
        // Otherwise, keep the current tab selection
    }
};

onMounted(() => {
    // Initial check
    if (queryData.value) {
        updateDataFromQuery();
        refreshChart();
    }
});

// Watch for changes in the query data and update our local copy
watch(
    [queryData, isLoading],
    async ([newData, newLoading]) => {
        if (newData && !newLoading) {
            updateDataFromQuery();
            await refreshChart();
        }
    },
    { deep: true },
);
</script>
<template>
    <Page
        title="Organization"
        description="Organize your organization structure here"
        sticky
    >
        <Tabs v-if="currentTab && data" v-model="currentTab" :key="keyRef">
            <div class="flex flex-wrap gap-2 justify-between mb-4">
                <div>
                    <TabsList>
                        <TabsTrigger
                            v-for="(label, i) in Object.keys(data)"
                            :key="i"
                            :value="label"
                        >
                            {{ label }}
                        </TabsTrigger>
                    </TabsList>
                    <OgPositionCategoryForm>
                        <Button class="ml-2" size="sm">
                            Add New
                            <PlusIcon />
                        </Button>
                    </OgPositionCategoryForm>
                </div>
                <div class="inline-flex gap-x-2">
                    <Button
                        variant="outline"
                        size="sm"
                        @click="() => vocApi?.zoomReset()"
                    >
                        <ScanSearchIcon :size="18" />
                        <span class="sr-only">Reset</span>
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        @click="() => vocApi?.zoomIn()"
                    >
                        <ZoomInIcon :size="18" />
                        <span class="sr-only">Zoom In</span>
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        @click="() => vocApi?.zoomOut()"
                    >
                        <ZoomOutIcon :size="18" />
                        <span class="sr-only">Zoom Out</span>
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        @click="() => vocApi?.expandAll()"
                    >
                        <Maximize2Icon :size="18" />
                        <span class="sr-only">Expand All</span>
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        @click="() => vocApi?.collapseAll()"
                    >
                        <Minimize2Icon :size="18" />
                        <span class="sr-only">Collapse All</span>
                    </Button>
                </div>
            </div>

            <TabsContent
                v-for="(label, i) in Object.keys(data)"
                :key="`content-${i}`"
                :value="label"
            >
                <div class="w-full">
                    <div
                        class="relative rounded border bg-card shadow min-h-[70vh] overflow-hidden"
                    >
                        <vue3-org-chart
                            v-if="data[label]"
                            :data="data[label]"
                            @on-ready="initVue3OrgChart"
                            class="h-full w-full min-h-[70vh] bg-grid"
                            :style="{
                                '--vue3-org-chart-line-color':
                                    'hsl(var(--primary))',
                            }"
                        >
                            <template
                                #node="{ item, children, open, toggleChildren }"
                            >
                                <div class="flex flex-col items-center gap-2">
                                    <OgItem
                                        :item="item"
                                        :children="children"
                                        :open="open"
                                    />
                                    <div
                                        v-if="children && children.length"
                                        class="flex justify-center mt-1"
                                    >
                                        <button
                                            class="h-6 w-6 rounded-full flex items-center justify-center transition-colors"
                                            :class="[
                                                open
                                                    ? 'bg-primary text-primary-foreground hover:bg-primary/90'
                                                    : 'bg-muted text-muted-foreground hover:bg-muted/80',
                                            ]"
                                            @click="toggleChildren"
                                        >
                                            <ChevronDownIcon v-if="!open" />
                                            <ChevronUpIcon v-else />
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <template #no-data>
                                <div
                                    class="h-full w-full flex flex-col items-center justify-center text-muted-foreground p-8 gap-4"
                                >
                                    <span>No data available</span>
                                </div>
                            </template>
                        </vue3-org-chart>
                    </div>
                </div>
            </TabsContent>
        </Tabs>
    </Page>
</template>
