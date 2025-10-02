<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Plus, Trash2, Pencil } from 'lucide-vue-next';
import { defineProps, ref } from 'vue'

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Data Siswa X RPL', href: '/siswax' },
];

interface Siswa {
  id_siswa: number
  nama: string
  email: string
  kelas: string
  jurusan: string
}

const props = defineProps<{
  siswa: {
    data: Siswa[],
    links: { url: string | null; label: string; active: boolean }[]
  },
  kelasList?: string[],
  selectedKelas?: string | null,
  filters?: {
    search?: string
  }
}>()

// State modal hapus
const showConfirmModal = ref(false)
const selectedSiswa = ref<Siswa | null>(null)

// Function hapus
const confirmDelete = (siswa: Siswa) => {
  selectedSiswa.value = siswa
  showConfirmModal.value = true
}
const cancelDelete = () => {
  showConfirmModal.value = false
  selectedSiswa.value = null
}
const proceedDelete = () => {
  if (selectedSiswa.value) {
    window.location.href = route('siswax.hapus', selectedSiswa.value.id_siswa)
  }
}

// Search query
const searchQuery = ref(props.filters?.search || '')

// Function filter kelas
const filterKelas = (kelas: string) => {
  router.get(route('siswax'), { kelas, search: searchQuery.value }, { preserveState: true })
}

// Function search
const searchData = () => {
  router.get(route('siswax'), { search: searchQuery.value, kelas: props.selectedKelas }, { preserveState: true })
}
</script>

<template>
  <Head title="Data Siswa X RPL" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
      <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-lg">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
          <h1 class="text-xl font-semibold text-white">Data Siswa Kelas X</h1>
          <TextLink :href="route('siswax.tambah')"
            class="flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition">
            <Plus class="h-4 w-4" />
            Tambah Siswa
          </TextLink>
        </div>

        <!-- Search & Filter -->
        <div class="mb-6 flex items-center gap-3">
          <!-- Searchbar -->
          <input
            v-model="searchQuery"
            @keyup.enter="searchData"
            type="text"
            placeholder="Cari siswa..."
            class="rounded-lg bg-zinc-800 border border-zinc-700 text-sm text-white px-3 py-2 focus:ring-2 focus:ring-green-600 focus:outline-none w-64"
          />
          <button
            @click="searchData"
            class="px-4 py-2 rounded-lg bg-green-600 text-white text-sm font-medium hover:bg-green-700 transition"
          >
            Cari
          </button>

          <!-- Filter Kelas -->
          <label class="text-sm text-zinc-300 ml-4">Filter Kelas:</label>
          <select
            class="rounded-lg bg-zinc-800 border border-zinc-700 text-sm text-white px-3 py-2 focus:ring-2 focus:ring-green-600 focus:outline-none"
            @change="filterKelas(($event.target as HTMLSelectElement).value)"
          >
            <option value="">Semua Kelas</option>
            <option
              v-for="kelas in props.kelasList"
              :key="kelas"
              :value="kelas"
              :selected="props.selectedKelas === kelas"
            >
              {{ kelas }}
            </option>
          </select>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-lg border border-zinc-800">
          <table class="min-w-full divide-y divide-zinc-800">
            <thead class="bg-zinc-800">
              <tr>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Id Siswa</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Nama</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Email</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Kelas</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Jurusan</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800 bg-zinc-900 text-sm text-zinc-200">
              <tr v-for="siswa in props.siswa.data" :key="siswa.id_siswa" class="hover:bg-zinc-800/60 transition">
                <td class="px-6 py-4 text-center">{{ siswa.id_siswa }}</td>
                <td class="px-6 py-4 text-center">{{ siswa.nama }}</td>
                <td class="px-6 py-4 text-center">{{ siswa.email }}</td>
                <td class="px-6 py-4 text-center">{{ siswa.kelas }}</td>
                <td class="px-6 py-4 text-center">{{ siswa.jurusan }}</td>
                <td class="px-6 py-4 text-center">
                  <div class="flex justify-center gap-2">
                    <TextLink
                      :href="route('siswax.edit', siswa.id_siswa)"
                      class="rounded-lg bg-yellow-500 p-2 text-white hover:bg-yellow-600"
                    >
                      <Pencil class="w-4 h-4" />
                    </TextLink>
                    <button
                      @click="confirmDelete(siswa)"
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
            v-for="link in props.siswa.links"
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
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Konfirmasi Hapus</h3>
          </div>
          <div class="mb-6">
            <p class="text-zinc-300">
              Apakah Anda yakin ingin menghapus data siswa <strong class="text-white">{{ selectedSiswa?.nama }}</strong>?
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
