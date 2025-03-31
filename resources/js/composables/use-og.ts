import fetcher from "@/lib/fetcher";
import { useQuery } from "@tanstack/vue-query";

export function useOg() {
    return useQuery({
        queryKey: ["og"],
        queryFn: async () => {
            const { data } = await fetcher.get("/og");

            return data;
        },
    });
}
