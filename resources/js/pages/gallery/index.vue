<script setup lang="ts">
import { cn } from "@/lib/utils";
import fetcher from "@/lib/fetcher";
import draggable from "vuedraggable";
import { toast } from "@/components/ui/toast";
import { ref, watch, watchEffect } from "vue";
import { Button } from "@/components/ui/button";
import { galleryQueryKeys } from "@/enums/query-keys";
import { Separator } from "@/components/ui/separator";
import CreateForm from "./components/create-form.vue";
import { useMutation } from "@/composables/use-mutation";
import { Card, CardContent } from "@/components/ui/card";
import { QueryClient, useQueryClient } from "@tanstack/vue-query";
import BasicPage from "@/components/global-layout/basic-page.vue";
import { IServerParams, useGallery } from "@/composables/use-gallery";
import {
    XIcon,
    PlusIcon,
    CheckIcon,
    ImageIcon,
    LayoutListIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
} from "lucide-vue-next";
import EditForm from "./components/edit-form.vue";

const featuredList = ref<any[]>([]);
const galleryList = ref<any[]>([]);
const isDragMode = ref<boolean>(false);
const hasChanges = ref<boolean>(false);

const MAX_FEATURED_ITEMS = 6;

const originalFeatured = ref<any[]>([]);
const originalGallery = ref<any[]>([]);

const serverParams = ref<IServerParams>({
    page: 1,
});

const { data: dataGalleries } = useGallery(serverParams.value);
const queryClient: QueryClient = useQueryClient();

// Process API data but preserve user changes
watchEffect(() => {
    if (dataGalleries.value) {
        const { data } = dataGalleries.value.data;
        const { featured } = dataGalleries.value.meta;

        featuredList.value = JSON.parse(JSON.stringify(featured));
        galleryList.value = JSON.parse(JSON.stringify(data));
    }
});

watch(
    () => dataGalleries.value,
    (values) => {
        if (values) {
            const { data } = values.data;
            const { featured } = values.meta;
            originalGallery.value = JSON.parse(JSON.stringify(data));
            originalFeatured.value = JSON.parse(JSON.stringify(featured));
        }
    },
    { immediate: true },
);

watch(
    () => serverParams.value,
    () => {
        if (dataGalleries.value) {
            const { data } = dataGalleries.value.data;
            const { featured } = dataGalleries.value.meta;

            galleryList.value = JSON.parse(JSON.stringify(data));
            featuredList.value = JSON.parse(JSON.stringify(featured));
        }
    },
    { deep: true },
);

// Improved change detection that checks order of items as well
watch(
    [featuredList, galleryList],
    () => {
        checkForChanges();
    },
    { deep: true },
);

// Handle featured list changes
const handleFeaturedChange = (evt: any) => {
    if (evt.added) {
        // Apply featured status to added item
        const index = featuredList.value.findIndex(
            (item) => item.id === evt.added.element.id,
        );

        if (index !== -1) {
            featuredList.value[index] = {
                ...featuredList.value[index],
                featured: true,
            };
        }

        // Check if we exceeded the max limit
        if (featuredList.value.length > MAX_FEATURED_ITEMS) {
            const addedItemIndex = featuredList.value.findIndex(
                (item) => item.id === evt.added.element.id,
            );

            if (addedItemIndex !== -1) {
                const removedItem = featuredList.value.splice(
                    addedItemIndex,
                    1,
                )[0];

                // Push to gallery and ensure proper sort order
                galleryList.value.push({
                    ...removedItem,
                    featured: false,
                });
            }

            toast({
                title: "Limit Reached",
                description: `Maximum featured images is ${MAX_FEATURED_ITEMS}`,
                variant: "destructive",
            });
        }
    }

    if (evt.removed) {
        // The item was moved from featured to gallery
        const removedItemId = evt.removed.element.id;

        // Find the item in the gallery list and mark as not featured
        const galleryIndex = galleryList.value.findIndex(
            (item) => item.id === removedItemId,
        );

        if (galleryIndex !== -1) {
            galleryList.value[galleryIndex] = {
                ...galleryList.value[galleryIndex],
                featured: false,
            };
        }
    }

    // Update sort order for all items in featured list
    featuredList.value = featuredList.value.map((item, index) => ({
        ...item,
        sort: index + 1,
    }));

    checkForChanges();
};

