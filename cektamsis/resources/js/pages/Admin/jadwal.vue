<script setup lang="ts">
    import TextLink from '@/components/TextLink.vue';
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
    import {
        defineProps,
        ref
    } from 'vue'

    const breadcrumbs: BreadcrumbItem[] = [{
            title: 'Dashboard',
            href: '/dashboard',
        },
        {
            title: 'Data Jadwal',
            href: '/jadwal',
        },
    ];
    interface jadwal {
        id_jadwal: number
        guru: string
        mapel: string
        kelas: string
        hari: number
        jam_mulai: string
        jam_selesai: string
    }

    // contoh dummy data
    const props = defineProps < {
        jadwal: jadwal[]
    } > ()

    // State untuk modal konfirmasi
    const showConfirmModal = ref(false)
    const selectedjadwal = ref<jadwal | null>(null)

    // Function untuk menampilkan konfirmasi hapus
    const confirmDelete = (jadwal: jadwal) => {
        selectedjadwal.value = jadwal
        showConfirmModal.value = true
    }

    // Function untuk membatalkan hapus
    const cancelDelete = () => {
        showConfirmModal.value = false
        selectedjadwal.value = null
    }

    // Function untuk melanjutkan hapus
    const proceedDelete = () => {
        if (selectedjadwal.value) {
            // Redirect ke route hapus
            window.location.href = route('jadwal.hapus', selectedjadwal.value.id_jadwal)
        }
    }
</script>

<template>

    <Head title="Data Jadwal" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
            <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-lg">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-xl font-semibold text-white">Data Jadwal</h1>
                    <TextLink :href="route('jadwal.tambah')"
                        class="flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition">
                        <Plus class="h-4 w-4" />
                        Tambah Jadwal
                    </TextLink>
                </div>

                <!-- Table -->
                <div class="overflow-hidden rounded-lg border border-zinc-800">
                    <table class="min-w-full divide-y divide-zinc-800">
                        <thead class="bg-zinc-800">
                            <tr>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Id Jadwal
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Nama mapel
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Nama Guru
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Nama Kelas
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Hari
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Jam Mulai
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Jam Selesai
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800 bg-zinc-900 text-sm text-zinc-200">
                            <tr v-for="jadwal in props.jadwal" :key="jadwal.id_jadwal"
                                class="hover:bg-zinc-800/60 transition">
                                <td class="px-6 py-4 text-center">{{ jadwal.id_jadwal }}</td>
                                <td class="px-6 py-4 text-center">{{ jadwal.mapel }}</td>
                                <td class="px-6 py-4 text-center">{{ jadwal.guru }}</td>
                                <td class="px-6 py-4 text-center">{{ jadwal.kelas }}</td>
                                <td class="px-6 py-4 text-center">{{ jadwal.hari }}</td>
                                <td class="px-6 py-4 text-center">{{ jadwal.jam_mulai }}</td>
                                <td class="px-6 py-4 text-center">{{ jadwal.jam_selesai }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <TextLink :href="route('jadwal.edit', jadwal.id_jadwal)"
                                            class="rounded-lg bg-yellow-500 px-3 py-1 text-xs font-semibold text-white hover:bg-yellow-600">
                                            Edit
                                        </TextLink>
                                        <button @click="confirmDelete(jadwal)"
                                            class="rounded-lg bg-red-600 px-3 py-1 text-xs font-semibold text-white hover:bg-red-700 transition">
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

        <!-- Modal Konfirmasi Hapus -->
        <div v-if="showConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="cancelDelete"></div>

            <!-- Modal -->
            <div class="relative bg-zinc-900 rounded-lg border border-zinc-800 shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-white">Konfirmasi Hapus</h3>
                    </div>

                    <!-- Content -->
                    <div class="mb-6">
                        <p class="text-zinc-300">
                            Apakah Anda yakin ingin menghapus data guru <strong class="text-white">{{ selectedjadwal?.hari }}</strong>?
                        </p>
                        <p class="text-sm text-zinc-400 mt-2">
                            Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3">
                        <button @click="cancelDelete"
                            class="px-4 py-2 text-sm font-medium text-zinc-300 bg-zinc-800 rounded-lg hover:bg-zinc-700 transition">
                            Batal
                        </button>
                        <button @click="proceedDelete"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
