<script setup lang="ts">
import { ref, watch } from "vue";
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
import {
    FormControl,
    FormDescription,
    FormField,
    FormItem,
    FormMessage,
} from "@/components/ui/form";
import fetcher from "@/lib/fetcher";
import { useForm } from "vee-validate";
import Select from "@/components/select.vue";
import { toast } from "@/components/ui/toast";
import { useFormMutation } from "@/composables/use-form-mutation";
import { QueryClient } from "@tanstack/query-core";
import { useQueryClient } from "@tanstack/vue-query";
import { tableQueryKeys } from "@/enums/query-keys";

const isOpen = ref<boolean>(false);
const emit = defineEmits(["update:open"]);
const queryClient: QueryClient = useQueryClient();

const props = defineProps<{
    row: any;
}>();

const form = useForm();

const { isPending, mutate } = useFormMutation(
    async (data) => await fetcher.post(`/role/assign/${props.row.id}`, data),
    form,
    {
        onSuccess: ({ message: description }: any) => {
            toast({ description });
            isOpen.value = false;
            queryClient.invalidateQueries(
                tableQueryKeys.lists("/users") as any,
            );
        },
    },
);

const onSubmit = form.handleSubmit(async (data) => {
    mutate({
        role_id: data.role_id ? data.role_id.id : undefined,
    });
});

watch(isOpen, (newIsOpen) => {
    emit("update:open", newIsOpen);

    if (newIsOpen && props.row.roles.length) {
        const role = props.row.roles[0];

        form.setValues({
            role_id: {
                id: role.id,
                name: role.name,
            },
        });
    }
});
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger asChild>
            <slot />
        </DialogTrigger>
        <DialogContent class="min-w-max">
            <DialogHeader class="flex items-start">
                <DialogTitle>Set user ability</DialogTitle>
                <DialogDescription>
                    You can assign user ability dynamicly
                </DialogDescription>
            </DialogHeader>

            <div class="flex flex-col space-y-4">
                <div class="flex flex-col gap-y-2">
                    <FormField v-slot="{ componentField }" name="role_id">
                        <FormItem>
                            <FormLabel>Ability</FormLabel>
                            <FormControl>
                                <Select
                                    v-bind="componentField"
                                    api-url="/roles"
                                    :id-key="(role: any) => role.id"
                                    :name-key="(role: any) => role.name"
                                />
                            </FormControl>
                            <FormMessage />
                            <FormDescription>
                                The user ability at all of the app will be
                                follow the last ability changed.
                            </FormDescription>
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
