import { ref } from "vue";
import { useForm } from "@/lib/form";
import { useRouter } from "vue-router";

export function useAuth() {
  const router = useRouter();
  const isAuthenticated = ref(checkTokenExists());

  function checkTokenExists() {
    return !!document.cookie.includes("token=");
  }

  function setToken(token: string) {
    const isSecure = window.location.protocol === "https:";

    document.cookie = `token=${token}; path=/; samesite=strict; max-age=${60 * 60 * 24 * 7}${isSecure ? "; secure" : ""}`; // 7 days
    isAuthenticated.value = true;
  }

  function removeToken() {
    document.cookie = "token=; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT";
    isAuthenticated.value = false;
  }

  // Login form hook
  const form = useForm({
    defaultValues: {
      email: "",
      password: "",
    },
    onSuccess: ({ user, token }) => {
      // Set token in cookies
      setToken(token);

      // Redirect to dashboard or home page
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
    isAuthenticated,
    setToken,
    removeToken,
    requireAuth,
    logout,
  };
}
