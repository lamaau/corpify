import { useForm, useSetFormErrors } from "vee-validate";
import { z } from "zod";

// Types for configuration and options
interface FetcherConfig {
    baseURL?: string;
    headers?: Record<string, string>;
}

interface RequestOptions {
    headers?: Record<string, string>;
    params?: Record<string, string>;
}

// Simple fetcher utility
const fetcher = {
    config: {
        baseURL: import.meta.env.VITE_API_URL,
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
    },

    // Configure base settings
    configure(config: FetcherConfig) {
        this.config = {
            ...this.config,
            ...config,
            headers: {
                ...this.config.headers,
                ...config.headers,
            },
        };
        return this;
    },

    // Build URL with query parameters
    buildURL(url: string, params?: Record<string, string>): string {
        // Ensure `baseURL` is always used
        const baseURL = this.config.baseURL?.replace(/\/$/, "");
        const normalizedURL = url.startsWith("/") ? url : `/${url}`;

        const fullURL = baseURL ? `${baseURL}${normalizedURL}` : normalizedURL;

        if (params) {
            const urlObj = new URL(fullURL, window.location.origin);
            Object.entries(params).forEach(([key, value]) => {
                urlObj.searchParams.append(key, value);
            });
            return urlObj.toString();
        }

        return fullURL;
    },

    // Prepare headers for request
    prepareHeaders(options?: RequestOptions): Headers {
        const headers = new Headers(this.config.headers);

        if (options?.headers) {
            Object.entries(options.headers).forEach(([key, value]) => {
                headers.set(key, value);
            });
        }

        const csrfToken = document.cookie
            .split("; ")
            .find((row) => row.startsWith("XSRF-TOKEN="))
            ?.split("=")[1];

        if (csrfToken) {
            headers.set("X-XSRF-TOKEN", decodeURIComponent(csrfToken));
        }

        const authToken = document.cookie
            .split("; ")
            .find((row) => row.startsWith("token="))
            ?.split("=")[1];

        if (authToken) {
            headers.set(
                "Authorization",
                `Bearer ${decodeURIComponent(authToken)}`,
            );
        }

        return headers;
    },

    // Generic request method
    async request(
        method: string,
        url: string,
        data?: any,
        options: RequestOptions = {},
    ) {
        const headers = this.prepareHeaders(options);
        const fullURL = this.buildURL(url, options.params);

        try {
            if (data instanceof FormData) {
                headers.delete("Content-Type");
            }

            const requestOptions: RequestInit = {
                method,
                headers,
                body:
                    data instanceof FormData
                        ? data
                        : data
                          ? JSON.stringify(data)
                          : undefined,
            };

            const response = await fetch(fullURL, requestOptions);

            // Parse response body first
            // const contentType = response.headers.get("content-type");
            let responseData;

            // if (contentType?.includes("application/json")) {
            responseData = await response.json();
            // } else {
            //   responseData = await response.text();
            // }

            // Check response status AFTER parsing body
            if (!response.ok) {
                const error = new Error(
                    `HTTP error! status: ${response.status}`,
                );
                (error as any).response = {
                    status: response.status,
                    data: responseData,
                };

                throw error;
            }

            return responseData;
        } catch (error) {
            throw error;
        }
    },

    // HTTP method helpers
    get(url: string, options: RequestOptions = {}) {
        const defaultOptions: RequestOptions = {
            headers: {
                "Content-Type": "application/json",
                ...(options.headers || {}),
            },
        };

        return this.request("GET", url, undefined, {
            ...defaultOptions,
            ...options,
        });
    },

    post(url: string, data?: any, options: RequestOptions = {}) {
        return this.request("POST", url, data, options);
    },

    put(url: string, data?: any, options: RequestOptions = {}) {
        return this.request("PUT", url, data, options);
    },

    delete(url: string, options: RequestOptions = {}) {
        return this.request("DELETE", url, undefined, options);
    },

    // Form data upload
    postForm(url: string, formData: FormData, options: RequestOptions = {}) {
        const formOptions = {
            ...options,
            headers: {
                ...options.headers,
                "Content-Type": "multipart/form-data",
            },
        };
        return this.request("POST", url, formData, formOptions);
    },

    // Optional: Zod schema validation helper
    validate<T>(schema: z.ZodSchema<T>) {
        return async (data: unknown): Promise<T> => {
            return schema.parse(data);
        };
    },
};

export default fetcher;

export function handleFormSubmit<T>(
    form: Pick<ReturnType<typeof useForm>, "handleSubmit" | "setErrors">,
    submitFn: (data: any) => Promise<void>,
) {
    // Return a function that will be passed to form.handleSubmit
    // This preserves the isSubmitting state management
    return form.handleSubmit(async (values: any) => {
        try {
            await submitFn(values);
        } catch (error: any) {
            if (error?.response?.data?.errors) {
                const validationErrors = error.response.data.errors;

                // Format errors for vee-validate
                const formattedErrors = Object.entries(validationErrors).reduce(
                    (acc, [field, messages]) => {
                        acc[field] = Array.isArray(messages)
                            ? messages[0]
                            : messages;
                        return acc;
                    },
                    {} as Record<string, string>,
                );

                form.setErrors(formattedErrors);
            }

            throw error;
        }
    });
}