// Handle gallery list changes
const handleGalleryChange = (evt: any) => {
    if (evt.added) {
        // Item moved from featured to gallery
        const index = galleryList.value.findIndex(
            (item) => item.id === evt.added.element.id,
        );

        if (index !== -1) {
            galleryList.value[index] = {
                ...galleryList.value[index],
                featured: false,
            };
        }
    }

    if (evt.removed) {
        // The item was moved from gallery to featured
        const removedItemId = evt.removed.element.id;

        // Find the item in the featured list and mark as featured
        const featuredIndex = featuredList.value.findIndex(
            (item) => item.id === removedItemId,
        );

        if (featuredIndex !== -1) {
            featuredList.value[featuredIndex] = {
                ...featuredList.value[featuredIndex],
                featured: true,
            };
        }
    }

    // Update sort order for all items in gallery list
    galleryList.value = galleryList.value.map((item, index) => ({
        ...item,
        sort: index + 1,
    }));

    checkForChanges();
};

// Separate function to check for changes (can be called manually)
const checkForChanges = () => {
    if (
        originalFeatured.value.length === 0 &&
        originalGallery.value.length === 0
    )
        return;

    // Check for differences in featured items (IDs and order)
    const featuredIds = featuredList.value.map((item) => item.id);
    const originalFeaturedIds = originalFeatured.value.map((item) => item.id);

    // Check if any IDs are different
    const hasDifferentFeaturedItems =
        featuredIds.length !== originalFeaturedIds.length ||
        featuredIds.some((id) => !originalFeaturedIds.includes(id)) ||
        originalFeaturedIds.some((id) => !featuredIds.includes(id));

    // Check if order is different (even if same IDs)
    const hasOrderChanged =
        featuredIds.length === originalFeaturedIds.length && // Only check order if same length
        featuredIds.some((id, index) => id !== originalFeaturedIds[index]);

    // Also check gallery for changes
    const galleryIds = galleryList.value.map((item) => item.id);
    const originalGalleryIds = originalGallery.value.map((item) => item.id);

    const hasDifferentGalleryItems =
        galleryIds.length !== originalGalleryIds.length ||
        galleryIds.some((id) => !originalGalleryIds.includes(id)) ||
        originalGalleryIds.some((id) => !galleryIds.includes(id));

    const hasGalleryOrderChanged =
        galleryIds.length === originalGalleryIds.length &&
        galleryIds.some((id, index) => id !== originalGalleryIds[index]);

    hasChanges.value =
        hasDifferentFeaturedItems ||
        hasOrderChanged ||
        hasDifferentGalleryItems ||
        hasGalleryOrderChanged;
};

const { isPending, mutate } = useMutation(
    async (data) => await fetcher.post("/gallery/sort", data),
    {
        onSuccess: () => {
            queryClient.invalidateQueries(
                galleryQueryKeys.page(serverParams.value) as any,
            );

            hasChanges.value = false;
            isDragMode.value = false;

            toast({
                title: "Successfully",
                description: "Your gallery is up to date",
                variant: "default",
            });
        },
    },
);

const saveChanges = () => {
    // Ensure all items have proper sort values before saving
    const featuredWithSort = featuredList.value.map((item, index) => ({
        id: item.id,
        sort: index + 1,
    }));

    const galleryWithSort = galleryList.value.map((item, index) => ({
        id: item.id,
        sort: index + 1,
    }));

    mutate({
        featured: featuredWithSort,
        gallery: galleryWithSort,
    });
};

// Handle page navigation while preserving changes
const goToPrevPage = () => {
    if (hasChanges.value) {
        const confirm = window.confirm(
            "You have unsaved changes. Changing pages will lose these changes. Continue?",
        );

        if (!confirm) return;
    }

    serverParams.value.page--;
    hasChanges.value = false;
};

const goToNextPage = () => {
    if (hasChanges.value) {
        const confirm = window.confirm(
            "You have unsaved changes. Changing pages will lose these changes. Continue?",
        );

        if (!confirm) return;
    }

    serverParams.value.page++;
    hasChanges.value = false;
};
</script>

