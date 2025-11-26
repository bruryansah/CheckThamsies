<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
        <!-- Header -->
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
                        <button
                            @click="toggleDropdown"
                            class="flex items-center gap-2 rounded-xl bg-white/80 px-4 py-2 font-medium text-gray-700 shadow-md backdrop-blur-md transition-all duration-300 hover:bg-white/90"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                ></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Pengaturan
                            <svg
                                class="h-4 w-4 transition-transform duration-300"
                                :class="{ 'rotate-180': dropdownOpen }"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <transition name="dropdown">
                            <div
                                v-if="dropdownOpen"
                                class="absolute top-full right-0 z-10 mt-2 w-48 rounded-xl border border-gray-200 bg-white shadow-xl backdrop-blur-md"
                            >
                                <div class="p-2">
                                    <button
                                        @click="
                                            showChangePasswordModal = true;
                                            closeDropdown();
                                        "
                                        class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left text-gray-700 transition-colors duration-200 hover:bg-gray-100"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                            ></path>
                                        </svg>
                                        Ubah Password
                                    </button>
                                    <button
                                        @click="logout"
                                        class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left text-red-600 transition-colors duration-200 hover:bg-red-50"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                            ></path>
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

        <main class="mx-auto max-w-7xl px-4 py-8 pt-38 sm:px-6 lg:px-8">
            <!-- Stats Cards Component -->
            <StatsCards :stats="dynamicStats" />

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="space-y-8 lg:col-span-2">
                    <!-- Quick Actions Component -->
                    <QuickActions
                        v-model:selectedJadwal="selectedJadwal"
                        :jadwalData="localJadwalData"
                        :isGeneratingQR="isGeneratingQR"
                        :isFinalized="isSelectedFinalizedToday"
                        :formatHari="formatHari"
                        @generateQR="generateQRCode"
                        @showAttendance="showAttendanceModal = true"
                        @finalize="finalizeAbsensi"
                    />

                    <!-- Schedule Card Component -->
                    <ScheduleCard
                        :filteredSchedule="filteredSchedule"
                        v-model="selectedHari"
                        :selectedLantai="selectedLantai"
                        @update:selectedLantai="selectedLantai = $event"
                        :selectedRuang="selectedRuang"
                        @update:selectedRuang="selectedRuang = $event"
                        :availableLantai="availableLantai"
                        :availableRuang="availableRuang"
                        :formatHari="formatHari"
                    />
                </div>

                <div class="space-y-8">
                    <!-- QR Code Card Component -->
                    <QRCodeCard :qrImage="qrImage" :selectedJadwalLabel="selectedJadwalLabel" @refresh="refreshQRCode" />

                    <!-- Recent Attendance Component -->
                    <RecentAttendance :recentAttendance="recentAttendance" :getStatusClass="getAttendanceStatusClass" />

                    <!-- Statistics Card Component -->
                    <StatisticsCard :stats="attendanceStats" />
                </div>
            </div>
        </main>

        <!-- Attendance Modal Component -->
        <AttendanceModal
            :show="showAttendanceModal"
            :attendanceData="filteredAttendanceData"
            :filter="attendanceFilter"
            :kelasData="filteredKelasData"
            :availableSubjects="availableSubjects"
            :formatHari="formatHari"
            :processStatus="processAttendanceStatus"
            :getStatusClass="getAttendanceStatusClass"
            @close="showAttendanceModal = false"
            @export="exportToPDF"
            @update:filter="attendanceFilter = $event"
            @resetFilter="resetFilters"
        />

        <!-- Change Password Modal (tetap inline karena kompleks) -->
        <div
            v-if="showChangePasswordModal"
            class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
            @click.self="closePasswordModal"
        >
            <div class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white shadow-2xl" @click.stop>
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                            ></path>
                        </svg>
                        <h2 class="text-lg font-semibold text-gray-900">Ubah Password</h2>
                    </div>
                    <button @click="closePasswordModal" type="button" class="text-gray-400 transition-colors hover:text-gray-600">✕</button>
                </div>
                <form @submit.prevent="submitPasswordChange" class="space-y-4 p-6">
                    <div>
                        <label for="current_password" class="mb-2 block text-sm font-medium text-gray-700">Password Saat Ini</label>
                        <input
                            id="current_password"
                            type="password"
                            v-model="passwordForm.current_password"
                            class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            :class="{ 'border-red-500': passwordErrors.current_password }"
                            placeholder="Masukkan password saat ini"
                            autocomplete="current-password"
                            required
                        />
                        <p v-if="passwordErrors.current_password" class="mt-1 text-sm text-red-500">{{ passwordErrors.current_password[0] }}</p>
                    </div>
                    <div>
                        <label for="new_password" class="mb-2 block text-sm font-medium text-gray-700">Password Baru</label>
                        <input
                            id="new_password"
                            type="password"
                            v-model="passwordForm.new_password"
                            class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            :class="{ 'border-red-500': passwordErrors.new_password }"
                            placeholder="Masukkan password baru (min. 8 karakter)"
                            autocomplete="new-password"
                            required
                        />
                        <p v-if="passwordErrors.new_password" class="mt-1 text-sm text-red-500">{{ passwordErrors.new_password[0] }}</p>
                    </div>
                    <div>
                        <label for="new_password_confirmation" class="mb-2 block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                        <input
                            id="new_password_confirmation"
                            type="password"
                            v-model="passwordForm.new_password_confirmation"
                            class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            :class="{ 'border-red-500': passwordErrors.new_password_confirmation }"
                            placeholder="Konfirmasi password baru"
                            autocomplete="new-password"
                            required
                        />
                        <p v-if="passwordErrors.new_password_confirmation" class="mt-1 text-sm text-red-500">
                            {{ passwordErrors.new_password_confirmation[0] }}
                        </p>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button
                            type="button"
                            @click="closePasswordModal"
                            class="flex-1 rounded-xl bg-gray-500 py-3 font-medium text-white transition-colors hover:bg-gray-600"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="processingPassword"
                            class="flex-1 rounded-xl bg-blue-600 py-3 font-medium text-white transition-colors hover:bg-blue-700 disabled:bg-blue-400"
                        >
                            <span v-if="processingPassword">Mengubah...</span>
                            <span v-else>Ubah Password</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div
            v-if="showConfirmationModal"
            class="fixed inset-0 z-[70] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
            @click.self="closeConfirmModal"
        >
            <div class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white shadow-2xl" @click.stop>
                <div class="p-6">
                    <div class="mb-4 flex items-center justify-center">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                                ></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="mb-2 text-center text-lg font-semibold text-gray-900">Konfirmasi</h3>
                    <p class="mb-6 text-center text-gray-600">{{ confirmationMessage }}</p>
                    <div class="flex gap-3">
                        <button
                            @click="closeConfirmModal"
                            class="flex-1 rounded-xl bg-gray-500 py-3 font-medium text-white transition-colors hover:bg-gray-600"
                        >
                            Batal
                        </button>
                        <button
                            @click="confirmAction"
                            class="flex-1 rounded-xl bg-blue-600 py-3 font-medium text-white transition-colors hover:bg-blue-700"
                        >
                            Ya, Lanjutkan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast Notification - Fixed Version -->
        <transition name="fade-scale">
            <div
                v-if="showToast"
                class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/40 backdrop-blur-sm"
                @click.self="hideToast"
            >
                <div class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-2xl" @click.stop>
                    <!-- Success Icon -->
                    <div v-if="toastType === 'success'" class="mx-auto mb-4 h-16 w-16">
                        <svg class="mx-auto h-16 w-16" viewBox="0 0 52 52">
                            <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none" />
                            <path class="checkmark-check" fill="none" d="M14 27l7 7 16-16" />
                        </svg>
                    </div>

                    <!-- Error Icon -->
                    <div v-else class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-100">
                        <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>

                    <!-- Title & Message -->
                    <h2 class="mb-2 text-xl font-semibold text-gray-900">
                        {{ toastType === 'success' ? 'Berhasil' : 'Gagal' }}
                    </h2>
                    <p class="mb-6 text-gray-600">{{ toastMessage }}</p>

                    <!-- Close Button -->
                    <button
                        @click="hideToast"
                        class="w-full rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 px-4 py-2 font-medium text-white shadow transition-all duration-300 hover:from-blue-700 hover:to-purple-700"
                    >
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

