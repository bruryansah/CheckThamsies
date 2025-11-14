<script setup lang="ts">
    import AppLayout from '@/layouts/AppLayout.vue'
    import {
        Head,
        useForm,
        Link
    } from '@inertiajs/vue3'
    import {
        onMounted
    } from 'vue'
    import type {
        BreadcrumbItem
    } from '@/types'

    // Breadcrumb
    const breadcrumbs: BreadcrumbItem[] = [{
            title: 'Dashboard',
            href: '/dashboard'
        },
        {
            title: 'Edit Absensi',
            href: '/absensi'
        },
    ]

    // Props dari controller
    const props = defineProps < {
        absensi: {
            id_absensi_pelajaran: number
            id_siswa: number
            id_jadwal: number
            waktu_scan: string
            status: string
            keterangan: string
            verifikasi: string
        },
        siswa: {
            id_siswa: number
            nama: string
        } [],
        jadwal: {
            id_jadwal: number
            nama_mapel: string
        }
    } > ()

    // Inertia form
    const form = useForm({
        id_siswa: '',
        id_jadwal: '',
        waktu_scan: '',
        status: '',
        keterangan: '',
        verifikasi: '',
    })

    // Isi form dengan data absensi saat component dimount
    onMounted(() => {
        if (props.absensi) {
            form.id_siswa = props.absensi.id_siswa?.toString() || ''
            form.id_jadwal = props.absensi.id_jadwal?.toString() || ''
            form.waktu_scan = props.absensi.waktu_scan || ''
            form.status = props.absensi.status || ''
            form.keterangan = props.absensi.keterangan || ''
            form.verifikasi = props.absensi.verifikasi || ''
        }
    })
</script>

<template>

    <Head title="Edit Absensi" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
            <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-lg max-w-2xl w-full mx-auto">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-xl font-semibold text-white">Form Edit Absensi</h1>
                </div>

                <!-- Form -->
                <form @submit.prevent="form.put(`/pelajaranxi/update/${props.absensi.id_absensi_pelajaran}`)"
                    class="space-y-5">
                    <!-- Siswa -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Nama Siswa</label>
                        <select v-model="form.id_siswa" disabled
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 cursor-not-allowed">
                            <option v-for="item in props.siswa" :key="item.id_siswa" :value="item.id_siswa">
                                {{ item . nama }}
                            </option>
                        </select>
                        <span v-if="form.errors.id_siswa" class="text-red-500 text-sm">
                            {{ form . errors . id_siswa }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Mata Pelajaran</label>
                        <select v-model="form.id_jadwal" disabled
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 cursor-not-allowed">
                            <option v-for="item in props.jadwal" :key="item.id_jadwal" :value="item.id_jadwal">
                                {{ item . nama_mapel }}
                            </option>
                        </select>
                        <span v-if="form.errors.id_jadwal" class="text-red-500 text-sm">
                            {{ form . errors . id_jadwal }}
                        </span>
                    </div>


                    <!-- waktu scan -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Waktu Scan</label>
                        <input v-model="form.waktu_scan" type="datetime"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2" />
                        <span v-if="form.errors.waktu_scan"
                            class="text-red-500 text-sm">{{ form . errors . waktu_scan }}</span>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Status</label>
                        <select v-model="form.status"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2">
                            <option value="">-- Pilih Status --</option>
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="alfa">Alpa</option>
                        </select>
                        <span v-if="form.errors.status" class="text-red-500 text-sm">{{ form . errors . status }}</span>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Keterangan</label>
                        <textarea v-model="form.keterangan" rows="3"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2"
                            placeholder="Tambahkan keterangan jika perlu"></textarea>
                        <span v-if="form.errors.keterangan"
                            class="text-red-500 text-sm">{{ form . errors . keterangan }}</span>
                    </div>

                    <!-- Verifikasi -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Verifikasi</label>
                        <select v-model="form.verifikasi"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2">
                            <option value="">-- Pilih Status --</option>
                            <option value="approve">Approve</option>
                            <option value="disapprove">Disapprove</option>
                        </select>
                        <span v-if="form.errors.status"
                            class="text-red-500 text-sm">{{ form . errors . verifikasi }}</span>
                    </div>

                    <!-- Tombol -->
                    <div class="flex justify-end gap-3">
                        <Link href="/pelajaranxi"
                            class="rounded-lg bg-zinc-700 px-4 py-2 text-sm font-semibold text-white hover:bg-zinc-600">
                        Batal</Link>
                        <button type="submi" :disabled="form.processing"
                            class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700 disabled:opacity-50">
                            {{ form . processing ? 'Menyimpan...' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
