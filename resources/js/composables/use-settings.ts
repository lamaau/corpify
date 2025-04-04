import fetcher from "@/lib/fetcher";
import { useQuery } from "@tanstack/vue-query";
import { settingQueryKeys } from "@/enums/query-keys";

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
