<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, watch } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";
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
const currentUrl = page.url;

const openedDropdown = ref<string | null>(null); // hanya 1 dropdown terbuka
const sidebarContentRef = ref<HTMLElement | null>(null);

// computed untuk cek dropdown aktif (klik + URL)
const activeDropdowns = computed(() => {
  const siswaRoutes = ["/siswax", "/siswaxi", "/siswaxii"];
  const absenRoutes = ["/absenx", "/absenxi", "/absenxii"];
  const pelajaranRoutes = ["/pelajaranx", "/pelajaranxi", "/pelajaranxii"];

  const autoOpen: string[] = [];

  if (siswaRoutes.some(route => currentUrl.startsWith(route))) {
    autoOpen.push("Data Siswa");
  }
  if (absenRoutes.some(route => currentUrl.startsWith(route))) {
    autoOpen.push("Data Absensi");
  }

  if (openedDropdown.value) {
    autoOpen.push(openedDropdown.value);
  }

  return Array.from(new Set(autoOpen));
});

function toggleDropdown(title: string) {
  if (openedDropdown.value === title) {
    openedDropdown.value = null; // tutup kalau diklik lagi
  } else {
    openedDropdown.value = title; // hanya buka 1
  }
}

// scroll state
function saveScrollPosition() {
  if (sidebarContentRef.value) {
    const scrollTop = sidebarContentRef.value.scrollTop;
    sessionStorage.setItem("sidebar-scroll-position", scrollTop.toString());
  }
}

function restoreScrollPosition() {
  const savedPosition = sessionStorage.getItem("sidebar-scroll-position");
  if (savedPosition && sidebarContentRef.value) {
    sidebarContentRef.value.scrollTop = parseInt(savedPosition, 10);
  }
}

function navigateWithScrollPreservation(href: string) {
  saveScrollPosition();
  router.visit(href, {
    preserveScroll: true,
    preserveState: true,
  });
}

function handleScroll() {
  saveScrollPosition();
}

watch(
  () => page.url,
  () => {
    setTimeout(() => {
      restoreScrollPosition();
    }, 50);
  }
);

onMounted(() => {
  sidebarContentRef.value = document.getElementById(
    "sidebar-content"
  ) as HTMLElement;

  if (sidebarContentRef.value) {
    sidebarContentRef.value.addEventListener("scroll", handleScroll);

    setTimeout(() => {
      restoreScrollPosition();
    }, 100);
  }

  window.addEventListener("beforeunload", saveScrollPosition);
});

onUnmounted(() => {
  if (sidebarContentRef.value) {
    sidebarContentRef.value.removeEventListener("scroll", handleScroll);
  }
  window.removeEventListener("beforeunload", saveScrollPosition);
});

const mainNavItems = [
  { title: "Dashboard", href: "/dashboard", icon: LayoutGrid },
  { title: "Manage User", href: "/user", icon: Users },
  { title: "Data Guru", href: "/guru", icon: Users },
  { title: "Data Jadwal", href: "/jadwal", icon: Users },
  { title: "Data Mapel", href: "/mapel", icon: BookOpen },
  { title: "Data Kelas", href: "/kelas", icon: School },
  { title: "Data Jurusan", href: "/jurusan", icon: Group },
  {
    title: "Data Siswa",
    icon: GraduationCap,
    children: [
      { title: "Kelas X", href: "/siswax" },
      { title: "Kelas XI", href: "/siswaxi" },
      { title: "Kelas XII", href: "/siswaxii" },
    ],
  },
  {
    title: "Absen Sekolah",
    icon: GraduationCap,
    children: [
      { title: "Kelas X", href: "/absenx" },
      { title: "Kelas XI", href: "/absenxi" },
      { title: "Kelas XII", href: "/absenxii" },
    ],
  },
  {
    title: "Absen Pelajaran",
    icon: GraduationCap,
    children: [
      { title: "Kelas X", href: "/pelajaranx" },
      { title: "Kelas XI", href: "/pelajaranxi" },
      { title: "Kelas XII", href: "/pelajaranxii" },
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
            <button
              @click="navigateWithScrollPreservation('/dashboard')"
              class="flex items-center w-full text-left"
            >
              <AppLogo class="w-9 h-9" />
              <span class="ml-2">My Dashboard</span>
            </button>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarHeader>

    <!-- Menu Utama -->
    <SidebarContent
      class="px-2 py-4 overflow-y-auto custom-scroll"
      id="sidebar-content"
    >
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
              <span>{{ activeDropdowns.includes(item.title) ? "▾" : "▸" }}</span>
            </button>
            <div
              v-if="activeDropdowns.includes(item.title)"
              class="ml-6 pl-2 border-l border-gray-700 space-y-1"
            >
              <button
                v-for="child in item.children"
                :key="child.title"
                @click="navigateWithScrollPreservation(child.href)"
                class="block w-full text-left px-4 py-2 rounded-md text-sm transition text-gray-400 hover:bg-white hover:text-black"
                :class="{
                  'bg-white text-black font-semibold':
                    currentUrl === child.href ||
                    currentUrl.startsWith(child.href + '/'),
                }"
              >
                {{ child.title }}
              </button>
            </div>
          </div>

          <!-- Jika tidak ada children -->
          <button
            v-else
            @click="navigateWithScrollPreservation(item.href)"
            class="w-full flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition text-gray-300 hover:bg-white hover:text-black"
            :class="{
              'bg-white text-black font-semibold':
                currentUrl === item.href ||
                currentUrl.startsWith(item.href + '/'),
            }"
          >
            <component :is="item.icon" class="w-5 h-5" />
            <span>{{ item.title }}</span>
          </button>
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
