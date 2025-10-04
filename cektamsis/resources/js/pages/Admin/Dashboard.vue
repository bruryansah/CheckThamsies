<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Users, User, GraduationCap, Shield, Settings, PieChart } from 'lucide-vue-next';
import { ref, computed, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Line } from 'vue-chartjs';
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  LineElement,
  LinearScale,
  PointElement,
  CategoryScale
} from 'chart.js';

// ✅ Daftarkan komponen ChartJS
ChartJS.register(
  Title,
  Tooltip,
  Legend,
  LineElement,
  LinearScale,
  PointElement,
  CategoryScale
);

interface KelasDistribusi {
  nama_kelas: string;
  hadir: number;
  terlambat: number;
  izin: number;
  sakit: number;
  alfa: number;
  total: number;
}

interface RekapHarian {
  tanggal: string;
  hadir: number;
  terlambat: number;
  izin: number;
  sakit: number;
  alfa: number;
}

interface Props {
  totalUsers: number;
  totalSiswa: number;
  totalGuru: number;
  totalAdmin: number;
  totalabsen: number;
  totalterlambat: number;
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
    user: any;
  };
}

// ✅ Props dari Laravel
const props = defineProps<Props>();


const currentYear = new Date().getFullYear();
const selectedYear = ref<number>(currentYear);
const selectedMonth = ref<number>(new Date().getMonth() + 1);

const months = [
  "Januari","Februari","Maret","April","Mei","Juni",
  "Juli","Agustus","September","Oktober","November","Desember"
];

const years = Array.from({ length: 5 }, (_, i) => currentYear - i); 
const filteredRekap = computed(() => {
  return (props.rekapHarian ?? []).filter(r => {
    const date = new Date(r.tanggal);
    return (
      date.getFullYear() === selectedYear.value &&
      (date.getMonth() + 1) === selectedMonth.value
    );
  });
});

const chartData = computed(() => {
  const labels = filteredRekap.value.map(r => {
    const date = new Date(r.tanggal);
    return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
  });

  return {
    labels,
    datasets: [
      {
        label: 'Hadir',
        data: filteredRekap.value.map(r => r.hadir),
        borderColor: '#22c55e',
        backgroundColor: 'rgba(34,197,94,0.2)',
        tension: 0.3,
        fill: false,
        pointBackgroundColor: '#22c55e',
        pointRadius: 4,
      },
      {
        label: 'Terlambat',
        data: filteredRekap.value.map(r => r.terlambat),
        borderColor: '#f97316',
        backgroundColor: 'rgba(249,115,22,0.2)',
        tension: 0.3,
        fill: false,
        pointBackgroundColor: '#f97316',
        pointRadius: 4,
      },
      {
        label: 'Izin',
        data: filteredRekap.value.map(r => r.izin),
        borderColor: '#3b82f6',
        backgroundColor: 'rgba(59,130,246,0.2)',
        tension: 0.3,
        fill: false,
        pointBackgroundColor: '#3b82f6',
        pointRadius: 4,
      },
      {
        label: 'Sakit',
        data: filteredRekap.value.map(r => r.sakit),
        borderColor: '#eab308',
        backgroundColor: 'rgba(234,179,8,0.2)',
        tension: 0.3,
        fill: false,
        pointBackgroundColor: '#eab308',
        pointRadius: 4,
      },
      {
        label: 'Alfa',
        data: filteredRekap.value.map(r => r.alfa),
        borderColor: '#ef4444',
        backgroundColor: 'rgba(239,68,68,0.2)',
        tension: 0.3,
        fill: false,
        pointBackgroundColor: '#ef4444',
        pointRadius: 4,
      }
    ]
  };
});

// ----------------------
// 🔹 Opsi Chart
// ----------------------
const chartOptions = {
  responsive: true,
  plugins: {
    legend: {
      labels: { color: '#fff' },
    },
  },
  scales: {
    x: {
      ticks: { color: '#fff' },
      grid: { color: 'rgba(255,255,255,0.1)' },
    },
    y: {
      ticks: { stepSize: 1, color: '#fff' },
      grid: { color: 'rgba(255,255,255,0.1)' },
      beginAtZero: true,
    },
  },
};

