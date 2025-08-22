<script setup lang="ts">
    import TextLink from '@/components/TextLink.vue'
    import AppLayout from '@/layouts/AppLayout.vue'
    import {
        type BreadcrumbItem
    } from '@/types'
    import {
        Head,
        useForm,
        Link
    } from '@inertiajs/vue3'
    import Jurusan from './jurusan.vue'
import { identity } from '@vueuse/core'

    // Breadcrumb
    const breadcrumbs: BreadcrumbItem[] = [{
            title: 'Dashboard',
            href: '/dashboard'
        },
        {
            title: 'Tambah Kelas',
            href: '/kelas'
        },
    ]

    const props = defineProps < {
        kelas: {
            id_kelas: number;
            nama_kelas: string;
            tingkat_kelas: number;
            total_siswa: number;
        } []
        jurusan: {
            id_jurusan: number;
            nama_jurusan: string;
        } []
        guru: {
            id_guru: number;
            nama: string;
        } []
    } > ()

    // Inertia form untuk tambah user
    const form = useForm({
        nama_kelas: '',
        tingkat_kelas: '',
        total_siswa: '',
        id_jurusan: '',
        id_guru: ''
    })

    // Submit form
    const submitForm = () => {
        form.post('/kelas/tambahstore', {
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

    <Head title="Tambah Kelas" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
            <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-lg max-w-2xl w-full mx-auto">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-xl font-semibold text-white">Form Tambah Kelas</h1>
                </div>

                <!-- Form -->
                <form @submit.prevent="submitForm" class="space-y-5">
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Nama Kelas</label>
                        <input v-model="form.nama_kelas" type="text"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none"
                            placeholder="Masukkan nama user" />
                        <span v-if="form.errors.nama_kelas"
                            class="text-red-500 text-sm">{{ form . errors . nama_kelas }}</span>
                    </div>
                    <!-- Tingkat Kelas -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Tingkat Kelas</label>
                        <input v-model="form.tingkat_kelas" type="text"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none"
                            placeholder="Masukkan Tingkat Kelas" />
                        <span v-if="form.errors.tingkat_kelas"
                            class="text-red-500 text-sm">{{ form . errors . tingkat_kelas }}</span>
                    </div>
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Total Siswa</label>
                        <input v-model="form.total_siswa" type="text"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none"
                            placeholder="Masukkan Total Siswa" />
                        <span v-if="form.errors.total_siswa"
                            class="text-red-500 text-sm">{{ form . errors . total_siswa }}</span>
                    </div>

                    <!-- Guru -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Pilih Walas</label>
                        <select v-model="form.id_guru"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none">
                            <option value="">-- Pilih Walas --</option>
                            <option v-for="guru in props.guru" :key="guru.id_guru" :value="guru.id_guru">
                                {{ guru . nama }}
                            </option>
                        </select>
                        <span v-if="form.errors.id_guru"
                            class="text-red-500 text-sm">{{ form . errors . id_guru }}</span>
                    </div>

                    <!-- Jurusan -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Pilih Jurusan</label>
                        <select v-model="form.id_jurusan"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none">
                            <option value="">-- Pilih Jurusan --</option>
                            <option v-for="jurusan in props.jurusan" :key="jurusan.id_jurusan" :value="jurusan.id_jurusan">
                                {{ jurusan . nama_jurusan }}
                            </option>
                        </select>
                        <span v-if="form.errors.id_jurusan"
                            class="text-red-500 text-sm">{{ form . errors . id_jurusan }}</span>
                    </div>

                    <!-- Tombol -->
                    <div class="flex justify-end gap-3">
                        <Link href="/Kelas"
                            class="rounded-lg bg-zinc-700 px-4 py-2 text-sm font-semibold text-white hover:bg-zinc-600">
                        Batal
                        </Link>
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
