<script setup lang="ts">
import fetcher from "@/lib/fetcher";
import { ref, watch, computed, nextTick } from "vue";
import { cn, debounce } from "@/lib/utils";
import { Button } from "@/components/ui/button";
import { Check, ChevronsUpDown, SearchIcon } from "lucide-vue-next";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/components/ui/popover";
import {
    Command,
    CommandGroup,
    CommandItem,
    CommandList,
} from "@/components/ui/command";
import { Primitive } from "reka-ui";

const props = defineProps({
    modelValue: { type: Object, default: null },
    apiUrl: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        default: "",
    },
    idKey: {
        type: [String, Function],
        default: "name",
    },
    nameKey: {
        type: [String, Function],
        default: "name",
    },
    error: {
        type: String,
        default: null,
    },
    placeholder: {
        type: String,
        default: "Search...",
    },
    emptyMessage: {
        type: String,
        default: "No items found.",
    },
    loadingMessage: {
        type: String,
        default: "Loading...",
    },
});

const value = ref("");
const items = ref([]);
const open = ref(false);
const searchQuery = ref("");
const isLoading = ref(false);
const searchRef = ref<HTMLInputElement | null>(null);

const emit = defineEmits(["update:modelValue"]);

const fetchData = async () => {
    isLoading.value = true;

    const params = { search: searchQuery.value };

    const filteredParams = Object.fromEntries(
        Object.entries(params).filter(([_, value]) => value !== ""),
    );

    try {
        const {
            data: { data },
        } = await fetcher.get(props.apiUrl, {
            params: Object.keys(filteredParams).length
                ? filteredParams
                : undefined,
        });

        items.value = data;
    } catch (error) {
        console.error("Failed to fetch data:", error);
    } finally {
        isLoading.value = false;
    }
};

const debouncedFetchData = debounce(() => {
    fetchData();
}, 300);

watch(
    searchQuery,
    () => {
        debouncedFetchData();
    },
    { immediate: true },
);

const getDisplayValue = (item: any) => {
    if (!item) return "";
    if (typeof props.nameKey === "function") {
        return props.nameKey(item);
    }

    return item[props.nameKey];
};

const getIdValue = (item: any) => {
    if (!item) return "";
    if (typeof props.idKey === "function") {
        return props.idKey(item);
    }
    return item[props.idKey];
};

const selectedLabel = computed(() => {
    if (!value.value || !items.value?.length) return null;

    return items.value.find((item) => getIdValue(item) === value.value);
});

// Set initial value from modelValue
watch(
    () => props.modelValue,
    (newModelValue) => {
        if (newModelValue) {
            value.value = getIdValue(newModelValue);
        } else {
            value.value = "";
        }
    },
    { immediate: true },
);

watch(selectedLabel, (newSelectedItem) => {
    emit("update:modelValue", newSelectedItem || null);
});

const handleSelect = (item: any) => {
    value.value = getIdValue(item);
    emit("update:modelValue", item);
    open.value = false;
};

const onCommandMounted = () => {
    nextTick(() => {
        setTimeout(() => searchRef.value?.focus());
    });
};

const isSelected = computed(() => {
    return (item: any) =>
        (!value.value &&
            props.modelValue &&
            getIdValue(props.modelValue) === getIdValue(item)) ||
        value.value === getIdValue(item);
});
</script>
<template>
    <Popover v-model:open="open">
        <PopoverTrigger as-child>
            <Button
                type="button"
                size="default"
                variant="outline"
                role="combobox"
                :aria-expanded="open"
                class="w-full"
                :class="{
                    'border-red-500': error,
                    'justify-end': !selectedLabel && !modelValue,
                    'justify-between': selectedLabel || modelValue,
                }"
            >
                <!-- Custom trigger slot -->
                <slot name="trigger" :selected="selectedLabel || modelValue">
                    {{
                        selectedLabel
                            ? getDisplayValue(selectedLabel)
                            : modelValue
                              ? getDisplayValue(modelValue)
                              : label
                    }}
                </slot>
                <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-full p-0">
            <Command @vue:mounted="onCommandMounted">
                <slot name="input">
                    <div
                        class="flex items-center border-b px-3"
                        cmdk-input-wrapper
                    >
                        <SearchIcon class="mr-2 h-4 w-4 shrink-0 opacity-50" />
                        <input
                            ref="searchRef"
                            v-model="searchQuery"
                            :class="
                                cn(
                                    'flex h-10 w-full rounded-md bg-transparent py-3 text-sm outline-none placeholder:text-muted-foreground disabled:cursor-not-allowed disabled:opacity-50',
                                )
                            "
                        />
                    </div>
                </slot>

                <Primitive
                    v-show="!items.length || isLoading"
                    class="py-6 text-center text-sm"
                >
                    <slot name="empty" :is-loading="isLoading">
                        {{ isLoading ? loadingMessage : emptyMessage }}
                    </slot>
                </Primitive>

                <CommandList v-show="!isLoading && items.length">
                    <CommandGroup>
                        <template v-for="item in items" :key="getIdValue(item)">
                            <slot
                                name="item"
                                :item="item"
                                :selected="
                                    (selectedLabel &&
                                        getIdValue(selectedLabel) ===
                                            getIdValue(item)) ||
                                    (modelValue &&
                                        getIdValue(modelValue) ===
                                            getIdValue(item))
                                "
                                :select="() => handleSelect(item)"
                            >
                                <CommandItem
                                    :value="getIdValue(item)"
                                    @select="() => handleSelect(item)"
                                    :class="
                                        cn(
                                            // i dont know why but make like this, the bg will applied correctly 😎
                                            'data-[highlighted]:bg-transparent data-[highlighted]:text-primary',
                                            isSelected(item) &&
                                                'bg-accent text-accent-foreground data-[highlighted]:bg-accent data-[highlighted]:text-accent-foreground',
                                        )
                                    "
                                >
                                    <Check
                                        :class="
                                            cn(
                                                'mr-2 h-4 w-4',
                                                isSelected(item)
                                                    ? 'opacity-100'
                                                    : 'opacity-0',
                                            )
                                        "
                                    />
                                    {{ getDisplayValue(item) }}
                                </CommandItem>
                            </slot>
                        </template>
                    </CommandGroup>
                </CommandList>
            </Command>
        </PopoverContent>
    </Popover>
</template>
