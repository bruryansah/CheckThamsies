<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
  absensiSekolah: { type: Array, default: () => [] },
  showAbsensiModal: { type: Boolean, default: false },
})

// State filter & pencarian
const bulan = ref(new Date().toISOString().slice(0, 7)) // format YYYY-MM
const tahun = ref(String(new Date().getFullYear()))
const query = ref('')

// Sumber tahun unik dari data
const tahunOptions = computed(() => {
  const set = new Set()
  props.absensiSekolah.forEach((i) => {
    const y = String((i.tanggal || '').slice(0, 4))
    if (y) set.add(y)
  })
  const now = String(new Date().getFullYear())
  set.add(now)
  return Array.from(set).sort()
})

// Helper format
const fmtDate = (d) => {
  if (!d) return '-'
  try {
    const dt = new Date(d)
    if (!Number.isNaN(dt.getTime())) {
      return dt.toLocaleDateString('id-ID', { weekday: 'short', day: '2-digit', month: 'short', year: 'numeric' })
    }
  } catch {}
  // fallback jika tanggal sudah string Y-m-d
  return d
}
const fmtTime = (t) => (t ? t.toString().slice(0, 5) : '-')

// Badge status
const statusClass = (s) => {
  const st = (s || '').toLowerCase()
  if (st === 'hadir') return 'bg-green-100 text-green-700 border border-green-200'
  if (st === 'terlambat') return 'bg-orange-100 text-orange-700 border border-orange-200'
  if (st === 'izin') return 'bg-blue-100 text-blue-700 border border-blue-200'
  if (st === 'sakit') return 'bg-purple-100 text-purple-700 border border-purple-200'
  if (st === 'alfa') return 'bg-red-100 text-red-700 border border-red-200'
  if (st === 'pulang cepat') return 'bg-yellow-100 text-yellow-700 border border-yellow-200'
  return 'bg-gray-100 text-gray-700 border border-gray-200'
}

// Filter hasil
const filtered = computed(() => {
  const q = query.value.trim().toLowerCase()
  const bln = bulan.value // YYYY-MM
  const th = tahun.value // YYYY
  return (props.absensiSekolah || []).filter((i) => {
    const tgl = (i.tanggal || '')
    const inYear = th ? tgl.startsWith(th) : true
    const inMonth = bln ? tgl.startsWith(bln) : true // tanggal format Y-m-d cocok dengan startsWith(Y-m)
    const matchesQuery = q
      ? [i.status, i.keterangan].some((v) => (v || '').toString().toLowerCase().includes(q))
      : true
    return inYear && inMonth && matchesQuery
  })
})

const totalHadir = computed(() => filtered.value.filter((i) => {
  const s = (i.status || '').toLowerCase()
  return s === 'hadir' || s === 'terlambat' || s === 'pulang cepat'
}).length)
const totalIzin = computed(() => filtered.value.filter((i) => (i.status || '').toLowerCase() === 'izin').length)
const totalSakit = computed(() => filtered.value.filter((i) => (i.status || '').toLowerCase() === 'sakit').length)
const totalAlfa = computed(() => filtered.value.filter((i) => (i.status || '').toLowerCase() === 'alfa').length)
</script>

<template>
  <transition name="fade">
    <div v-if="showAbsensiModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
      <div class="w-full max-w-6xl rounded-2xl bg-white text-gray-900 shadow-2xl p-6 relative overflow-hidden border border-gray-200">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-xl font-semibold">Data Absensi Sekolah</h2>
          <button @click="$emit('close')" class="text-gray-400 hover:text-gray-700 transition text-lg font-bold">✕</button>
        </div>

        <!-- Ringkasan -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
          <div class="p-3 rounded-xl bg-green-50 text-center">
            <p class="text-2xl font-bold text-green-600">{{ totalHadir }}</p>
            <p class="text-xs text-gray-600">Hadir</p>
          </div>
          <div class="p-3 rounded-xl bg-blue-50 text-center">
            <p class="text-2xl font-bold text-blue-600">{{ totalIzin }}</p>
            <p class="text-xs text-gray-600">Izin</p>
          </div>
          <div class="p-3 rounded-xl bg-purple-50 text-center">
            <p class="text-2xl font-bold text-purple-600">{{ totalSakit }}</p>
            <p class="text-xs text-gray-600">Sakit</p>
          </div>
          <div class="p-3 rounded-xl bg-red-50 text-center">
            <p class="text-2xl font-bold text-red-600">{{ totalAlfa }}</p>
            <p class="text-xs text-gray-600">Alfa</p>
          </div>
        </div>

        <!-- Filter Bar -->
        <div class="flex flex-col sm:flex-row gap-3 mb-5">
          <input v-model="query" type="text" placeholder="Cari status/keterangan..." class="flex-1 rounded-lg border border-gray-300 bg-white text-sm p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
          <input v-model="bulan" type="month" class="rounded-lg border border-gray-300 bg-white text-sm p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
          <select v-model="tahun" class="rounded-lg border border-gray-300 bg-white text-sm p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option v-for="y in tahunOptions" :key="y" :value="y">{{ y }}</option>
          </select>
        </div>

        <!-- Tabel -->
        <div class="overflow-x-auto rounded-xl border border-gray-200">
          <table class="w-full text-sm text-left border-collapse">
            <thead class="border-b border-gray-200 text-gray-700 bg-gray-50">
              <tr>
                <th class="py-2 px-3">Tanggal</th>
                <th class="py-2 px-3">Jam Masuk</th>
                <th class="py-2 px-3">Jam Pulang</th>
                <th class="py-2 px-3">Status</th>
                <th class="py-2 px-3">Keterangan</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="filtered.length === 0">
                <td colspan="5" class="px-4 py-6 text-center text-gray-500">Tidak ada data untuk filter saat ini.</td>
              </tr>
              <tr v-for="(item, index) in filtered" :key="index" class="odd:bg-white even:bg-gray-50 border-b">
                <td class="px-4 py-2 whitespace-nowrap">{{ fmtDate(item.tanggal) }}</td>
                <td class="px-4 py-2 whitespace-nowrap font-medium">{{ fmtTime(item.jam_masuk) }}</td>
                <td class="px-4 py-2 whitespace-nowrap">{{ fmtTime(item.jam_keluar) }}</td>
                <td class="px-4 py-2">
                  <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold" :class="statusClass(item.status)">{{ (item.status || '-').toString().toUpperCase() }}</span>
                </td>
                <td class="px-4 py-2">{{ item.keterangan || '-' }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Footer -->
        <div class="flex justify-end mt-4">
          <button @click="$emit('close')" class="rounded-xl bg-gray-600 text-white text-sm px-4 py-2 hover:bg-gray-700">Tutup</button>
        </div>
      </div>
    </div>
  </transition>
</template>
