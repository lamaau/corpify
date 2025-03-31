import fetcher from "@/lib/fetcher";
import { ogQueryKeys } from "@/enums/query-keys";
import { useMutation, useQuery, useQueryClient } from "@tanstack/vue-query";

export function useOg() {
    const queryClient = useQueryClient();

    const fetchOg = () => {
        return useQuery({
            queryKey: ogQueryKeys.all,
            queryFn: async () => {
                const { data } = await fetcher.get("/og");

                return data;
            },
        });
    };

    const deleteOg = (id: string) => {
        return useMutation({
            mutationKey: ogQueryKeys.detail(id),
            mutationFn: async () => {
                return await fetcher.delete(`/og/${id}`);
            },
            onSuccess: () => {
                queryClient.invalidateQueries(ogQueryKeys.all as any);
            },
        });
    };

    return { fetchOg, deleteOg };
}
