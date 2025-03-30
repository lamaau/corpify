import { z } from "zod";
import fetcher from "@/lib/fetcher";

export const LoginRequestSchema = z.object({
    email: z.string().email(),
    password: z.string().min(6),
});

export const authApi = {
    login: (credentials: z.infer<typeof LoginRequestSchema>) => {
        return fetcher.post("/auth/login", credentials);
    },
    logout: () => {
        return fetcher.post("/auth/logout");
    },
};
