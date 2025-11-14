<script setup>
    const props = defineProps({
        absensiSekolah: {
            type: Array,
            default: () => [],
        },
    })

    // kalau kamu di template pakai nama dataAbsensi, buat alias
    const dataAbsensi = props.absensiSekolah
</script>

<template>
    <transition name="fade">
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm p-4">
            <div
                class="w-full max-w-6xl rounded-2xl bg-white text-gray-900 shadow-2xl p-6 relative overflow-hidden border border-gray-200">

                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold">Data Absensi</h2>
                    <button @click="$emit('close')"
                        class="text-gray-400 hover:text-gray-700 transition text-lg font-bold">✕</button>
                </div>

                <!-- Filter Bar -->
                <div class="flex flex-wrap gap-3 mb-5">
                    <select
                        class="rounded-lg border border-gray-300 bg-white text-sm p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option>Bulan</option>
                    </select>
                    <select
                        class="rounded-lg border border-gray-300 bg-white text-sm p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option>Tahun</option>
                    </select>
                </div>

                <!-- Tabel -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead class="border-b border-gray-200 text-gray-700 bg-gray-50">
                            <tr>
                                <th class="py-2 px-3">Tanggal</th>
                                <th class="py-2 px-3">Jam_Masuk</th>
                                <th class="py-2 px-3">Jam_Pulang</th>
                                <th class="py-2 px-3">Status</th>
                                <th class="py-2 px-3">Keterangan</th>
                                <th class="py-2 px-3">Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in dataAbsensi" :key="index"
                                class="odd:bg-white even:bg-gray-50 border-b">
                                <td class="px-4 py-2">{{ item . tanggal }}</td>
                                <td class="px-4 py-2">{{ item . jam_masuk }}</td>
                                <td class="px-4 py-2">{{ item . jam_pulang }}</td>
                                <td class="px-4 py-2">{{ item . status }}</td>
                                <td class="px-4 py-2">{{ item . keterangan || '-' }}</td>
                                <td class="px-4 py-2">{{ item . verifikasi || 'menunggu' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </transition>
</template>

<style scoped>
    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.3s ease;
    }

    .fade-enter-from,
    .fade-leave-to {
        opacity: 0;
    }
</style>
