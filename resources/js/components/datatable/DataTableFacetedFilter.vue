<script setup lang="ts">
import { computed, watch } from "vue";
import { cn } from "@/lib/utils";
import { XIcon } from "lucide-vue-next";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Separator } from "@/components/ui/separator";
import { PlusCircledIcon, CheckIcon } from "@radix-icons/vue";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/components/ui/popover";
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
    CommandSeparator,
} from "@/components/ui/command";
import { Column } from "@tanstack/vue-table";
import { FacetedFilterOption } from "@/types/datatable";

interface DataTableFacetedFilter<T = unknown> {
    title: string;
    column?: Column<T, any>;
    options: FacetedFilterOption[];
}

const props = defineProps<DataTableFacetedFilter>();
const emit = defineEmits(["onFilter"]);

const facets = computed(() => props.column?.getFacetedUniqueValues());
const selectedValues = computed(
    () => new Set(props.column?.getFilterValue() as any),
);

const filterFunction = (
    list: DataTableFacetedFilter["options"],
    term: string,
) => list.filter((i) => i.name.toLowerCase()?.includes(term));

watch(selectedValues, (newSelectedValue) => {
    emit("onFilter", newSelectedValue);
});
</script>
<template>
    <Popover>
        <PopoverTrigger as-child>
            <Button variant="outline" size="sm" class="h-8 border-dashed">
                <PlusCircledIcon class="h-4 w-4" />
                <span>{{ title }}</span>
                <template v-if="selectedValues.size > 0">
                    <Separator orientation="vertical" class="mx-2 h-4" />
                    <Badge
                        variant="secondary"
                        class="rounded-sm px-1 font-normal lg:hidden"
                    >
                        {{ selectedValues.size }}
                    </Badge>
                    <div class="hidden space-x-1 lg:flex">
                        <Badge
                            v-if="selectedValues.size > 2"
                            variant="secondary"
                            class="rounded-sm px-1 font-normal"
                        >
                            {{ selectedValues.size }} selected
                        </Badge>

                        <template v-else>
                            <Badge
                                v-for="option in options.filter((option) =>
                                    selectedValues.has(option.value),
                                )"
                                :key="option.value"
                                variant="secondary"
                                class="rounded-sm px-1 font-normal"
                            >
                                {{ option.name }}
                            </Badge>
                        </template>
                    </div>
                </template>
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-min p-0" align="start">
            <Command :filter-function="filterFunction">
                <CommandInput :placeholder="title" />
                <CommandList>
                    <CommandEmpty>No results found.</CommandEmpty>
                    <CommandGroup>
                        <CommandItem
                            v-for="option in options"
                            :key="option.value"
                            :value="option"
                            @select="
                                () => {
                                    const isSelected = selectedValues.has(
                                        option.value,
                                    );
                                    if (isSelected) {
                                        selectedValues.delete(option.value);
                                    } else {
                                        selectedValues.add(option.value);
                                    }
                                    const filterValues =
                                        Array.from(selectedValues);
                                    column?.setFilterValue(
                                        filterValues.length
                                            ? filterValues
                                            : undefined,
                                    );
                                }
                            "
                        >
                            <div
                                :class="
                                    cn(
                                        'mr-2 flex h-4 w-4 items-center justify-center rounded-sm border border-primary',
                                        selectedValues.has(option.value)
                                            ? 'bg-primary text-primary-foreground'
                                            : 'opacity-50 [&_svg]:invisible',
                                    )
                                "
                            >
                                <CheckIcon :class="cn('h-4 w-4')" />
                            </div>
                            <component v-if="option.icon" :is="option.icon" />
                            <span>{{ option.name }}</span>
                            <span
                                v-if="facets?.get(option.value)"
                                class="ml-auto flex h-4 w-4 items-center justify-center font-mono text-xs"
                            >
                                {{ facets.get(option.value) }}
                            </span>
                        </CommandItem>
                    </CommandGroup>

                    <template v-if="selectedValues.size > 0">
                        <CommandSeparator />
                        <CommandGroup>
                            <CommandItem
                                :value="{ label: 'Clear filters' }"
                                class="justify-center text-center"
                                @select="column?.setFilterValue(undefined)"
                            >
                                Clear filters
                            </CommandItem>
                        </CommandGroup>
                    </template>
                </CommandList>
            </Command>
        </PopoverContent>
    </Popover>
    <Button
        v-show="selectedValues.size > 0"
        size="sm"
        class="h-8"
        variant="ghost"
        @click="column?.setFilterValue(undefined)"
    >
        Reset
        <XIcon class="h-4 w-4" />
    </Button>
</template>
