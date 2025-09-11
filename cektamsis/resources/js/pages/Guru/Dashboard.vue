<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
        <header
            class="fixed top-0 z-50 w-full border-b border-white/20 bg-white/80 shadow-lg backdrop-blur-md transition-all duration-500 ease-in-out"
            :class="isNavbarVisible ? 'translate-y-0 opacity-100' : '-translate-y-full opacity-0'"
        >
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

                    <!-- Settings Dropdown -->
                    <div class="dropdown-container relative">
                        <button @click="toggleDropdown"
                            class="flex items-center gap-2 rounded-xl bg-white/80 px-4 py-2 font-medium text-gray-700 shadow-md backdrop-blur-md hover:bg-white/90 transition-all duration-300">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg> 
                            Pengaturan
                            <svg class="h-4 w-4 transition-transform duration-300" :class="{ 'rotate-180': dropdownOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <transition name="dropdown">
                            <div v-if="dropdownOpen"
                                class="absolute top-full right-0 z-10 mt-2 w-48 rounded-xl border border-gray-200 bg-white shadow-xl backdrop-blur-md">
                                <div class="p-2">
                                    <button
                                        @click="showChangePasswordModal = true; closeDropdown();"
                                        class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        Ubah Password
                                    </button>
                                    <button @click="logout"
                                        class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left text-red-600 hover:bg-red-50 transition-colors duration-200">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Logout
                                    </button>
                                </div>
                            </div>
                        </transition>
                    </div>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-8 pt-25 sm:px-6 lg:px-8">
            <!-- Stats cards remain the same -->
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

            <!-- Rest of the dashboard content remains the same -->
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="space-y-8 lg:col-span-2">
                    <!-- Quick Actions Card -->
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
                                v-model="selectedJadwal"
                                @change="onJadwalChange"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 transition-all duration-300 focus:border-transparent focus:ring-2 focus:ring-blue-500"
                            >
                                <option disabled value="">-- Pilih Jadwal --</option>
                                
                                <option v-for="j in localJadwalData" :key="j.id_jadwal" :value="j.idenc">
                                    {{ j.mata_pelajaran }} - {{ j.nama_kelas }} 
                                    ({{ formatScheduleDate(j.tanggal) }} {{ j.jam_mulai }} - {{ j.jam_selesai }})
                                </option>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <button
                                @click="generateQRCode"
                                :disabled="!selectedJadwal || isGeneratingQR"
                                :class="selectedJadwal && !isGeneratingQR ? 'hover:from-blue-500 hover:to-blue-600' : 'cursor-not-allowed opacity-50'"
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
                                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M12 12v4.01M12 12V8.01"
                                            ></path>
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
                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"
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

                    <!-- Schedule Card -->
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

                <!-- Right Sidebar -->
                <div class="space-y-8">
                    <!-- QR Code Card -->
                    <div v-if="qrImage" class="rounded-2xl border border-white/30 bg-white/80 p-6 shadow-lg backdrop-blur-md">
                        <h2 class="mb-4 flex items-center text-xl font-bold text-gray-900">
                            <svg class="mr-2 h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M12 12v4.01M12 12V8.01"
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
                                    <!-- image injected by generateQRCode -->
                                </div>
                            </div>
                            <p class="mb-2 text-sm text-gray-600">
                                {{ selectedJadwalLabel }}
                            </p>
                            <p class="text-xs text-gray-500">Siswa dapat memindai QR code ini untuk melakukan absensi</p>
                            <button
                                @click="refreshQRCode"
                                class="mt-4 rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white transition-colors duration-300 hover:bg-blue-600"
                            >
                                Refresh QR Code
                            </button>
                        </div>
                    </div>

                    <!-- Recent Attendance -->
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
                                <span class="rounded-full px-2 py-1 text-xs font-medium" :class="getAttendanceStatusClass(attendance)">
                                    {{ attendance.status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Card -->
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
                                        <div class="h-2 rounded-full bg-green-500" :style="{ width: attendanceStats.hadirPct + '%' }"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">{{ attendanceStats.hadirPct }}%</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Siswa Terlambat</span>
                                <div class="flex items-center">
                                    <div class="mr-2 h-2 w-16 rounded-full bg-gray-200">
                                        <div class="h-2 rounded-full bg-red-500" :style="{ width: attendanceStats.terlambatPct + '%' }"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">{{ attendanceStats.terlambatPct }}%</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Alpha/Izin/Sakit</span>
                                <div class="flex items-center">
                                    <div class="mr-2 h-2 w-16 rounded-full bg-gray-200">
                                        <div class="h-2 rounded-full bg-yellow-500" :style="{ width: attendanceStats.alphaIzinSakitPct + '%' }"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900">{{ attendanceStats.alphaIzinSakitPct }}%</span>
                                </div>
                            </div>

                            <div class="mt-6 border-t border-gray-200 pt-4">
                                <h3 class="mb-3 text-sm font-semibold text-gray-700">Siswa Sering Terlambat</h3>
                                <div class="space-y-2">
                                    <div
                                        v-for="student in attendanceStats.topLateStudents"
                                        :key="student.name"
                                        class="flex items-center justify-between text-sm"
                                    >
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

        <!-- Attendance Modal -->
        <div
            v-if="showAttendanceModal"
            class="bg-opacity-50 fixed inset-0 z-50 flex items-center justify-center bg-black p-4 backdrop-blur-sm"
            @click="showAttendanceModal = false"
        >
            <div class="max-h-[80vh] w-full max-w-6xl overflow-y-auto rounded-2xl bg-white p-6" @click.stop>
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-900">Data Absensi</h2>
                    <div class="flex items-center space-x-4">
                        <button @click="exportToPDF" class="rounded-lg bg-red-500 px-4 py-2 text-white shadow transition hover:bg-red-600">
                            Export PDF
                        </button>
                        <button
                            @click="showAttendanceModal = false"
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 transition-colors duration-300 hover:bg-gray-200"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
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
                                    <span class="rounded-full px-3 py-1 text-xs font-medium" :class="getAttendanceStatusClass(student)">
                                        {{ student.status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Change Password Modal -->
        <div v-if="showChangePasswordModal"
            class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
            @click.self="closePasswordModal">
            <div class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white shadow-2xl" @click.stop>
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <h2 class="text-lg font-semibold text-gray-900">Ubah Password</h2>
                    </div>
                    <button @click="closePasswordModal" type="button"
                        class="text-gray-400 transition-colors hover:text-gray-600">✕</button>
                </div>
                <form @submit.prevent="submitPasswordChange" class="p-6 space-y-4">
                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password Saat Ini
                        </label>
                        <input id="current_password" type="password" v-model="passwordForm.current_password"
                            class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 placeholder-gray-400
                   focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            :class="{ 'border-red-500': passwordErrors.current_password }"
                            placeholder="Masukkan password saat ini" autocomplete="current-password" required />
                        <p v-if="passwordErrors.current_password" class="text-red-500 text-sm mt-1">
                            {{ passwordErrors.current_password[0] }}
                        </p>
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password Baru
                        </label>
                        <input id="new_password" type="password" v-model="passwordForm.new_password"
                            class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 placeholder-gray-400
                   focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            :class="{ 'border-red-500': passwordErrors.new_password }"
                            placeholder="Masukkan password baru (min. 8 karakter)" autocomplete="new-password" required />
                        <p v-if="passwordErrors.new_password" class="text-red-500 text-sm mt-1">
                            {{ passwordErrors.new_password[0] }}
                        </p>
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password Baru
                        </label>
                        <input id="new_password_confirmation" type="password"
                            v-model="passwordForm.new_password_confirmation"
                            class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 placeholder-gray-400
                   focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            :class="{ 'border-red-500': passwordErrors.new_password_confirmation }"
                            placeholder="Konfirmasi password baru" autocomplete="new-password" required />
                        <p v-if="passwordErrors.new_password_confirmation" class="text-red-500 text-sm mt-1">
                            {{ passwordErrors.new_password_confirmation[0] }}
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="closePasswordModal"
                            class="flex-1 rounded-xl bg-gray-500 py-3 text-white font-medium transition-colors hover:bg-gray-600">
                            Batal
                        </button>
                        <button type="submit" :disabled="processingPassword"
                            class="flex-1 rounded-xl bg-blue-600 py-3 text-white font-medium transition-colors hover:bg-blue-700 disabled:bg-blue-400">
                            <span v-if="processingPassword">Mengubah...</span>
                            <span v-else>Ubah Password</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div v-if="showConfirmationModal"
            class="fixed inset-0 z-[70] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
            @click.self="closeConfirmModal">
            <div class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white shadow-2xl" @click.stop>
                <div class="p-6">
                    <div class="flex items-center justify-center mb-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Konfirmasi</h3>
                    <p class="text-gray-600 text-center mb-6">{{ confirmationMessage }}</p>
                    <div class="flex gap-3">
                        <button @click="closeConfirmModal"
                            class="flex-1 rounded-xl bg-gray-500 py-3 text-white font-medium transition-colors hover:bg-gray-600">
                            Batal
                        </button>
                        <button @click="confirmAction"
                            class="flex-1 rounded-xl bg-blue-600 py-3 text-white font-medium transition-colors hover:bg-blue-700">
                            Ya, Lanjutkan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast Notification -->
        <transition name="fade-scale">
            <div v-if="showToast"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
                <div class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-2xl">
                    <!-- Success Animation -->
                    <div v-if="toastType === 'success'" class="mx-auto mb-4 h-16 w-16">
                        <svg class="mx-auto h-16 w-16" viewBox="0 0 52 52">
                            <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none" />
                            <path class="checkmark-check" fill="none" d="M14 27l7 7 16-16" />
                        </svg>
                    </div>

                    <!-- Error Icon -->
                    <div v-else
                        class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-100">
                        <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>

                    <!-- Title -->
                    <h2 class="mb-2 text-xl font-semibold text-gray-900">
                        {{ toastType === 'success' ? 'Berhasil' : 'Gagal' }}
                    </h2>

                    <!-- Message -->
                    <p class="mb-6 text-gray-600">{{ toastMessage }}</p>

                    <!-- Action -->
                    <button @click="showToast = false"
                        class="w-full rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 px-4 py-2 font-medium text-white shadow hover:from-blue-700 hover:to-purple-700">
                        Tutup
                    </button>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';
import QRCode from 'qrcode';
import { computed, nextTick, onMounted, onUnmounted, ref } from 'vue';

// Props
const props = defineProps({
    auth: Object,
    flash: Object,
    guru: Object,
    jadwalData: { type: Array, default: () => [] },
    absensiData: { type: Array, default: () => [] },
    kelasData: { type: Array, default: () => [] },
});

// Reactive data
const localAbsensiData = ref(props.absensiData ?? []);
const allAttendanceData = ref(localAbsensiData.value ?? []);
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
const localKelasData = ref(props.kelasData ?? []);
const localJadwalData = ref(props.jadwalData ?? []);
const isNavbarVisible = ref(true);
const qrImage = ref('');
const selectedJadwal = ref('');
const isGeneratingQR = ref(false);

// Password Modal Settings
const showChangePasswordModal = ref(false);
const dropdownOpen = ref(false);
const passwordForm = ref({
    current_password: '',
    new_password: '',
    new_password_confirmation: '',
});
const passwordErrors = ref({});
const processingPassword = ref(false);

// Toast Notification
const toastMessage = ref('');
const toastType = ref('success');
const showToast = ref(false);

// Confirmation Modal
const showConfirmationModal = ref(false);
const confirmationMessage = ref('');
const confirmationCallback = ref(null);

// Functions
const showNotification = (msg, type = 'success') => {
    toastMessage.value = msg;
    toastType.value = type;
    showToast.value = true;
    setTimeout(() => (showToast.value = false), 3000);
};

const showConfirmModal = (message, callback) => {
    confirmationMessage.value = message;
    confirmationCallback.value = callback;
    showConfirmationModal.value = true;
};

const confirmAction = () => {
    if (confirmationCallback.value) {
        confirmationCallback.value();
    }
    closeConfirmModal();
};

const closeConfirmModal = () => {
    showConfirmationModal.value = false;
    confirmationMessage.value = '';
    confirmationCallback.value = null;
};

const toggleDropdown = () => (dropdownOpen.value = !dropdownOpen.value);
const closeDropdown = () => (dropdownOpen.value = false);

const closePasswordModal = () => {
    showChangePasswordModal.value = false;
    passwordForm.value = {
        current_password: '',
        new_password: '',
        new_password_confirmation: '',
    };
    passwordErrors.value = {};
};

const submitPasswordChange = () => {
    if (processingPassword.value) return;
    processingPassword.value = true;
    passwordErrors.value = {};

    router.put(route('guru.password.update'), passwordForm.value, {
        onSuccess: () => {
            showNotification('Password berhasil diubah!', 'success');
            closePasswordModal();
        },
        onError: (errors) => {
            // Handle specific error for wrong current password
            
            
            // Set form errors for field highlighting
            passwordErrors.value = Object.fromEntries(
                Object.entries(errors).map(([key, val]) => [
                    key, Array.isArray(val) ? val : [val]
                ])
            );
        },
        onFinish: () => (processingPassword.value = false),
    });
};

let lastScrollY = window.scrollY;
let scrollTimeout = null;

function handleScroll() {
    clearTimeout(scrollTimeout);

    if (window.scrollY > lastScrollY) {
        isNavbarVisible.value = false;
    } else {
        scrollTimeout = setTimeout(() => {
            isNavbarVisible.value = true;
        }, 100);
    }

    lastScrollY = window.scrollY;
}

/**
 * 🔹 Attendance Status Classification
 */
const LATE_TIME_THRESHOLD = '06:45';

const getAttendanceStatusClass = (student) => {
    const status = (student.status || '').toLowerCase();
    const attendanceTime = student.time || student.waktu;

    if (status === 'hadir') {
        if (attendanceTime && attendanceTime > LATE_TIME_THRESHOLD) {
            return 'bg-red-100 text-red-800 border border-red-300';
        } else {
            return 'bg-green-100 text-green-800 border border-green-300';
        }
    } else if (status === 'terlambat') {
        return 'bg-red-100 text-red-800 border border-red-300';
    } else if (['alpha', 'izin', 'sakit', 'tidak hadir', 'alpa'].includes(status)) {
        return 'bg-yellow-100 text-yellow-800 border border-yellow-300';
    } else {
        return 'bg-gray-100 text-gray-800 border border-gray-300';
    }
};

const processAttendanceStatus = (attendance) => {
    if (attendance.status === 'Hadir' && attendance.time > LATE_TIME_THRESHOLD) {
        return {
            ...attendance,
            displayStatus: 'Terlambat',
            originalStatus: attendance.status,
        };
    }
    return {
        ...attendance,
        displayStatus: attendance.status,
        originalStatus: attendance.status,
    };
};

/**
 * 🔹 QR Code Absensi
 */
const generateQRCode = async () => {
    if (!selectedJadwal.value) {
        showNotification('Silakan pilih jadwal terlebih dahulu!', 'error');
        return;
    }
    try {
        isGeneratingQR.value = true;
        const payload = `jadwal:${selectedJadwal.value}`;
        const qrDataUrl = await QRCode.toDataURL(payload, {
            width: 250,
            margin: 2,
            color: { dark: '#000000', light: '#ffffff' },
        });
        qrImage.value = qrDataUrl;
        await nextTick();
        const qrDiv = document.getElementById('qrcode');
        if (qrDiv) {
            qrDiv.innerHTML = '';
            const img = document.createElement('img');
            img.src = qrImage.value;
            img.className = 'h-48 w-48 object-contain';
            img.alt = 'QR Code Absensi';
            qrDiv.appendChild(img);
        }
        showNotification('QR Code berhasil di-generate!', 'success');
    } catch (error) {
        console.error('Error generating QR code:', error);
        showNotification('Gagal membuat QR Code. Silakan coba lagi.', 'error');
    } finally {
        isGeneratingQR.value = false;
    }
};

const refreshQRCode = async () => {
    if (!qrImage.value) {
        showNotification('Belum ada QR Code untuk direfresh. Silakan generate dulu.', 'error');
        return;
    }
    
    // Show confirmation modal instead of browser confirm
    showConfirmModal('Apakah anda yakin untuk merefresh QR Code?', async () => {
        await generateQRCode();
        showNotification('QR Code berhasil direfresh!', 'success');
    });
};

const onJadwalChange = () => {
    qrImage.value = '';
    const qrDiv = document.getElementById('qrcode');
    if (qrDiv) {
        qrDiv.innerHTML = '<p class="text-sm text-gray-500">QR Code akan muncul di sini</p>';
    }
};

/**
 * 🔹 Utility Functions
 */
const exportToPDF = () => {
    const doc = new jsPDF();
    doc.setFontSize(14);
    doc.text('Laporan Absensi Siswa', 14, 15);
    doc.setFontSize(10);
    doc.text(`Kelas: ${attendanceFilter.value.class || 'Semua'}`, 14, 22);
    doc.text(`Mata Pelajaran: ${attendanceFilter.value.subject || 'Semua'}`, 14, 28);

    const tableData = (filteredAttendanceData.value || []).map((a, index) => {
        let pdfStatus = a.status || '-';
        const status = (a.status || '').toLowerCase();
        const time = a.time || a.waktu;

        if (status === 'hadir' && time && time > LATE_TIME_THRESHOLD) {
            pdfStatus = 'Terlambat';
        }

        return [index + 1, a.name || '-', a.class || '-', a.subject || '-', a.date || '-', a.time || '-', pdfStatus];
    });

    if (tableData.length === 0) {
        doc.text('Tidak ada data absensi untuk filter ini.', 14, 40);
    } else {
        autoTable(doc, {
            startY: 35,
            head: [['No', 'Nama Siswa', 'Kelas', 'Mata Pelajaran', 'Tanggal', 'Waktu', 'Status']],
            body: tableData,
            theme: 'grid',
            styles: { fontSize: 9, cellPadding: 3 },
            headStyles: { fillColor: [41, 128, 185] },
            didParseCell: function (data) {
                if (data.column.index === 6 && data.cell.text[0] === 'Terlambat') {
                    data.cell.styles.textColor = [220, 38, 38];
                    data.cell.styles.fontStyle = 'bold';
                }
            },
        });
    }
    doc.save(`Absensi_${attendanceFilter.value.class || 'Semua'}_${Date.now()}.pdf`);
};

const months = ref(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']);

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

const recentAttendance = computed(() => {
    return [...localAbsensiData.value]
        .sort((a, b) => new Date(b.tanggal + ' ' + b.waktu) - new Date(a.tanggal + ' ' + a.waktu))
        .slice(0, 5)
        .map((attendance) => processAttendanceStatus(attendance));
});

const today = new Date().toISOString().split('T')[0];

const attendanceStats = computed(() => {
    const todayData = localAbsensiData.value.filter((a) => {
        const recordDate = a.tanggal || a.date;
        return recordDate === today || recordDate === new Date().toLocaleDateString('en-CA');
    });

    const dataToProcess = todayData.length > 0 ? todayData : localAbsensiData.value;
    const total = dataToProcess.length || 1;

    const hadirCount = dataToProcess.filter((a) => {
        const status = (a.status || '').toLowerCase();
        const time = a.time || a.waktu;
        return status === 'hadir' && time && time <= LATE_TIME_THRESHOLD;
    }).length;

    const terlambatCount = dataToProcess.filter((a) => {
        const status = (a.status || '').toLowerCase();
        const time = a.time || a.waktu;
        return (status === 'hadir' && time && time > LATE_TIME_THRESHOLD) || status === 'terlambat';
    }).length;

    const alphaIzinSakitCount = dataToProcess.filter((a) => {
        const status = (a.status || '').toLowerCase();
        return ['alpha', 'izin', 'sakit', 'tidak hadir', 'alpa'].includes(status);
    }).length;

    const lateMap = {};
    dataToProcess.forEach((a) => {
        const status = (a.status || '').toLowerCase();
        const time = a.time || a.waktu;
        const studentName = a.name || a.nama_siswa || 'Unknown';

        if ((status === 'hadir' && time && time > LATE_TIME_THRESHOLD) || status === 'terlambat') {
            lateMap[studentName] = (lateMap[studentName] || 0) + 1;
        }
    });

    const topLateStudents = Object.entries(lateMap)
        .map(([name, count]) => ({ name, lateCount: count }))
        .sort((a, b) => b.lateCount - a.lateCount)
        .slice(0, 5);

    return {
        hadirPct: Math.round((hadirCount / total) * 100),
        terlambatPct: Math.round((terlambatCount / total) * 100),
        alphaIzinSakitPct: Math.round((alphaIzinSakitCount / total) * 100),
        topLateStudents,
        counts: {
            hadir: hadirCount,
            terlambat: terlambatCount,
            alphaIzinSakit: alphaIzinSakitCount,
            total,
        },
    };
});

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

const selectedJadwalLabel = computed(() => {
    if (!selectedJadwal.value) return '';
    const jadwal = localJadwalData.value.find(j => j.id_jadwal == selectedJadwal.value);
    return jadwal ? `${jadwal.mata_pelajaran} - ${jadwal.nama_kelas}` : '';
});

const logout = () => {
    router.post(route('logout'));
};

const filterSchedule = () => {
    console.log('Filtering schedule...');
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
    window.addEventListener('scroll', handleScroll);
    
    // Close dropdown on click outside
    const handleClick = (e) => {
        const dropdown = document.querySelector('.dropdown-container');
        if (dropdown && !dropdown.contains(e.target)) closeDropdown();
    };
    document.addEventListener('click', handleClick);

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
            if (qrImage.value) {
                refreshQRCode();
            }
        },
        5 * 60 * 1000,
    );
});

onUnmounted(() => {
    document.removeEventListener('click', handleClick);
    window.removeEventListener('scroll', handleScroll);
    if (qrInterval) {
        clearInterval(qrInterval);
    }
});
</script>

<style scoped>
/* Dropdown Animation */
.dropdown-enter-active, .dropdown-leave-active {
    transition: all 0.3s ease;
}
.dropdown-enter-from, .dropdown-leave-to {
    opacity: 0;
    transform: translateY(-10px) scale(0.95);
}
.dropdown-enter-to, .dropdown-leave-from {
    opacity: 1;
    transform: translateY(0) scale(1);
}

/* Toast Animation */
.fade-scale-enter-active, .fade-scale-leave-active {
    transition: all 0.3s ease;
}
.fade-scale-enter-from, .fade-scale-leave-to {
    opacity: 0;
    transform: scale(0.9);
}
.fade-scale-enter-to, .fade-scale-leave-from {
    opacity: 1;
    transform: scale(1);
}

/* Checkmark Animation */
.checkmark-circle {
    stroke: #4ade80;
    stroke-width: 3;
    stroke-miterlimit: 10;
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    fill: none;
    animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
}

.checkmark-check {
    stroke: #4ade80;
    stroke-width: 3;
    stroke-linecap: round;
    stroke-linejoin: round;
    fill: none;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
}

@keyframes stroke {
    100% {
        stroke-dashoffset: 0;
    }
}

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