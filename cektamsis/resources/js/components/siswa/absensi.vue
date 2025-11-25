<script setup>
const props = defineProps({
absensiSekolah: {
    type: Array,
    default: () => []
    },
    showAbsensiModal: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
    <transition name="fade">
        <div v-if="absensiSekolah"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 backdrop-blur-sm p-4">

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
                    <select class="rounded-lg border border-gray-300 bg-white text-sm p-2">
                        <option>Bulan</option>
                    </select>
                    <select class="rounded-lg border border-gray-300 bg-white text-sm p-2">
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
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in absensiSekolahData" :key="index"
                                class="odd:bg-white even:bg-gray-50 border-b">
                                <td class="px-4 py-2">{{ item.tanggal }}</td>
                                <td class="px-4 py-2">{{ item.jam_masuk }}</td>
                                <td class="px-4 py-2">{{ item.jam_keluar }}</td>
                                <td class="px-4 py-2">{{ item.status }}</td>
                                <td class="px-4 py-2">{{ item.keterangan || '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </transition>
</template>
