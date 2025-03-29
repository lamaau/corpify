import { h } from "vue";
import { Badge } from "@/components/ui/badge";
import DataTableRowActions from "@/components/datatable/DataTableRowActions.vue";
import DataTableColumnHeader from "@/components/datatable/DataTableColumnHeader.vue";

export const createColumns = (tableRef: any) => [
  {
    id: "actions",
    cell: ({ row }: any) => {
      return h(DataTableRowActions, { row, tableRef });
    },
  },
  {
    accessorKey: "name",
    header: ({ column }: any) => {
      return h(DataTableColumnHeader, {
        column,
        title: "Nama",
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
      return h(
        Badge,
        { variant: "outline", class: "p-1" },
        row.getValue("summary")
      );
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
