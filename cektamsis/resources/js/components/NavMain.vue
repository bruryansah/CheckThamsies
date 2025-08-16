<script setup lang="ts">
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import { SidebarMenu, SidebarMenuItem, SidebarMenuButton } from "@/components/ui/sidebar";
import { type NavItem } from "@/types";

defineProps<{
  items: NavItem[];
}>();

// state dropdown terbuka
const openDropdown = ref<string | null>(null);

const toggleDropdown = (title: string) => {
  openDropdown.value = openDropdown.value === title ? null : title;
};
</script>

<template>
  <SidebarMenu>
    <template v-for="item in items" :key="item.title">
      <!-- Jika punya children (dropdown) -->
      <SidebarMenuItem v-if="item.children">
        <SidebarMenuButton
          @click="toggleDropdown(item.title)"
          class="w-full flex justify-between items-center"
        >
          <div class="flex items-center gap-2">
            <component :is="item.icon" v-if="item.icon" class="w-4 h-4" />
            <span>{{ item.title }}</span>
          </div>
          <span class="text-xs">
            {{ openDropdown === item.title ? "▲" : "▼" }}
          </span>
        </SidebarMenuButton>

        <!-- Submenu dengan animasi -->
        <transition
          name="slide-fade"
          enter-active-class="transition duration-300 ease-out"
          leave-active-class="transition duration-200 ease-in"
          enter-from-class="opacity-0 -translate-y-2"
          enter-to-class="opacity-100 translate-y-0"
          leave-from-class="opacity-100 translate-y-0"
          leave-to-class="opacity-0 -translate-y-2"
        >
          <div
            v-if="openDropdown === item.title"
            class="ml-6 mt-1 space-y-1"
          >
            <SidebarMenuItem v-for="child in item.children" :key="child.title">
              <SidebarMenuButton as-child>
                <Link :href="child.href">{{ child.title }}</Link>
              </SidebarMenuButton>
            </SidebarMenuItem>
          </div>
        </transition>
      </SidebarMenuItem>

      <!-- Kalau menu biasa (tanpa children) -->
      <SidebarMenuItem v-else>
        <SidebarMenuButton as-child>
          <Link :href="item.href">
            <div class="flex items-center gap-2">
              <component :is="item.icon" v-if="item.icon" class="w-4 h-4" />
              <span>{{ item.title }}</span>
            </div>
          </Link>
        </SidebarMenuButton>
      </SidebarMenuItem>
    </template>
  </SidebarMenu>
</template>
