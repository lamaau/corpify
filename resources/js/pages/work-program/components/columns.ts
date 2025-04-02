import { h, Ref } from "vue";
import Title from "./title.vue";
import TableAction from "./table-action.vue";
import { Badge } from "@/components/ui/badge";
import DataTableColumnHeader from "@/components/datatable/DataTableColumnHeader.vue";

export const createColumns = (tableRef: Ref) => [
    {
        id: "actions",
        cell: ({ row }: any) => {
            return h(TableAction, {
                row: row.original,
            });
        },
    },
    {
        accessorKey: "thumbnail",
        header: ({ column }: any) => {
            return h(DataTableColumnHeader, {
                column,
                title: "Title",
            });
        },
        cell: ({ row }: any) => {
            return h(Title, { row: row.original });
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
            return row.original.summary;
        },
    },
    {
        accessorKey: "program",
        header: ({ column }: any) => {
            return h(DataTableColumnHeader, {
                column,
                title: "Program",
            });
        },
        cell: ({ row }: any) => {
            return h("div", row.original.program.name);
        },
    },
    {
        accessorKey: "status",
        header: ({ column }: any) => {
            return h(DataTableColumnHeader, {
                column,
                title: "Status",
            });
        },
        cell: ({ row }: any) => {
            return h(Badge, row.original.status.name);
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
