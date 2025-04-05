<script setup lang="ts">
import { isProxy, ref, toRaw, watch } from "vue";
import { Button } from "@/components/ui/button";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/components/ui/dialog";
import { Textarea } from "@/components/ui/textarea";
import {
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/components/ui/form";
import fetcher from "@/lib/fetcher";
import { useForm } from "vee-validate";
import { Input } from "@/components/ui/input";
import { toast } from "@/components/ui/toast";
import IconPicker from "@/components/icon-picker.vue";
import { galleryQueryKeys } from "@/enums/query-keys";
import { ICarouseTextList } from "@/composables/use-settings";
import { useFormMutation } from "@/composables/use-form-mutation";
import { QueryClient, useQueryClient } from "@tanstack/vue-query";

const isOpen = ref<boolean>(false);
const queryClient: QueryClient = useQueryClient();
const context = ref<string>("hero_carousel_text");

const props = defineProps<{
    item?: ICarouseTextList;
    items: ICarouseTextList[];
}>();

const form = useForm({
    initialValues: {
        icon: "",
        title: "",
        summary: "",
        context: context.value,
    },
});

watch(isOpen, () => {
    if (props.item) {
        form.setValues({
            icon: props.item.icon,
            title: props.item.title,
            summary: props.item.summary,
        });
    }
});

const { isPending, mutate } = useFormMutation(
    async (formData) => await fetcher.post(`settings/site`, formData),
    form,
    {
        onSuccess: ({ message: description }: any) => {
            queryClient.invalidateQueries(galleryQueryKeys.all as any);
            toast({ description });
            isOpen.value = false;
        },
        onError: (error: any) => {
            const err = error.response.data.errors;
            if (err) {
                const index = !props.item
                    ? props.items.length
                    : props.items.findIndex((i) => i.id === props.item?.id);

                const prefix = `${context.value}.${index}`;

                const getError = (field: string) =>
                    err?.[`${prefix}.${field}`]?.[0];

                form.setErrors({
                    icon: getError("icon"),
                    title: getError("title"),
                    summary: getError("summary"),
                });
            }
        },
    },
);

const onSubmit = form.handleSubmit(async (data) => {
    const rawItems = isProxy(props.items) ? toRaw(props.items) : props.items;

    const filteredItems = props.item
        ? rawItems.filter((i) => i.id !== props.item?.id)
        : rawItems;

    const resultArray = [{ ...data }, ...filteredItems];

    const payload = {
        context: context.value,
        hero_carousel_text: resultArray.map((item) =>
            Object.fromEntries(
                Object.entries(item).filter(
                    ([, value]) =>
                        value !== null && value !== undefined && value !== "",
                ),
            ),
        ),
    };

    mutate(payload);
});
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger asChild>
            <slot />
        </DialogTrigger>
        <DialogContent class="min-w-max">
            <DialogHeader class="flex items-start">
                <DialogTitle>
                    {{
                        item ? "Update text carousel" : "Add new text carousel"
                    }}
                </DialogTitle>
                <DialogDescription>
                    Max character of summary is 255 character
                </DialogDescription>
            </DialogHeader>

            <div class="flex flex-col space-y-4">
                <div class="flex flex-col gap-y-2">
                    <FormField v-slot="{ componentField }" name="icon">
                        <FormItem>
                            <FormLabel>Icon</FormLabel>
                            <FormControl>
                                <IconPicker v-bind="componentField" />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>

                    <FormField v-slot="{ componentField }" name="title">
                        <FormItem>
                            <FormControl>
                                <FormLabel>Title</FormLabel>
                                <Input v-bind="componentField" />
                                <FormMessage />
                            </FormControl>
                        </FormItem>
                    </FormField>

                    <FormField v-slot="{ componentField }" name="summary">
                        <FormItem>
                            <FormControl>
                                <FormLabel>Summary</FormLabel>
                                <Textarea v-bind="componentField" />
                                <FormMessage />
                            </FormControl>
                        </FormItem>
                    </FormField>
                </div>
            </div>
            <DialogFooter>
                <Button :disabled="isPending" @click="onSubmit">
                    Submit
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
