<template>
    <div
        v-if="show"
        class="bg-opacity-50 fixed inset-0 z-50 flex items-center justify-center bg-black p-4 backdrop-blur-sm"
        @click="$emit('close')"
    >
        <div class="max-h-[80vh] w-full max-w-6xl overflow-y-auto rounded-2xl bg-white p-6" @click.stop>
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-900">Data Absensi</h2>
                <div class="flex items-center space-x-4">
                    <button @click="$emit('export')" class="rounded-lg bg-red-500 px-4 py-2 text-white shadow transition hover:bg-red-600">
                        Export PDF
                    </button>
                    <button
                        @click="$emit('close')"
                        class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 transition-colors duration-300 hover:bg-gray-200"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="mb-6 flex flex-wrap gap-4 text-gray-900">
                <select
                    :value="filter.class"
                    @change="$emit('update:filter', { ...filter, class: $event.target.value })"
                    class="rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">Semua Kelas</option>
                    <option v-for="kelas in kelasData" :key="kelas.id" :value="kelas.nama_kelas">
                        {{ kelas.nama_kelas }}
                    </option>
                </select>

                <select
                    :value="filter.subject"
                    @change="$emit('update:filter', { ...filter, subject: $event.target.value })"
                    class="rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">Semua Mata Pelajaran</option>
                    <option v-for="subject in availableSubjects" :key="subject" :value="subject">
                        {{ subject }}
                    </option>
                </select>

                <input
                    type="date"
                    :value="filter.date"
                    @change="$emit('update:filter', { ...filter, date: $event.target.value })"
                    class="rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500"
                    placeholder="Filter Tanggal"
                />

                <button
                    v-if="filter.date || filter.class || filter.subject"
                    @click="$emit('resetFilter')"
                    class="rounded-lg bg-gray-500 px-4 py-2 text-white transition hover:bg-gray-600"
                >
                    Reset Filter
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="sticky top-0 z-10 border-b bg-gray-50">
                            <th class="px-4 py-3 text-left font-semibold text-gray-900">Nama Siswa</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-900">Kelas</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-900">Mata Pelajaran</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-900">Hari</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-900">Lantai</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-900">Ruang</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-900">Tanggal</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-900">Waktu</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-900">Status</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-900">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(student, index) in attendanceData"
                            :key="index"
                            class="odd:bg-white even:bg-gray-50 border-b transition-colors duration-200 hover:bg-gray-100"
                        >
                            <td  class="px-4 py-3 text-gray-900">{{ student.name || student.nama_siswa }}</td>
                            <td  class="px-4 py-3 text-gray-900">{{ student.class || student.nama_kelas }}</td>
                            <td class="px-4 py-3 text-gray-900">{{ student.subject || student.mata_pelajaran }}</td>
                            <td  class="px-4 py-3 text-gray-900">{{ formatHari(student.hari) }}</td>
                            <td  class="px-4 py-3 text-gray-900">{{ student.lantai }}</td>
                            <td  class="px-4 py-3 text-gray-900">{{ student.ruang }}</td>
                            <td  class="px-4 py-3 text-gray-900">{{ student.date || student.tanggal }}</td>
                            <td class="px-4 py-3 text-gray-900">{{ student.time || student.waktu }}</td>
                            <td  class="px-4 py-3 text-gray-900">
                                <span
                                    class="inline-flex items-center gap-1 rounded-full border px-2.5 py-0.5 text-xs font-semibold"
                                    :class="getStatusClass(processStatus(student))"
                                >
                                    {{ processStatus(student).displayStatus }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-700">{{ student.keterangan || '-' }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div v-if="attendanceData.length === 0" class="py-8 text-center">
                    <svg class="mx-auto mb-4 h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"
                        ></path>
                    </svg>
                    <p class="text-gray-500">Tidak ada data absensi untuk kelas dan mata pelajaran yang Anda ajar</p>
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
