<script setup lang="ts">
import { cn } from "@/lib/utils";
import fetcher from "@/lib/fetcher";
import draggable from "vuedraggable";
import { toast } from "@/components/ui/toast";
import { ref, watch, watchEffect } from "vue";
import { Button } from "@/components/ui/button";
import { Separator } from "@/components/ui/separator";
import { settingQueryKeys } from "@/enums/query-keys";
import { useMutation } from "@/composables/use-mutation";
import { Card, CardContent } from "@/components/ui/card";
import { QueryClient, useQueryClient } from "@tanstack/vue-query";
import CreateCarouselTextForm from "./create-carousel-text-form.vue";
import { useSettingsQuery, ICarouseTextList } from "@/composables/use-settings";
import { lucideIconComponents } from "@/plugins/lucide-icon";
import {
    XIcon,
    PlusIcon,
    CheckIcon,
    LayoutListIcon,
    PencilIcon,
    Trash2Icon,
} from "lucide-vue-next";

const carouselList = ref<ICarouseTextList[]>([]);
const originalCarousel = ref<ICarouseTextList[]>([]);

const isDragMode = ref<boolean>(false);
const hasChanges = ref<boolean>(false);
const context = ref<string>("hero_carousel_text");

const { data: dataCarsousel } = useSettingsQuery(context.value);
const queryClient: QueryClient = useQueryClient();

watchEffect(() => {
    if (dataCarsousel.value) {
        const { hero_carousel_text } = dataCarsousel.value;
        carouselList.value = JSON.parse(JSON.stringify(hero_carousel_text));
    }
});

watch(
    () => dataCarsousel.value,
    (values) => {
        if (values) {
            const { hero_carousel_text } = values;
            originalCarousel.value = JSON.parse(
                JSON.stringify(hero_carousel_text),
            );
        }
    },
    { immediate: true },
);

const checkForChanges = () => {
    if (originalCarousel.value.length === 0) return;

    // Check for differences in featured items (IDs and order)
    const featuredIds = carouselList.value.map((item) => item.id);
    const originalCarouselIds = originalCarousel.value.map(
        (item) => item.id,
    );

    // Check if any IDs are different
    const hasDifferentFeaturedItems =
        featuredIds.length !== originalCarouselIds.length ||
        featuredIds.some((id) => !originalCarouselIds.includes(id)) ||
        originalCarouselIds.some((id) => !featuredIds.includes(id));

    // Check if order is different (even if same IDs)
    const hasOrderChanged =
        featuredIds.length === originalCarouselIds.length && // Only check order if same length
        featuredIds.some((id, index) => id !== originalCarouselIds[index]);

    hasChanges.value = hasDifferentFeaturedItems || hasOrderChanged;
};

watch(carouselList, checkForChanges, { deep: true });

// Handle featured list changes
const handleFeaturedChange = (evt: any) => {
    hasChanges.value = true;

    carouselList.value = carouselList.value.map((item, index) => ({
        ...item,
        sort: index + 1,
    }));
};

// Remove item from carousel list
const removeItem = (index: number) => {
    carouselList.value.splice(index, 1);
    hasChanges.value = true;

    // Update sort order after removal
    carouselList.value = carouselList.value.map((item, idx) => ({
        ...item,
        sort: idx + 1,
    }));
};

// When entering drag mode, store the current state to compare against
watch(isDragMode, (newValue) => {
    if (newValue) {
        // Entering drag mode - capture original state
        originalCarousel.value = JSON.parse(JSON.stringify(carouselList.value));
        hasChanges.value = false;
    }
});

const { isPending, mutate } = useMutation(
    async (formData) => await fetcher.post("/settings/site", formData),
    {
        onSuccess: () => {
            queryClient.invalidateQueries(
                settingQueryKeys.detail(context.value) as any,
            );

            hasChanges.value = false;
            isDragMode.value = false;

            toast({
                title: "Successfully",
                description: "Your carousel image is up to date",
                variant: "default",
            });
        },
    },
);

const saveChanges = () => {
    const formData = new FormData();
    formData.append("context", context.value);

    carouselList.value.forEach((item, index) => {
        Object.entries(item).forEach(([key, value]) => {
            if (value) {
                formData.append(
                    `hero_carousel_text[${index}][${key}]`,
                    value as any,
                );
            }
        });
    });

    mutate(formData);
};
</script>

