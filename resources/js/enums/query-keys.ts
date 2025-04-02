export const tableQueryKeys = {
    all: ["all-table"] as const,
    lists: (url: string) => [...tableQueryKeys.all, url] as const,
};

export const ogQueryKeys = {
    all: ["all-og"] as const,
    detail: (id: string | number) => [...ogQueryKeys.all, id] as const,
};

export const constantQueryKeys = {
    posts: ["all-post-constants"] as const,
};

export const postQueryKeys = {
    all: ["all-posts"] as const,
    detail: (id: string | number) => [...postQueryKeys.all, id] as const,
};

export const workProgramQueryKeys = {
    all: ["all-work-programs"] as const,
    detail: (id: string | number) => [...workProgramQueryKeys.all, id] as const,
};
