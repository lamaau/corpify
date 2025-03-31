<script setup lang="ts">
import { Button } from "@/components/ui/button";
import { DotsHorizontalIcon } from "@radix-icons/vue";
import {
    DropdownMenu,
    DropdownMenuItem,
    DropdownMenuContent,
    DropdownMenuTrigger,
    DropdownMenuShortcut,
    DropdownMenuSeparator,
} from "@/components/ui/dropdown-menu";
</script>
<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button
                variant="ghost"
                class="flex h-8 w-8 p-0 data-[state=open]:bg-muted"
            >
                <DotsHorizontalIcon class="h-4 w-4" />
                <span class="sr-only">Open menu</span>
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="start" class="w-[160px]">
            <DropdownMenuItem>Ubah</DropdownMenuItem>
            <DropdownMenuItem>Duplikat</DropdownMenuItem>
            <DropdownMenuSeparator />
            <DropdownMenuItem>
                Hapus
                <DropdownMenuShortcut>⌘⌫</DropdownMenuShortcut>
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
