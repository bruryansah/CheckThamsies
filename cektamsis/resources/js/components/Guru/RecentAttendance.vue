<template>
    <div class="rounded-2xl border border-white/30 bg-white/80 p-6 shadow-lg backdrop-blur-md">
        <h2 class="mb-6 flex items-center text-xl font-bold text-gray-900">
            <svg class="mr-2 h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                ></path>
            </svg>
            Absensi Terbaru
        </h2>
        <div class="space-y-3">
            <div
                v-for="(attendance, index) in recentAttendance"
                :key="index"
                class="flex items-center justify-between rounded-xl bg-gradient-to-r from-gray-50 to-gray-100 p-3 transition-all duration-300 hover:from-green-50 hover:to-green-100"
            >
                <div class="flex items-center space-x-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-r from-blue-400 to-blue-600">
                        <span class="text-xs font-bold text-white">{{
                            (attendance.name || attendance.nama_siswa || 'U').charAt(0)
                        }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ attendance.name || attendance.nama_siswa }}</p>
                        <p class="text-xs text-gray-500">{{ attendance.time || attendance.waktu }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1 rounded-full border px-2.5 py-0.5 text-xs font-semibold" :class="getStatusClass(attendance)">
                    {{ attendance.displayStatus }}
                </span>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    recentAttendance: {
        type: Array,
        required: true,
    },
    getStatusClass: {
        type: Function,
        required: true,
    },
});
</script>
