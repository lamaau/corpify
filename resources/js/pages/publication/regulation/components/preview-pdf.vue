<script setup lang="ts">
import {
    Sheet,
    SheetTitle,
    SheetHeader,
    SheetTrigger,
    SheetContent,
    SheetDescription,
} from "@/components/ui/sheet";
import { IRowProps } from "../types";

defineProps<IRowProps>();
</script>

<template>
    <Sheet>
        <SheetTrigger as-child>
            <slot />
        </SheetTrigger>
        <SheetContent class="w-[48vw] max-w-full" side="right">
            <SheetHeader class="gap-y-1">
                <SheetTitle>{{ row.title }}</SheetTitle>
                <SheetDescription>
                    {{ row.summary }}
                </SheetDescription>
            </SheetHeader>
            <div class="mt-4 border rounded-lg overflow-hidden">
                <iframe :src="row.file" class="w-full h-[87vh]"></iframe>
            </div>
        </SheetContent>
    </Sheet>
</template>
