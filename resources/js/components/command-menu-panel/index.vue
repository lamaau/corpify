<script setup lang="ts">
import {
    CommandDialog,
    CommandEmpty,
    CommandInput,
    CommandList,
    CommandSeparator,
} from "@/components/ui/command";
import { useMagicKeys } from "@vueuse/core";
import { Search } from "lucide-vue-next";
import { ref, watch, computed } from "vue";
import CommandChangeTheme from "./command-change-theme.vue";
import CommandToPage from "./command-to-page.vue";

const open = ref(false);

const { Meta_K, Ctrl_K } = useMagicKeys({
    passive: false,
    onEventFired(e) {
        if (e.key === "k" && (e.metaKey || e.ctrlKey)) e.preventDefault();
    },
});

watch([Meta_K, Ctrl_K], (v) => {
    if (v[0] || v[1]) handleOpenChange();
});

function handleOpenChange() {
    open.value = !open.value;
}

const firstKey = computed(() =>
    navigator?.userAgent.includes("Mac OS") ? "⌘" : "Ctrl",
);
</script>

<template>
    <div class="theme-stone dark:dark">
        <div
            class="text-sm flex items-center justify-between text-muted-foreground border border-primary/5 bg-primary/5 px-4 py-2 rounded min-w-[220px] cursor-pointer"
            @click="handleOpenChange"
        >
            <div class="flex items-center gap-2">
                <Search class="w-4 h-4" />
                <span class="text-xs font-semibold text-primary/30"
                    >Search Menu</span
                >
            </div>
            <kbd
                class="pointer-events-none inline-flex h-5 select-none items-center gap-1 rounded border bg-primary/5 px-1.5 font-mono text-[10px] font-medium text-muted-foreground opacity-100"
            >
                <span class="text-xs">{{ firstKey }}</span
                >K
            </kbd>
        </div>

        <CommandDialog v-model:open="open">
            <CommandInput placeholder="Type a command or search..." />
            <CommandList>
                <CommandEmpty> No results found. </CommandEmpty>

                <CommandToPage @click="handleOpenChange" />
                <CommandSeparator />
                <CommandChangeTheme @click="handleOpenChange" />
            </CommandList>
        </CommandDialog>
    </div>
</template>
