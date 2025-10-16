<template>
    <div class="rounded-2xl border border-white/30 bg-white/80 p-6 shadow-lg backdrop-blur-md">
        <h2 class="mb-6 flex items-center text-xl font-bold text-gray-900">
            <svg class="mr-2 h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Aksi Cepat
        </h2>

        <div class="mb-4">
            <label class="mb-2 block text-sm font-medium text-gray-700">Pilih Jadwal:</label>
            <select
                :value="selectedJadwal"
                @change="$emit('update:selectedJadwal', $event.target.value)"
                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-900 transition-all duration-300 focus:border-transparent focus:ring-2 focus:ring-blue-500"
            >
                <option disabled value="" class="text-gray-500">-- Pilih Jadwal --</option>
                <option v-for="j in jadwalData" :key="j.id_jadwal" :value="j.idenc" class="text-gray-900">
                    {{ j.mata_pelajaran }} - {{ j.nama_kelas }} ({{ formatHari(j.hari) }} {{ j.jam_mulai }} - {{ j.jam_selesai }}) -
                    Lt.{{ j.lantai }} R.{{ j.ruang }}
                </option>
            </select>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <button
                @click="$emit('generateQR')"
                :disabled="!selectedJadwal || isGeneratingQR"
                :class="selectedJadwal && !isGeneratingQR ? 'hover:from-blue-500 hover:to-blue-600' : 'cursor-not-allowed opacity-50'"
                class="group transform rounded-xl border border-blue-200 bg-gradient-to-r from-blue-50 to-blue-100 p-4 transition-all duration-300 hover:scale-105 hover:border-blue-500"
            >
                <div class="flex items-center space-x-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-500 transition-colors duration-300 group-hover:bg-white">
                        <svg class="h-5 w-5 text-white group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M12 12v4.01M12 12V8.01"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="font-semibold text-blue-900 group-hover:text-white">
                            <span v-if="isGeneratingQR">Generating...</span>
                            <span v-else>Generate QR Absen</span>
                        </p>
                        <p class="text-sm text-blue-600 group-hover:text-blue-100">Buat kode QR untuk absen</p>
                    </div>
                </div>
            </button>

            <button
                @click="$emit('showAttendance')"
                class="group transform rounded-xl border border-green-200 bg-gradient-to-r from-green-50 to-green-100 p-4 transition-all duration-300 hover:scale-105 hover:border-green-500 hover:from-green-500 hover:to-green-600"
            >
                <div class="flex items-center space-x-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-500 transition-colors duration-300 group-hover:bg-white">
                        <svg class="h-5 w-5 text-white group-hover:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="font-semibold text-green-900 group-hover:text-white">Lihat Absensi</p>
                        <p class="text-sm text-green-600 group-hover:text-green-100">Cek kehadiran siswa</p>
                    </div>
                </div>
            </button>

            <button
                @click="$emit('finalize')"
                :disabled="!selectedJadwal"
                :class="selectedJadwal ? 'hover:from-yellow-500 hover:to-yellow-600' : 'cursor-not-allowed opacity-50'"
                class="group transform rounded-xl border border-yellow-200 bg-gradient-to-r from-yellow-50 to-yellow-100 p-4 transition-all duration-300 hover:scale-105 hover:border-yellow-500"
            >
                <div class="flex items-center space-x-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-yellow-500 transition-colors duration-300 group-hover:bg-white">
                        <svg class="h-5 w-5 text-white group-hover:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 8h10M5 6a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6z" />
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="font-semibold text-yellow-900 group-hover:text-white">Tutup Absen</p>
                    </div>
                </div>
            </button>
        </div>
    </div>
</template>

<script setup>
defineProps({
    selectedJadwal: String,
    jadwalData: Array,
    isGeneratingQR: Boolean,
    isFinalized: Boolean,
    formatHari: Function,
});

defineEmits(['update:selectedJadwal', 'generateQR', 'showAttendance', 'finalize']);
</script>
