<template>
    <div
        v-if="show"
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black bg-opacity-50 p-0 sm:p-4 backdrop-blur-sm"
        @click="$emit('close')"
    >
        <div 
            class="w-full h-[95vh] sm:h-auto sm:max-h-[85vh] sm:max-w-6xl bg-white sm:rounded-2xl rounded-t-3xl overflow-hidden flex flex-col shadow-2xl" 
            @click.stop
        >
            <!-- Header dengan gradient modern -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-4 sm:px-6 py-4 sm:py-5 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-white">Data Absensi</h2>
                        <p class="text-blue-100 text-xs sm:text-sm mt-1">Kelola data kehadiran siswa</p>
                    </div>
                    <button
                        @click="$emit('close')"
                        class="flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-full bg-white shadow-lg transition-all duration-300 hover:bg-gray-100 active:scale-95"
                    >
                        <svg class="h-5 w-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Filter Section dengan design card -->
            <div class="px-4 sm:px-6 py-4 bg-gray-50 border-b flex-shrink-0 overflow-x-auto">
                <div class="flex flex-col sm:flex-row sm:flex-wrap gap-2 sm:gap-3">
                    <select
                        :value="filter.class"
                        @change="$emit('update:filter', { ...filter, class: $event.target.value })"
                        class="w-full sm:w-auto flex-1 sm:flex-initial rounded-xl border-2 border-gray-200 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                    >
                        <option value="" class="text-gray-900">📚 Semua Kelas</option>
                        <option v-for="kelas in kelasData" :key="kelas.id" :value="kelas.nama_kelas" class="text-gray-900">
                            {{ kelas.nama_kelas }}
                        </option>
                    </select>

                    <select
                        :value="filter.subject"
                        @change="$emit('update:filter', { ...filter, subject: $event.target.value })"
                        class="w-full sm:w-auto flex-1 sm:flex-initial rounded-xl border-2 border-gray-200 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                    >
                        <option value="" class="text-gray-900">📖 Semua Mata Pelajaran</option>
                        <option v-for="subject in availableSubjects" :key="subject" :value="subject" class="text-gray-900">
                            {{ subject }}
                        </option>
                    </select>

                    <input
                        type="date"
                        :value="filter.date"
                        @change="$emit('update:filter', { ...filter, date: $event.target.value })"
                        class="w-full sm:w-auto flex-1 sm:flex-initial rounded-xl border-2 border-gray-200 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all bg-white"
                    />

                    <div class="flex gap-2 w-full sm:w-auto">
                        <button
                            v-if="filter.date || filter.class || filter.subject"
                            @click="$emit('resetFilter')"
                            class="flex-1 sm:flex-initial rounded-xl bg-gray-600 px-4 py-2.5 text-sm text-white transition-all hover:bg-gray-700 active:scale-95 font-medium"
                        >
                            🔄 Reset
                        </button>
                        
                        <button 
                            @click="$emit('export')" 
                            class="flex-1 sm:flex-initial rounded-xl bg-gradient-to-r from-red-500 to-red-600 px-4 py-2.5 text-sm text-white shadow-lg transition-all hover:shadow-xl hover:from-red-600 hover:to-red-700 active:scale-95 font-medium"
                        >
                            📄 Export PDF
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table Container - Scrollable -->
            <div class="flex-1 overflow-auto">
                <!-- Desktop View -->
                <div class="hidden md:block">
                    <table class="w-full">
                        <thead>
                            <tr class="sticky top-0 z-10 bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Siswa</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kelas</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Mata Pelajaran</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Hari</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Lantai</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Ruang</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Waktu</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr
                                v-for="(student, index) in attendanceData"
                                :key="index"
                                class="hover:bg-blue-50 transition-colors duration-200"
                            >
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ student.name || student.nama_siswa }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ student.class || student.nama_kelas }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ student.subject || student.mata_pelajaran }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ formatHari(student.hari) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ student.lantai }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ student.ruang }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ student.date || student.tanggal }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ student.time || student.waktu }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold"
                                        :class="getStatusClass(processStatus(student))"
                                    >
                                        {{ processStatus(student).displayStatus }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ student.keterangan || '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile View - Card Layout -->
                <div class="md:hidden p-4 space-y-3">
                    <div
                        v-for="(student, index) in attendanceData"
                        :key="index"
                        class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300"
                    >
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-3">
                            <h3 class="font-bold text-white text-base">{{ student.name || student.nama_siswa }}</h3>
                            <p class="text-blue-100 text-xs mt-0.5">{{ student.class || student.nama_kelas }}</p>
                        </div>
                        
                        <div class="p-4 space-y-2.5">
                            <div class="flex items-start">
                                <span class="text-gray-500 text-xs font-medium w-28 flex-shrink-0">Mata Pelajaran:</span>
                                <span class="text-gray-900 text-sm font-medium">{{ student.subject || student.mata_pelajaran }}</span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-2.5">
                                <div>
                                    <span class="text-gray-500 text-xs font-medium block mb-1">Hari</span>
                                    <span class="text-gray-900 text-sm">{{ formatHari(student.hari) }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 text-xs font-medium block mb-1">Tanggal</span>
                                    <span class="text-gray-900 text-sm">{{ student.date || student.tanggal }}</span>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-2.5">
                                <div>
                                    <span class="text-gray-500 text-xs font-medium block mb-1">Lantai</span>
                                    <span class="text-gray-900 text-sm font-medium">{{ student.lantai }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 text-xs font-medium block mb-1">Ruang</span>
                                    <span class="text-gray-900 text-sm font-medium">{{ student.ruang }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500 text-xs font-medium block mb-1">Waktu</span>
                                    <span class="text-gray-900 text-sm">{{ student.time || student.waktu }}</span>
                                </div>
                            </div>
                            
                            <div class="pt-2 border-t border-gray-100">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-500 text-xs font-medium">Status:</span>
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold"
                                        :class="getStatusClass(processStatus(student))"
                                    >
                                        {{ processStatus(student).displayStatus }}
                                    </span>
                                </div>
                                
                                <div v-if="student.keterangan" class="mt-2">
                                    <span class="text-gray-500 text-xs font-medium block mb-1">Keterangan:</span>
                                    <span class="text-gray-700 text-sm">{{ student.keterangan }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="attendanceData.length === 0" class="py-12 px-4 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"
                            ></path>
                        </svg>
                    </div>
                    <h3 class="text-gray-900 font-semibold text-base mb-1">Tidak Ada Data</h3>
                    <p class="text-gray-500 text-sm max-w-sm mx-auto">Tidak ada data absensi untuk kelas dan mata pelajaran yang Anda ajar</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    show: Boolean,
    attendanceData: Array,
    filter: Object,
    kelasData: Array,
    availableSubjects: Array,
    formatHari: Function,
    processStatus: Function,
    getStatusClass: Function,
});

defineEmits(['close', 'export', 'update:filter', 'resetFilter']);
</script>