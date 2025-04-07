import { abilityQueryKeys } from "@/enums/query-keys";
import fetcher from "@/lib/fetcher";
import { useQuery } from "@tanstack/vue-query";

export type TAbility = {
    id: number;
    name: string;
};

export interface IAbilityAPIResponse {
    current_page: number;
    data: TAbility[];
    // another pagination type here
    // will be refactored to use Generic Types
}

export function useAbilityQuery() {
    return useQuery<IAbilityAPIResponse>({
        queryKey: abilityQueryKeys.all,
        queryFn: async () => {
            const { data } = await fetcher.get(`/abilities`);

            return data;
        },
    });
}
