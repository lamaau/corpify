<script setup lang="ts">
import { IFeature } from "../types";
import { Button } from "@/components/ui/button";
import { lucideIconComponents } from "@/plugins/lucide-icon";
import {
    HoverCard,
    HoverCardContent,
    HoverCardTrigger,
} from "@/components/ui/hover-card";
import { Separator } from "@/components/ui/separator";

defineProps<{
    features: IFeature[];
}>();
</script>
<template>
    <HoverCard>
        <HoverCardTrigger as-child>
            <Button variant="link" class="pl-0"
                >{{ features.length }} Feature</Button
            >
        </HoverCardTrigger>
        <HoverCardContent class="w-max p-0">
            <div class="flex flex-col">
                <template
                    v-for="(feature, index) in features"
                    :key="index.toString()"
                >
                    <div
                        class="inline-flex gap-x-3 items-center p-4 hover:bg-accent cursor-default"
                    >
                        <component
                            :is="
                                lucideIconComponents[
                                    feature.icon as keyof typeof lucideIconComponents
                                ]
                            "
                            class="h-4 w-4 shrink-0"
                        />
                        <span class="text-sm">{{ feature.feature_name }}</span>
                    </div>
                    <Separator v-if="index < features.length - 1" />
                </template>
            </div>
        </HoverCardContent>
    </HoverCard>
</template>
