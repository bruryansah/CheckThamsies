<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2 } from 'lucide-vue-next';
import { defineProps, ref, computed } from 'vue'

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Data Jadwal', href: '/jadwal' },
];

interface Jadwal {
  id_jadwal: number
  guru: string
  mapel: string
  kelas: string
  hari: string
  lantai: string
  ruang: string
  jam_mulai: string
  jam_selesai: string
}

const props = defineProps<{
  jadwal: {
    data: Jadwal[]
    links: { url: string | null; label: string; active: boolean }[]
  }
}>()

// 🔍 Search
const searchQuery = ref('')
const filteredJadwal = computed(() => {
  if (!searchQuery.value) return props.jadwal.data
  return props.jadwal.data.filter(j =>
    j.mapel.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    j.guru.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    j.ruang.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    j.lantai.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    j.kelas.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    j.hari.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

// Modal konfirmasi hapus
const showConfirmModal = ref(false)
const selectedJadwal = ref<Jadwal | null>(null)

const confirmDelete = (jadwal: Jadwal) => {
  selectedJadwal.value = jadwal
  showConfirmModal.value = true
}
const cancelDelete = () => {
  showConfirmModal.value = false
  selectedJadwal.value = null
}
const proceedDelete = () => {
  if (selectedJadwal.value) {
    window.location.href = route('jadwal.hapus', selectedJadwal.value.id_jadwal)
  }
}
</script>

<template>
  <Head title="Data Jadwal" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
      <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-lg">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
          <h1 class="text-xl font-semibold text-white">Data Jadwal</h1>
          <div class="flex items-center gap-2">
            <!-- 🔍 Search bar -->
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari jadwal..."
              class="pl-3 pr-3 py-2 w-48 rounded-lg border border-zinc-700 bg-zinc-800 text-sm text-white placeholder-zinc-400 focus:ring focus:ring-green-500 focus:outline-none"
            />
            <TextLink
              :href="route('jadwal.tambah')"
              class="flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition"
            >
              <Plus class="h-4 w-4" />
              Tambah Jadwal
            </TextLink>
          </div>
        </div>

        <!-- Table (Desktop) -->
        <div class="hidden md:block overflow-x-auto">
          <table class="min-w-full divide-y divide-zinc-800">
            <thead class="bg-zinc-800">
              <tr>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Id Jadwal</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Nama Mapel</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Nama Guru</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Nama Kelas</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Hari</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Lantai</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Ruangan</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Jam Mulai</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Jam Selesai</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300 w-32">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800 bg-zinc-900 text-sm text-zinc-200">
              <tr v-for="jadwal in filteredJadwal" :key="jadwal.id_jadwal" class="hover:bg-zinc-800/60 transition">
                <td class="px-6 py-4 text-center">{{ jadwal.id_jadwal }}</td>
                <td class="px-6 py-4 text-center">{{ jadwal.mapel }}</td>
                <td class="px-6 py-4 text-center">{{ jadwal.guru }}</td>
                <td class="px-6 py-4 text-center">{{ jadwal.kelas }}</td>
                <td class="px-6 py-4 text-center">{{ jadwal.hari }}</td>
                <td class="px-6 py-4 text-center">{{ jadwal.lantai }}</td>
                <td class="px-6 py-4 text-center">{{ jadwal.ruang }}</td>
                <td class="px-6 py-4 text-center">{{ jadwal.jam_mulai }}</td>
                <td class="px-6 py-4 text-center">{{ jadwal.jam_selesai }}</td>
                <td class="px-6 py-4 text-center">
                  <div class="flex justify-center gap-2">
                    <TextLink
                      :href="route('jadwal.edit', jadwal.id_jadwal)"
                      class="rounded-lg bg-yellow-500 p-2 text-white hover:bg-yellow-600"
                    >
                      <Pencil class="w-4 h-4" />
                    </TextLink>
                    <button
                      @click="confirmDelete(jadwal)"
                      class="rounded-lg bg-red-600 p-2 text-white hover:bg-red-700 transition"
                    >
                      <Trash2 class="w-4 h-4" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center mt-4 gap-2">
          <button
            v-for="link in props.jadwal.links"
            :key="link.label"
            v-html="link.label"
            :disabled="!link.url"
            @click.prevent="link.url && $inertia.visit(link.url)"
            class="px-3 py-1 rounded-lg border text-sm"
            :class="{
              'bg-zinc-700 text-white': link.active,
              'bg-zinc-900 text-zinc-400 hover:bg-zinc-800': !link.active
            }"
          />
        </div>
      </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div v-if="showConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="cancelDelete"></div>
      <div class="relative bg-zinc-900 rounded-lg border border-zinc-800 shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-white mb-4">Konfirmasi Hapus</h3>
          <p class="text-zinc-300">
            Apakah Anda yakin ingin menghapus jadwal
            <strong class="text-white">{{ selectedJadwal?.mapel }}</strong>?
          </p>
          <p class="text-sm text-zinc-400 mt-2">Tindakan ini tidak dapat dibatalkan.</p>
          <div class="flex justify-end gap-3 mt-6">
            <button
              @click="cancelDelete"
              class="px-4 py-2 text-sm font-medium text-zinc-300 bg-zinc-800 rounded-lg hover:bg-zinc-700 transition"
            >
              Batal
            </button>
            <button
              @click="proceedDelete"
              class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition"
            >
              Ya, Hapus
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
