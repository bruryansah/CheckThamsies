<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
        <header class="sticky top-0 z-50 border-b border-white/20 bg-white/80 shadow-lg backdrop-blur-md">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between py-4">
                    <div class="flex items-center space-x-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-purple-600">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                ></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-xl font-bold text-transparent">
                                Dashboard Guru
                            </h1>
                            <p class="text-sm text-gray-500">Selamat datang, {{ teacherName }}</p>
                        </div>
                    </div>

                    <button
                        @click="logout"
                        class="group relative transform rounded-xl bg-gradient-to-r from-red-500 to-red-600 px-6 py-2.5 font-medium text-white shadow-lg transition-all duration-300 hover:scale-105 hover:from-red-600 hover:to-red-700 hover:shadow-xl"
                    >
                        <div class="flex items-center space-x-2">
                            <svg
                                class="h-4 w-4 transition-transform duration-300 group-hover:rotate-12"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                ></path>
                            </svg>
                            <span>Logout</span>
                        </div>
                    </button>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                <div
                    v-for="(stat, index) in stats"
                    :key="index"
                    class="group transform rounded-2xl border border-white/30 bg-white/80 p-6 shadow-lg backdrop-blur-md transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl"
                    :style="{ animationDelay: `${index * 100}ms` }"
                >
                    <div class="mb-4 flex items-center justify-between">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-xl transition-transform duration-300 group-hover:scale-110"
                            :class="stat.bgColor"
                        >
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="stat.icon"></path>
                            </svg>
                        </div>
                        <span :class="stat.textColor" class="text-sm font-medium">{{ stat.change }}</span>
                    </div>
                    <h3 class="mb-1 text-2xl font-bold text-gray-900">{{ stat.value }}</h3>
                    <p class="text-sm text-gray-600">{{ stat.label }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="space-y-8 lg:col-span-2">
                    <div class="rounded-2xl border border-white/30 bg-white/80 p-6 shadow-lg backdrop-blur-md">
                        <h2 class="mb-6 flex items-center text-xl font-bold text-gray-900">
                            <svg class="mr-2 h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Aksi Cepat
                        </h2>

                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Pilih Kelas:</label>
                            <select
                                v-model="selectedClass"
                                @change="filterScheduleByClass"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 transition-all duration-300 focus:border-transparent focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">Semua Kelas</option>
                                <option v-for="kelas in kelasData" :key="kelas.id" :value="kelas.id">
                                    {{ kelas.nama_kelas }}
                                </option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Pilih Mata Pelajaran untuk QR Code:</label>
                            <select
                                v-model="selectedSubject"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 transition-all duration-300 focus:border-transparent focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">Pilih Mata Pelajaran</option>
                                <option v-for="subject in availableSubjects" :key="subject" :value="subject">
                                    {{ subject }}
                                </option>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <button
                                @click="generateQRCode"
                                :disabled="!selectedSubject"
                                :class="selectedSubject ? 'hover:from-blue-500 hover:to-blue-600' : 'cursor-not-allowed opacity-50'"
                                class="group transform rounded-xl border border-blue-200 bg-gradient-to-r from-blue-50 to-blue-100 p-4 transition-all duration-300 hover:scale-105 hover:border-blue-500"
                            >
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-500 transition-colors duration-300 group-hover:bg-white"
                                    >
                                        <svg
                                            class="h-5 w-5 text-white group-hover:text-blue-500"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M12 12h4.01M12 12v4.01M12 12V8.01"
                                            ></path>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-semibold text-blue-900 group-hover:text-white">Generate QR Absen</p>
                                        <p class="text-sm text-blue-600 group-hover:text-blue-100">Buat kode QR untuk absen</p>
                                    </div>
                                </div>
                            </button>

                            <button
                                @click="showAttendanceModal = true"
                                class="group transform rounded-xl border border-green-200 bg-gradient-to-r from-green-50 to-green-100 p-4 transition-all duration-300 hover:scale-105 hover:border-green-500 hover:from-green-500 hover:to-green-600"
                            >
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-500 transition-colors duration-300 group-hover:bg-white"
                                    >
                                        <svg
                                            class="h-5 w-5 text-white group-hover:text-green-500"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"
                                            ></path>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-semibold text-green-900 group-hover:text-white">Lihat Absensi</p>
                                        <p class="text-sm text-green-600 group-hover:text-green-100">Cek kehadiran siswa</p>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>

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
                                    v-model="selectedDate"
                                    @change="filterSchedule"
                                    class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:border-transparent focus:ring-2 focus:ring-purple-500"
                                >
                                    <option value="">Tanggal</option>
                                    <option v-for="date in availableDates" :key="date" :value="date">
                                        {{ formatDate(date) }}
                                    </option>
                                </select>

                                <select
                                    v-model="selectedMonth"
                                    @change="filterSchedule"
                                    class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:border-transparent focus:ring-2 focus:ring-purple-500"
                                >
                                    <option value="">Bulan</option>
                                    <option v-for="(month, index) in months" :key="index" :value="index + 1">
                                        {{ month }}
                                    </option>
                                </select>

                                <select
                                    v-model="selectedYear"
                                    @change="filterSchedule"
                                    class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:border-transparent focus:ring-2 focus:ring-purple-500"
                                >
                                    <option value="">Tahun</option>
                                    <option v-for="year in availableYears" :key="year" :value="year">
                                        {{ year }}
                                    </option>
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
                                        <h3 class="font-semibold text-gray-900">{{ schedule.mata_pelajaran }}</h3>
                                        <span class="rounded-full bg-purple-100 px-3 py-1 text-sm font-medium text-purple-700">
                                            {{ schedule.nama_kelas }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600">{{ schedule.jam_mulai }} - {{ schedule.jam_selesai }}</p>
                                    <p class="mt-1 text-xs text-gray-500">{{ schedule.ruangan }} • {{ formatScheduleDate(schedule.tanggal) }}</p>
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
                </div>

                <div class="space-y-8">
                    <div v-if="qrCodeData" class="rounded-2xl border border-white/30 bg-white/80 p-6 shadow-lg backdrop-blur-md">
                        <h2 class="mb-4 flex items-center text-xl font-bold text-gray-900">
                            <svg class="mr-2 h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M12 12h4.01M12 12v4.01M12 12V8.01"
                                ></path>
                            </svg>
                            QR Code Absensi
                        </h2>
                        <div class="text-center">
                            <div class="mb-4 inline-block rounded-2xl bg-white p-4 shadow-inner">
                                <div
                                    id="qrcode"
                                    class="mx-auto flex h-48 w-48 items-center justify-center rounded-xl border-4 border-dashed border-gray-300 bg-gray-50"
                                >
                                    <p class="text-sm text-gray-500">QR Code akan muncul di sini</p>
                                </div>
                            </div>
                            <p class="mb-2 text-sm text-gray-600">{{ qrCodeData.subject }} - {{ qrCodeData.class }}</p>
                            <p class="text-xs text-gray-500">Berlaku: {{ qrCodeData.validUntil }}</p>
                            <button
                                @click="refreshQRCode"
                                class="mt-4 rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white transition-colors duration-300 hover:bg-blue-600"
                            >
                                Refresh QR Code
                            </button>
                        </div>
                    </div>

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
                                        <span class="text-xs font-bold text-white">{{ attendance.name.charAt(0) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ attendance.name }}</p>
                                        <p class="text-xs text-gray-500">{{ attendance.time }}</p>
                                    </div>
                                </div>
                                <span
                                    class="rounded-full px-2 py-1 text-xs font-medium"
                                    :class="
                                        attendance.status === 'Hadir'
                                            ? 'bg-green-100 text-green-800'
                                            : attendance.status === 'Terlambat'
                                              ? 'bg-yellow-100 text-yellow-800'
                                              : 'bg-red-100 text-red-800'
                                    "
                                >
                                    {{ attendance.status }}
                                </span>
                            </div>
                        </div>
                    </div>

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
                            Statistik Siswa
                        </h2>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Kehadiran Hari Ini</span>
                                <div class="flex items-center">
                                    <div class="mr-2 h-2 w-16 rounded-full bg-gray-200">
                                        <div class="h-2 rounded-full bg-green-500" style="width: 85%"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">85%</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Siswa Terlambat</span>
                                <div class="flex items-center">
                                    <div class="mr-2 h-2 w-16 rounded-full bg-gray-200">
                                        <div class="h-2 rounded-full bg-yellow-500" style="width: 12%"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">12%</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Tidak Hadir</span>
                                <div class="flex items-center">
                                    <div class="mr-2 h-2 w-16 rounded-full bg-gray-200">
                                        <div class="h-2 rounded-full bg-red-500" style="width: 3%"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">3%</span>
                                </div>
                            </div>

                            <div class="mt-6 border-t border-gray-200 pt-4">
                                <h3 class="mb-3 text-sm font-semibold text-gray-700">Siswa Sering Terlambat</h3>
                                <div class="space-y-2">
                                    <div v-for="student in topLateStudents" :key="student.id" class="flex items-center justify-between text-sm">
                                        <span class="text-gray-600">{{ student.name }}</span>
                                        <span class="font-medium text-red-500">{{ student.lateCount }}x</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <div
            v-if="showAttendanceModal"
            class="bg-opacity-50 fixed inset-0 z-50 flex items-center justify-center bg-black p-4 backdrop-blur-sm"
            @click="showAttendanceModal = false"
        >
            <div class="max-h-[80vh] w-full max-w-6xl overflow-y-auto rounded-2xl bg-white p-6" @click.stop>
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-900">Data Absensi</h2>
                    <button
                        @click="showAttendanceModal = false"
                        class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 transition-colors duration-300 hover:bg-gray-200"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <button @click="exportToPDF" class="rounded-lg bg-red-500 px-4 py-2 text-white shadow transition hover:bg-red-600">
                        Export PDF
                    </button>
                </div>

                <div class="mb-6 flex flex-wrap gap-4">
                    <select
                        v-model="attendanceFilter.class"
                        @change="filterAttendanceData"
                        class="rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">Semua Kelas</option>
                        <option v-for="kelas in kelasData" :key="kelas.id" :value="kelas.nama_kelas">
                            {{ kelas.nama_kelas }}
                        </option>
                    </select>

                    <select
                        v-model="attendanceFilter.subject"
                        @change="filterAttendanceData"
                        class="rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">Semua Mata Pelajaran</option>
                        <option v-for="subject in availableSubjects" :key="subject" :value="subject">
                            {{ subject }}
                        </option>
                    </select>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Nama Siswa</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Kelas</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Mata Pelajaran</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Tanggal</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Waktu</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(student, index) in filteredAttendanceData"
                                :key="index"
                                class="border-b transition-colors duration-200 hover:bg-gray-50"
                            >
                                <td class="px-4 py-3">{{ student.name }}</td>
                                <td class="px-4 py-3">{{ student.class }}</td>
                                <td class="px-4 py-3">{{ student.subject }}</td>
                                <td class="px-4 py-3">{{ student.date }}</td>
                                <td class="px-4 py-3">{{ student.time }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="rounded-full px-3 py-1 text-xs font-medium"
                                        :class="
                                            student.status === 'Hadir'
                                                ? 'bg-green-100 text-green-800'
                                                : student.status === 'Terlambat'
                                                  ? 'bg-yellow-100 text-yellow-800'
                                                  : 'bg-red-100 text-red-800'
                                        "
                                    >
                                        {{ student.status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router, usePage } from '@inertiajs/vue3';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';
import QRCode from 'qrcode';
import { computed, onMounted, ref } from 'vue';

// Reactive data
const { props } = usePage();
const allAttendanceData = ref(props.absensiData ?? []);
const filteredAttendanceData = ref([...allAttendanceData.value]);
const teacherName = ref(props.guru?.nama ?? 'Guru');
const showAttendanceModal = ref(false);
const jadwalData = ref(props.jadwalData ?? []);
const selectedClass = ref('');
const selectedSubject = ref('');
const selectedDate = ref('');
const selectedMonth = ref('');
const selectedYear = ref('');
const attendanceFilter = ref({
    class: '',
    subject: '',
});

const refreshQRCode = async () => {
    if (!qrCodeData.value) {
        alert('Belum ada QR Code untuk direfresh. Silakan generate dulu.');
        return;
    }

    const confirmRefresh = confirm('Apakah anda yakin untuk merefresh QR Code?');
    if (!confirmRefresh) return;

    await generateQRCode(); // panggil ulang generator
};

const generateQRCode = async () => {
    if (!selectedSubject.value || !selectedClass.value) {
        alert('Pilih kelas dan mata pelajaran dulu!');
        return;
    }

    const currentTime = new Date();
    const validUntil = new Date(currentTime.getTime() + 10 * 60000); // 10 menit

    const subjectSchedule = jadwalData.value.find((schedule) => schedule.mata_pelajaran === selectedSubject.value);

    qrCodeData.value = {
        subject: selectedSubject.value,
        class: subjectSchedule ? subjectSchedule.nama_kelas : selectedClass.value,
        timestamp: currentTime.getTime(),
        validUntil: validUntil.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
        }),
        teacherId: props.guru?.id ?? 'GURU001',
        sessionId: `QR_${selectedSubject.value}_${Date.now()}`,
    };

    // Generate QR beneran
    qrImage.value = await QRCode.toDataURL(qrCodeData.value.sessionId, {
        width: 200,
        margin: 2,
        color: { dark: '#000000', light: '#ffffff' },
    });

    const qrDiv = document.getElementById('qrcode');
    qrDiv.innerHTML = '';
    const img = document.createElement('img');
    img.src = qrImage.value;
    img.className = 'h-48 w-48';
    qrDiv.appendChild(img);
};

const qrCodeData = ref(null);
const qrImage = ref(null);
const selectedJadwal = ref(null);

const exportToPDF = () => {
    const doc = new jsPDF();

    doc.setFontSize(14);
    doc.text('Laporan Absensi Siswa', 14, 15);
    doc.setFontSize(10);
    doc.text(`Kelas: ${selectedClass.value || 'Semua'}`, 14, 22);
    doc.text(`Tanggal: ${selectedDate.value || 'Semua'}`, 14, 28);

    const tableData = (filteredAttendanceData.value || []).map((a, index) => [
        index + 1,
        a.name || '-',
        a.class || '-',
        a.subject || '-',
        a.date || '-',
        a.status || '-',
    ]);

    if (tableData.length === 0) {
        doc.text('Tidak ada data absensi untuk filter ini.', 14, 40);
    } else {
        autoTable(doc, {
            startY: 35,
            head: [['No', 'Nama Siswa', 'Kelas', 'Mata Pelajaran', 'Tanggal', 'Status']],
            body: tableData,
            theme: 'grid',
            styles: { fontSize: 9, cellPadding: 3 },
            headStyles: { fillColor: [41, 128, 185] },
        });
    }

    doc.save(`Absensi_${selectedClass.value || 'Semua'}_${Date.now()}.pdf`);
};

const months = ref(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']);

const kelasData = ref(props.kelasData ?? []);

const stats = ref([
    {
        label: 'Total Siswa',
        value: '190',
        change: '+12%',
        icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
        bgColor: 'bg-gradient-to-r from-blue-500 to-blue-600',
        textColor: 'text-green-600',
    },
    {
        label: 'Hadir Hari Ini',
        value: '170',
        change: '+8%',
        icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        bgColor: 'bg-gradient-to-r from-green-500 to-green-600',
        textColor: 'text-green-600',
    },
    {
        label: 'Total Kelas',
        value: '6',
        change: 'Aktif',
        icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
        bgColor: 'bg-gradient-to-r from-purple-500 to-purple-600',
        textColor: 'text-blue-600',
    },
    {
        label: 'Mata Pelajaran',
        value: '4',
        change: 'Aktif',
        icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
        bgColor: 'bg-gradient-to-r from-orange-500 to-orange-600',
        textColor: 'text-green-600',
    },
]);

const recentAttendance = ref([
    { name: 'Ahmad Rizki', time: '07:05', status: 'Hadir' },
    { name: 'Siti Nurhaliza', time: '07:03', status: 'Hadir' },
    { name: 'Budi Setiawan', time: '07:15', status: 'Terlambat' },
    { name: 'Dewi Kartika', time: '07:02', status: 'Hadir' },
    { name: 'Eko Prasetyo', time: '07:08', status: 'Hadir' },
]);

const topLateStudents = ref([
    { id: 1, name: 'Budi Setiawan', lateCount: 5 },
    { id: 2, name: 'Andi Pratama', lateCount: 4 },
    { id: 3, name: 'Rini Kartika', lateCount: 3 },
]);

const availableSubjects = computed(() => {
    if (!jadwalData.value) return [];
    return [...new Set(jadwalData.value.map((item) => item.mata_pelajaran))];
});

const availableDates = computed(() => {
    if (!jadwalData.value) return [];
    return [...new Set(jadwalData.value.map((item) => item.tanggal))].sort();
});

const availableYears = computed(() => {
    if (!jadwalData.value) return [];
    return [...new Set(jadwalData.value.map((item) => new Date(item.tanggal).getFullYear()))].sort();
});

const filteredSchedule = computed(() => {
    let filtered = jadwalData.value ?? [];

    if (selectedClass.value) {
        filtered = filtered.filter((item) => item.kelas_id == selectedClass.value);
    }
    if (selectedDate.value) {
        filtered = filtered.filter((item) => item.tanggal === selectedDate.value);
    }
    if (selectedMonth.value) {
        filtered = filtered.filter((item) => {
            const itemMonth = new Date(item.tanggal).getMonth() + 1;
            return itemMonth == selectedMonth.value;
        });
    }
    if (selectedYear.value) {
        filtered = filtered.filter((item) => {
            const itemYear = new Date(item.tanggal).getFullYear();
            return itemYear == selectedYear.value;
        });
    }

    return filtered.sort((a, b) => {
        const dateA = new Date(a.tanggal + ' ' + a.jam_mulai);
        const dateB = new Date(b.tanggal + ' ' + b.jam_mulai);
        return dateA - dateB;
    });
});

const logout = () => {
    router.post(route('logout'));
};

const filterSchedule = () => {
    console.log('Filtering schedule...');
};

const filterScheduleByClass = () => {
    console.log('Filtering by class:', selectedClass.value);
};

const filterAttendanceData = () => {
    let filtered = allAttendanceData.value;

    if (attendanceFilter.value.class) {
        filtered = filtered.filter((item) => item.class === attendanceFilter.value.class);
    }

    if (attendanceFilter.value.subject) {
        filtered = filtered.filter((item) => item.subject === attendanceFilter.value.subject);
    }

    filteredAttendanceData.value = filtered;
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    });
};

const formatScheduleDate = (dateString) => {
    const date = new Date(dateString);
    const today = new Date();
    const isToday = date.toDateString() === today.toDateString();

    if (isToday) {
        return 'Hari ini';
    } else {
        return date.toLocaleDateString('id-ID', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
        });
    }
};

