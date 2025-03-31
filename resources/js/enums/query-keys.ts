export const ogQueryKeys = {
    all: ["all"] as const,
    detail: (id: string | number) => [...ogQueryKeys.all, id] as const,
};
