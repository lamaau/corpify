<script setup>
import { ref } from "vue";
import draggable from "vuedraggable";
import { Separator } from "@/components/ui/separator";
import { Card, CardContent } from "@/components/ui/card";
import BasicPage from "@/components/global-layout/basic-page.vue";

// Gallery items data
const items = ref([
    {
        id: "1",
        src: "/placeholder.svg?height=300&width=400",
        alt: "Gallery image 1",
        featured: true,
    },
    {
        id: "2",
        src: "/placeholder.svg?height=300&width=400",
        alt: "Gallery image 2",
        featured: true,
    },
    {
        id: "3",
        src: "/placeholder.svg?height=300&width=400",
        alt: "Gallery image 3",
        featured: false,
    },
    {
        id: "4",
        src: "/placeholder.svg?height=300&width=400",
        alt: "Gallery image 4",
        featured: false,
    },
    {
        id: "5",
        src: "/placeholder.svg?height=300&width=400",
        alt: "Gallery image 5",
        featured: false,
    },
    {
        id: "6",
        src: "/placeholder.svg?height=300&width=400",
        alt: "Gallery image 6",
        featured: false,
    },
    {
        id: "7",
        src: "/placeholder.svg?height=300&width=400",
        alt: "Gallery image 7",
        featured: false,
    },
    {
        id: "8",
        src: "/placeholder.svg?height=300&width=400",
        alt: "Gallery image 8",
        featured: false,
    },
]);

// Keep track of featured and non-featured items separately
const featuredList = ref(items.value.filter((item) => item.featured));
const galleryList = ref(items.value.filter((item) => !item.featured));

// Update the master items list when either list changes
const updateMasterList = () => {
    // Combine both lists ensuring proper featured status
    items.value = [
        ...featuredList.value.map((item) => ({ ...item, featured: true })),
        ...galleryList.value.map((item) => ({ ...item, featured: false })),
    ];
};

// Handle changes in the featured list
const handleFeaturedChange = (evt) => {
    // If an item was added from the gallery list
    if (evt.added) {
        // Make sure the item is marked as featured
        const index = featuredList.value.findIndex(
            (item) => item.id === evt.added.element.id,
        );
        if (index !== -1) {
            featuredList.value[index] = {
                ...featuredList.value[index],
                featured: true,
            };
        }

        // Update the master list
        updateMasterList();
    }
};

// Handle changes in the gallery list
const handleGalleryChange = (evt) => {
    // If an item was added from the featured list
    if (evt.added) {
        // Make sure the item is marked as not featured
        const index = galleryList.value.findIndex(
            (item) => item.id === evt.added.element.id,
        );
        if (index !== -1) {
            galleryList.value[index] = {
                ...galleryList.value[index],
                featured: false,
            };
        }

        // Update the master list
        updateMasterList();
    }
};
</script>

<template>
    <BasicPage title="Gallery" description="List of all your gallery here" sticky>
        <div class="space-y-8">
            <!-- Featured Items Section -->
            <div>
                <h2 class="text-xl font-semibold mb-4">
                    Featured Items ({{ featuredList.length }})
                </h2>
                <Card>
                    <CardContent class="p-4">
                        <draggable
                            v-model="featuredList"
                            :group="{
                                name: 'gallery-items',
                                pull: true,
                                put: true,
                            }"
                            item-key="id"
                            class="p-4 bg-muted/30 rounded-lg min-h-[200px]"
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
                            <!-- Empty state message inside the draggable -->
                            <template v-if="featuredList.length === 0">
                                <p class="text-muted-foreground">
                                    Drag items here to feature them
                                </p>
                            </template>

                            <!-- Regular item template -->
                            <template #item="{ element }">
                                <div :data-id="element.id" class="cursor-move">
                                    <div
                                        class="relative aspect-video rounded-md overflow-hidden border bg-card"
                                    >
                                        <img
                                            :src="element.src"
                                            :alt="element.alt"
                                            class="w-full h-full object-cover"
                                        />
                                        <div
                                            class="absolute inset-0 bg-black/30 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center"
                                        >
                                            <span class="text-white font-medium"
                                                >Drag to reorder or
                                                unfeatured</span
                                            >
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
                <h2 class="text-xl font-semibold mb-4">
                    Gallery ({{ galleryList.length }})
                </h2>
                <Card>
                    <CardContent class="p-4">
                        <draggable
                            v-model="galleryList"
                            :group="{
                                name: 'gallery-items',
                                pull: true,
                                put: true,
                            }"
                            item-key="id"
                            class="p-4 bg-background rounded-lg min-h-[200px]"
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
                            <!-- Empty state message inside the draggable -->
                            <template v-if="galleryList.length === 0">
                                <p class="text-muted-foreground">
                                    No items in gallery
                                </p>
                            </template>

                            <!-- Regular item template -->
                            <template #item="{ element }">
                                <div :data-id="element.id" class="cursor-move">
                                    <div
                                        class="relative aspect-square rounded-md overflow-hidden border bg-card"
                                    >
                                        <img
                                            :src="element.src"
                                            :alt="element.alt"
                                            class="w-full h-full object-cover"
                                        />
                                        <div
                                            class="absolute inset-0 bg-black/30 opacity-0 hover:opacity-100 transition-opacity flex items-center justify-center"
                                        >
                                            <span class="text-white font-medium"
                                                >Drag to reorder or
                                                feature</span
                                            >
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
