<script setup lang="ts">
import TextLink from '@/components/TextLink.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import { Head, useForm, Link } from '@inertiajs/vue3'

// Breadcrumb
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Tambah User', href: '/user' },
]

// Array role yang tersedia
const availableRoles = ['admin', 'guru', 'user']

// Inertia form untuk tambah user
const form = useForm({
  name: '',
  email: '',
  role: '',
  password: '',
})

// Submit form
const submitForm = () => {
  form.post('/user/tambahstore', {
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
  <Head title="Tambah User" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
      <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-lg max-w-2xl w-full mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
          <h1 class="text-xl font-semibold text-white">Form Tambah User</h1>
        </div>

        <!-- Form -->
        <form @submit.prevent="submitForm" class="space-y-5">
          <!-- Nama -->
          <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1">Nama User</label>
            <input v-model="form.name" type="text"
              class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none"
              placeholder="Masukkan nama user" />
            <span v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</span>
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1">Email User</label>
            <input v-model="form.email" type="email"
              class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none"
              placeholder="Masukkan email user" />
            <span v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</span>
          </div>

          <!-- Role -->
          <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1">Pilih Role</label>
            <select v-model="form.role"
              class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none">
              <option value="">-- Pilih Role --</option>
              <option v-for="role in availableRoles" :key="role" :value="role">
                {{ role }}
              </option>
            </select>
            <span v-if="form.errors.role" class="text-red-500 text-sm">{{ form.errors.role }}</span>
          </div>

          <!-- Password -->
          <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1">Password</label>
            <input v-model="form.password" type="password"
              class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none"
              placeholder="Masukkan password" />
            <span v-if="form.errors.password" class="text-red-500 text-sm">{{ form.errors.password }}</span>
          </div>

          <!-- Tombol -->
          <div class="flex justify-end gap-3">
            <Link href="/user"
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
