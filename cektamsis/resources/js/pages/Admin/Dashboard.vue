<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Users, User, GraduationCap, Shield, RefreshCw, AlertTriangle, PieChart, Settings, Database } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3'

// 🔹 Import komponen dropdown user
import UserInfo from '@/components/UserInfo.vue';
import { DropdownMenu, DropdownMenuContent, DropdownMenuGroup, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { LogOut, Settings as SettingsIcon } from 'lucide-vue-next';
import { Link, router } from '@inertiajs/vue3';
import type { User as UserType } from '@/types';

interface KelasDistribusi {
  nama_kelas: string;
  hadir: number;
  izin: number;
  sakit: number;
  alfa: number;
  total: number;
}

// Props dari Laravel
interface Props {
  totalUsers: number;
  totalSiswa: number;
  totalGuru: number;
  totalAdmin: number;
  totalabsen: number;
  totalizin: number;
  totalsakit: number;
  totalalfa: number;
  absen: number;
  warning: number;
  telat: number;
  alfa: number;
   distribusi: KelasDistribusi[];
  auth: {
    user: UserType;
  };
}

const props = defineProps<Props>();

// Logout handler
const handleLogout = () => {
  router.flushAll();
};

const user = usePage().props.auth.user

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
]

// Stats
const stats = computed(() => ({
  totalUsers: props.totalUsers || 0,
  totalSiswa: props.totalSiswa || 0,
  totalGuru: props.totalGuru || 0,
  totalAdmin: props.totalAdmin || 0,
  totalabsen: props.totalabsen || 0,
  totalizin: props.totalizin || 0,
  totalsakit: props.totalsakit || 0,
  totalalfa: props.totalalfa || 0,
  absen: props.absen || 0,
  warning: props.warning || 0,
  telat: props.telat || 0,
  alfa: props.alfa || 0,

}));

// Ambil langsung dari props
const kelasData = computed(() => props.distribusi || []);


const getPercentage = (hadir: number, total: number) => Math.round((hadir / total) * 100);
const getStatusColor = (p: number) => p >= 90 ? 'text-green-400' : p >= 80 ? 'text-yellow-400' : p >= 70 ? 'text-orange-400' : 'text-red-400';
const getProgressColor = (p: number) => p >= 90 ? 'bg-green-500' : p >= 80 ? 'bg-yellow-500' : p >= 70 ? 'bg-orange-500' : 'bg-red-500';
</script>

<style>
/* Webkit browsers (Chrome, Safari, Edge) */
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: #27272a;
  border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #52525b;
  border-radius: 10px;
  transition: background-color 0.2s ease;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #71717a;
}
</style>

