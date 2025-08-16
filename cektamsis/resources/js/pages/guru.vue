<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
  {
    title: 'Data Guru',
    href: '/guru',
  },
];

// contoh dummy data
const teachers = [
  { id: 1, name: 'Budi Santoso', email: 'budi@example.com', subject: 'Matematika', kelas: 'X IPA 1' },
];
</script>

<template>
  <Head title="Data Guru" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
      <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-lg">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
          <h1 class="text-xl font-semibold text-white">Data Guru</h1>
          <button
            class="flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition"
          >
            <Plus class="h-4 w-4" />
            Tambah Guru
          </button>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-lg border border-zinc-800">
          <table class="min-w-full divide-y divide-zinc-800">
            <thead class="bg-zinc-800">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-zinc-300">Id Guru</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-zinc-300">Nama</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-zinc-300">Email</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-zinc-300">Mapel</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-zinc-300">Kelas</th>
                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800 bg-zinc-900 text-sm text-zinc-200">
              <tr
                v-for="teacher in teachers"
                :key="teacher.id"
                class="hover:bg-zinc-800/60 transition"
              >
                <td class="px-6 py-4">{{ teacher.id }}</td>
                <td class="px-6 py-4">{{ teacher.name }}</td>
                <td class="px-6 py-4">{{ teacher.email }}</td>
                <td class="px-6 py-4">{{ teacher.subject }}</td>
                <td class="px-6 py-4">{{ teacher.kelas }}</td>
                <td class="px-6 py-4">
                  <div class="flex justify-center gap-2">
                    <button
                      class="rounded-lg bg-yellow-500 px-3 py-1 text-xs font-semibold text-white hover:bg-yellow-600"
                    >
                      Edit
                    </button>
                    <button
                      class="rounded-lg bg-red-600 px-3 py-1 text-xs font-semibold text-white hover:bg-red-700"
                    >
                      Hapus
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
