<script setup lang="ts">
import {
    HoverCard,
    HoverCardContent,
    HoverCardTrigger,
} from "@/components/ui/hover-card";
import { Button } from "@/components/ui/button";
import { Separator } from "@/components/ui/separator";

defineProps<{
    abilities: any;
}>();
</script>
<template>
    <HoverCard>
        <HoverCardTrigger as-child>
            <Button variant="link" class="pl-0">
                {{ abilities.length }} Ability
            </Button>
        </HoverCardTrigger>
        <HoverCardContent class="w-max p-0">
            <div v-show="!abilities.length">
                <div class="p-4 text-muted-foreground text-sm">
                    Not have abilities yet
                </div>
            </div>
            <div v-show="abilities.length" class="flex flex-col">
                <template
                    v-for="(ability, index) in abilities"
                    :key="index.toString()"
                >
                    <div
                        class="inline-flex gap-x-3 items-center p-4 hover:bg-accent cursor-default"
                    >
                        <span class="text-sm capitalize">{{ ability.name }}</span>
                    </div>
                    <Separator v-if="index < abilities.length - 1" />
                </template>
            </div>
        </HoverCardContent>
    </HoverCard>
</template>
