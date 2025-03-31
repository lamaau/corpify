<script setup lang="ts">
import { ref, watch } from "vue";
import { useForm } from "vee-validate";
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
    item: any;
}>();

const isOpen = ref<boolean>(false);
const queryClient = useQueryClient();

const { isSubmitting, setValues, ...form } = useForm({
    initialValues: {
        position_category_name: null,
        position_category_summary: null,
    },
});

const onSubmit = handleFormSubmit(form, async (data) => {
    const { message: description } = await fetcher.put(
        `/position-categories/${props.item.category.id}`,
        data,
    );

    toast({ description });
    isOpen.value = false;
    queryClient.invalidateQueries(ogQueryKeys.all as any);
});

watch(isOpen, (state) => {
    if (state) {
        setValues({
            position_category_name: props.item.category.name,
            position_category_summary: props.item.category.summary,
        });
    }
});
</script>
<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger asChild>
            <slot />
        </DialogTrigger>
        <DialogContent>
            <DialogHeader class="flex flex-col gap-y-1.5">
                <DialogTitle>Review</DialogTitle>
                <DialogDescription>
                    You can update the field, max length of name is 25 character
                </DialogDescription>
            </DialogHeader>

            <FormField
                v-slot="{ componentField }"
                name="position_category_name"
            >
                <FormItem>
                    <FormLabel>Name</FormLabel>
                    <FormControl>
                        <Input type="text" v-bind="componentField" />
                    </FormControl>
                    <FormMessage />
                </FormItem>
            </FormField>
            <FormField
                v-slot="{ componentField }"
                name="position_category_summary"
            >
                <FormItem>
                    <FormLabel>Summary</FormLabel>
                    <FormControl>
                        <Textarea type="text" v-bind="componentField" />
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
