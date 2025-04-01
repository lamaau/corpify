<script setup lang="ts">
import { ref, watch, defineEmits, defineProps } from "vue";
import { Label } from "./ui/label";
import { cn } from "@/lib/utils";

const props = defineProps<{
    label: string;
    error?: string;
    file?: string; // Can be an external URL
}>();

// Refs
const fileInput = ref<HTMLInputElement | null>(null);
const file = ref<File | null>(null);
const imagePreview = ref<string | null>(props.file || null); // Initialize with prop

// Emit event for parent component
const emit = defineEmits<{
    (e: "file-selected", file: File | null): void;
}>();

// Watch for external `file` prop changes
watch(
    () => props.file,
    (newFile) => {
        if (newFile) {
            imagePreview.value = newFile; // Set as preview if valid URL
        } else {
            imagePreview.value = null;
        }
    },
);

// Trigger file input on click
const triggerFileInput = () => {
    fileInput.value?.click();
};

// Handle file upload
const handleFileUpload = (event: Event) => {
    const input = event.target as HTMLInputElement;
    if (!input.files || input.files.length === 0) return;

    const selectedFile = input.files[0];

    // Validate file type
    if (!selectedFile.type.startsWith("image/")) {
        alert("Only image files are allowed.");
        return;
    }

    // Validate file size (max 5MB)
    if (selectedFile.size > 5 * 1024 * 1024) {
        alert("File size must be less than 5MB.");
        return;
    }

    // Read file as Data URL for preview
    const reader = new FileReader();
    reader.onload = () => {
        imagePreview.value = reader.result as string;
        file.value = selectedFile;
        emit("file-selected", selectedFile);
    };
    reader.readAsDataURL(selectedFile);
};

// Handle drag & drop
const handleDrop = (event: DragEvent) => {
    event.preventDefault();
    if (!event.dataTransfer || !event.dataTransfer.files.length) return;

    fileInput.value!.files = event.dataTransfer.files;
    handleFileUpload({ target: fileInput.value } as Event);
};

// Remove uploaded image
const removeImage = () => {
    file.value = null;
    imagePreview.value = null;
    if (fileInput.value) fileInput.value.value = "";
    emit("file-selected", null);
};
</script>

<template>
    <div>
        <Label :class="cn('block mb-2', error && 'text-destructive')">
            {{ label }}
        </Label>
        <div
            :class="
                cn(
                    'border-2 border-dashed p-4 rounded-lg flex items-center justify-center cursor-pointer hover:bg-primary-foreground transition relative w-full h-40',
                    error && 'border-destructive',
                )
            "
            @click="triggerFileInput"
            @dragover.prevent
            @drop="handleDrop"
        >
            <input
                ref="fileInput"
                type="file"
                class="hidden"
                accept="image/*"
                @change="handleFileUpload"
            />

            <!-- Image Preview -->
            <div
                v-if="imagePreview"
                class="relative w-full h-full flex items-center justify-center"
            >
                <img
                    :src="imagePreview"
                    alt="Preview"
                    class="max-h-full max-w-full object-contain rounded-lg"
                />
                <button
                    class="absolute top-2 right-2 bg-red-500 text-white text-xs rounded-full px-2 py-1"
                    @click.stop="removeImage"
                >
                    ✕
                </button>
            </div>

            <p v-else class="text-gray-500 text-center">
                Click to upload or drag & drop
            </p>
        </div>
        <div v-show="error" class="mt-2">
            <span class="text-sm font-medium text-destructive">
                {{ error }}
            </span>
        </div>
    </div>
</template>
