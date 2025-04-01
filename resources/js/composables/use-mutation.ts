import type { UseMutationOptions } from "@tanstack/vue-query";
import { useMutation as QueryMutation } from "@tanstack/vue-query";

export function useMutation(
    mutationFn: (data?: any) => Promise<any>,
    options?: Omit<UseMutationOptions, "mutationFn">,
) {
    const mutation = QueryMutation({
        mutationFn,
        ...options,
        onError: (error: any, variables, context) => {
            // add error handler here if needed

            // @ts-ignore
            if (options?.onError) {
                // @ts-ignore
                options.onError(error, variables, context);
            }
        },
    });

    return mutation;
}
