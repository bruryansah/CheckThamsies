<script setup lang="ts">
import TextLink from '@/components/TextLink.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import { Head, useForm, Link } from '@inertiajs/vue3'

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Tambah Data', href: '/guru' },
]

const form = useForm({
  nama: '',
  email: '',
  kelas: '',
  mapel: '',
})
</script>

<template>
  <Head title="Data Guru" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
      <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-lg max-w-2xl w-full mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
          <h1 class="text-xl font-semibold text-white">Form Data Guru</h1>
        </div>

        <!-- Form -->
        <form @submit.prevent="form.post('/guru')" class="space-y-5">
          <!-- Id Users -->
          <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1">Id Users</label>
            <input
              v-model="form.nama"
              type="text"
              class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none"
              placeholder="Masukkan nama guru"
            />
            <span v-if="form.errors.nama" class="text-red-500 text-sm">{{ form.errors.nama }}</span>
          </div>
          <!-- Nama -->
          <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1">Nama Guru</label>
            <input
              v-model="form.nama"
              type="text"
              class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none"
              placeholder="Masukkan nama guru"
            />
            <span v-if="form.errors.nama" class="text-red-500 text-sm">{{ form.errors.nama }}</span>
          </div>
          <!-- Nama -->
          <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1">Nama Guru</label>
            <input
              v-model="form.nama"
              type="text"
              class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none"
              placeholder="Masukkan nama guru"
            />
            <span v-if="form.errors.nama" class="text-red-500 text-sm">{{ form.errors.nama }}</span>
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1">Email</label>
            <input
              v-model="form.email"
              type="email"
              class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none"
              placeholder="Masukkan email guru"
            />
            <span v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</span>
          </div>

          <!-- Kelas -->
          <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1">Kelas</label>
            <select
              v-model="form.kelas"
              class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none"
            >
              <option value="">-- Pilih Kelas --</option>
              <option value="X RPL">X RPL</option>
              <option value="XI RPL">XI RPL</option>
              <option value="XII RPL">XII RPL</option>
            </select>
            <span v-if="form.errors.kelas" class="text-red-500 text-sm">{{ form.errors.kelas }}</span>
          </div>

          <!-- Mapel -->
          <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1">Mata Pelajaran</label>
            <input
              v-model="form.mapel"
              type="text"
              class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none"
              placeholder="Masukkan mata pelajaran"
            />
            <span v-if="form.errors.mapel" class="text-red-500 text-sm">{{ form.errors.mapel }}</span>
          </div>

          <!-- Tombol -->
          <div class="flex justify-end gap-3">
            <Link
              href="/guru"
              class="rounded-lg bg-zinc-700 px-4 py-2 text-sm font-semibold text-white hover:bg-zinc-600"
            >
              Batal
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700 disabled:opacity-50"
            >
              Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