<template>
    <div
        class="flex w-full flex-col md:flex-row items-start md:items-center justify-between gap-4"
    >
        <div>
            <h3 class="text-lg font-medium">Hero text carousel</h3>
            <p class="text-sm text-muted-foreground">
                Manage your site's hero carousel texts. These texts will be
                displayed in the hero section of your site.
            </p>
        </div>
        <div class="self-start md:self-auto space-x-2">
            <CreateCarouselTextForm :items="dataCarsousel?.hero_carousel_text">
                <Button :disabled="isDragMode">
                    Add New
                    <PlusIcon class="ml-2 h-4 w-4" />
                </Button>
            </CreateCarouselTextForm>
            <Button
                v-show="!isDragMode"
                variant="outline"
                @click="isDragMode = true"
            >
                Sort Items
                <LayoutListIcon class="ml-2 h-4 w-4" />
            </Button>
            <Button
                v-show="isDragMode && !hasChanges && !isPending"
                variant="outline"
                @click="isDragMode = false"
            >
                Cancel
                <XIcon class="ml-2 h-4 w-4" />
            </Button>
            <Button
                v-show="isDragMode && hasChanges"
                variant="outline"
                @click="saveChanges"
                :disabled="isPending || !hasChanges"
            >
                {{ isPending ? "Saving..." : "Save Changes" }}
                <CheckIcon class="ml-2 h-4 w-4" />
            </Button>
        </div>
    </div>

    <Separator class="my-4" />

    <Card>
        <CardContent class="p-4">
            <draggable
                :disabled="!isDragMode"
                v-model="carouselList"
                :group="{
                    name: 'gallery-items',
                    pull: true,
                    put: true,
                }"
                item-key="id"
                class="rounded-lg min-h-[200px]"
                :class="{
                    'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4':
                        carouselList.length > 0,
                    'flex items-center justify-center border-2 border-dashed':
                        carouselList.length === 0,
                }"
                ghost-class="opacity-50"
                chosen-class="shadow-lg"
                animation="300"
                @change="handleFeaturedChange"
                id="featured-list"
            >
                <!-- Featured item template -->
                <template #item="{ element, index }">
                    <div
                        :data-id="element.id"
                        :data-sort="index + 1"
                        :class="
                            cn(
                                isDragMode &&
                                    'cursor-move animate-drag-pulse hover:animate-none',
                            )
                        "
                        :style="{
                            animationDelay: `${index * 0.1}s`,
                        }"
                    >
                        <div
                            class="relative aspect-video rounded-md overflow-hidden flex flex-col justify-center items-center border bg-muted"
                        >
                            <!-- Overlay (for drag mode) -->
                            <div
                                :class="
                                    cn(
                                        isDragMode &&
                                            'absolute inset-0 bg-black/70 opacity-100 duration-75 transition-all flex items-center justify-center',
                                    )
                                "
                            >
                                <div
                                    class="absolute top-2 right-2 space-x-2 z-10"
                                >
                                    <CreateCarouselTextForm
                                        :item="element"
                                        :items="
                                            dataCarsousel?.hero_carousel_text
                                        "
                                    >
                                        <Button
                                            v-if="isDragMode"
                                            variant="outline"
                                            size="sm"
                                        >
                                            <PencilIcon class="h-4 w-4" />
                                        </Button>
                                    </CreateCarouselTextForm>
                                    <Button
                                        v-if="
                                            isDragMode &&
                                            carouselList.length > 1
                                        "
                                        variant="destructive"
                                        size="sm"
                                        @click.stop="removeItem(index)"
                                    >
                                        <Trash2Icon class="h-4 w-4" />
                                    </Button>
                                </div>

                                <!-- Drag message -->
                                <div
                                    v-show="isDragMode"
                                    class="text-primary-foreground font-medium absolute inset-0 flex items-center justify-center text-center"
                                >
                                    Drag to reorder
                                </div>

                                <!-- Content display -->
                                <div
                                    class="flex flex-col gap-y-4 items-center justify-center text-center px-4"
                                >
                                    <component
                                        :is="
                                            lucideIconComponents[
                                                element.icon as keyof typeof lucideIconComponents
                                            ]
                                        "
                                        class="w-8 h-8 text-muted-foreground"
                                    />
                                    <h4
                                        class="text-foreground text-lg font-semibold"
                                    >
                                        {{ element.title }}
                                    </h4>
                                    <p class="text-muted-foreground text-sm">
                                        {{ element.summary }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </draggable>
        </CardContent>
    </Card>
</template>
