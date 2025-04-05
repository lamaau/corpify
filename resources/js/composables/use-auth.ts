import { ref } from "vue";
import { useForm } from "@/lib/form";
import { useRouter } from "vue-router";
import { User } from "@/components/app-sidebar/types";

export function useAuth() {
    const router = useRouter();
    const authenticatedUser = ref<User | null>(getUser());
    const isAuthenticated = ref<boolean>(checkTokenExists());

    function checkTokenExists() {
        return !!document.cookie.includes("token=");
    }

    function setUser(user: User) {
        localStorage.setItem("user", JSON.stringify(user));
    }

    function getUser() {
        const user = localStorage.getItem("user");

        return user ? JSON.parse(user) : null;
    }

    function setToken(token: string) {
        const isSecure = window.location.protocol === "https:";

        document.cookie = `token=${token}; path=/; samesite=strict; max-age=${60 * 60 * 24 * 7}${isSecure ? "; secure" : ""}`; // 7 days
        isAuthenticated.value = true;
    }

    function removeToken() {
        document.cookie =
            "token=; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT";
        isAuthenticated.value = false;
    }

    // Login form hook
    const form = useForm({
        defaultValues: {
            email: "",
            password: "",
        },
        onSuccess: ({ user, token }) => {
            setUser({
                name: user.profile.name,
                email: user.email,
                avatar: user.profile.avatar,
            });

            setToken(token);
            router.push("/dashboard");
        },
        onError: (error) => {
            // Optional: handle login error (show message, etc.)
            console.error("Login failed", error);
        },
    });

    // Navigation guard
    function requireAuth() {
        if (!isAuthenticated.value) {
            router.push("/login");
        }
    }

    // Logout function
    function logout() {
        removeToken();
        router.push("/login");
    }

    return {
        form,
        authenticatedUser,
        isAuthenticated,
        setToken,
        removeToken,
        requireAuth,
        logout,
    };
}