// Import Composables
import { useAttendance } from '@/composables/useAttendance';
import { useNotification } from '@/composables/useNotification';
import { useSchedule } from '@/composables/useSchedule';

// Import Components
import AttendanceModal from '@/components/Guru/AttendanceModal.vue';
import QRCodeCard from '@/components/Guru/QRCodeCard.vue';
import QuickActions from '@/components/Guru/QuickActions.vue';
import RecentAttendance from '@/components/Guru/RecentAttendance.vue';
import ScheduleCard from '@/components/Guru/ScheduleCard.vue';
import StatisticsCard from '@/components/Guru/StatisticsCard.vue';
import StatsCards from '@/components/Guru/StatsCards.vue';

// Props - tambahkan absensiHistory
const props = defineProps({
    auth: Object,
    flash: Object,
    guru: Object,
    jadwalData: { type: Array, default: () => [] },
    absensiData: { type: Array, default: () => [] }, // Untuk hari ini
    absensiHistory: { type: Array, default: () => [] }, // Untuk semua waktu
    kelasData: { type: Array, default: () => [] },
    lantai: { type: Number, default: 0 },
    ruang: { type: Number, default: 0 },
});

// Reactive data
const jadwalData = ref(Array.isArray(props.jadwalData) ? props.jadwalData : []);
const absensiData = ref(Array.isArray(props.absensiData) ? props.absensiData : []); // Hari ini
const absensiHistory = ref(Array.isArray(props.absensiHistory) ? props.absensiHistory : []); // Semua waktu
const kelasData = ref(Array.isArray(props.kelasData) ? props.kelasData : []);

