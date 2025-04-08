import { h } from "vue";
import Profile from "./profile.vue";
import DataTableRowActions from "./table-action.vue";
import DataTableColumnHeader from "@/components/datatable/DataTableColumnHeader.vue";

export const createColumns = (tableRef: any) => [
    {
        id: "actions",
        cell: ({ row }: any) => {
            return h(DataTableRowActions, {
                row: row.original,
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
        accessorKey: "roles",
        header: ({ column }: any) => {
            return h(DataTableColumnHeader, {
                column,
                title: "Ability",
            });
        },
        cell: ({ row }: any) => {
            const roles = row.getValue("roles");
            if (!roles.length) {
                return h("span", { class: "p-2 text-muted-foreground" }, "N/A");
            }

            return roles[0].name;
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
            return row.original.created_at.formatted.date;
        },
    },
];