// ----------------------
// 🔹 Breadcrumbs & Stats
// ----------------------
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
];

const stats = computed(() => ({
  totalUsers: props.totalUsers || 0,
  totalSiswa: props.totalSiswa || 0,
  totalGuru: props.totalGuru || 0,
  totalAdmin: props.totalAdmin || 0,
  totalabsen: props.totalabsen || 0,
  totalterlambat: props.totalterlambat || 0,
  totalizin: props.totalizin || 0,
  totalsakit: props.totalsakit || 0,
  totalalfa: props.totalalfa || 0,
  absen: props.absen || 0,
  warning: props.warning || 0,
  telat: props.telat || 0,
  alfa: props.alfa || 0,
}));

// ----------------------
// 🔹 Data Distribusi
// ----------------------
const kelasData = computed(() => props.distribusi || []);

function getPercentage(hadir:number, total:number) {
  if (!total || total === 0) return 0;
  return Math.round((hadir / total) * 100);
}
const getStatusColor = (p: number) => p >= 90 ? 'text-green-400' : p >= 80 ? 'text-yellow-400' : p >= 70 ? 'text-orange-400' : 'text-red-400';
const getProgressColor = (p: number) => p >= 90 ? 'bg-green-500' : p >= 80 ? 'bg-yellow-500' : p >= 70 ? 'bg-orange-500' : 'bg-red-500';
</script>

