<script setup lang="ts">
import { Button } from "@/components/ui/button";
import {
    Dialog,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
    DialogContent,
    DialogDescription,
} from "@/components/ui/dialog";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { Checkbox } from "@/components/ui/checkbox";
import {
    FormControl,
    FormLabel,
    FormField,
    FormItem,
    FormMessage,
} from "@/components/ui/form";
import { ref, watch } from "vue";
import fetcher from "@/lib/fetcher";
import { useForm } from "vee-validate";
import { PlusIcon } from "lucide-vue-next";
import { toast } from "@/components/ui/toast";
import { tableQueryKeys } from "@/enums/query-keys";
import { useFormMutation } from "@/composables/use-form-mutation";
import { IAbilityAPIResponse, TAbility } from "@/composables/use-ability";
import { InvalidateQueryFilters, QueryClient } from "@tanstack/query-core";
import { useQueryClient } from "@tanstack/vue-query";

const isOpen = ref<boolean>(false);
const emit = defineEmits(["update:open"]);
const props = defineProps<{ row?: any; abilities?: IAbilityAPIResponse }>();

const dataAbilities = ref<TAbility[]>([]);
const queryClient = useQueryClient();

const form = useForm({
    initialValues: {
        name: "",
        summary: "",
        abilities: [] as number[],
    },
});

watch(isOpen, (val) => {
    emit("update:open", val);

    if (val && props.row) {
        // Populate form values when editing
        form.setValues({
            name: props.row.name || "",
            summary: props.row.summary || "",
            abilities: props.row.abilities?.map((a: any) => a.id) || [],
        });
    }

    if (val && props.abilities) {
        dataAbilities.value = props.abilities.data;
    }
});

const { isPending, mutate } = useFormMutation(
    async (data) => await fetcher.put(`/roles/${props.row.id}`, data),
    form,
    {
        onSuccess: ({ message }: any) => {
            toast({ description: message });
            isOpen.value = false;
            queryClient.invalidateQueries(
                tableQueryKeys.lists(
                    "/roles",
                ) as InvalidateQueryFilters<unknown>,
            );
        },
    },
);

const onSubmit = form.handleSubmit((data) => {
    mutate(data);
});
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger asChild>
            <slot />
        </DialogTrigger>

        <DialogContent class="min-w-max">
            <DialogHeader class="flex items-start">
                <DialogTitle>Update the ability</DialogTitle>
                <DialogDescription>
                    You can assign user or member to use the ability
                </DialogDescription>
            </DialogHeader>

            <div class="flex flex-col space-y-4">
                <!-- Name Input -->
                <FormField v-slot="{ componentField }" name="name">
                    <FormItem>
                        <FormControl>
                            <FormLabel>Name</FormLabel>
                            <Input v-bind="componentField" />
                            <FormMessage />
                        </FormControl>
                    </FormItem>
                </FormField>

                <!-- Summary Textarea -->
                <FormField v-slot="{ componentField }" name="summary">
                    <FormItem>
                        <FormControl>
                            <FormLabel>Summary</FormLabel>
                            <Textarea v-bind="componentField" />
                            <FormMessage />
                        </FormControl>
                    </FormItem>
                </FormField>

                <!-- Multiple Checkbox Array -->
                <FormField v-slot="{ setValue, value }" name="abilities">
                    <FormItem>
                        <FormLabel>Abilities</FormLabel>
                        <div class="flex flex-col space-y-4">
                            <div
                                v-for="option in dataAbilities"
                                :key="option.id"
                                class="flex items-center space-x-2"
                            >
                                <Checkbox
                                    :id="`ability-${option.id}`"
                                    :model-value="value?.includes(option.id)"
                                    @update:model-value="
                                        (checked) => {
                                            // Get current value as array
                                            const currentValue = Array.isArray(
                                                value,
                                            )
                                                ? [...value]
                                                : [];

                                            if (checked) {
                                                // Add option.id if it's not already in the array
                                                if (
                                                    !currentValue.includes(
                                                        option.id,
                                                    )
                                                ) {
                                                    currentValue.push(
                                                        option.id,
                                                    );
                                                }
                                            } else {
                                                // Remove option.id if it exists
                                                const index =
                                                    currentValue.indexOf(
                                                        option.id,
                                                    );
                                                if (index !== -1) {
                                                    currentValue.splice(
                                                        index,
                                                        1,
                                                    );
                                                }
                                            }

                                            // Update the field value
                                            setValue(currentValue);
                                        }
                                    "
                                />
                                <label
                                    :for="`ability-${option.id}`"
                                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                >
                                    {{ option.name }}
                                </label>
                            </div>
                        </div>
                        <FormMessage />
                    </FormItem>
                </FormField>
            </div>

            <DialogFooter>
                <Button :disabled="isPending" @click="onSubmit">
                    Submit
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
