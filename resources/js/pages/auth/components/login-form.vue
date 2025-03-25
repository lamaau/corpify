<script lang="ts" setup>
import { Button } from "@/components/ui/button";
import GitHubButton from "./github-button.vue";
import GoogleButton from "./google-button.vue";
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
import { Label } from "@/components/ui/label";
import { Separator } from "@/components/ui/separator";
import { Input } from "@/components/ui/input";
</script>

<template>
  <Card class="w-full max-w-sm">
    <CardHeader>
      <CardTitle class="text-2xl"> Login </CardTitle>
      <CardDescription>
        Enter your email and password below to log into your account. Not have
        an account?
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
        <Input id="email" type="email" placeholder="m@example.com" reqred />
      </div>
      <div class="grid gap-2">
        <div class="flex items-center justify-between">
          <Label for="password"> Password </Label>
          <ToForgotPasswordLink />
        </div>
        <Input id="password" type="password" reqred placeholder="*********" />
      </div>

      <Button class="w-full"> Login </Button>

      <Separator label="Or continue with" />

      <div class="flex items-center justify-between gap-4">
        <GitHubButton />
        <GoogleButton />
      </div>

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
