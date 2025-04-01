export const tableQueryKeys = {
    all: ["all"] as const,
    lists: (url: string) => [...tableQueryKeys.all, url] as const,
};

export const ogQueryKeys = {
    all: ["all"] as const,
    detail: (id: string | number) => [...ogQueryKeys.all, id] as const,
};

export const constantQueryKeys = {
    posts: ["posts"] as const,
};

export const postQueryKeys = {
    all: ["all"] as const,
    detail: (id: string | number) => [...postQueryKeys.all, id] as const,
};
