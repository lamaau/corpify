import type { Updater } from "@tanstack/vue-table";
import type { Ref } from "vue";
import { type ClassValue, clsx } from "clsx";
import { twMerge } from "tailwind-merge";

export function pickBy<T>(
  object: Record<string, T>,
  predicate: (value: T, key: string) => boolean
): Record<string, T> {
  if (!object || typeof object !== "object") {
    throw new TypeError("Expected an object");
  }
  if (typeof predicate !== "function") {
    throw new TypeError("Expected a function");
  }

  return Object.keys(object).reduce(
    (result: Record<string, T>, key: string) => {
      if (predicate(object[key], key)) {
        result[key] = object[key];
      }
      return result;
    },
    {}
  );
}

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
}

export function valueUpdater<T extends Updater<any>>(
  updaterOrValue: T,
  ref: Ref
) {
  ref.value =
    typeof updaterOrValue === "function"
      ? updaterOrValue(ref.value)
      : updaterOrValue;
}