// Update useAttendance untuk menggunakan absensiData (hari ini)
const { formatHari, getTodayHariLower, normalizeHari, processAttendanceStatus, getAttendanceStatusClass, filteredAbsensiData } = useAttendance(
    jadwalData,
    absensiData,
); // Gunakan absensiData untuk Stats & Recent

// Use Schedule Composable
const { selectedHari, selectedLantai, selectedRuang, availableLantai, availableRuang, filteredSchedule } = useSchedule(jadwalData);

// ⭐ PERUBAHAN: Tambahkan hideToast
const { toastMessage, toastType, showToast, showNotification, hideToast } = useNotification();

// Local state
const teacherName = ref(props.guru?.nama ?? 'Guru');
const selectedJadwal = ref('');
const isGeneratingQR = ref(false);
const qrImage = ref('');
const showAttendanceModal = ref(false);
const isNavbarVisible = ref(true);
const localJadwalData = ref(Array.isArray(props.jadwalData) ? props.jadwalData : []);
const attendanceFilter = ref({ class: '', subject: '', date: '' });

// Password Modal
const showChangePasswordModal = ref(false);
const dropdownOpen = ref(false);
const passwordForm = ref({ current_password: '', new_password: '', new_password_confirmation: '' });
const passwordErrors = ref({});
const processingPassword = ref(false);

// Confirmation Modal
const showConfirmationModal = ref(false);
const confirmationMessage = ref('');
const confirmationCallback = ref(null);

const today = new Date().toISOString().split('T')[0];

// Computed Properties
const dynamicStats = computed(() => {
    const attendanceData = filteredAbsensiData.value;
    const jadwalArray = Array.isArray(props.jadwalData) ? props.jadwalData : [];
    const kelasArray = Array.isArray(props.kelasData) ? props.kelasData : [];

    const teacherClasses = [...new Set(jadwalArray.map((j) => j.nama_kelas))];
    const totalStudentsInTeacherClasses = kelasArray
        .filter((kelas) => teacherClasses.includes(kelas.nama_kelas))
        .reduce((total, kelas) => total + (parseInt(kelas.total_siswa) || 0), 0);

    const uniqueClasses = [...new Set(jadwalArray.map((j) => j.nama_kelas))].length;
    const uniqueSubjects = [...new Set(jadwalArray.map((j) => j.mata_pelajaran))].length;

    const todayAttendance = attendanceData.filter((a) => {
        const recordDate = a.tanggal || a.date;
        return recordDate === today || recordDate === new Date().toLocaleDateString('en-CA');
    });

    const presentToday = todayAttendance.filter((a) => (a.status || '').toLowerCase() === 'hadir').length;

    return [
        {
            label: 'Total Siswa',
            value: totalStudentsInTeacherClasses || '0',
            change: totalStudentsInTeacherClasses > 0 ? `${uniqueClasses} kelas` : 'Belum ada',
            icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
            bgColor: 'bg-gradient-to-r from-blue-500 to-blue-600',
            textColor: 'text-blue-600',
        },
        {
            label: 'Hadir Hari Ini',
            value: presentToday || '0',
            change: todayAttendance.length > 0 ? `${presentToday}/${todayAttendance.length}` : 'Belum ada',
            icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            bgColor: 'bg-gradient-to-r from-green-500 to-green-600',
            textColor: 'text-green-600',
        },
        {
            label: 'Kelas yang Diajar',
            value: uniqueClasses || '0',
            change: uniqueClasses > 0 ? `${uniqueClasses} kelas` : 'Belum ada',
            icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
            bgColor: 'bg-gradient-to-r from-purple-500 to-purple-600',
            textColor: 'text-purple-600',
        },
        {
            label: 'Mata Pelajaran',
            value: uniqueSubjects || '0',
            change: uniqueSubjects > 0 ? `${uniqueSubjects} mapel` : 'Belum ada',
            icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
            bgColor: 'bg-gradient-to-r from-orange-500 to-orange-600',
            textColor: 'text-orange-600',
        },
    ];
});

