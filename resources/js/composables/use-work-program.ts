import fetcher from "@/lib/fetcher";
import { useQuery } from "@tanstack/vue-query";
import { workProgramQueryKeys } from "@/enums/query-keys";

export type TWorkProgram = {
    id: number;
    title: string;
    slug: string;
    body: string;
    summary: string;
    thumbnail: string;
    program: {
        id: number;
        name: string;
    };
    status: {
        value: number;
        name: string;
    };
};

export function useWorkProgramQuery(id: string) {
    return useQuery<TWorkProgram>({
        queryKey: workProgramQueryKeys.detail(id),
        queryFn: async () => {
            const { data } = await fetcher.get(`/work-programs/${id}`);

            return data;
        },
        enabled: !!id,
    });
}
