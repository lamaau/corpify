<script setup lang="ts">
import { ref, watch } from "vue";
import { useForm } from "vee-validate";
import Select from "@/components/select.vue";
import { toast } from "@/components/ui/toast";
import { Button } from "@/components/ui/button";
import { ogQueryKeys } from "@/enums/query-keys";
import { useQueryClient } from "@tanstack/vue-query";
import fetcher, { handleFormSubmit } from "@/lib/fetcher";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogTitle,
    DialogTrigger,
} from "@/components/ui/dialog";
import {
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/components/ui/form";

const props = defineProps<{
    open: boolean;
    item: any;
    children: any;
}>();

const isOpen = ref<boolean>(false);
const queryClient = useQueryClient();

const { isSubmitting, setValues, ...form } = useForm({
    initialValues: {
        user_id: null,
        parent_id: null,
        position_id: null,
        position_category_id: null,
    },
});

const onSubmit = handleFormSubmit(form, async (data) => {
    const newData = {
        ...data,
        user_id: data.user_id?.id ?? null,
    };

    const { message: description } = await fetcher.put(
        `/og/${props.item.id}`,
        newData,
    );

    toast({ description });
    isOpen.value = false;
    queryClient.invalidateQueries(ogQueryKeys.all as any);
});

function setLocalValues([state, newItem]: any) {
    if (state) {
        setValues({
            // @ts-ignore
            user_id: {
                id: newItem.user.id,
                profile: {
                    name: newItem.user.name,
                },
            },
            parent_id: newItem.parentId,
            position_id: newItem.position.id,
            position_category_id: newItem.category.id,
        });
    }
}

watch([isOpen, props.item], setLocalValues, { deep: true });
</script>
<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger asChild>
            <slot />
        </DialogTrigger>
        <DialogContent class="max-w-md">
            <DialogHeader class="flex flex-col gap-y-1">
                <DialogTitle>{{ item.position.name }}</DialogTitle>
                <DialogDescription>
                    {{ item.category.name }}
                </DialogDescription>
            </DialogHeader>
            <FormField v-slot="{ componentField }" name="user_id">
                <FormItem>
                    <FormLabel>Account</FormLabel>
                    <FormControl>
                        <Select
                            v-bind="componentField"
                            api-url="/users"
                            :id-key="(user: any) => user.id"
                            :name-key="(user: any) => user.profile.name"
                        />
                    </FormControl>
                    <FormMessage />
                </FormItem>
            </FormField>
            <DialogFooter>
                <Button @click="onSubmit" :disabled="isSubmitting">
                    Save changes
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
