<script lang="ts" setup>
import OverviewChart from "./overview-chart.vue";
import RecentSales from "./recent-sales.vue";
import {
  Card,
  CardHeader,
  CardTitle,
  CardDescription,
  CardContent,
} from "@/components/ui/card";
</script>

<template>
  <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <Card>
      <CardHeader
        class="flex flex-row items-center justify-between pb-2 space-y-0"
      >
        <CardTitle class="text-sm font-medium"> Total Revenue </CardTitle>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          strokeLinecap="round"
          strokeLinejoin="round"
          strokeWidth="2"
          class="w-4 h-4 text-muted-foreground"
        >
          <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
        </svg>
      </CardHeader>
      <CardContent>
        <div class="text-2xl font-bold">$45,231.89</div>
        <p class="text-xs text-muted-foreground">+20.1% from last month</p>
      </CardContent>
    </Card>
    <Card>
      <CardHeader
        class="flex flex-row items-center justify-between pb-2 space-y-0"
      >
        <CardTitle class="text-sm font-medium"> Subscriptions </CardTitle>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          strokeLinecap="round"
          strokeLinejoin="round"
          strokeWidth="2"
          class="w-4 h-4 text-muted-foreground"
        >
          <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
          <circle cx="9" cy="7" r="4" />
          <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
        </svg>
      </CardHeader>
      <CardContent>
        <div class="text-2xl font-bold">+2350</div>
        <p class="text-xs text-muted-foreground">+180.1% from last month</p>
      </CardContent>
    </Card>
    <Card>
      <CardHeader
        class="flex flex-row items-center justify-between pb-2 space-y-0"
      >
        <CardTitle class="text-sm font-medium"> Sales </CardTitle>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          strokeLinecap="round"
          strokeLinejoin="round"
          strokeWidth="2"
          class="w-4 h-4 text-muted-foreground"
        >
          <rect width="20" height="14" x="2" y="5" rx="2" />
          <path d="M2 10h20" />
        </svg>
      </CardHeader>
      <CardContent>
        <div class="text-2xl font-bold">+12,234</div>
        <p class="text-xs text-muted-foreground">+19% from last month</p>
      </CardContent>
    </Card>
    <Card>
      <CardHeader
        class="flex flex-row items-center justify-between pb-2 space-y-0"
      >
        <CardTitle class="text-sm font-medium"> Active Now </CardTitle>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          strokeLinecap="round"
          strokeLinejoin="round"
          strokeWidth="2"
          class="w-4 h-4 text-muted-foreground"
        >
          <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
        </svg>
      </CardHeader>
      <CardContent>
        <div class="text-2xl font-bold">+573</div>
        <p class="text-xs text-muted-foreground">+201 since last hour</p>
      </CardContent>
    </Card>
  </div>

  <div class="grid grid-cols-1 gap-4 lg:grid-cols-7">
    <Card class="col-span-1 lg:col-span-4">
      <CardHeader>
        <CardTitle>Overview</CardTitle>
      </CardHeader>
      <CardContent>
        <OverviewChart />
      </CardContent>
    </Card>
    <Card class="col-span-1 lg:col-span-3">
      <CardHeader>
        <CardTitle>Recent Sales</CardTitle>
        <CardDescription> You made 265 sales this month. </CardDescription>
      </CardHeader>
      <CardContent>
        <RecentSales />
      </CardContent>
    </Card>
  </div>
</template>
