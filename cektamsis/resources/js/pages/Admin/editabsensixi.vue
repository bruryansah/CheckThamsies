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
            id_absensi: number
            id_siswa: number
            tanggal: string
            jam_masuk: string | null
            jam_keluar: string | null
            status: string
            keterangan: string
        },
        siswa: {
            id_siswa: number
            nama: string
        } []
    } > ()

    // Inertia form
    const form = useForm({
        id_siswa: '',
        tanggal: '',
        jam_masuk: '',
        jam_keluar: '',
        status: '',
        keterangan: '',
        verifikasi: '',
    })

    // Isi form dengan data absensi saat component dimount
    onMounted(() => {
        if (props.absensi) {
            form.id_siswa = props.absensi.id_siswa?.toString() || ''
            form.tanggal = props.absensi.tanggal || ''
            form.jam_masuk = props.absensi.jam_masuk || ''
            form.jam_keluar = props.absensi.jam_keluar || ''
            form.status = props.absensi.status || ''
            form.keterangan = props.absensi. keterangan || ''
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
                <form @submit.prevent="form.put(`/absenxi/update/${props.absensi.id_absensi}`)" class="space-y-5">
                    <!-- Siswa -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Nama Siswa</label>
                        <select v-model="form.id_siswa"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none">
                            <option value="">-- Pilih Siswa --</option>
                            <option v-for="s in props.siswa" :key="s.id_siswa" :value="s.id_siswa">
                                {{ s . nama }}
                            </option>
                        </select>
                        <span v-if="form.errors.id_siswa"
                            class="text-red-500 text-sm">{{ form . errors . id_siswa }}</span>
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Tanggal</label>
                        <input v-model="form.tanggal" type="date"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2" />
                        <span v-if="form.errors.tanggal"
                            class="text-red-500 text-sm">{{ form . errors . tanggal }}</span>
                    </div>

                    <!-- Jam Masuk -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Jam Masuk</label>
                        <input type="time" step="1" v-model="form.jam_masuk"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2
           focus:ring focus:ring-green-500 focus:outline-none disabled:opacity-50" />
                        <span v-if="form.errors.jam_masuk" class="text-red-500 text-sm">
                            {{ form . errors . jam_masuk }}
                        </span>
                    </div>

                    <!-- Jam Keluar -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Jam Keluar</label>
                        <input type="time" step="1" v-model="form.jam_keluar"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2
           focus:ring focus:ring-green-500 focus:outline-none disabled:opacity-50" />
                        <span v-if="form.errors.jam_keluar" class="text-red-500 text-sm">
                            {{ form . errors . jam_keluar }}
                        </span>
                    </div>


                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Status</label>
                        <select v-model="form.status" disabble
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2">
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
                    <!-- Tombol -->
                    <div class="flex justify-end gap-3">
                        <Link href="/absenx"
                            class="rounded-lg bg-zinc-700 px-4 py-2 text-sm font-semibold text-white hover:bg-zinc-600">
                        Batal</Link>
                        <button type="submit" :disabled="form.processing"
                            class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700 disabled:opacity-50">
                            {{ form . processing ? 'Menyimpan...' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