const recentAttendance = computed(() => {
    // Dapatkan hari ini dalam format lowercase (senin, selasa, dll)
    const todayHari = getTodayHariLower();
    const todayStr = new Date().toISOString().split('T')[0];

    return (
        [...filteredAbsensiData.value]
            // Filter: hanya data hari ini (tanggal dan hari harus sesuai)
            .filter((a) => {
                const recordDate = a.date || a.tanggal;
                const recordHari = normalizeHari(a.hari || '');

                // Harus tanggal hari ini DAN hari sesuai
                return recordDate === todayStr && recordHari === todayHari;
            })
            .sort((a, b) => new Date(b.tanggal + ' ' + b.waktu) - new Date(a.tanggal + ' ' + a.waktu))
            .slice(0, 5)
            .map((attendance) => processAttendanceStatus(attendance))
            .map((a) => {
                const statusLower = (a.originalStatus || a.status || '').toLowerCase();
                if (['izin', 'sakit'].includes(statusLower) && a.keterangan) {
                    return { ...a, displayStatus: `${a.displayStatus}` };
                }
                return a;
            })
    );
});

// attendanceStats tetap menggunakan filteredAbsensiData (hari ini)
// ⭐ GANTI computed property attendanceStats yang lama dengan yang ini
// Lokasi: resources/js/Pages/Guru/Dashboard.vue (sekitar baris 350-400)

