<template>
    <div class="rounded-2xl border border-white/30 bg-white/80 p-6 shadow-lg backdrop-blur-md">
        <h2 class="mb-6 flex items-center text-xl font-bold text-gray-900">
            <svg class="mr-2 h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                ></path>
            </svg>
            Statistik Siswa Hari Ini
        </h2>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Kehadiran Hari Ini</span>
                <div class="flex items-center">
                    <div class="mr-2 h-2 w-16 rounded-full bg-gray-200">
                        <div class="h-2 rounded-full bg-green-500" :style="{ width: stats.hadirPct + '%' }"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ stats.hadirPct }}%</span>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Siswa Terlambat</span>
                <div class="flex items-center">
                    <div class="mr-2 h-2 w-16 rounded-full bg-gray-200">
                        <div class="h-2 rounded-full bg-red-500" :style="{ width: stats.terlambatPct + '%' }"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ stats.terlambatPct }}%</span>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Alpha</span>
                <div class="flex items-center">
                    <div class="mr-2 h-2 w-16 rounded-full bg-gray-200">
                        <div class="h-2 rounded-full bg-red-500" :style="{ width: stats.alphaPct + '%' }"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ stats.alphaPct }}%</span>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Izin</span>
                <div class="flex items-center">
                    <div class="mr-2 h-2 w-16 rounded-full bg-gray-200">
                        <div class="h-2 rounded-full bg-yellow-500" :style="{ width: stats.izinPct + '%' }"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ stats.izinPct }}%</span>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Sakit</span>
                <div class="flex items-center">
                    <div class="mr-2 h-2 w-16 rounded-full bg-gray-200">
                        <div class="h-2 rounded-full bg-orange-500" :style="{ width: stats.sakitPct + '%' }"></div>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">{{ stats.sakitPct }}%</span>
                </div>
            </div>

            <div class="mt-6 border-t border-gray-200 pt-4">
                <h3 class="mb-3 text-sm font-semibold text-gray-700">Siswa Sering Terlambat (Semua Waktu)</h3>
                <div class="space-y-2">
                    <div
                        v-for="student in stats.topLateStudents"
                        :key="student.name + '-' + (student.kelas || '')"
                        class="flex items-center justify-between text-sm"
                    >
                        <span class="text-gray-600">{{ student.name }} <span class="text-gray-400">•</span> {{ student.kelas }}</span>
                        <span class="font-medium text-red-500">{{ student.lateCount }}x</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    stats: {
        type: Object,
        required: true,
    },
});
</script>