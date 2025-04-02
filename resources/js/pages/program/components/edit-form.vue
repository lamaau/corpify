<script setup lang="ts">
import { ref, defineEmits, watch, onMounted } from "vue";
import { Button } from "@/components/ui/button";
import {
    Dialog,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
    DialogDescription,
    DialogScrollContent,
} from "@/components/ui/dialog";
import {
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/components/ui/form";
import { cn } from "@/lib/utils";
import fetcher from "@/lib/fetcher";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import IconPicker from "@/components/icon-picker.vue";
import { useForm, useFieldArray } from "vee-validate";
import { PlusIcon, Trash2Icon } from "lucide-vue-next";
import { useFormMutation } from "@/composables/use-form-mutation";
import { toast } from "@/components/ui/toast";
import { tableQueryKeys } from "@/enums/query-keys";
import {
    InvalidateQueryFilters,
    QueryClient,
    useQueryClient,
} from "@tanstack/vue-query";
import { IProgram } from "../types";

const props = defineProps<{
    row: IProgram;
}>();

const emit = defineEmits(["update:open"]);

const isOpen = ref<boolean>(false);
const queryClient: QueryClient = useQueryClient();

const form = useForm({
    initialValues: {
        name: "",
        summary: "",
        program_id: props.row.id,
        features: [{ feature_name: "", icon: "" }],
    },
});

const { fields, push, remove } = useFieldArray("features");

const { mutate, isPending } = useFormMutation(
    async (data) => fetcher.put(`/programs/${props.row.id}`, data),
    form,
    {
        // @ts-ignore
        onSuccess: ({ message: description }) => {
            isOpen.value = false;
            toast({ description });
            queryClient.invalidateQueries(
                tableQueryKeys.lists(
                    "/programs",
                ) as InvalidateQueryFilters<unknown>,
            );
        },
    },
);

const onSubmit = form.handleSubmit((data) => mutate(data));

const fillValues = () => {
    const features = props.row.features?.length
        ? JSON.parse(JSON.stringify(props.row.features))
        : [{ feature_name: "", icon: "" }];

    form.setValues({
        name: props.row.name,
        summary: props.row.summary,
        features,
    });
};

onMounted(fillValues);

watch(isOpen, (newValue) => {
    emit("update:open", newValue);
});
</script>
<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger asChild>
            <slot />
        </DialogTrigger>
        <DialogScrollContent class="w-min">
            <DialogHeader class="flex items-start">
                <DialogTitle>Update program</DialogTitle>
                <DialogDescription>
                    You can add new feature or change existing features
                </DialogDescription>
            </DialogHeader>

            <div class="flex flex-col space-y-4">
                <div class="flex flex-col gap-y-2">
                    <FormField v-slot="{ componentField }" name="name">
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

                <div class="flex flex-col gap-y-3">
                    <div
                        v-for="(field, index) in fields"
                        :key="field.key"
                        :class="cn('flex flex-col')"
                    >
                        <div class="flex flex-row mb-3" v-show="field.isFirst">
                            <Label class="w-full">Feature Name</Label>
                            <Label
                                :class="
                                    cn(
                                        'w-full',
                                        field.isFirst ? 'mr-12' : 'ml-4',
                                    )
                                "
                            >
                                Feature Icon
                            </Label>
                        </div>
                        <div class="flex flex-row gap-x-3">
                            <FormField
                                v-slot="{ componentField }"
                                :name="`features[${index}].feature_name`"
                            >
                                <FormItem class="w-full">
                                    <FormControl>
                                        <Input v-bind="componentField" />
                                    </FormControl>
                                    <FormMessage />
                                </FormItem>
                            </FormField>
                            <FormField
                                v-slot="{ componentField }"
                                :name="`features[${index}].icon`"
                            >
                                <FormItem class="w-full">
                                    <FormControl>
                                        <IconPicker v-bind="componentField" />
                                    </FormControl>
                                    <FormMessage />
                                </FormItem>
                            </FormField>
                            <Button
                                v-if="fields.length > 1"
                                type="button"
                                size="icon"
                                variant="outline"
                                class="px-4 mt-1 hover:bg-destructive hover:text-destructive-foreground"
                                @click="remove(index)"
                            >
                                <Trash2Icon class="h-4 w-4" />
                            </Button>
                        </div>
                    </div>
                    <Button
                        type="button"
                        variant="outline"
                        class="mt-2"
                        @click="push({ name: '' })"
                    >
                        <PlusIcon class="h-4 w-4" /> Add Feature
                    </Button>
                </div>
            </div>
            <DialogFooter>
                <Button @click="onSubmit" :disabled="isPending">
                    Submit
                </Button>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