<template>
  <Head title="Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">

    <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
      <!-- Statistik Cards -->
      <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
        <!-- Total Users -->
        <div class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-5 shadow-lg hover:shadow-xl transition">
          <div class="flex items-center justify-between">
            <h3 class="text-sm font-medium text-zinc-400">Total Users</h3>
            <Users class="h-5 w-5 text-indigo-400" />
          </div>
          <p class="mt-4 text-3xl font-bold text-white">{{ stats.totalUsers }}</p>
        </div>

        <!-- Total Siswa -->
        <div class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-5 shadow-lg hover:shadow-xl transition">
          <div class="flex items-center justify-between">
            <h3 class="text-sm font-medium text-zinc-400">Total Siswa</h3>
            <GraduationCap class="h-5 w-5 text-green-400" />
          </div>
          <p class="mt-4 text-3xl font-bold text-white">{{ stats.totalSiswa }}</p>
        </div>

        <!-- Total Guru -->
        <div class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-5 shadow-lg hover:shadow-xl transition">
          <div class="flex items-center justify-between">
            <h3 class="text-sm font-medium text-zinc-400">Total Guru</h3>
            <User class="h-5 w-5 text-purple-400" />
          </div>
          <p class="mt-4 text-3xl font-bold text-white">{{ stats.totalGuru }}</p>
        </div>

        <!-- Total Admin -->
        <div class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-5 shadow-lg hover:shadow-xl transition">
          <div class="flex items-center justify-between">
            <h3 class="text-sm font-medium text-zinc-400">Total Admin</h3>
            <Shield class="h-5 w-5 text-amber-400" />
          </div>
          <p class="mt-4 text-3xl font-bold text-white">{{ stats.totalAdmin }}</p>
        </div>
      </div>

      <!-- Monitor & Alerts Section -->
      <div class="grid gap-6 lg:grid-cols-3">
        <!-- Monitor Kehadiran Real-time Container -->
        <div class="lg:col-span-2 rounded-2xl border border-zinc-800 bg-zinc-900/60 p-6 shadow-lg">
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-blue-500/20 rounded-lg">
                <RefreshCw class="h-5 w-5 text-blue-400" />
              </div>
              <h3 class="text-lg font-semibold text-white">Monitor Kehadiran Real-time</h3>
            </div>
            <div class="flex items-center gap-2">
              <RefreshCw class="h-4 w-4 text-blue-400" />
              <select class="bg-zinc-800 text-white text-sm rounded-lg px-3 py-1 border border-zinc-700">
                <option>Hari Ini</option>
                <option>Kemarin</option>
                <option>Minggu Ini</option>
              </select>
            </div>
          </div>

          <!-- Grid 2 Cards dalam satu container -->
          <div class="grid md:grid-cols-2 gap-6">
            <!-- Card Absen Kehadiran -->
            <div class="rounded-xl border border-zinc-700/50 bg-zinc-800/40 p-5">
              <h4 class="text-sm font-medium text-zinc-300 mb-4">Absen Masuk Hari Ini</h4>
              <div class="space-y-3">
                <div class="flex justify-between items-center py-2 px-3 bg-zinc-700/30 rounded-lg">
                  <span class="text-zinc-300">07:00 - 07:30</span>
                  <span class="text-green-400 font-semibold">{{ stats.absen }}</span>
                </div>
                <div class="flex justify-between items-center py-2 px-3 bg-zinc-700/30 rounded-lg">
                  <span class="text-zinc-300">07:30 - 08:00</span>
                  <span class="text-yellow-400 font-semibold">{{ stats.warning }}</span>
                </div>
                <div class="flex justify-between items-center py-2 px-3 bg-zinc-700/30 rounded-lg">
                  <span class="text-zinc-300">Terlambat</span>
                  <span class="text-red-400 font-semibold">{{ stats.telat }}</span>
                </div>
                <div class="flex justify-between items-center py-2 px-3 bg-zinc-700/30 rounded-lg">
                  <span class="text-zinc-300">Alfa</span>
                  <span class="text-red-400 font-semibold">{{ stats.alfa }}</span>
                </div>
              </div>
            </div>

            <!-- Card Status Kehadiran -->
            <div class="rounded-xl border border-zinc-700/50 bg-zinc-800/40 p-5">
              <h4 class="text-sm font-medium text-zinc-300 mb-4">Status Kehadiran</h4>
              <div class="space-y-3">
                <div class="flex justify-between items-center py-2 px-3 bg-zinc-700/30 rounded-lg">
                  <div class="flex items-center gap-3">
                    <div class="w-4 h-4 rounded-full bg-green-500"></div>
                    <span class="text-zinc-300">Hadir</span>
                  </div>
                  <span class="text-white font-semibold">{{ stats.totalabsen }}</span>
                </div>
                <div class="flex justify-between items-center py-2 px-3 bg-zinc-700/30 rounded-lg">
                  <div class="flex items-center gap-3">
                    <div class="w-4 h-4 rounded-full bg-blue-500"></div>
                    <span class="text-zinc-300">Izin</span>
                  </div>
                  <span class="text-white font-semibold">{{ stats.totalizin }}</span>
                </div>
                <div class="flex justify-between items-center py-2 px-3 bg-zinc-700/30 rounded-lg">
                  <div class="flex items-center gap-3">
                    <div class="w-4 h-4 rounded-full bg-yellow-500"></div>
                    <span class="text-zinc-300">Sakit</span>
                  </div>
                  <span class="text-white font-semibold">{{ stats.totalsakit }}</span>
                </div>
                <div class="flex justify-between items-center py-2 px-3 bg-zinc-700/30 rounded-lg">
                  <div class="flex items-center gap-3">
                    <div class="w-4 h-4 rounded-full bg-red-500"></div>
                    <span class="text-zinc-300">Alpha</span>
                  </div>
                  <span class="text-white font-semibold">{{ stats.totalalfa }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>


  <!-- Aksi Cepat (dipindahkan dari bawah) -->
  <div class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-6 shadow-lg">
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <div class="p-2 bg-purple-500/20 rounded-lg">
          <Settings class="h-5 w-5 text-purple-400" />
        </div>
        <h3 class="text-lg font-semibold text-white">Aksi Cepat</h3>
      </div>
    </div>

    <!-- Quick actions -->
    <div class="space-y-4">
      <div class="flex justify-between items-center py-3 border-b border-zinc-800">
        <span class="text-zinc-300">Tambah User Baru</span>
        <button href="Admin/tambahuser" class="text-blue-400 hover:text-blue-300 transition">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
        </button>
      </div>
      <div class="flex justify-between items-center py-3 border-b border-zinc-800">
        <span class="text-zinc-300">Kelola Role & Permission</span>
        <button class="text-blue-400 hover:text-blue-300 transition">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 5l7 7-7 7"></path>
          </svg>
        </button>
      </div>
      <div class="flex justify-between items-center py-3 border-b border-zinc-800">
        <span class="text-zinc-300">Import Data Siswa</span>
        <button class="text-blue-400 hover:text-blue-300 transition">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
          </svg>
        </button>
      </div>
      <div class="flex justify-between items-center py-3">
        <span class="text-zinc-300">Backup & Restore</span>
        <button class="text-blue-400 hover:text-blue-300 transition">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
          </svg>
        </button>
      </div>
    </div>
  </div>
</div>
    </div>

<!-- Bottom Section -->
<div class="grid gap-6">
  <!-- Distribusi Kehadiran per Kelas (full width) -->
  <div class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-6 shadow-lg">
    <div class="flex items-center gap-3 mb-6">
      <div class="p-2 bg-green-500/20 rounded-lg">
        <PieChart class="h-5 w-5 text-green-400" />
      </div>
      <h3 class="text-lg font-semibold text-white">Distribusi Kehadiran per Kelas</h3>
    </div>

    <!-- Data Kehadiran per Kelas -->
          <div class="space-y-4 max-h-80 overflow-y-auto" style="scrollbar-width: thin; scrollbar-color: #52525b #27272a;">
            <div v-for="kelas in kelasData" :key="kelas.nama_kelas"
                 class="p-4 bg-zinc-800/40 rounded-lg hover:bg-zinc-800/60 transition-all duration-200">
              <div class="flex items-center justify-between mb-3">
                <h4 class="text-sm font-medium text-white">{{ kelas.nama_kelas }}</h4>
                <span :class="getStatusColor(getPercentage(kelas.hadir, kelas.total))"
                      class="text-sm font-semibold">
                  {{ getPercentage(kelas.hadir, kelas.total) }}%
                </span>
              </div>

              <!-- Progress Bar -->
              <div class="w-full bg-zinc-700/50 rounded-full h-2 mb-3">
                <div :class="getProgressColor(getPercentage(kelas.hadir, kelas.total))"
                     class="h-2 rounded-full transition-all duration-300"
                     :style="{ width: getPercentage(kelas.hadir, kelas.total) + '%' }">
                </div>
              </div>

              <!-- Detail Status -->
              <div class="grid grid-cols-4 gap-2 text-xs">
                <div class="text-center">
                  <div class="flex items-center justify-center gap-1">
                    <div class="w-2 h-2 rounded-full bg-green-500"></div>
                    <span class="text-zinc-400">Hadir</span>
                  </div>
                  <div class="text-white font-medium mt-1">{{ kelas.hadir }}</div>
                </div>
                <div class="text-center">
                  <div class="flex items-center justify-center gap-1">
                    <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                    <span class="text-zinc-400">Izin</span>
                  </div>
                  <div class="text-white font-medium mt-1">{{ kelas.izin }}</div>
                </div>
                <div class="text-center">
                  <div class="flex items-center justify-center gap-1">
                    <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                    <span class="text-zinc-400">Sakit</span>
                  </div>
                  <div class="text-white font-medium mt-1">{{ kelas.sakit }}</div>
                </div>
                <div class="text-center">
                  <div class="flex items-center justify-center gap-1">
                    <div class="w-2 h-2 rounded-full bg-red-500"></div>
                    <span class="text-zinc-400">Alpha</span>
                  </div>
                  <div class="text-white font-medium mt-1">{{ kelas.alfa }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Summary -->
          <div class="mt-6 pt-4 border-t border-zinc-700">
            <div class="grid grid-cols-2 gap-4 text-sm">
              <div class="text-center p-3 bg-zinc-800/40 rounded-lg">
                  <div class="text-zinc-400">Rata-rata Kehadiran</div>
                  <div class="text-xl font-bold text-green-400 mt-1">
                      {{ Math.round(kelasData.reduce((acc, k) => acc + getPercentage(k.hadir, k.total), 0) / kelasData.length) }}%
                    </div>
                </div>
                <div class="text-center p-3 bg-zinc-800/40 rounded-lg">
                    <div class="text-zinc-400">Total Siswa</div>
                    <div class="text-xl font-bold text-blue-400 mt-1">
                        {{ kelasData.reduce((acc, k) => acc + k.total, 0) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

  </AppLayout>
</template>