<style>
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
        <div class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-5 shadow-lg">
          <div class="flex items-center justify-between">
            <h3 class="text-sm text-zinc-400">Total Users</h3>
            <Users class="h-5 w-5 text-indigo-400" />
          </div>
          <p class="mt-4 text-3xl font-bold text-white">{{ stats.totalUsers }}</p>
        </div>

        <div class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-5 shadow-lg">
          <div class="flex items-center justify-between">
            <h3 class="text-sm text-zinc-400">Total Siswa</h3>
            <GraduationCap class="h-5 w-5 text-green-400" />
          </div>
          <p class="mt-4 text-3xl font-bold text-white">{{ stats.totalSiswa }}</p>
        </div>

        <div class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-5 shadow-lg">
          <div class="flex items-center justify-between">
            <h3 class="text-sm text-zinc-400">Total Guru</h3>
            <User class="h-5 w-5 text-purple-400" />
          </div>
          <p class="mt-4 text-3xl font-bold text-white">{{ stats.totalGuru }}</p>
        </div>

        <div class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-5 shadow-lg">
          <div class="flex items-center justify-between">
            <h3 class="text-sm text-zinc-400">Total Admin</h3>
            <Shield class="h-5 w-5 text-amber-400" />
          </div>
          <p class="mt-4 text-3xl font-bold text-white">{{ stats.totalAdmin }}</p>
        </div>
      </div>

      <!-- Monitor Kehadiran -->
      <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 rounded-2xl border border-zinc-800 bg-zinc-900/60 p-6 shadow-lg">
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-blue-500/20 rounded-lg">
                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16h16M4 12h16" />
                </svg>
              </div>
              <h3 class="text-lg font-semibold text-white">Monitor Kehadiran Real-time</h3>
            </div>

            <!-- Filter -->
            <div class="flex items-center gap-3">
              <select v-model="selectedYear" class="bg-zinc-800 text-white text-sm rounded-lg px-3 py-1.5 border border-zinc-700">
                <option disabled value="">Pilih Tahun</option>
                <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
              </select>
              <select v-model="selectedMonth" class="bg-zinc-800 text-white text-sm rounded-lg px-3 py-1.5 border border-zinc-700">
                <option disabled value="">Pilih Bulan</option>
                <option v-for="(month, index) in months" :key="index" :value="index+1">{{ month }}</option>
              </select>
            </div>
          </div>

          <div class="h-80">
            <Line :data="chartData" :options="chartOptions" />
          </div>
        </div>

        <!-- Aksi Cepat -->
        <div class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-6 shadow-lg">
          <div class="flex items-center gap-3 mb-6">
            <div class="p-2 bg-purple-500/20 rounded-lg">
              <Settings class="h-5 w-5 text-purple-400" />
            </div>
            <h3 class="text-lg font-semibold text-white">Aksi Cepat</h3>
          </div>

          <div class="space-y-4">
            <button onclick="window.location.href='/user/tambah'" class="flex justify-between items-center w-full py-3 border-b border-zinc-800 text-zinc-300 hover:text-blue-300">
              <span>Tambah User Baru</span>
              <svg class="h-4 w-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
            </button>

            <button onclick="window.location.href='/guru/tambah'" class="flex justify-between items-center w-full py-3 border-b border-zinc-800 text-zinc-300 hover:text-blue-300">
              <span>Tambah Guru</span>
              <svg class="h-4 w-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
            </button>

            <button onclick="window.location.href='/siswa/tambah'" class="flex justify-between items-center w-full py-3 border-b border-zinc-800 text-zinc-300 hover:text-blue-300">
              <span>Tambah Siswa</span>
              <svg class="h-4 w-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Distribusi per Kelas -->
      <div class="rounded-2xl border border-zinc-800 bg-zinc-900/60 p-6 shadow-lg">
        <div class="flex items-center gap-3 mb-6">
          <div class="p-2 bg-green-500/20 rounded-lg">
            <PieChart class="h-5 w-5 text-green-400" />
          </div>
          <h3 class="text-lg font-semibold text-white">Distribusi Kehadiran per Kelas</h3>
        </div>

        <div class="space-y-4 max-h-80 overflow-y-auto custom-scrollbar">
          <div v-for="kelas in kelasData" :key="kelas.nama_kelas" class="p-4 bg-zinc-800/40 rounded-lg">
            <div class="flex items-center justify-between mb-3">
              <h4 class="text-sm font-medium text-white">{{ kelas.nama_kelas }}</h4>
              <span :class="getStatusColor(getPercentage(kelas.hadir, kelas.total))" class="text-sm font-semibold">
                {{ getPercentage(kelas.hadir, kelas.total) }}%
              </span>
            </div>

            <div class="w-full bg-zinc-700/50 rounded-full h-2 mb-3">
              <div :class="getProgressColor(getPercentage(kelas.hadir, kelas.total))"
                   class="h-2 rounded-full"
                   :style="{ width: getPercentage(kelas.hadir, kelas.total) + '%' }"></div>
            </div>

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
          <div class="grid grid-cols-3 gap-5 text-sm">
            <div class="text-center p-3 bg-zinc-800/40 rounded-lg">
              <div class="text-zinc-400">Total Alfa</div>
              <div class="text-xl font-bold text-red-400 mt-1">
                {{ stats.totalalfa }}
              </div>
            </div>
            <div class="text-center p-3 bg-zinc-800/40 rounded-lg">
              <div class="text-zinc-400">Total Izin</div>
              <div class="text-xl font-bold text-blue-400 mt-1">
                {{ stats.totalizin }}
              </div>
            </div>
            <div class="text-center p-3 bg-zinc-800/40 rounded-lg">
              <div class="text-zinc-400">Total Sakit</div>
              <div class="text-xl font-bold text-yellow-400 mt-1">
                {{ stats.totalsakit }}
              </div>
            </div>
          </div>
          <br>
           <div class="grid grid-cols-2 gap-5 text-sm">
            <div class="text-center p-3 bg-zinc-800/40 rounded-lg">
              <div class="text-zinc-400">Total Kehadiran</div>
              <div class="text-xl font-bold text-green-400 mt-1">
                {{ stats.totalabsen }}
              </div>
            </div>
            <div class="text-center p-3 bg-zinc-800/40 rounded-lg">
              <div class="text-zinc-400">Total Siswa</div>
              <div class="text-xl font-bold text-white-400 mt-1">
                {{ stats.totalSiswa }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
