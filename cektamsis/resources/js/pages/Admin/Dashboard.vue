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
import { Line } from 'vue-chartjs'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  LineElement,
  LinearScale,
  PointElement,
  CategoryScale
} from 'chart.js'

// Register ChartJS components
ChartJS.register(
  Title,
  Tooltip,
  Legend,
  LineElement,
  LinearScale,
  PointElement,
  CategoryScale
)

interface KelasDistribusi {
  nama_kelas: string;
  hadir: number;
  izin: number;
  sakit: number;
  alfa: number;
  total: number;
}

interface RekapHarian {
  tanggal: string;
  hadir: number;
  izin: number;
  sakit: number;
  alfa: number;
}

interface rekap {
  rekapHarian: Props[];
  auth: {
    user: UserType;
  };
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
    rekapHarian: RekapHarian[];
  auth: {
    user: UserType;
  };
}

const props = defineProps<Props>();
const chartData = computed(() => {
  const labels = (props.rekapHarian ?? []).map(r => {
    const date = new Date(r.tanggal)
    return date.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit' })
  })

  return {
    labels,
    datasets: [
      {
        label: 'hadir',
        data: props.rekapHarian.map(r => r.hadir),
        borderColor: '#22c55e',
        backgroundColor: 'rgba(34,197,94,0.2)',
        tension: 0.3,
        fill: false,
        pointBackgroundColor: '#22c55e',
        pointRadius: 4,
      },
      {
        label: 'izin',
        data: props.rekapHarian.map(r => r.izin),
        borderColor: '#3b82f6',
        backgroundColor: 'rgba(59,130,246,0.2)',
        tension: 0.3,
        fill: false,
        pointBackgroundColor: '#3b82f6',
        pointRadius: 4,
      },
      {
        label: 'sakit',
        data: props.rekapHarian.map(r => r.sakit),
        borderColor: '#eab308',
        backgroundColor: 'rgba(234,179,8,0.2)',
        tension: 0.3,
        fill: false,
        pointBackgroundColor: '#eab308',
        pointRadius: 4,
      },
      {
        label: 'alfa',
        data: props.rekapHarian.map(r => r.alfa),
        borderColor: '#ef4444',
        backgroundColor: 'rgba(239,68,68,0.2)',
        tension: 0.3,
        fill: false,
        pointBackgroundColor: '#ef4444',
        pointRadius: 4,
      }
    ]
  }
})


// Options chart
const chartOptions = {
  responsive: true,
  plugins: {
    legend: {
      labels: {
        color: '#fff', // biar kelihatan di dark mode
      },
    },
  },
  scales: {
    x: {
      ticks: {
        color: '#fff',
      },
      grid: {
        color: 'rgba(255,255,255,0.1)',
      },
    },
    y: {
      ticks: {
        stepSize: 1,       // ✅ hanya bilangan bulat
        color: '#fff',
      },
      grid: {
        color: 'rgba(255,255,255,0.1)',
      },
      beginAtZero: true,
    },
  },
}


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


function getPercentage(hadir:number, total:number) {
  if (!total || total === 0) return 0; // hindari pembagian nol
  return (hadir / total) * 100;
}
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
        <div class="lg:col-span-2 rounded-2xl border border-zinc-800 bg-zinc-900/60 p-6 shadow-lg">
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <div class="p-2 bg-blue-500/20 rounded-lg">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16h16M4 12h16" />
          </svg>
        </div>
        <h3 class="text-lg font-semibold text-white">Monitor Kehadiran Real-time</h3>
      </div>
    </div>

    <!-- Chart Container -->
    <div class="h-80">
      <Line :data="chartData" :options="chartOptions" />
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
  <button onclick="window.location.href='/user/tambah'"
          class="flex justify-between items-center w-full py-3 border-b border-zinc-800 text-zinc-300 hover:text-blue-300 transition">
    <span>Tambah User Baru</span>
    <svg class="h-4 w-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
    </svg>
  </button>

  <button onclick="window.location.href='/guru/tambah'"
          class="flex justify-between items-center w-full py-3 border-b border-zinc-800 text-zinc-300 hover:text-blue-300 transition">
    <span>Tambah Guru</span>
    <svg class="h-4 w-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
    </svg>
  </button>

  <button onclick="window.location.href='/siswa/tambah'"
          class="flex justify-between items-center w-full py-3 border-b border-zinc-800 text-zinc-300 hover:text-blue-300 transition">
    <span>Tambah Siswa</span>
    <svg class="h-4 w-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
    </svg>
  </button>
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
