<template>
    <div class="rounded-2xl border border-white/30 bg-white/80 p-6 shadow-lg backdrop-blur-md">
        <div class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <h2 class="flex items-center text-xl font-bold text-gray-900">
                <svg class="mr-2 h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                    ></path>
                </svg>
                Jadwal Pelajaran
            </h2>

            <div class="flex flex-wrap gap-2">
                <select
                    :value="selectedHari"
                    @change="$emit('update:selectedHari', $event.target.value)"
                    class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm text-gray-900 focus:border-transparent focus:ring-2 focus:ring-purple-500"
                >
                    <option value="" class="text-gray-500">Semua Hari</option>
                    <option value="senin" class="text-gray-900">Senin</option>
                    <option value="selasa" class="text-gray-900">Selasa</option>
                    <option value="rabu" class="text-gray-900">Rabu</option>
                    <option value="kamis" class="text-gray-900">Kamis</option>
                    <option value="jumat" class="text-gray-900">Jumat</option>
                </select>

                <select
                    :value="selectedLantai"
                    @change="$emit('update:selectedLantai', $event.target.value)"
                    class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm text-gray-900 focus:border-transparent focus:ring-2 focus:ring-purple-500"
                >
                    <option value="" class="text-gray-500">Semua Lantai</option>
                    <option v-for="lantai in availableLantai" :key="lantai" :value="lantai" class="text-gray-900">Lantai {{ lantai }}</option>
                </select>

                <select
                    :value="selectedRuang"
                    @change="$emit('update:selectedRuang', $event.target.value)"
                    class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm text-gray-900 focus:border-transparent focus:ring-2 focus:ring-purple-500"
                >
                    <option value="" class="text-gray-500">Semua Ruang</option>
                    <option v-for="ruang in availableRuang" :key="ruang" :value="ruang" class="text-gray-900">Ruang {{ ruang }}</option>
                </select>
            </div>
        </div>

        <div class="space-y-4">
            <div
                v-for="(schedule, index) in filteredSchedule"
                :key="index"
                class="group flex items-center rounded-xl border border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100 p-4 transition-all duration-300 hover:border-purple-300 hover:from-purple-50 hover:to-purple-100"
                :style="{ animationDelay: `${index * 150}ms` }"
            >
                <div
                    class="mr-4 h-16 w-2 rounded-full bg-gradient-to-b from-purple-400 to-purple-600 transition-all duration-300 group-hover:from-purple-500 group-hover:to-purple-700"
                ></div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <h3 class="font-semibold text-gray-900">{{ schedule.mata_pelajaran }}</h3>
                            <span v-if="schedule.status_aktif" class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">
                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Aktif
                            </span>
                            <span v-else class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">
                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Ditutup
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span class="rounded-full bg-purple-100 px-3 py-1 text-sm font-medium text-purple-700">
                                {{ schedule.nama_kelas }}
                            </span>
                            <span class="rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-700">
                                Lt.{{ schedule.lantai }}
                            </span>
                            <span class="rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700">
                                R.{{ schedule.ruang }}
                            </span>
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-gray-600">{{ schedule.jam_mulai }} - {{ schedule.jam_selesai }}</p>
                    <p class="mt-1 text-xs text-gray-500">
                        {{ formatHari(schedule.hari) }} • Lantai {{ schedule.lantai }} • Ruang {{ schedule.ruang }}
                    </p>
                </div>
            </div>

            <div v-if="filteredSchedule.length === 0" class="py-8 text-center">
                <svg class="mx-auto mb-4 h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                    ></path>
                </svg>
                <p class="text-gray-500">Tidak ada jadwal untuk filter yang dipilih</p>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    filteredSchedule: {
        type: Array,
        required: true,
    },
    selectedHari: String,
    selectedLantai: String,
    selectedRuang: String,
    availableLantai: Array,
    availableRuang: Array,
    formatHari: Function,
});

defineEmits(['update:selectedHari', 'update:selectedLantai', 'update:selectedRuang']);
</script>
