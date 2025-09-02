<script setup lang="ts">
import TextLink from '@/components/TextLink.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import { Head } from '@inertiajs/vue3'
import { Plus } from 'lucide-vue-next'
import { defineProps, ref, computed } from 'vue'

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Data Guru', href: '/guru' },
]

interface User {
  id: number
  name: string
  email: string
  role: string
}

// ubah type props -> sekarang bukan array biasa, tapi ada data & links
const props = defineProps<{
  users: {
    data: User[]
    links: { url: string | null; label: string; active: boolean }[]
  }
}>()

// State untuk modal konfirmasi
const showConfirmModal = ref(false)
const selecteduser = ref<User | null>(null)

// Function untuk menentukan warna role
const getRoleColor = (role: string) => {
  const roleColors = {
    admin: 'bg-red-600 text-white',
    guru: 'bg-blue-600 text-white',
    user: 'bg-green-600 text-white'
  }

  const normalizedRole = role.toLowerCase()
  return roleColors[normalizedRole as keyof typeof roleColors] || 'bg-zinc-600 text-white'
}

// Function untuk menampilkan konfirmasi hapus
const confirmDelete = (user: User) => {
  selecteduser.value = user
  showConfirmModal.value = true
}

// Function untuk membatalkan hapus
const cancelDelete = () => {
  showConfirmModal.value = false
  selecteduser.value = null
}

// Function untuk melanjutkan hapus
const proceedDelete = () => {
  if (selecteduser.value) {
    window.location.href = route('user.hapus', selecteduser.value.id)
  }
}
</script>

<template>
  <Head title="Data Guru" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
      <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-lg">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
          <h1 class="text-xl font-semibold text-white">Data User</h1>
          <TextLink
            :href="route('user.tambah')"
            class="flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition"
          >
            <Plus class="h-4 w-4" />
            Tambah User
          </TextLink>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-lg border border-zinc-800">
          <table class="min-w-full divide-y divide-zinc-800">
            <thead class="bg-zinc-800">
              <tr>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">
                  Id User
                </th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">
                  Nama
                </th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">
                  Email
                </th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">
                  Roles
                </th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">
                  Aksi
                </th>
              </tr>
            </thead>
            <tbody
              class="divide-y divide-zinc-800 bg-zinc-900 text-sm text-zinc-200"
            >
              <tr
                v-for="user in props.users.data"
                :key="user.id"
                class="hover:bg-zinc-800/60 transition"
              >
                <td class="px-6 py-4 text-center">{{ user.id }}</td>
                <td class="px-6 py-4 text-center">{{ user.name }}</td>
                <td class="px-6 py-4 text-center">{{ user.email }}</td>
                <td class="px-6 py-4 text-center">
                  <span
                    :class="getRoleColor(user.role)"
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold capitalize"
                  >
                    {{ user.role }}
                  </span>
                </td>
                <td class="px-6 py-4 text-center">
                  <div class="flex justify-center gap-2">
                    <TextLink
                      :href="route('user.edit', user.id)"
                      class="rounded-lg bg-yellow-500 px-3 py-1 text-xs font-semibold text-white hover:bg-yellow-600"
                    >
                      Edit
                    </TextLink>
                    <button
                      @click="confirmDelete(user)"
                      class="rounded-lg bg-red-600 px-3 py-1 text-xs font-semibold text-white hover:bg-red-700 transition"
                    >
                      Hapus
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
            v-for="link in props.users.links"
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

        <!-- Role Legend -->
        <div class="mt-6 pt-4 border-t border-zinc-800">
          <p class="text-sm text-zinc-400 mb-2">Keterangan Role:</p>
          <div class="flex flex-wrap gap-3">
            <div class="flex items-center gap-2">
              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-600 text-white">
                Admin
              </span>
              <span class="text-xs text-zinc-400">Administrator sistem</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-600 text-white">
                Guru
              </span>
              <span class="text-xs text-zinc-400">Tenaga pengajar</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-600 text-white">
                User
              </span>
              <span class="text-xs text-zinc-400">Pengguna biasa</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div
      v-if="showConfirmModal"
      class="fixed inset-0 z-50 flex items-center justify-center"
    >
      <!-- Backdrop -->
      <div
        class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
        @click="cancelDelete"
      ></div>

      <!-- Modal -->
      <div
        class="relative bg-zinc-900 rounded-lg border border-zinc-800 shadow-xl max-w-md w-full mx-4"
      >
        <div class="p-6">
          <!-- Header -->
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">
              Konfirmasi Hapus
            </h3>
          </div>

          <!-- Content -->
          <div class="mb-6">
            <p class="text-zinc-300">
              Apakah Anda yakin ingin menghapus data user
              <strong class="text-white">{{ selecteduser?.name }}</strong>
              dengan role
              <span
                :class="getRoleColor(selecteduser?.role || '')"
                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold capitalize ml-1"
              >
                {{ selecteduser?.role }}
              </span>
              ?
            </p>
            <p class="text-sm text-zinc-400 mt-2">
              Tindakan ini tidak dapat dibatalkan.
            </p>
          </div>

          <!-- Actions -->
          <div class="flex justify-end gap-3">
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
