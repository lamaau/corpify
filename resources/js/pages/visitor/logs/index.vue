<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { BarChart } from "@/components/ui/chart-bar";
import { LineChart } from "@/components/ui/chart-line";
import { DonutChart } from "@/components/ui/chart-donut";

const timeRange = ref("daily");
const isLoading = ref(false);

const visitorData = ref([
    {
        date: "2025-04-01",
        count: 142,
        country: "United States",
        referer: "Google",
    },
    { date: "2025-04-01", count: 87, country: "Germany", referer: "Direct" },
    { date: "2025-04-01", count: 53, country: "Japan", referer: "Twitter" },
    {
        date: "2025-04-02",
        count: 165,
        country: "United States",
        referer: "Google",
    },
    {
        date: "2025-04-02",
        count: 92,
        country: "United Kingdom",
        referer: "Reddit",
    },
    { date: "2025-04-02", count: 78, country: "Canada", referer: "Bing" },
    {
        date: "2025-04-03",
        count: 189,
        country: "United States",
        referer: "Google",
    },
    { date: "2025-04-03", count: 103, country: "France", referer: "LinkedIn" },
    { date: "2025-04-03", count: 67, country: "Brazil", referer: "Facebook" },
    {
        date: "2025-04-04",
        count: 201,
        country: "United States",
        referer: "Google",
    },
    { date: "2025-04-04", count: 112, country: "India", referer: "Direct" },
    { date: "2025-04-04", count: 74, country: "Australia", referer: "Twitter" },
    {
        date: "2025-04-05",
        count: 178,
        country: "United States",
        referer: "Google",
    },
    { date: "2025-04-05", count: 98, country: "Germany", referer: "Facebook" },
    { date: "2025-04-05", count: 81, country: "Mexico", referer: "LinkedIn" },
    {
        date: "2025-04-06",
        count: 203,
        country: "United States",
        referer: "Google",
    },
    { date: "2025-04-06", count: 117, country: "China", referer: "Baidu" },
    { date: "2025-04-06", count: 89, country: "Spain", referer: "Direct" },
]);

const totalVisitors = computed(() =>
    visitorData.value.reduce((sum, entry) => sum + entry.count, 0),
);
const uniqueVisitors = computed(() => Math.round(totalVisitors.value * 0.7));

const visitorsByCountry = computed(() => {
    const map = new Map();
    visitorData.value.forEach(({ country, count }) =>
        map.set(country, (map.get(country) || 0) + count),
    );
    return Array.from(map.entries())
        .map(([country, count]) => ({ country, count }))
        .sort((a, b) => b.count - a.count);
});

const visitorsByReferer = computed(() => {
    const map = new Map();
    visitorData.value.forEach(({ referer, count }) =>
        map.set(referer, (map.get(referer) || 0) + count),
    );
    return Array.from(map.entries())
        .map(([referer, count]) => ({ referer, count }))
        .sort((a, b) => b.count - a.count);
});

const chartData = computed(() => {
    const map = new Map();
    visitorData.value.forEach(({ date, count }) =>
        map.set(date, (map.get(date) || 0) + count),
    );
    return Array.from(map.entries())
        .map(([date, count]) => ({ date, count }))
        .sort(
            (a, b) => new Date(a.date).getTime() - new Date(b.date).getTime(),
        );
});

const visitorTrendData = computed(() =>
    chartData.value.map(({ date, count }) => ({
        name: date.slice(5),
        visits: count,
    })),
);
const refererBarChartData = computed(() =>
    visitorsByReferer.value
        .slice(0, 6)
        .map(({ referer, count }) => ({ name: referer, total: count })),
);
const pieChartData = computed(() =>
    visitorsByCountry.value
        .slice(0, 6)
        .map(({ country, count }) => ({ name: country, value: count })),
);

const handleTimeRangeChange = (val: string) => {
    timeRange.value = val;
};

onMounted(() => {
    // Normally you would fetch the data here
});
</script>

<template>
    <div class="p-4 mx-auto w-full max-w-7xl">
        <div class="flex flex-col gap-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold tracking-tight text-primary">
                    Visitor Dashboard
                </h1>
                <Select
                    v-model="timeRange"
                    @update:modelValue="handleTimeRangeChange"
                >
                    <SelectTrigger class="w-32">
                        <SelectValue :placeholder="timeRange" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="daily">Daily</SelectItem>
                        <SelectItem value="weekly">Weekly</SelectItem>
                        <SelectItem value="monthly">Monthly</SelectItem>
                    </SelectContent>
                </Select>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm text-muted-foreground"
                            >Total Visitors</CardTitle
                        >
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-primary">
                            {{ totalVisitors }}
                        </div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm text-muted-foreground"
                            >Unique Visitors</CardTitle
                        >
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-primary">
                            {{ uniqueVisitors }}
                        </div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm text-muted-foreground"
                            >Countries</CardTitle
                        >
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-primary">
                            {{ visitorsByCountry.length }}
                        </div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm text-muted-foreground"
                            >Avg. Session</CardTitle
                        >
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-primary">
                            3m 42s
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Line Chart -->
            <Card>
                <CardHeader>
                    <CardTitle class="text-primary">Visitor Trend</CardTitle>
                </CardHeader>
                <CardContent class="pt-2 px-2">
                    <div class="w-full h-72 overflow-hidden">
                        <LineChart
                            :data="visitorTrendData"
                            index="name"
                            :categories="['visits']"
                            :y-formatter="(tick) => tick.toString()"
                        />
                    </div>
                </CardContent>
            </Card>

            <!-- Bar + List -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Country List -->
                <Card>
                    <CardHeader>
                        <CardTitle class="text-primary"
                            >Visitors by Country</CardTitle
                        >
                    </CardHeader>
                    <CardContent class="space-y-3">
                        <div
                            v-for="(item, index) in visitorsByCountry.slice(
                                0,
                                5,
                            )"
                            :key="index"
                            class="flex justify-between items-center"
                        >
                            <div class="flex items-center gap-2">
                                <span
                                    class="w-6 text-center text-muted-foreground"
                                >
                                    {{ index + 1 }}
                                </span>
                                <span class="text-sm">{{ item.country }}</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <div
                                    class="h-2 w-20 rounded-full overflow-hidden bg-muted"
                                >
                                    <div
                                        class="h-full bg-primary"
                                        :style="{
                                            width: `${Math.round((item.count / visitorsByCountry[0].count) * 100)}%`,
                                        }"
                                    ></div>
                                </div>
                                <div class="text-sm text-muted-foreground">
                                    {{
                                        Math.round(
                                            (item.count / totalVisitors) * 100,
                                        )
                                    }}%
                                </div>
                                <div class="font-semibold">
                                    {{ item.count }}
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Bar Chart -->
                <Card>
                    <CardHeader>
                        <CardTitle class="text-primary"
                            >Traffic Sources</CardTitle
                        >
                    </CardHeader>
                    <CardContent class="pt-2 px-2">
                        <div class="w-full h-64 overflow-hidden">
                            <BarChart
                                :data="refererBarChartData"
                                index="name"
                                :categories="['total']"
                                :y-formatter="(tick) => tick.toString()"
                            />
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Donut Chart -->
            <Card>
                <CardHeader>
                    <CardTitle class="text-primary"
                        >Top Countries (Pie Chart)</CardTitle
                    >
                </CardHeader>
                <CardContent class="pt-2 px-2">
                    <div class="w-full h-64 overflow-hidden">
                        <DonutChart
                            :data="pieChartData"
                            category="value"
                            index="name"
                        />
                    </div>
                </CardContent>
            </Card>
        </div>
    </div>
</template>
