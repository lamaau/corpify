<script setup lang="ts">
import fetcher from "@/lib/fetcher";
import OgItem from "./components/og-item.vue";
import { onMounted, ref, computed } from "vue";
import { Button } from "@/components/ui/button";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import {
    Maximize2Icon,
    Minimize2Icon,
    ScanSearchIcon,
    ZoomInIcon,
    ZoomOutIcon,
} from "lucide-vue-next";
import Page from "@/components/global-layout/basic-page.vue";

const keyRef = ref(0);
const vocApi = ref();
const data = ref<Record<string, any>>({});

const defaultTab = computed(() =>
    Object.keys(data.value).length > 0 ? Object.keys(data.value)[0] : "",
);

const initVue3OrgChart = ({ api }: any) => {
    vocApi.value = api;
};

onMounted(() => {
    fetcher.get("/og").then(({ data: response }: any) => {
        data.value = response;
        keyRef.value++;
    });
});
</script>

<template>
    <Page
        title="Organization"
        description="Organize your organization structure here"
        sticky
    >
        <Tabs v-if="defaultTab" :default-value="defaultTab" class="w-full">
            <div class="flex flex-wrap gap-2 justify-between mb-4">
                <TabsList>
                    <TabsTrigger
                        v-for="(label, i) in Object.keys(data)"
                        :key="i"
                        :value="label"
                    >
                        {{ label }}
                    </TabsTrigger>
                </TabsList>
                <div class="inline-flex gap-x-2">
                    <Button
                        variant="outline"
                        size="sm"
                        @click="() => vocApi.zoomReset()"
                    >
                        <ScanSearchIcon :size="18" />
                        <span class="sr-only">Reset</span>
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        @click="() => vocApi.zoomIn()"
                    >
                        <ZoomInIcon :size="18" />
                        <span class="sr-only">Zoom In</span>
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        @click="() => vocApi.zoomOut()"
                    >
                        <ZoomOutIcon :size="18" />
                        <span class="sr-only">Zoom Out</span>
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        @click="() => vocApi.expandAll()"
                    >
                        <Maximize2Icon :size="18" />
                        <span class="sr-only">Expand All</span>
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        @click="() => vocApi.collapseAll()"
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
                            :key="keyRef"
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
                                    <OgItem :item :children :open />

                                    <div
                                        v-if="children.length"
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
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="14"
                                                height="14"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >
                                                <polyline
                                                    v-if="!open"
                                                    points="6 9 12 15 18 9"
                                                ></polyline>
                                                <polyline
                                                    v-else
                                                    points="18 15 12 9 6 15"
                                                ></polyline>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <template #no-data>
                                <div
                                    class="h-full w-full flex flex-col items-center justify-center text-muted-foreground p-8 gap-4"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line
                                            x1="12"
                                            y1="8"
                                            x2="12"
                                            y2="12"
                                        ></line>
                                        <line
                                            x1="12"
                                            y1="16"
                                            x2="12.01"
                                            y2="16"
                                        ></line>
                                    </svg>
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
