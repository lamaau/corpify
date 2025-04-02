<script setup lang="ts">
import { cn } from "@/lib/utils";
import { Label } from "./ui/label";
import {
    ref,
    watch,
    computed,
    withDefaults,
    defineEmits,
    defineProps,
} from "vue";
import { FileUpIcon } from "lucide-vue-next";

interface IProps {
    label: string;
    error?: string;
    file?: string; // Can be an external URL
    allowedMimes?: string[];
}

const props = withDefaults(defineProps<IProps>(), {
    allowedMimes: () => ["image/jpeg", "image/png"],
});

// Refs
const fileInput = ref<HTMLInputElement | null>(null);
const file = ref<File | null>(null);
const filePreview = ref<string | null>(props.file || null); // Initialize with prop
const fileType = ref<string | null>(null);

// Emit event for parent component
const emit = defineEmits<{
    (e: "file-selected", file: File | null): void;
}>();

// Watch for external `file` prop changes
watch(
    () => props.file,
    (newFile) => {
        if (newFile) {
            filePreview.value = newFile; // Set as preview if valid URL

            // Try to determine file type from URL
            if (newFile.toLowerCase().endsWith(".pdf")) {
                fileType.value = "pdf";
            } else if (newFile.match(/\.(jpeg|jpg|gif|png|webp|svg)$/i)) {
                fileType.value = "image";
            } else {
                fileType.value = "other";
            }
        } else {
            filePreview.value = null;
            fileType.value = null;
        }
    },
    { immediate: true },
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

    // Validate file type using allowedMimes prop
    if (!props.allowedMimes.includes(selectedFile.type)) {
        alert(`Only ${props.allowedMimes.join(", ")} files are allowed.`);
        return;
    }

    // Validate file size (max 5MB)
    if (selectedFile.size > 5 * 1024 * 1024) {
        alert("File size must be less than 5MB.");
        return;
    }

    // Set file type for rendering correct preview
    if (selectedFile.type === "application/pdf") {
        fileType.value = "pdf";
    } else if (selectedFile.type.startsWith("image/")) {
        fileType.value = "image";
    } else {
        fileType.value = "other";
    }

    // Read file as Data URL for preview
    const reader = new FileReader();
    reader.onload = () => {
        filePreview.value = reader.result as string;
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

// Remove uploaded file
const removeFile = () => {
    file.value = null;
    filePreview.value = null;
    fileType.value = null;
    if (fileInput.value) fileInput.value.value = "";
    emit("file-selected", null);
};

// Generate accept attribute string from allowedMimes
const acceptAttribute = computed(() => props.allowedMimes.join(","));
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
                :accept="acceptAttribute"
                @change="handleFileUpload"
            />
            <!-- File Preview -->
            <div
                v-if="filePreview"
                class="relative w-full h-full flex items-center justify-center"
            >
                <!-- Image Preview -->
                <img
                    v-if="fileType === 'image'"
                    :src="filePreview"
                    alt="Image Preview"
                    class="max-h-full max-w-full object-contain rounded-lg"
                />

                <!-- PDF Preview -->
                <div
                    v-else-if="fileType === 'pdf'"
                    class="flex flex-col items-center justify-center"
                >
                    <FileUpIcon :size="55" class="text-muted-foreground" />
                    <span class="sr-only">PDF Document</span>
                    <span
                        class="text-xs text-muted-foreground truncate max-w-full mt-1"
                    >
                        {{ file?.name || "Document" }}
                    </span>
                </div>

                <!-- Other File Types -->
                <div v-else class="flex flex-col items-center justify-center">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="48"
                        height="48"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="text-blue-500 mb-2"
                    >
                        <path
                            d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"
                        ></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                    <span class="text-sm font-medium text-center"
                        >File Document</span
                    >
                    <span
                        class="text-xs text-gray-500 truncate max-w-full mt-1"
                    >
                        {{ file?.name || "Document" }}
                    </span>
                </div>

                <button
                    class="absolute top-2 right-2 bg-red-500 text-white text-xs rounded-full px-2 py-1"
                    @click.stop="removeFile"
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
