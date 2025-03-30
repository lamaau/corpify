<script setup lang="ts">
import { Button } from "@/components/ui/button";
import {
    FormControl,
    FormField,
    FormItem,
    FormLabel,
} from "@/components/ui/form";
import { Label } from "@/components/ui/label";
import { Input } from "@/components/ui/input";
import { Separator } from "@/components/ui/separator";
import { Textarea } from "@/components/ui/textarea";
import { useForm } from "vee-validate";
import Draggable from "vuedraggable";
import { computed, ref, watchEffect } from "vue";
import { GripIcon, TrashIcon } from "lucide-vue-next";
import fetcher from "@/lib/fetcher";
import { useSettings } from "@/composables/use-settings";
import { toast } from "@/components/ui/toast";

const { data: settings } = useSettings();

const initialValue = { title: "", summary: "", file: null, sort: 1 };
const isInitialized = ref(false);

// Define the type for app_hero_carousel items
interface CarouselItem {
    title: string;
    summary: string;
    file: File | null;
    sort: number;
}

// Initialize form
const { handleSubmit, isSubmitting, setFieldValue, values, setValues } =
    useForm<{
        app_hero_carousel: CarouselItem[];
    }>({
        initialValues: {
            app_hero_carousel: [],
        },
    });

// Watch for settings changes and initialize form only once
watchEffect(() => {
    if (settings.value && !isInitialized.value) {
        // Add sort field if missing for newly loaded settings
        const formattedSettings = settings.value.length
            ? settings.value.map((item: any, index: number) => ({
                  ...item,
                  sort: item.sort || index + 1,
                  // Initialize file as null since it can't be loaded from server
                  file: null,
                  file_url: item.file,
              }))
            : [initialValue];

        setValues({
            app_hero_carousel: formattedSettings,
        });
        isInitialized.value = true;
    }
});

// Computed property for draggable list
const fields = computed({
    get: () => values.app_hero_carousel,
    set: (newValue) => {
        const updatedValues = newValue.map((item, index) => ({
            ...item,
            sort: index + 1,
        }));

        setFieldValue("app_hero_carousel", updatedValues);
    },
});

// Add new item
const addItem = () => {
    const newSort = fields.value.length + 1;
    setFieldValue("app_hero_carousel", [
        ...fields.value,
        { ...initialValue, sort: newSort },
    ]);
};

// Remove item
const removeItem = (index: number) => {
    const newCarousels = [...fields.value];
    newCarousels.splice(index, 1);

    // Reorder sort values after removal
    const reorderedCarousels = newCarousels.map((item, idx) => ({
        ...item,
        sort: idx + 1,
    }));

    setFieldValue("app_hero_carousel", reorderedCarousels);
};

const handleFileChange = (event: Event, index: number) => {
    const target = event.target as HTMLInputElement;
    const file = target.files ? target.files[0] : null;

    // Create a new array to ensure reactivity
    const updatedCarousels = [...fields.value];
    updatedCarousels[index] = {
        ...updatedCarousels[index],
        file: file,
    };

    setFieldValue("app_hero_carousel", updatedCarousels);
};

const onSubmit = handleSubmit(async (values) => {
    try {
        const formData = new FormData();

        values.app_hero_carousel.forEach((row, index) => {
            Object.entries(row).forEach(([key, value]) => {
                if (key === "file") {
                    if (value instanceof File) {
                        formData.append(
                            `app_hero_carousel[${index}][file]`,
                            value,
                        );
                    }
                } else {
                    formData.append(
                        `app_hero_carousel[${index}][${key}]`,
                        String(value),
                    );
                }
            });
        });

        const { message } = await fetcher.post("settings/app", formData);
        toast({
            description: message,
        });
    } catch (error) {
        console.error(error);
    }
});
</script>

<template>
    <div>
        <h3 class="text-lg font-medium">Application</h3>
        <p class="text-sm text-muted-foreground">
            This is how others see your site
        </p>
    </div>
    <Separator orientation="horizontal" class="my-3" />
    <form class="space-y-8" @submit="onSubmit">
        <div class="flex flex-col gap-y-3">
            <div>
                <div class="mb-4">
                    <h4 class="text-lg font-medium">Carousel</h4>
                    <p class="text-sm text-muted-foreground">
                        Add carousel image, title, and summary of your site
                    </p>
                </div>
                <Draggable
                    v-model="fields"
                    item-key="sort"
                    handle=".drag-handle"
                    class="space-y-4 w-full"
                    ghost-class="bg-background"
                >
                    <template #item="{ element, index }">
                        <div
                            class="flex flex-col w-full p-4 bg-sidebar rounded-lg"
                        >
                            <div class="flex flex-row items-center">
                                <button
                                    type="button"
                                    class="cursor-grab drag-handle mr-4"
                                >
                                    <GripIcon class="text-gray-300" />
                                </button>
                                <div class="flex flex-col gap-y-3 w-full">
                                    <div>
                                        <Label>Photo file</Label>
                                        <Input
                                            class="mt-1"
                                            type="file"
                                            @change="
                                                (event: Event) =>
                                                    handleFileChange(
                                                        event,
                                                        index,
                                                    )
                                            "
                                        />
                                        <div
                                            v-show="element.file_url"
                                            class="mt-1"
                                        >
                                            <a
                                                :href="element.file_url"
                                                target="blank"
                                                class="text-primary text-sm"
                                            >
                                                Preview
                                            </a>
                                        </div>
                                    </div>
                                    <FormField
                                        v-slot="{ componentField }"
                                        :name="`app_hero_carousel.[${index}].title`"
                                    >
                                        <FormItem>
                                            <FormLabel>Title</FormLabel>
                                            <FormControl>
                                                <Input
                                                    type="text"
                                                    v-bind="componentField"
                                                />
                                            </FormControl>
                                        </FormItem>
                                    </FormField>
                                    <FormField
                                        v-slot="{ componentField }"
                                        :name="`app_hero_carousel.[${index}].summary`"
                                    >
                                        <FormItem>
                                            <FormLabel>Summary</FormLabel>
                                            <FormControl>
                                                <Textarea
                                                    v-bind="componentField"
                                                />
                                            </FormControl>
                                        </FormItem>
                                    </FormField>
                                </div>
                            </div>
                            <div v-if="fields.length > 1" class="ml-10 mt-4">
                                <Button
                                    type="button"
                                    size="sm"
                                    variant="destructive"
                                    @click="removeItem(index)"
                                >
                                    <TrashIcon />
                                    <span>Remove</span>
                                </Button>
                            </div>
                        </div>
                    </template>
                </Draggable>

                <Separator orientation="horizontal" class="mt-4" />
                <Button
                    type="button"
                    variant="outline"
                    size="sm"
                    class="w-20 mt-4 text-xs"
                    @click="addItem"
                >
                    Add Item
                </Button>
            </div>
        </div>

        <div class="flex justify-start gap-2">
            <Button size="sm" type="submit" :disabled="isSubmitting">
                Update setting
            </Button>
        </div>
    </form>
</template>
