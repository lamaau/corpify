import type {
  RowData,
  GroupColumnDef,
  DisplayColumnDef,
  AccessorColumnDef,
} from "@tanstack/vue-table";

export { default as DataTable } from "./DataTable.vue";
export { default as DataTableRowActions } from "./DataTableRowActions.vue";
export { default as DataTableColumnHeader } from "./DataTableColumnHeader.vue";

export type ColumnDef<TData extends RowData, TValue = unknown> =
  | DisplayColumnDef<TData, TValue>
  | GroupColumnDef<TData, TValue>
  | AccessorColumnDef<TData, TValue>;
