import { useForm } from "vee-validate";
import { useMutation } from "@tanstack/vue-query";
import type { UseMutationOptions } from "@tanstack/vue-query";

export function useFormMutation(
    mutationFn: (data: any) => Promise<any>,
    form: ReturnType<typeof useForm>,
    options?: Omit<UseMutationOptions, "mutationFn">,
) {
    const mutation = useMutation({
        mutationFn,
        ...options,
        onError: (error: any, variables, context) => {
            // Map API errors to form errors
            if (error.response?.data?.errors) {
                // Handle validation errors (field-specific)
                const serverErrors = error.response.data.errors;
                Object.keys(serverErrors).forEach((field) => {
                    form.setFieldError(field, serverErrors[field][0]);
                });
            } else if (error.response?.data?.message) {
                // Handle single error message
                form.setErrors({ _form: error.response.data.message });
            } else {
                // Handle unexpected errors
                form.setErrors({ _form: "An unexpected error occurred" });
            }

            // Call the original onError if provided
            // @ts-ignore
            if (options?.onError) {
                // @ts-ignore
                options.onError(error, variables, context);
            }
        },
    });

    return mutation;
}
