<script lang="ts" setup>
import fetcher from "@/lib/fetcher";
import { Label } from "@/components/ui/label";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { useAuth } from "@/composables/use-auth";
import PrivacyPolicyButton from "./privacy-policy-button.vue";
import TermsOfServiceButton from "./terms-of-service-button.vue";
import ToForgotPasswordLink from "./to-forgot-password-link.vue";
import {
    Card,
    CardHeader,
    CardTitle,
    CardDescription,
    CardContent,
} from "@/components/ui/card";

const { form } = useAuth();

const onSubmit = () => {
    form.submit(async (data) => {
        return await fetcher.post("/auth/login", data);
    });
};
</script>
<template>
    <Card class="w-full max-w-sm">
        <CardHeader>
            <CardTitle class="text-2xl"> Login </CardTitle>
            <CardDescription>
                Enter your email and password below to log into your account.
                Not have an account?
                <Button
                    variant="link"
                    class="px-0 text-muted-foreground"
                    @click="$router.push('dashboard')"
                >
                    Sign Up
                </Button>
            </CardDescription>
        </CardHeader>
        <CardContent class="grid gap-4">
            <div class="grid gap-2">
                <Label for="email"> Email </Label>
                <Input
                    v-model="form.data.email"
                    id="email"
                    type="email"
                    placeholder="m@example.com"
                    required
                />
                <span v-if="form.state.errors?.email" class="text-red-500">
                    {{ form.state.errors?.email[0] }}
                </span>
            </div>
            <div class="grid gap-2">
                <div class="flex items-center justify-between">
                    <Label for="password"> Password </Label>
                    <ToForgotPasswordLink />
                </div>
                <Input
                    v-model="form.data.password"
                    id="password"
                    type="password"
                    reqred
                    placeholder="*********"
                />
                <span v-if="form.state.errors?.password" class="text-red-500">
                    {{ form.state.errors?.password[0] }}
                </span>
            </div>

            <Button
                :disabled="form.state.processing"
                class="w-full"
                @click="onSubmit"
            >
                Login
            </Button>

            <CardDescription>
                By clicking login, you agree to our
                <TermsOfServiceButton />
                and
                <PrivacyPolicyButton />
            </CardDescription>
        </CardContent>
    </Card>
</template>

<style scoped></style>
