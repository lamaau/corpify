import { h, Ref } from "vue";
import TableAction from "./table-action.vue";
import DataTableColumnHeader from "@/components/datatable/DataTableColumnHeader.vue";

export const createColumns = (tableRef: Ref) => [
    {
        id: "actions",
        cell: ({ row }: any) => {
            return h(TableAction, { row: row.original });
        },
    },
    {
        accessorKey: "title",
        header: ({ column }: any) => {
            return h(DataTableColumnHeader, {
                column,
                title: "Title",
            });
        },
        cell: ({ row }: any) => {
            return row.getValue("title");
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
