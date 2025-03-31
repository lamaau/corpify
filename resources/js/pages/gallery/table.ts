import { h } from "vue";
import Photo from "./components/Photo.vue";
import { Badge } from "@/components/ui/badge";
import DataTableRowActions from "./components/DataTableRowActions.vue";
import DataTableColumnHeader from "@/components/datatable/DataTableColumnHeader.vue";

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
        accessorKey: "file",
        header: ({ column }: any) => {
            return h(DataTableColumnHeader, {
                column,
                title: "Status",
            });
        },
        cell: ({ row }: any) => {
            return h(Photo, { row: row.original });
        },
    },
    {
        accessorKey: "status",
        header: ({ column }: any) => {
            return h(DataTableColumnHeader, {
                column,
                title: "Type",
            });
        },
        cell: ({ row }: any) => {
            return h(Badge, row.getValue("status"));
        },
    },
    {
        accessorKey: "caption",
        header: ({ column }: any) => {
            return h(DataTableColumnHeader, {
                column,
                title: "caption",
            });
        },
        cell: ({ row }: any) => {
            return row.getValue("caption");
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
