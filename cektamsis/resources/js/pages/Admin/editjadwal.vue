<script setup lang="ts">
import TextLink from '@/components/TextLink.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import { Head, useForm, Link } from '@inertiajs/vue3'
import { HardDrive } from 'lucide-vue-next'
import { watch, onMounted } from 'vue'

// Breadcrumb
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Edit Jadwal', href: '/jadwal' },
]

// Props dari controller
const props = defineProps<{
  jadwal: {
    id_jadwal: number
    id_guru: number
    id_kelas: number
    id_mapel: number
    hari: string
    jam_mulai: string
    jam_selesai: string
  },
  guru: {
    id_guru: number
    nama: string
  }[],
  kelas: {
    id_kelas: number
    nama_kelas: string
  }[],
  mapel: {
    id_mapel: number
    nama_mapel: string
  }[],
}>()

// Inertia form
const form = useForm({
  id_guru: '',
  id_kelas: '',
  id_mapel: '',
  hari:'',
  jam_mulai: '',
  jam_selesai: '',
})

// Isi form dengan data jadwal saat component dimount
onMounted(() => {
  if (props.jadwal) {
    form.id_guru = props.jadwal.id_guru ? String(props.jadwal.id_guru) : ''
    form.id_kelas = props.jadwal.id_kelas ? String(props.jadwal.id_kelas) : ''
    form.id_mapel = props.jadwal.id_mapel ? String(props.jadwal.id_mapel) : ''
    form.hari = props.jadwal.hari || ''
    form.jam_mulai = props.jadwal.jam_mulai || ''
    form.jam_selesai = props.jadwal.jam_selesai || ''
  }
})

// Function untuk submit form
const submitForm = () => {
  form.put(`/jadwal/update/${props.jadwal.id_jadwal}`, {
    onSuccess: () => {
      console.log('Jadwal berhasil diperbarui!')
    },
    onError: (errors) => {
      console.error('Error:', errors)
    }
  })
}
</script>

<template>
  <Head title="Edit Jadwal" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
      <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-lg max-w-2xl w-full mx-auto">
        <div class="flex items-center justify-between mb-6">
          <h1 class="text-xl font-semibold text-white">Form Edit Jadwal</h1>
        </div>

        <form @submit.prevent="submitForm" class="space-y-5">
          <!-- Guru -->
          <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1">Pilih Guru</label>
            <select v-model="form.id_guru"
              class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none">
              <option value="">-- Pilih Guru --</option>
              <option v-for="g in props.guru" :key="g.id_guru" :value="String(g.id_guru)">
                {{ g.nama }}
              </option>
            </select>
            <span v-if="form.errors.id_guru" class="text-red-500 text-sm">{{ form.errors.id_guru }}</span>
          </div>

          <!-- Kelas -->
          <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1">Pilih Kelas</label>
            <select v-model="form.id_kelas"
              class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none">
              <option value="">-- Pilih Kelas --</option>
              <option v-for="k in props.kelas" :key="k.id_kelas" :value="String(k.id_kelas)">
                {{ k.nama_kelas }}
              </option>
            </select>
            <span v-if="form.errors.id_kelas" class="text-red-500 text-sm">{{ form.errors.id_kelas }}</span>
          </div>

          <!-- Mapel -->
          <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1">Pilih Mata Pelajaran</label>
            <select v-model="form.id_mapel"
              class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none">
              <option value="">-- Pilih Mapel --</option>
              <option v-for="m in props.mapel" :key="m.id_mapel" :value="String(m.id_mapel)">
                {{ m.nama_mapel }}
              </option>
            </select>
            <span v-if="form.errors.id_mapel" class="text-red-500 text-sm">{{ form.errors.id_mapel }}</span>
          </div>

          <!-- Hari -->
          <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1">Hari</label>
            <input
              v-model="form.hari"
              type="date"
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
            <input v-model="form.jam_mulai" type="time"
              class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none" />
            <span v-if="form.errors.jam_mulai" class="text-red-500 text-sm">{{ form.errors.jam_mulai }}</span>
          </div>

          <!-- Jam Selesai -->
          <div>
            <label class="block text-sm font-medium text-zinc-300 mb-1">Jam Selesai</label>
            <input v-model="form.jam_selesai" type="time"
              class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none" />
            <span v-if="form.errors.jam_selesai" class="text-red-500 text-sm">{{ form.errors.jam_selesai }}</span>
          </div>

          <!-- Tombol -->
          <div class="flex justify-end gap-3">
            <Link href="/jadwal"
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
