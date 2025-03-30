import fetcher from "@/lib/fetcher";
import { useQuery } from "@tanstack/vue-query";

export function useSettings() {
    return useQuery({
        queryKey: ["settings"],
        queryFn: async () => {
            const { data } = await fetcher.get("settings/app");

            return data;
        },
    });
}
