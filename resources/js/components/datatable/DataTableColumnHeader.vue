<script setup lang="ts">
import { ref, watch } from "vue";
import { pickBy } from "lodash";
import {
    ArrowDownIcon,
    ArrowUpIcon,
    EyeNoneIcon,
    CaretSortIcon,
} from "@radix-icons/vue";

import { cn } from "@/lib/utils";
import { Button } from "@/components/ui/button";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";

defineOptions({
    inheritAttrs: false,
});

const props = defineProps({
    column: { type: Object },
    title: { type: String },
});

const server = ref({
    sort: {
        column: null,
        direction: null,
    },
});

const toggleSorting = (sort) => {
    server.value.sort.column = props.column.id;
    server.value.sort.direction = sort ? "desc" : "asc";
};

watch(
    server,
    () => {
        let params = pickBy(server.value);
        console.log("filtered", params);
    },
    { deep: true },
);
</script>
<template>
    <div
        v-if="column.getCanSort()"
        :class="cn('flex items-center space-x-2', $attrs.class ?? '')"
    >
        <DropdownMenu>
            <DropdownMenuTrigger as-child>
                <Button
                    variant="ghost"
                    size="sm"
                    class="-ml-3 h-8 data-[state=open]:bg-accent"
                >
                    <span>{{ title }}</span>
                    <ArrowDownIcon
                        v-if="
                            column.id === server.sort.column &&
                            server.sort.direction === 'desc'
                        "
                        class="ml-2 h-4 w-4"
                    />
                    <ArrowUpIcon
                        v-else-if="
                            column.id === server.sort.column &&
                            server.sort.direction === 'asc'
                        "
                        class="ml-2 h-4 w-4"
                    />
                    <CaretSortIcon v-else class="ml-2 h-4 w-4" />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="start">
                <DropdownMenuItem @click="toggleSorting(false)">
                    <ArrowUpIcon
                        class="mr-2 h-3.5 w-3.5 text-muted-foreground/70"
                    />
                    Asc
                </DropdownMenuItem>
                <DropdownMenuItem @click="toggleSorting(true)">
                    <ArrowDownIcon
                        class="mr-2 h-3.5 w-3.5 text-muted-foreground/70"
                    />
                    Desc
                </DropdownMenuItem>
                <DropdownMenuSeparator />
                <DropdownMenuItem @click="column.toggleVisibility(false)">
                    <EyeNoneIcon
                        class="mr-2 h-3.5 w-3.5 text-muted-foreground/70"
                    />
                    Hide
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    </div>

    <div v-else :class="$attrs.class">
        {{ title }}
    </div>
</template>