let qrInterval = null;

onMounted(() => {
    const cards = document.querySelectorAll('.group');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';

        setTimeout(() => {
            card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    qrInterval = setInterval(
        () => {
            if (qrCodeData.value) {
                refreshQRCode();
            }
        },
        5 * 60 * 1000,
    );
});
</script>

<style scoped>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.8;
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.group {
    animation: fadeInUp 0.6s ease-out forwards;
}

.group:nth-child(odd) {
    animation-delay: 0.1s;
}

.group:nth-child(even) {
    animation-delay: 0.2s;
}

.group:hover {
    transform: translateY(-4px) scale(1.02);
}

.backdrop-blur-md {
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}

.bg-green-500,
.bg-blue-500,
.bg-purple-500,
.bg-yellow-500,
.bg-red-500 {
    animation: pulse 2s infinite;
    transition: width 1s ease-in-out;
}

#qrcode {
    transition: all 0.3s ease-in-out;
}

#qrcode:hover {
    transform: scale(1.05);
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.fixed.inset-0 > div {
    animation: modalSlideIn 0.3s ease-out;
}

select {
    transition: all 0.3s ease;
    color: #000;
}

select:focus {
    transform: scale(1.02);
}

@media (max-width: 640px) {
    .grid-cols-1.sm\\:grid-cols-2 {
        gap: 3rem;
    }

    .text-2xl {
        font-size: 1.5rem;
    }

    .p-6 {
        padding: 1rem;
    }

    .flex-wrap {
        gap: 0.5rem;
    }
}

@media (max-width: 768px) {
    .flex-col.sm\\:flex-row {
        align-items: flex-start;
    }

    .gap-4 {
        gap: 0.75rem;
    }
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes gradientShift {
    0%,
    100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

.bg-gradient-to-r {
    background-size: 200% 200%;
    animation: gradientShift 4s ease infinite;
}
td {
    color: #000;
}
tbody tr:hover {
    transform: translateX(4px);
}

.space-y-4 > div {
    transition: all 0.3s ease;
}

.space-y-4 > div:hover {
    transform: translateX(4px);
    background-color: rgba(59, 130, 246, 0.05);
    border-radius: 0.5rem;
    padding: 0.5rem;
}

select:hover {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
