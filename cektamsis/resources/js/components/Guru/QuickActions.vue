<template>
    <div class="rounded-2xl border border-white/30 bg-white/80 p-6 shadow-lg backdrop-blur-md">
        <h2 class="mb-6 flex items-center text-xl font-bold text-gray-900">
            <svg class="mr-2 h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Aksi Cepat
        </h2>

        <!-- Dropdown Pilih Jadwal -->
        <div class="mb-8 relative" ref="dropdownContainer">
            <label class="mb-2 block text-sm font-semibold text-gray-700 flex items-center">
                <svg class="mr-2 h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
                Pilih Jadwal:
            </label>

            <div class="relative">
                <div
                    @click="toggleDropdown"
                    class="flex items-center justify-between w-full rounded-xl border border-gray-300 bg-white/90 px-4 py-3 text-gray-900 shadow-md transition-all duration-300 cursor-pointer hover:border-blue-400 focus:border-blue-500"
                >
                    <span v-if="selectedLabel" class="truncate">{{ selectedLabel }}</span>
                    <span v-else class="text-gray-500">-- Pilih Jadwal --</span>
                    <svg
                        class="ml-2 h-5 w-5 text-gray-500 transition-transform duration-300 flex-shrink-0"
                        :class="{ 'rotate-180': isOpen }"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>

            <p v-if="!selectedJadwal" class="mt-2 text-sm text-gray-500 italic">Silakan pilih jadwal terlebih dahulu</p>
        </div>

        <!-- Dropdown Portal - Render di luar container -->
        <teleport to="body">
            <transition name="fade-slide">
                <div
                    v-if="isOpen"
                    class="fixed inset-0 z-[9999]"
                    @click="isOpen = false"
                >
                    <ul
                        v-if="dropdownPosition"
                        :style="{
                            position: 'fixed',
                            top: dropdownPosition.top + 'px',
                            left: dropdownPosition.left + 'px',
                            width: dropdownPosition.width + 'px'
                        }"
                        class="max-h-64 overflow-y-auto rounded-xl border border-gray-200 bg-white shadow-2xl ring-1 ring-black/5"
                        @click.stop
                    >
                        <li
                            v-for="j in activeJadwal"
                            :key="j.id_jadwal"
                            @click.stop="selectJadwal(j)"
                            class="cursor-pointer px-4 py-3 transition-colors duration-200 text-gray-700 hover:bg-blue-50 hover:text-blue-700 border-b border-gray-100 last:border-b-0"
                        >
                            <div class="text-sm font-medium">
                                {{ j.mata_pelajaran }} - {{ j.nama_kelas }}
                            </div>
                            <div class="text-xs text-gray-500">
                                ({{ formatHari(j.hari) }} {{ j.jam_mulai }} - {{ j.jam_selesai }}) - Lt.{{ j.lantai }} R.{{ j.ruang }}
                            </div>
                        </li>
                        <li
                            v-if="activeJadwal.length === 0"
                            class="px-4 py-2 text-gray-500 text-sm italic"
                        >
                            Tidak ada jadwal aktif tersedia
                        </li>
                    </ul>
                </div>
            </transition>
        </teleport>

        <!-- Tombol Aksi -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <!-- Generate QR -->
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

            <!-- Lihat Absensi -->
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

            <!-- Tutup Absen -->
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
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    selectedJadwal: String,
    jadwalData: Array,
    isGeneratingQR: Boolean,
    isFinalized: Boolean,
    formatHari: Function,
});

const emit = defineEmits(['update:selectedJadwal', 'generateQR', 'showAttendance', 'finalize']);

const isOpen = ref(false);
const selectedLabel = ref('');
const dropdownContainer = ref(null);
const dropdownPosition = ref(null);

// Filter hanya jadwal aktif
const activeJadwal = computed(() => {
    return props.jadwalData.filter(j => j.status === 'aktif');
});

// Calculate dropdown position
const updateDropdownPosition = () => {
    if (!dropdownContainer.value || !isOpen.value) return;
    
    const container = dropdownContainer.value.querySelector('div');
    if (!container) return;
    
    const rect = container.getBoundingClientRect();
    dropdownPosition.value = {
        top: rect.bottom + 8,
        left: rect.left,
        width: rect.width
    };
};

// Toggle dropdown
const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        setTimeout(updateDropdownPosition, 10);
    }
};

// Close dropdown ketika klik di luar
const handleClickOutside = (event) => {
    if (dropdownContainer.value && !dropdownContainer.value.contains(event.target)) {
        isOpen.value = false;
    }
};

// Handle window resize
const handleResize = () => {
    if (isOpen.value) {
        updateDropdownPosition();
    }
};

// Saat pilih jadwal
const selectJadwal = (jadwal) => {
    emit('update:selectedJadwal', String(jadwal.id_jadwal));
    selectedLabel.value = `${jadwal.mata_pelajaran} - ${jadwal.nama_kelas} (${props.formatHari(jadwal.hari)} ${jadwal.jam_mulai} - ${jadwal.jam_selesai})`;
    isOpen.value = false;
};

// Sinkronisasi label dengan props
watch(
    () => props.selectedJadwal,
    (val) => {
        if (!val) selectedLabel.value = '';
    }
);

// Lifecycle hooks
onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    window.addEventListener('resize', handleResize);
    window.addEventListener('scroll', updateDropdownPosition, true);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
    window.removeEventListener('resize', handleResize);
    window.removeEventListener('scroll', updateDropdownPosition, true);
});
</script>

<style scoped>
/* Transition untuk dropdown */
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.2s ease;
}

.fade-slide-enter-from {
    opacity: 0;
    transform: translateY(-10px);
}

.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

/* Scrollbar halus dan elegan */
ul::-webkit-scrollbar {
    width: 6px;
}

ul::-webkit-scrollbar-thumb {
    background-color: rgba(156, 163, 175, 0.6);
    border-radius: 8px;
}

ul::-webkit-scrollbar-thumb:hover {
    background-color: rgba(107, 114, 128, 0.8);
}
</style>