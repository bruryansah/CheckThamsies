<script setup lang="ts">
import TextLink from '@/components/TextLink.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import { Head, useForm, Link } from '@inertiajs/vue3'

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Tambah Jadwal', href: '/jadwal' },
]

const props = defineProps<{
  guru: { id_guru: number; nama: string }[]
  mapel: { id_mapel: number; nama_mapel: string }[]
  kelas: { id_kelas: number; nama_kelas: string }[]
}>()

// Pastikan id_guru dan id_kelas bertipe string
const form = useForm({
  id_guru: '',
  id_mapel: '',
  id_kelas: '',
  hari: '',
  jam_mulai: '',
  jam_selesai: '',
})
</script>

<template>
  <Head title="Tambah Jadwal" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
      <div
        class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-lg max-w-2xl w-full mx-auto"
      >
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
          <h1 class="text-xl font-semibold text-white">Form Tambah Jadwal</h1>
        </div>

        <!-- Form -->
        <form @submit.prevent="form.post('/jadwal/tambahstore')" class="space-y-5">
          <!-- Guru -->
          <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1">Guru</label>
            <select
              v-model="form.id_guru"
              class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none"
            >
              <option value="">-- Pilih Guru --</option>
              <option
                v-for="g in props.guru"
                :key="g.id_guru"
                :value="g.id_guru.toString()"
              >
                {{ g.nama }}
              </option>
            </select>
            <span v-if="form.errors.id_guru" class="text-red-500 text-sm">
              {{ form.errors.id_guru }}
            </span>
          </div>

          <!-- Mapel -->
          <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1">Mata Pelajaran</label>
            <select
              v-model="form.id_mapel"
              class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none"
            >
              <option value="">-- Pilih Mapel --</option>
              <option v-for="m in props.mapel" :key="m.id_mapel" :value="m.id_mapel.toString()">
                {{ m.nama_mapel }}
              </option>
            </select>
            <span v-if="form.errors.id_mapel" class="text-red-500 text-sm">
              {{ form.errors.id_mapel }}
            </span>
          </div>

          <!-- Kelas -->
          <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1">Kelas</label>
            <select
              v-model="form.id_kelas"
              class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none"
            >
              <option value="">-- Pilih Kelas --</option>
              <option
                v-for="k in props.kelas"
                :key="k.id_kelas"
                :value="k.id_kelas.toString()"
              >
                {{ k.nama_kelas }}
              </option>
            </select>
            <span v-if="form.errors.id_kelas" class="text-red-500 text-sm">
              {{ form.errors.id_kelas }}
            </span>
          </div>

          <!-- Hari -->
          <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1">Hari</label>
            <input
              v-model="form.hari"
              type="text"
              class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none"
              placeholder="Contoh: Senin"
            />
            <span v-if="form.errors.hari" class="text-red-500 text-sm">
              {{ form.errors.hari }}
            </span>
          </div>

          <!-- Jam Mulai -->
          <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1">Jam Mulai</label>
            <input
              v-model="form.jam_mulai"
              type="time"
              class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none"
            />
            <span v-if="form.errors.jam_mulai" class="text-red-500 text-sm">
              {{ form.errors.jam_mulai }}
            </span>
          </div>

          <!-- Jam Selesai -->
          <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1">Jam Selesai</label>
            <input
              v-model="form.jam_selesai"
              type="time"
              class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none"
            />
            <span v-if="form.errors.jam_selesai" class="text-red-500 text-sm">
              {{ form.errors.jam_selesai }}
            </span>
          </div>

          <!-- Tombol -->
          <div class="flex justify-end gap-3">
            <Link
              href="/jadwal"
              class="rounded-lg bg-zinc-700 px-4 py-2 text-sm font-semibold text-white hover:bg-zinc-600"
            >
              Batal
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700 disabled:opacity-50"
            >
              {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