const attendanceStats = computed(() => {
    // Filter hanya untuk hari ini (untuk persentase)
    const todayStr = new Date().toISOString().split('T')[0];
    const todayData = filteredAbsensiData.value.filter((a) => {
        const recordDate = a.date || a.tanggal;
        return recordDate === todayStr;
    });

    const total = todayData.length || 1;

    let hadirCount = 0,
        terlambatCount = 0,
        alphaCount = 0,
        izinCount = 0,
        sakitCount = 0;

    // Proses data hari ini untuk persentase
    todayData.forEach((a) => {
        const processedAttendance = processAttendanceStatus(a);
        const status = processedAttendance.displayStatus.toLowerCase();
        const originalStatus = (a.status || '').toLowerCase();

        if (status === 'hadir') hadirCount++;
        else if (status === 'terlambat') terlambatCount++;
        else if (status === 'alfa' || status === 'alpha') alphaCount++;
        else if (status === 'izin' || originalStatus === 'izin') izinCount++;
        else if (status === 'sakit' || originalStatus === 'sakit') sakitCount++;
    });

    // ⭐ PERBAIKAN: Hitung siswa terlambat dari SEMUA WAKTU menggunakan absensiHistory
    const lateMap = {};
    
    // Gunakan absensiHistory untuk data semua waktu
    absensiHistory.value.forEach((a) => {
        const processedAttendance = processAttendanceStatus(a);
        const status = processedAttendance.displayStatus.toLowerCase();
        
        // Hanya hitung yang terlambat
        if (status === 'terlambat') {
            const studentName = a.name || a.nama_siswa || 'Unknown';
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
        alphaPct: Math.round((alphaCount / total) * 100),
        izinPct: Math.round((izinCount / total) * 100),
        sakitPct: Math.round((sakitCount / total) * 100),
        topLateStudents,
    };
});

const availableSubjects = computed(() => {
    return [...new Set(jadwalData.value.map((item) => item.mata_pelajaran))];
});

const filteredKelasData = computed(() => {
    const teacherClasses = [...new Set(jadwalData.value.map((jadwal) => jadwal.nama_kelas))];
    return kelasData.value.filter((kelas) => teacherClasses.includes(kelas.nama_kelas));
});

const selectedJadwalObj = computed(() => {
    if (!selectedJadwal.value) return null;
    return jadwalData.value.find((j) => String(j.id_jadwal) === String(selectedJadwal.value)) || null;
});

const selectedJadwalLabel = computed(() => {
    const jadwal = selectedJadwalObj.value;
    return jadwal ? `${jadwal.mata_pelajaran} - ${jadwal.nama_kelas}` : '';
});

const isSelectedFinalizedToday = computed(() => {
    const jadwal = selectedJadwalObj.value;
    if (!jadwal) return false;
    const todayStr = new Date().toISOString().split('T')[0];
    return absensiData.value.some((a) => {
        const subject = a.subject || a.mata_pelajaran;
        const kelas = a.class || a.nama_kelas;
        const hari = (a.hari || '').toLowerCase();
        const tanggal = a.date || a.tanggal || '';
        const ket = (a.keterangan || '').toLowerCase();
        return (
            subject === jadwal.mata_pelajaran &&
            kelas === jadwal.nama_kelas &&
            hari === (jadwal.hari || '').toLowerCase() &&
            tanggal === todayStr &&
            ket.startsWith('otomatis alfa')
        );
    });
});

const filteredAttendanceData = computed(() => {
    let data = absensiHistory.value; // Gunakan history untuk modal

    if (attendanceFilter.value.class) {
        data = data.filter((a) => (a.class || a.nama_kelas) === attendanceFilter.value.class);
    }
    if (attendanceFilter.value.subject) {
        data = data.filter((a) => (a.subject || a.mata_pelajaran) === attendanceFilter.value.subject);
    }
    if (attendanceFilter.value.date) {
        data = data.filter((a) => (a.date || a.tanggal) === attendanceFilter.value.date);
    }

    return data;
});

// Functions
const toggleDropdown = () => (dropdownOpen.value = !dropdownOpen.value);
const closeDropdown = () => (dropdownOpen.value = false);

const showConfirmModal = (message, callback) => {
    confirmationMessage.value = message;
    confirmationCallback.value = callback;
    showConfirmationModal.value = true;
};

const confirmAction = () => {
    if (confirmationCallback.value) confirmationCallback.value();
    closeConfirmModal();
};

const closeConfirmModal = () => {
    showConfirmationModal.value = false;
    confirmationMessage.value = '';
    confirmationCallback.value = null;
};

const closePasswordModal = () => {
    showChangePasswordModal.value = false;
    passwordForm.value = { current_password: '', new_password: '', new_password_confirmation: '' };
    passwordErrors.value = {};
};

const submitPasswordChange = () => {
    if (processingPassword.value) return;
    processingPassword.value = true;
    passwordErrors.value = {};

    router.put('/guru/password', passwordForm.value, {
        onSuccess: () => {
            showNotification('Password berhasil diubah!', 'success');
            closePasswordModal();
        },
        onError: (errors) => {
            passwordErrors.value = Object.fromEntries(Object.entries(errors).map(([key, val]) => [key, Array.isArray(val) ? val : [val]]));
        },
        onFinish: () => (processingPassword.value = false),
    });
};

const generateQRCode = async () => {
    if (!selectedJadwal.value) {
        showNotification('Silakan pilih jadwal terlebih dahulu!', 'error');
        return;
    }

    // Cek status jadwal
    const selectedJadwalObj = jadwalData.value.find((j) => String(j.id_jadwal) === String(selectedJadwal.value));
    if (selectedJadwalObj && selectedJadwalObj.status === 'tutup') {
        showNotification('Jadwal ini sudah ditutup. Tidak bisa generate QR Code.', 'error');
        return;
    }

    if (isSelectedFinalizedToday.value) {
        showNotification('Absen untuk jadwal ini sudah ditutup hari ini.', 'error');
        return;
    }

    try {
        isGeneratingQR.value = true;
        const payload = `jadwal:${selectedJadwal.value}`;
        const qrDataUrl = await QRCode.toDataURL(payload, { width: 250, margin: 2, color: { dark: '#000000', light: '#ffffff' } });
        qrImage.value = qrDataUrl;
        await nextTick();
        const qrDiv = document.getElementById('qrcode');
        if (qrDiv) {
            qrDiv.innerHTML = '';
            const img = document.createElement('img');
            img.src = qrImage.value;
            img.className = 'h-32 w-32 sm:h-48 sm:w-48 object-contain';
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
        showNotification('Belum ada QR Code untuk direfresh.', 'error');
        return;
    }
    showConfirmModal('Apakah anda yakin untuk merefresh QR Code?', async () => {
        await generateQRCode();
        showNotification('QR Code berhasil direfresh!', 'success');
    });
};

const finalizeAbsensi = () => {
    if (!selectedJadwal.value) {
        showNotification('Silakan pilih jadwal terlebih dahulu!', 'error');
        return;
    }
    router.post(
        '/absensi-pelajaran/finalize',
        { id_jadwal: selectedJadwal.value },
        {
            onSuccess: () => {
                showNotification('Finalize berhasil diproses', 'success');
                router.visit(window.location.href, { preserveScroll: true });
            },
            onError: (errors) => {
                const msg = (errors && (errors.message || errors[Object.keys(errors)[0]])) || 'Gagal finalize';
                showNotification(String(msg), 'error');
            },
        },
    );
};

const exportToPDF = () => {
    const doc = new jsPDF();
    doc.setFontSize(14);
    doc.text('Laporan Absensi Siswa', 14, 15);
    doc.setFontSize(10);
    doc.text(`Guru: ${teacherName.value}`, 14, 22);
    doc.text(`Kelas: ${attendanceFilter.value.class || 'Semua'}`, 14, 28);
    doc.text(`Mata Pelajaran: ${attendanceFilter.value.subject || 'Semua'}`, 14, 34);

    const tableData = filteredAttendanceData.value.map((a, index) => {
        const processed = processAttendanceStatus(a);
        const statusText = (() => {
            const s = (a.status || '').toLowerCase();
            if (['izin', 'sakit'].includes(s) && a.keterangan) return `${processed.displayStatus} - ${a.keterangan}`;
            return processed.displayStatus || '-';
        })();
        return [
            index + 1,
            a.name || a.nama_siswa || '-',
            a.class || a.nama_kelas || '-',
            a.subject || a.mata_pelajaran || '-',
            formatHari(a.hari) || '-',
            a.lantai || '-',
            a.ruang || '-',
            a.date || a.tanggal || '-',
            a.time || a.waktu || '-',
            statusText,
            a.keterangan || '-',
        ];
    });

    if (tableData.length === 0) {
        doc.text('Tidak ada data absensi.', 14, 45);
    } else {
        autoTable(doc, {
            startY: 40,
            head: [['No', 'Nama', 'Kelas', 'Mapel', 'Hari', 'Lantai', 'Ruang', 'Tanggal', 'Waktu', 'Status', 'Keterangan']],
            body: tableData,
            theme: 'grid',
            styles: { fontSize: 8, cellPadding: 2 },
            headStyles: { fillColor: [41, 128, 185] },
        });
    }
    doc.save(`Absensi_${teacherName.value}_${attendanceFilter.value.class || 'Semua'}_${new Date().toISOString().split('T')[0]}.pdf`);
};

const resetFilters = () => {
    attendanceFilter.value = { class: '', subject: '', date: '' };
};

const logout = () => {
    router.post('/logout');
};

// Scroll handler
const handleScroll = () => {
    const currentScrollY = window.scrollY;
    if (currentScrollY > lastScrollY && currentScrollY > 100) {
        isNavbarVisible.value = false;
    } else {
        isNavbarVisible.value = true;
    }
    lastScrollY = currentScrollY;
};

let lastScrollY = 0;

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<style scoped>
/* Toast animation */
.fade-scale-enter-active,
.fade-scale-leave-active {
    transition: all 0.3s ease;
}

.fade-scale-enter-from,
.fade-scale-leave-to {
    opacity: 0;
    transform: scale(0.9);
}

/* Success checkmark animation */
.checkmark-circle {
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    stroke-width: 2;
    stroke-miterlimit: 10;
    stroke: #10b981;
    fill: none;
    animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
}

.checkmark-check {
    transform-origin: 50% 50%;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    stroke: #10b981;
    stroke-width: 3;
    animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
}

@keyframes stroke {
    100% {
        stroke-dashoffset: 0;
    }
}
.dropdown-enter-active,
.dropdown-leave-active {
    transition: all 0.3s ease;
}

.dropdown-enter-from,
.dropdown-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

.fade-scale-enter-active,
.fade-scale-leave-active {
    transition: all 0.3s ease;
}

.fade-scale-enter-from,
.fade-scale-leave-to {
    opacity: 0;
    transform: scale(0.9);
}

.checkmark-circle {
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    stroke-width: 2;
    stroke-miterlimit: 10;
    stroke: #10b981;
    fill: none;
    animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
}

.checkmark-check {
    transform-origin: 50% 50%;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    stroke: #10b981;
    stroke-width: 3;
    animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
}

@keyframes stroke {
    100% {
        stroke-dashoffset: 0;
    }
}
</style>
