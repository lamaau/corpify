<script setup lang="ts">
import { ref } from "vue";
import { Button } from "@/components/ui/button";
import fetcher from "@/lib/fetcher";
import { toast } from "@/components/ui/toast";
import { TrashIcon, PencilIcon } from "lucide-vue-next";
import EditForm from "./edit-form.vue";
import { DotsHorizontalIcon } from "@radix-icons/vue";
import DropdownItemButton from "@/components/dropdown-item-button.vue";
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from "@/components/ui/dialog";
import {
    DropdownMenu,
    DropdownMenuItem,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";

const isModalOpen = ref(false);
const isEditOpen = ref(false);

const props = defineProps<{
    row: any;
}>();

const emit = defineEmits(["reload"]);

const handleDelete = async () => {
    try {
        const { message } = await fetcher.delete(`/galleries/${props.row.id}`);

        toast({
            description: message,
        });

        isModalOpen.value = false;
        emit("reload");
    } catch (error) {
        console.log(error);
    }
};
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" class="flex h-8 w-8 p-0">
                <DotsHorizontalIcon class="h-4 w-4" />
                <span class="sr-only">Open menu</span>
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="start" class="w-[160px]">
            <EditForm
                v-model:open="isEditOpen"
                :row="row"
                @reload="
                    () => {
                        emit('reload');
                        isEditOpen = false;
                    }
                "
            >
                <DropdownItemButton>
                    <PencilIcon class="h-4 w-4 mr-2" /> Edit
                </DropdownItemButton>
            </EditForm>
            <DropdownMenuItem asChild>
                <DropdownItemButton @click="isModalOpen = true">
                    <TrashIcon class="h-4 w-4 mr-2" /> Hapus
                </DropdownItemButton>
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>

    <Dialog v-model:open="isModalOpen">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Konfirmasi</DialogTitle>
            </DialogHeader>
            <p>Apakah Anda yakin ingin menghapus?</p>
            <DialogFooter>
                <Button variant="outline" @click="isModalOpen = false">
                    Batal
                </Button>
                <Button @click="handleDelete" variant="destructive">
                    Hapus
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
