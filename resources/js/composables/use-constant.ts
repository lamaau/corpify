import fetcher from "@/lib/fetcher";
import { useQuery } from "@tanstack/vue-query";
import { constantQueryKeys } from "@/enums/query-keys";

export function usePostsStatusQuery() {
    return useQuery({
        queryKey: constantQueryKeys.posts,
        queryFn: async () => {
            const { data } = await fetcher.get("/constant/posts");

            return data;
        },
    });
}