<template>
    <BasicPage
        title="Gallery"
        description="List of all your gallery here"
        sticky
    >
        <template #actions>
            <CreateForm>
                <Button :disabled="isDragMode">
                    Add new
                    <PlusIcon class="ml-2 h-4 w-4" />
                </Button>
            </CreateForm>

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
        </template>

        <div class="space-y-8">
            <!-- Featured Section -->
            <div>
                <h2 class="text-xl font-semibold mb-4">
                    Featured Items ({{ featuredList.length }}/{{
                        MAX_FEATURED_ITEMS
                    }})
                </h2>
                <Card>
                    <CardContent class="p-4">
                        <draggable
                            :disabled="!isDragMode"
                            v-model="featuredList"
                            :group="{
                                name: 'gallery-items',
                                pull: true,
                                put: true,
                            }"
                            item-key="id"
                            class="bg-muted/30 rounded-lg min-h-[200px]"
                            :class="{
                                'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4':
                                    featuredList.length > 0,
                                'flex items-center justify-center border-2 border-dashed':
                                    featuredList.length === 0,
                            }"
                            ghost-class="opacity-50"
                            chosen-class="shadow-lg"
                            animation="300"
                            @change="handleFeaturedChange"
                            id="featured-list"
                        >
                            <!-- Empty state -->
                            <template v-if="featuredList.length === 0">
                                <p class="text-muted-foreground">
                                    Drag items here to feature them
                                </p>
                            </template>

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
                                        class="relative aspect-video rounded-md overflow-hidden border bg-card"
                                    >
                                        <img
                                            :src="element.file.file_url"
                                            :alt="element.alt"
                                            class="w-full h-full object-cover"
                                        />
                                        <div
                                            class="absolute inset-0 bg-black/70 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center"
                                        >
                                            <div
                                                v-show="isDragMode"
                                                class="text-white font-medium container text-center"
                                            >
                                                Drag to reorder or unfeatured
                                            </div>
                                            <div
                                                v-show="!isDragMode"
                                                class="flex flex-col gap-y-4 items-center justify-center"
                                            >
                                                <p class="text-white">
                                                    {{ element.caption }}
                                                </p>
                                                <div
                                                    class="inline-flex gap-x-2"
                                                >
                                                    <EditForm :row="element">
                                                        <Button
                                                            variant="secondary"
                                                        >
                                                            <ImageIcon
                                                                class="h-4 w-4"
                                                            />
                                                            Review image
                                                        </Button>
                                                    </EditForm>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </draggable>
                    </CardContent>
                </Card>
            </div>

            <Separator />

            <!-- Gallery Items Section -->
            <div>
                <div class="w-full flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">
                        Gallery ({{ galleryList.length }})
                    </h2>
                    <div class="inline-flex gap-x-2">
                        <Button
                            size="icon"
                            variant="secondary"
                            :disabled="
                                (dataGalleries?.data?.current_page || 0) <= 1 ||
                                isDragMode
                            "
                            @click="goToPrevPage"
                        >
                            <ChevronLeftIcon class="h-4 w-4" />
                            <span class="sr-only">Previous</span>
                        </Button>
                        <Button
                            size="icon"
                            variant="secondary"
                            :disabled="
                                (dataGalleries?.data?.current_page || 0) >=
                                    (dataGalleries?.data?.last_page || 0) ||
                                isDragMode
                            "
                            @click="goToNextPage"
                        >
                            <ChevronRightIcon class="h-4 w-4" />
                            <span class="sr-only">Next</span>
                        </Button>
                    </div>
                </div>
                <Card>
                    <CardContent class="p-4">
                        <draggable
                            :disabled="!isDragMode"
                            v-model="galleryList"
                            :group="{
                                name: 'gallery-items',
                                pull: true,
                                put: true,
                            }"
                            item-key="id"
                            class="bg-background rounded-lg min-h-[200px]"
                            :class="{
                                'grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4':
                                    galleryList.length > 0,
                                'flex items-center justify-center border-2 border-dashed':
                                    galleryList.length === 0,
                            }"
                            ghost-class="opacity-50"
                            chosen-class="shadow-lg"
                            animation="300"
                            @change="handleGalleryChange"
                            id="gallery-list"
                        >
                            <!-- Empty state -->
                            <template v-if="galleryList.length === 0">
                                <p class="text-muted-foreground">
                                    No items in gallery
                                </p>
                            </template>

                            <!-- Gallery item template -->
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
                                        animationDelay: `${index * 0.05}s`,
                                    }"
                                >
                                    <div
                                        class="relative aspect-square rounded-md overflow-hidden border bg-card"
                                    >
                                        <img
                                            :src="element.file.file_url"
                                            :alt="element.alt"
                                            class="w-full h-full object-cover"
                                        />
                                        <div
                                            class="absolute inset-0 bg-black/70 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center"
                                        >
                                            <div
                                                v-show="isDragMode"
                                                class="text-white font-medium container text-center"
                                            >
                                                Drag to reorder or feature
                                            </div>
                                            <div
                                                v-show="!isDragMode"
                                                class="flex flex-col gap-y-4 items-center justify-center container"
                                            >
                                                <p
                                                    class="text-white text-center"
                                                >
                                                    {{ element.caption }}
                                                </p>
                                                <div
                                                    class="inline-flex gap-x-2"
                                                >
                                                    <EditForm :row="element">
                                                        <Button
                                                            variant="secondary"
                                                        >
                                                            <ImageIcon
                                                                class="h-4 w-4"
                                                            />
                                                            Review image
                                                        </Button>
                                                    </EditForm>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </draggable>
                    </CardContent>
                </Card>
            </div>
        </div>
    </BasicPage>
</template>

<style scoped>
.sortable-ghost {
    opacity: 0.5;
}

.sortable-chosen {
    box-shadow:
        0 10px 15px -3px rgba(0, 0, 0, 0.1),
        0 4px 6px -2px rgba(0, 0, 0, 0.05);
}
</style>
