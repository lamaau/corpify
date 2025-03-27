import { z } from "zod";
import { reactive, toRaw } from "vue";

// Form options interface with optional schema and callbacks
interface FormOptions<T extends Record<string, any>> {
  defaultValues?: T;
  schema?: z.ZodSchema<T> | null;
  onSuccess?: (response: any) => void;
  onError?: (error: any) => void;
}

// Form error type
type FormErrors<T> = Partial<Record<keyof T, string[]>>;

// Form processing state
interface FormState {
  processing: boolean;
  errors: Record<string, string[]>;
  hasErrors: boolean;
  wasSuccessful: boolean;
  recentlySuccessful: boolean;
}

// Custom form hook
export function useForm<T extends Record<string, any>>(
  options: FormOptions<T> = {}
) {
  const {
    defaultValues = {} as T,
    schema = null,
    onSuccess,
    onError,
  } = options;

  // Reactive form data
  const data = reactive<T>({ ...defaultValues });

  // Form state
  const state = reactive<FormState>({
    processing: false,
    errors: {},
    hasErrors: false,
    wasSuccessful: false,
    recentlySuccessful: false,
  });

  // Validate form data
  const validate = () => {
    // Reset previous errors
    state.errors = {};
    state.hasErrors = false;

    // If no schema, skip validation
    if (!schema) return true;

    try {
      // Validate entire form data
      schema.parse(data);
      return true;
    } catch (err) {
      if (err instanceof z.ZodError) {
        // Transform Zod errors to our error format
        const errors: Record<string, string[]> = {};
        err.errors.forEach((error) => {
          const path = error.path.join(".");
          if (!errors[path]) {
            errors[path] = [];
          }
          errors[path].push(error.message);
        });

        // Set errors
        state.errors = errors;
        state.hasErrors = true;
        return false;
      }
      return false;
    }
  };

  // Reset form to initial state
  const reset = () => {
    Object.keys(data).forEach((key) => {
      (data as any)[key] = defaultValues[key];
    });
    state.errors = {};
    state.hasErrors = false;
    state.wasSuccessful = false;
    state.recentlySuccessful = false;
  };

  // Submit form with custom submit function
  const submit = async (submitFn: (formData: T) => Promise<any>) => {
    // Validate form only if schema is provided
    if (schema && !validate()) {
      return false;
    }

    // Set processing state
    state.processing = true;
    state.errors = {};
    state.hasErrors = false;

    try {
      // Call submit function with a raw object
      const response = await submitFn(toRaw(data) as T);

      // Success states
      state.wasSuccessful = true;
      state.recentlySuccessful = true;

      // Reset recently successful after 2 seconds
      setTimeout(() => {
        state.recentlySuccessful = false;
      }, 2000);

      // Call onSuccess callback if provided
      if (onSuccess) {
        onSuccess(response);
      }

      return response;
    } catch (error: any) {
      console.error("Form submission error:", error);

      // Handle different error scenarios
      if (error instanceof Response) {
        try {
          const errorData = await error.json();
          state.errors = errorData.errors || {
            _global: [errorData.message || "Unknown error"],
          };
        } catch {
          state.errors = { _global: ["Network or parsing error"] };
        }
      } else if (error.response?.data?.errors) {
        state.errors = error.response.data.errors;
      } else if (typeof error === "object" && error.errors) {
        state.errors = error.errors;
      } else {
        state.errors = {
          _global: [error.message || "An unexpected error occurred"],
        };
      }

      state.hasErrors = true;

      // Call onError callback if provided
      if (onError) {
        onError(error);
      }

      throw error;
    } finally {
      state.processing = false;
    }
  };

  // Clear specific field error
  const clearError = (field: keyof T) => {
    if (state.errors[field as string]) {
      delete state.errors[field as string];
      state.hasErrors = Object.keys(state.errors).length > 0;
    }
  };

  // Return form utilities
  return {
    data,
    state,
    reset,
    submit,
    validate,
    clearError,
    setError: (errors: FormErrors<T>) => {
      // Ensure values are defined
      state.errors = Object.fromEntries(
        Object.entries(errors).map(([key, value]) => [key, value ?? []])
      );
      state.hasErrors = Object.keys(state.errors).length > 0;
    },
  };
}
