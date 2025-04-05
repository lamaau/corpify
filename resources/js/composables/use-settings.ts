import fetcher from "@/lib/fetcher";
import { useQuery } from "@tanstack/vue-query";
import { settingQueryKeys } from "@/enums/query-keys";

export interface ICarouseImagelList {
    id: string;
    title: string;
    summary: string;
    file: string;
}

export interface ICarouseTextList {
    id: string;
    icon: string;
    title: string;
    summary: string;
}

export function useSettingsQuery(context: string) {
    return useQuery({
        queryKey: settingQueryKeys.detail(context),
        queryFn: async () => {
            const { data } = await fetcher.get("settings/site", {
                params: {
                    context,
                },
            });

            return data;
        },
        enabled: !!context,
    });
}
