<script setup lang="ts">
import { ref } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import AppLogo from "./AppLogo.vue";
import NavUser from "@/components/NavUser.vue";
import {
  Sidebar,
  SidebarContent,
  SidebarFooter,
  SidebarHeader,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from "@/components/ui/sidebar";
import {
  LayoutGrid,
  Users,
  GraduationCap,
  BookOpen,
  School,
  Group,
} from "lucide-vue-next";

const page = usePage();
const currentUrl = page.url; // URL aktif

const openedDropdown = ref<string | null>(null);
function toggleDropdown(title: string) {
  openedDropdown.value = openedDropdown.value === title ? null : title;
}

const mainNavItems = [
  { title: "Dashboard", href: "/dashboard", icon: LayoutGrid },
  { title: "Manage User", href: "/user", icon: Users },
  { title: "Data Guru", href: "/guru", icon: Users },
  { title: "Data Mapel", href: "/mapel", icon: BookOpen },
  { title: "Data Kelas", href: "/kelas", icon: School },
  { title: "Data Jurusan", href: "/jurusan", icon: Group },
  {
    title: "Data Siswa",
    icon: GraduationCap,
    children: [
      { title: "X RPL", href: "/siswax" },
      { title: "XI RPL", href: "/siswaxi" },
      { title: "XII RPL", href: "/siswaxii" },
    ],
  },
];
</script>

<template>
  <Sidebar collapsible="icon" variant="inset" class="w-64">
    <!-- Header -->
    <SidebarHeader class="px-6 py-6">
      <SidebarMenu>
        <SidebarMenuItem>
          <SidebarMenuButton as-child class="gap-3 text-lg font-semibold">
            <Link :href="route('dashboard')" class="flex items-center">
              <AppLogo class="w-9 h-9" />
              <span class="ml-2">My Dashboard</span>
            </Link>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarHeader>

    <!-- Menu Utama -->
    <SidebarContent class="px-2 py-4 overflow-y-auto custom-scroll">
      <nav class="space-y-2">
        <div v-for="item in mainNavItems" :key="item.title">
          <!-- Jika ada children (dropdown) -->
          <div v-if="item.children" class="space-y-1">
            <button
              class="w-full flex items-center justify-between px-4 py-3 rounded-lg font-medium transition text-gray-300 hover:bg-white hover:text-black"
              @click="toggleDropdown(item.title)"
            >
              <div class="flex items-center gap-3">
                <component :is="item.icon" class="w-5 h-5" />
                <span>{{ item.title }}</span>
              </div>
              <span>{{ openedDropdown === item.title ? "▾" : "▸" }}</span>
            </button>
            <div
              v-if="openedDropdown === item.title"
              class="ml-6 pl-2 border-l border-gray-700 space-y-1"
            >
              <Link
                v-for="child in item.children"
                :key="child.title"
                :href="child.href"
                class="block px-4 py-2 rounded-md text-sm transition text-gray-400 hover:bg-white hover:text-black"
                :class="{
                  'bg-white text-black font-semibold':
                    currentUrl === child.href ||
                    currentUrl.startsWith(child.href + '/'),
                }"
              >
                {{ child.title }}
              </Link>
            </div>
          </div>

          <!-- Jika tidak ada children -->
          <Link
            v-else
            :href="item.href"
            class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition text-gray-300 hover:bg-white hover:text-black"
            :class="{
              'bg-white text-black font-semibold':
                currentUrl === item.href ||
                currentUrl.startsWith(item.href + '/'),
            }"
          >
            <component :is="item.icon" class="w-5 h-5" />
            <span>{{ item.title }}</span>
          </Link>
        </div>
      </nav>
    </SidebarContent>

    <!-- Footer -->
    <SidebarFooter class="px-4 py-6 border-t mt-auto">
      <NavUser class="text-sm" />
    </SidebarFooter>
  </Sidebar>
  <slot />
</template>

<style scoped>
/* Scrollbar auto-hide */
.custom-scroll {
  scrollbar-width: thin;
  scrollbar-color: #9ca3af transparent;
}

/* Chrome, Edge, Safari */
.custom-scroll::-webkit-scrollbar {
  width: 6px;
}
.custom-scroll::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scroll::-webkit-scrollbar-thumb {
  background-color: #9ca3af;
  border-radius: 9999px;
}

/* Auto-hide effect */
.custom-scroll::-webkit-scrollbar {
  display: none;
}
.custom-scroll:hover::-webkit-scrollbar {
  display: block;
}
</style>
