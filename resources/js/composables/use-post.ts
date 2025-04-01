import fetcher from "@/lib/fetcher";
import { useQuery } from "@tanstack/vue-query";
import { postQueryKeys } from "@/enums/query-keys";

export type TDetailPost = {
    id: number;
    title: string;
    slug: string;
    body: string;
    summary: string;
    thumbnail: string;
    category: {
        id: number;
        category_name: string;
    };
    status: {
        value: number;
        name: string;
    };
};

export function usePostQuery() {
    const useShowQuery = (id: string) => {
        return useQuery<TDetailPost>({
            queryKey: postQueryKeys.detail(id),
            queryFn: async () => {
                const { data } = await fetcher.get(`/posts/${id}`);

                return data;
            },
            enabled: !!id,
        });
    };

    return { useShowQuery };
}
