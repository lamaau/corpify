<script setup lang="ts">
import { cn } from "@/lib/utils";

const props = defineProps<{
    class?: string;
}>();
</script>
<template>
    <button
        v-bind="$attrs"
        :class="
            cn(
                'w-full relative flex cursor-default select-none items-center rounded-sm gap-2 px-2 py-1 text-sm outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50 [&>svg]:size-4 [&>svg]:shrink-0',
                props.class,
            )
        "
    >
        <slot />
    </button>
</template>
