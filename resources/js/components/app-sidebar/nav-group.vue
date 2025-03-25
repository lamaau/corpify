<script lang="ts" setup>
import { useRoute } from "vue-router";
import type { NavGroup, NavItem } from "./types";
import {
  Collapsible,
  CollapsibleContent,
  CollapsibleTrigger,
} from "@/components/ui/collapsible";
import {
  SidebarGroup,
  SidebarGroupLabel,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
  SidebarMenuSub,
  SidebarMenuSubButton,
  SidebarMenuSubItem,
} from "@/components/ui/sidebar";
import { ChevronDown, ChevronRight } from "lucide-vue-next";

const { navMain } = defineProps<{
  navMain: NavGroup[];
}>();

const route = useRoute();

function isCollapsed(menu: NavItem): boolean {
  const pathname = route.path;
  navMain.forEach((group) => {
    group.items.forEach((item) => {
      if (item.url === pathname) {
        return true;
      }
    });
  });
  return !!menu.items?.some((item) => item.url === pathname);
}

function isActive(menu: NavItem): boolean {
  const pathname = route.path;
  if (menu.url) {
    return pathname === menu.url;
  }
  return !!menu.items?.some((item) => item.url === pathname);
}
</script>

<template>
  <SidebarGroup v-for="group in navMain" :key="group.title">
    <SidebarGroupLabel>{{ group.title }}</SidebarGroupLabel>
    <SidebarMenu>
      <template v-for="menu in group.items" :key="menu.title">
        <SidebarMenuItem v-if="!menu.items">
          <SidebarMenuButton as-child :is-active="isActive(menu)">
            <router-link :to="menu.url">
              <component :is="menu.icon" />
              <span>{{ menu.title }}</span>
            </router-link>
          </SidebarMenuButton>
        </SidebarMenuItem>
        <SidebarMenuItem v-else>
          <Collapsible as-child :default-open="isCollapsed(menu)">
            <CollapsibleTrigger as-child>
              <SidebarMenuButton :tooltip="menu.title">
                <component :is="menu.icon" />
                <span>{{ menu.title }}</span>
                <ChevronRight
                  v-if="!isCollapsed(menu)"
                  class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                />
                <ChevronDown
                  v-else
                  class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                />
              </SidebarMenuButton>
            </CollapsibleTrigger>
            <CollapsibleContent>
              <SidebarMenuSub>
                <SidebarMenuSubItem
                  v-for="subItem in menu.items"
                  :key="subItem.title"
                >
                  <SidebarMenuSubButton
                    as-child
                    :is-active="isActive(subItem as NavItem)"
                  >
                    <router-link :to="subItem?.url || '/'">
                      <span>{{ subItem.title }}</span>
                    </router-link>
                  </SidebarMenuSubButton>
                </SidebarMenuSubItem>
              </SidebarMenuSub>
            </CollapsibleContent>
          </Collapsible>
        </SidebarMenuItem>
      </template>
    </SidebarMenu>
  </SidebarGroup>
</template>
