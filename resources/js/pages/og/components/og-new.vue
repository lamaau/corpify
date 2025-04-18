<script setup lang="ts">
import { ref } from "vue";
import { useForm } from "vee-validate";
import Select from "@/components/select.vue";
import { toast } from "@/components/ui/toast";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { ogQueryKeys } from "@/enums/query-keys";
import { Textarea } from "@/components/ui/textarea";
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

const { isSubmitting, ...form } = useForm({
    initialValues: {
        user_id: null,
        parent_id: props.item.id,
        position_id: null,
        position_category_id: props.item.category?.id ?? null,
    },
});

const onSubmit = handleFormSubmit(form, async (data) => {
    const newData = {
        ...data,
        user_id: data.user_id?.id ?? null,
        position_id: data.position_id?.id ?? null,
    };

    const { message: description } = await fetcher.post(`/og`, newData);

    toast({ description });
    isOpen.value = false;
    queryClient.invalidateQueries(ogQueryKeys.all as any);
});
</script>
<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger asChild>
            <slot />
        </DialogTrigger>
        <DialogContent>
            <DialogHeader class="flex flex-col gap-y-1.5">
                <DialogTitle>Add new children</DialogTitle>
                <DialogDescription>
                    This will create new child based on current item
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
                            :name-key="(user: any) => `${user.profile.name}`"
                        />
                    </FormControl>
                    <FormMessage />
                </FormItem>
            </FormField>
            <FormField v-slot="{ componentField }" name="position_id">
                <FormItem>
                    <FormLabel>Position</FormLabel>
                    <FormControl>
                        <Select
                            v-bind="componentField"
                            api-url="/positions"
                            id-key="id"
                            name-key="position_name"
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
