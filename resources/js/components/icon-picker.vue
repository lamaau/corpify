<script setup lang="ts">
import { cn } from "@/lib/utils";
import { ChevronsUpDown } from "lucide-vue-next";
import { lucideIconComponents } from "@/plugins/lucide-icon";
import { Button, ButtonVariants } from "@/components/ui/button";
import { ref, computed, defineProps, defineEmits, withDefaults } from "vue";
import {
    Popover,
    PopoverTrigger,
    PopoverContent,
} from "@/components/ui/popover";
import {
    Command,
    CommandInput,
    CommandList,
    CommandEmpty,
    CommandGroup,
    CommandItem,
} from "@/components/ui/command";

interface IIconPickerProps {
    modelValue?: string;
    placeholder?: string;
    disabled?: boolean;
    size?: ButtonVariants["size"];
}

const props = withDefaults(defineProps<IIconPickerProps>(), {
    size: "lg",
});

const emit = defineEmits(["update:modelValue", "change"]);

const iconNames = Object.keys(lucideIconComponents);

const selectedIcon = computed({
    get: () => props.modelValue,
    set: (value) => {
        emit("update:modelValue", value);
        emit("change", value);
    },
});

const isOpen = ref<boolean>(false);
const searchQuery = ref<string>("");

const filteredIcons = computed(() => {
    if (!searchQuery.value) {
        return iconNames;
    }

    return iconNames.filter((icon) =>
        icon.toLowerCase().includes(searchQuery.value.toLowerCase()),
    );
});

const selectIcon = (icon: string) => {
    selectedIcon.value = icon;
    isOpen.value = false;
    searchQuery.value = "";
};

const currentIcon = computed(() => {
    return lucideIconComponents[
        selectedIcon.value as keyof typeof lucideIconComponents
    ];
});
</script>
<template>
    <div class="w-full">
        <Popover v-model:open="isOpen">
            <PopoverTrigger as-child>
                <Button
                    variant="outline"
                    role="combobox"
                    :size="size"
                    :aria-expanded="isOpen"
                    class="w-full justify-between px-4"
                    :disabled="disabled"
                >
                    <div class="flex items-center gap-2">
                        <component :is="currentIcon" class="h-4 w-4 shrink-0" />
                        <span v-if="selectedIcon">{{ selectedIcon }}</span>
                        <span v-else class="text-muted-foreground">
                            {{ placeholder }}
                        </span>
                    </div>
                    <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
                </Button>
            </PopoverTrigger>
            <PopoverContent class="min-w-64 w-min p-0">
                <Command>
                    <CommandInput
                        placeholder="Search icons..."
                        v-model="searchQuery"
                    />
                    <CommandEmpty v-if="filteredIcons.length === 0">
                        No icon found.
                    </CommandEmpty>
                    <CommandList v-else>
                        <CommandGroup>
                            <div
                                class="max-h-64 overflow-y-auto grid grid-cols-4 gap-2 p-2"
                            >
                                <CommandItem
                                    v-for="icon in filteredIcons"
                                    :key="icon"
                                    :value="icon"
                                    @select="
                                        selectIcon(
                                            icon as keyof typeof lucideIconComponents,
                                        )
                                    "
                                    class="flex flex-col items-center justify-center p-2 cursor-pointer data-[selected=true]:bg-accent"
                                    :class="
                                        cn(selectedIcon === icon && 'bg-accent')
                                    "
                                >
                                    <component
                                        :is="
                                            lucideIconComponents[
                                                icon as keyof typeof lucideIconComponents
                                            ]
                                        "
                                        class="h-6 w-6 text-foreground"
                                    />
                                    <span
                                        class="text-xs mt-1 text-muted-foreground truncate w-full text-center"
                                    >
                                        {{ icon }}
                                    </span>
                                </CommandItem>
                            </div>
                        </CommandGroup>
                    </CommandList>
                </Command>
            </PopoverContent>
        </Popover>
    </div>
</template>
