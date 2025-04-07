import { h } from "vue";
import Abilities from "./abilities.vue";
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
        accessorKey: "name",
        header: ({ column }: any) => {
            return h(DataTableColumnHeader, {
                column,
                title: "Name",
            });
        },
        cell: ({ row }: any) => {
            return row.getValue("name");
        },
    },
    {
        accessorKey: "summary",
        header: ({ column }: any) => {
            return h(DataTableColumnHeader, {
                column,
                title: "Summary",
            });
        },
        cell: ({ row }: any) => {
            const summary = row.getValue("summary");

            if (summary) return summary;

            return h("span", { class: "p-2 text-muted-foreground" }, "N/A");
        },
    },
    {
        accessorKey: "abilities",
        header: ({ column }: any) => {
            return h(DataTableColumnHeader, {
                column,
                title: "Ability",
            });
        },
        cell: ({ row }: any) => {
            return h(Abilities, { abilities: row.original.abilities });
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
