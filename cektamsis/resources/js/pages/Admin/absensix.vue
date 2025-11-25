<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2, Search } from 'lucide-vue-next';
import { defineProps, ref } from 'vue'

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Data Absensi Kelas X', href: '/absensix' },
];

interface Absen {
  id_absensi: number
  tanggal: string
  jam_masuk: string
  jam_keluar: string
  status: string
  keterangan: string | null
  siswa: string
  kelas: string
}

const props = defineProps<{
  absen: {
    data: Absen[]
    links: { url: string | null; label: string; active: boolean }[]
  },
  filters?: {
    search?: string
  }
}>()

// State untuk modal konfirmasi
const showConfirmModal = ref(false)
const selectedabsensi = ref<Absen | null>(null)

// State search
const search = ref(props.filters?.search || '')

const confirmDelete = (absen: Absen) => {
  selectedabsensi.value = absen
  showConfirmModal.value = true
}
const cancelDelete = () => {
  showConfirmModal.value = false
  selectedabsensi.value = null
}
const proceedDelete = () => {
  if (selectedabsensi.value) {
    window.location.href = route('absenx.hapus', selectedabsensi.value.id_absensi)
  }
}

// Function search
const performSearch = () => {
  router.get(route('absenx'), { search: search.value }, { preserveState: true})
}
</script>

<template>
  <Head title="Data Absensi Kelas X" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
      <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-lg">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
          <h1 class="text-xl font-semibold text-white">Data Absensi Kelas X</h1>
        </div>

        <!-- Searchbar -->
        <div class="relative mb-6 w-full md:w-1/3">
          <input
            v-model="search"
            @keyup.enter="performSearch"
            type="text"
            placeholder="Cari nama siswa..."
            class="w-full rounded-full bg-zinc-800 border border-zinc-700 pl-10 pr-4 py-2 text-sm text-white focus:ring-2 focus:ring-green-600 focus:outline-none"
          />
          <Search
            class="absolute left-3 top-2.5 h-4 w-4 text-zinc-400 cursor-pointer"
            @click="performSearch"
          />
        </div>

        <!-- Table (Desktop) -->
        <div class="hidden md:block overflow-x-auto">
          <table class="min-w-full divide-y divide-zinc-800">
            <thead class="bg-zinc-800">
              <tr>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Id Absensi</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Nama Siswa</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Tanggal</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Jam Masuk</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Jam Keluar</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Status</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Ketarangan</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300 w-32">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800 bg-zinc-900 text-sm text-zinc-200">
              <tr v-for="ab in props.absen.data" :key="ab.id_absensi"
                class="hover:bg-zinc-800/60 transition">
                <td class="px-6 py-4 text-center">{{ ab.id_absensi }}</td>
                <td class="px-6 py-4 text-center">{{ ab.siswa }}</td>
                <td class="px-6 py-4 text-center">{{ ab.tanggal }}</td>
                <td class="px-6 py-4 text-center">{{ ab.jam_masuk }}</td>
                <td class="px-6 py-4 text-center">{{ ab.jam_keluar || 'Kosong'}}</td>
                <td class="px-6 py-4 text-center">{{ ab.status }}</td>
                <td class="px-6 py-4 text-center">{{ ab.keterangan || 'Tidak ada'}}</td>
                    <td class="px-6 py-4 text-center">
                  <div class="flex justify-center gap-2">
                    <TextLink
                      :href="route('absenx.edit', ab.id_absensi)"
                      class="rounded-lg bg-yellow-500 p-2 text-white hover:bg-yellow-600"
                    >
                      <Pencil class="w-4 h-4" />
                    </TextLink>
                    <button
                      @click="confirmDelete(ab)"
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
          <button v-for="link in props.absen.links" :key="link.label" v-html="link.label" :disabled="!link.url"
            @click.prevent="link.url && $inertia.visit(link.url)" class="px-3 py-1 rounded-lg border text-sm"
            :class="{
              'bg-zinc-700 text-white': link.active,
              'bg-zinc-900 text-zinc-400 hover:bg-zinc-800': !link.active
            }" />
        </div>
      </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div v-if="showConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="cancelDelete"></div>
      <div class="relative bg-zinc-900 rounded-lg border border-zinc-800 shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Konfirmasi Hapus</h3>
          </div>
          <div class="mb-6">
            <p class="text-zinc-300">
              Apakah Anda yakin ingin menghapus Absensi <strong class="text-white">{{ selectedabsensi?.siswa }}</strong>?
            </p>
            <p class="text-sm text-zinc-400 mt-2">
              Tindakan ini tidak dapat dibatalkan.
            </p>
          </div>
          <div class="flex justify-end gap-3">
            <button @click="cancelDelete"
              class="px-4 py-2 text-sm font-medium text-zinc-300 bg-zinc-800 rounded-lg hover:bg-zinc-700 transition">
              Batal
            </button>
            <button @click="proceedDelete"
              class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
              Ya, Hapus
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
