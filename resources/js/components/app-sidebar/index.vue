<script lang="ts" setup>
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarRail,
} from "@/components/ui/sidebar";
import { sidebarData } from "./data/sidebar-data";

import NavFooter from "./nav-footer.vue";
import NavGroup from "./nav-group.vue";
import TeamSwitcher from "./team-switcher.vue";
import { useAuth } from "@/composables/use-auth";

const { authenticatedUser } = useAuth();
</script>

<template>
    <Sidebar collapsible="icon" class="z-50">
        <SidebarHeader>
            <TeamSwitcher :teams="sidebarData.teams" />
        </SidebarHeader>

        <SidebarContent>
            <NavGroup :nav-main="sidebarData.navMain" />
        </SidebarContent>

        <SidebarFooter v-if="authenticatedUser">
            <NavFooter :user="authenticatedUser" />
        </SidebarFooter>

        <SidebarRail />
    </Sidebar>
</template>

<style></style>
