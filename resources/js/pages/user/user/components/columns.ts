import { h } from "vue";
import DataTableRowActions from "./DataTableRowActions.vue";
import DataTableColumnHeader from "@/components/datatable/DataTableColumnHeader.vue";
import Profile from "./profile.vue";

export const createColumns = (tableRef: any) => [
    {
        id: "actions",
        cell: ({ row }: any) => {
            return h(DataTableRowActions, {
                row: row.original,
                onReload: () => {
                    tableRef.reload();
                },
            });
        },
    },
    {
        accessorKey: "profile",
        header: ({ column }: any) => {
            return h(DataTableColumnHeader, {
                column,
                title: "Profile",
            });
        },
        cell: ({ row }: any) => {
            return h(Profile, { row: row.original });
        },
    },
    {
        accessorKey: "created_at",
        header: ({ column }: any) => {
            return h(DataTableColumnHeader, {
                column,
                title: "Created at",
            });
        },
        cell: ({ row }: any) => {
            return row.original.created_at;
        },
    },
];
