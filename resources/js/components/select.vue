<script setup lang="ts">
import fetcher from "@/lib/fetcher";
import { ref, watch, computed, useSlots } from "vue";
import { Button } from "@/components/ui/button";
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from "@/components/ui/command";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/components/ui/popover";
import { cn, debounce } from "@/lib/utils";
import { Check, ChevronsUpDown } from "lucide-vue-next";

// Props for reusability
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

const slots = useSlots();
const open = ref(false);
const value = ref("");
const searchQuery = ref("");
const items = ref([]);
const isLoading = ref(false);

const emit = defineEmits(["update:modelValue"]);

const fetchData = async () => {
    isLoading.value = true;
    try {
        const {
            data: { data },
        } = await fetcher.get(props.apiUrl, {
            params: {
                search: searchQuery.value,
            },
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

// Get display value dynamically based on the nameKey
const getDisplayValue = (item) => {
    if (!item) return "";
    if (typeof props.nameKey === "function") {
        return props.nameKey(item);
    }
    return item[props.nameKey];
};

// Get ID value dynamically based on the idKey
const getIdValue = (item) => {
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

// Emit selected item when value changes
watch(selectedLabel, (newSelectedItem) => {
    emit("update:modelValue", newSelectedItem || null);
});

// Handle item selection
const handleSelect = (item) => {
    value.value = getIdValue(item);
    emit("update:modelValue", item);
    open.value = false;
};
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
            <Command>
                <!-- Custom input slot -->
                <slot name="input">
                    <CommandInput
                        :placeholder="placeholder"
                        :model-value="searchQuery"
                        @input="searchQuery = $event.target.value"
                    />
                </slot>

                <!-- Custom empty slot -->
                <slot name="empty" :is-loading="isLoading">
                    <CommandEmpty>
                        {{ isLoading ? loadingMessage : emptyMessage }}
                    </CommandEmpty>
                </slot>

                <CommandList>
                    <CommandGroup>
                        <!-- Custom item slot -->
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
                                >
                                    <Check
                                        :class="
                                            cn(
                                                'mr-2 h-4 w-4',
                                                (!value &&
                                                    modelValue &&
                                                    getIdValue(modelValue) ===
                                                        getIdValue(item)) ||
                                                    value === getIdValue(item)
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
