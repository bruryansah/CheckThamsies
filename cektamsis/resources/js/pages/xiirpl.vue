<script setup lang="ts">
    import AppLayout from '@/layouts/AppLayout.vue';
    import {
        type BreadcrumbItem
    } from '@/types';
    import {
        Head
    } from '@inertiajs/vue3';
    import {
        Plus
    } from 'lucide-vue-next';

    const breadcrumbs: BreadcrumbItem[] = [{
            title: 'Dashboard',
            href: '/dashboard',
        },
        {
            title: 'XII RPL',
            href: '/siswa',
        },
    ];

    // contoh dummy data
    const students = [{
            id: 1,
            name: 'Rina Aulia',
            email: 'rina@example.com',
            kelas: 'X IPA 1',
            jurusan: 'IPA',
            absen: 'Sakit',
            Keterangan:'Hadir'
        },
        {
            id: 2,
            name: 'Agus Pratama',
            email: 'agus@example.com',
            kelas: 'XI IPS 2',
            jurusan: 'IPS',
            absen: 'Absen',
            Keterangan:'Hadir'
        },
        {
            id: 3,
            name: 'Dewi Lestari',
            email: 'dewi@example.com',
            kelas: 'XII IPA 3',
            jurusan: 'IPA',
            absen: 'Alpa',
            Keterangan:'Hadir'
        },
        {
            id: 4,
            name: 'Fajar Nugraha',
            email: 'fajar@example.com',
            kelas: 'XI IPA 1',
            jurusan: 'IPA',
            absen: 'Izin',
            Keterangan:'Hadir'
        },
    ];

    function getAbsenClass(status: string) {
        switch (status) {
            case 'Sakit':
            case 'Izin':
                return 'bg-yellow-500 text-black';
            case 'Absen':
                return 'bg-green-600 text-white';
            case 'Alpa':
                return 'bg-red-600 text-white';
            case 'Hadir':
                return 'bg-green-600 text-white';
            case 'Tidak Hadir':
                return 'bg-red-600 text-white';
            default:
                return 'bg-zinc-600 text-white';
        }
    }
</script>

<template>

    <Head title="Data Siswa" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
            <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-lg">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-xl font-semibold text-white">Data Siswa XII RPL</h1>
                    <button
                        class="flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition">
                        <Plus class="h-4 w-4" />
                        Tambah Siswa
                    </button>
                </div>

                <!-- Table -->
                <div class="overflow-hidden rounded-lg border border-zinc-800">
                    <table class="min-w-full divide-y divide-zinc-800">
                        <thead class="bg-zinc-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-zinc-300">Id</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-zinc-300">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-zinc-300">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-zinc-300">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-zinc-300">Jurusan
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Absen
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Keterangan
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800 bg-zinc-900 text-sm text-zinc-200">
                            <tr v-for="student in students" :key="student.id"
                                class="hover:bg-zinc-800/60 transition">
                                <td class="px-6 py-4">{{ student . id }}</td>
                                <td class="px-6 py-4">{{ student . name }}</td>
                                <td class="px-6 py-4">{{ student . email }}</td>
                                <td class="px-6 py-4">{{ student . kelas }}</td>
                                <td class="px-6 py-4">{{ student . jurusan }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-lg text-xs font-semibold"
                                        :class="getAbsenClass(student.absen)">
                                        {{ student . absen }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-lg text-xs font-semibold"
                                        :class="getAbsenClass(student.Keterangan)">
                                        {{ student . Keterangan }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <button
                                            class="rounded-lg bg-yellow-500 px-3 py-1 text-xs font-semibold text-white hover:bg-yellow-600">
                                            Edit
                                        </button>
                                        <button
                                            class="rounded-lg bg-red-600 px-3 py-1 text-xs font-semibold text-white hover:bg-red-700">
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
