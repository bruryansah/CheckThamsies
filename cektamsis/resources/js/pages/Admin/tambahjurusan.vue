<script setup lang="ts">
import TextLink from '@/components/TextLink.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import { Head, useForm, Link } from '@inertiajs/vue3'

// Breadcrumb
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Tambah Jurusan', href: '/jurusan' },
]

// Inertia form untuk tambah user
const form = useForm({
  nama_jurusan: '',
})

// Submit form
const submitForm = () => {
  form.post('/jurusan/tambahstore', {
    onSuccess: () => {
      console.log('User berhasil ditambahkan!')
      form.reset() // reset form setelah berhasil
    },
    onError: (errors) => {
      console.error('Error:', errors)
    }
  })
}
</script>

<template>
  <Head title="Tambah Jurusan" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
      <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-lg max-w-2xl w-full mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
          <h1 class="text-xl font-semibold text-white">Form Tambah Jurusan</h1>
        </div>

        <!-- Form -->
        <form @submit.prevent="submitForm" class="space-y-5">
          <!-- Nama -->
          <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1">Nama Jurusan</label>
            <input v-model="form.nama_jurusan" type="text"
              class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none"
              placeholder="Masukkan nama Jurusan" />
            <span v-if="form.errors.nama_jurusan" class="text-red-500 text-sm">{{ form.errors.nama_jurusan }}</span>
          </div>
          <!-- Tombol -->
          <div class="flex justify-end gap-3">
            <Link href="/Kelas"
              class="rounded-lg bg-zinc-700 px-4 py-2 text-sm font-semibold text-white hover:bg-zinc-600">
              Batal
            </Link>
            <button type="submit" :disabled="form.processing"
              class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700 disabled:opacity-50">
              {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
