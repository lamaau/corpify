import fetcher from "@/lib/fetcher";
import { useQuery } from "@tanstack/vue-query";
import { galleryQueryKeys } from "@/enums/query-keys";

export interface IServerParams {
    page: number;
    search?: string;
}

export function useGallery(serverParams: IServerParams) {
    return useQuery({
        queryKey: galleryQueryKeys.page(serverParams),
        queryFn: async () => {
            const queryString = new URLSearchParams(
                Object.fromEntries(
                    Object.entries(serverParams).map(([key, value]) => [
                        key,
                        String(value),
                    ]),
                ),
            ).toString();

            return await fetcher.get(`/galleries?${queryString}`);
        },
    });
}
