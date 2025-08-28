<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { BookOpen, Calendar, CheckCircle, Eye, LogOut, QrCode, Users } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import { QrcodeStream } from 'vue-qrcode-reader';

const page = usePage();
const studentName = ref(page.props.auth?.user?.name ?? 'siswa');
const currentDate = ref('');

const checkinStatus = ref('Belum Absen');
const checkoutStatus = ref('Belum Pulang');
const canCheckout = ref(false);
const processingIn = ref(false);
const processingOut = ref(false);

const scannerOpen = ref(false);
const scannedCode = ref('');
const selectedStatus = ref('Hadir');

const toastMessage = ref('');
const toastType = ref<'success' | 'error'>('success');
const showToast = ref(false);

const stats = ref({
    totalKehadiran: 85,
    persentaseHadir: 94,
    totalKelas: 6,
    kelasAktif: 4,
    absenHariIni: 'Belum Absen',
    waktuAbsen: '',
});

const recentAttendance = ref([
    { name: 'Matematika', time: '07:30', status: 'Hadir', color: 'bg-green-500' },
    { name: 'Bahasa Indonesia', time: '08:15', status: 'Hadir', color: 'bg-green-500' },
    { name: 'Fisika', time: '09:00', status: 'Terlambat', color: 'bg-yellow-500' },
    { name: 'Kimia', time: '10:30', status: 'Hadir', color: 'bg-green-500' },
]);

const showNotification = (msg: string, type: 'success' | 'error' = 'success') => {
    toastMessage.value = msg;
    toastType.value = type;
    showToast.value = true;
    setTimeout(() => (showToast.value = false), 3000);
};

// ✅ Absen Masuk
const checkIn = () => {
    if (processingIn.value) return;
    processingIn.value = true;

    navigator.geolocation.getCurrentPosition(
        (pos) => {
            // Tentukan status berdasarkan jam
            const now = new Date();
            const batasJam = 8; // jam 08:00
            const batasMenit = 0;
            let status = selectedStatus.value;

            if (status === 'Hadir') {
                if (now.getHours() > batasJam || (now.getHours() === batasJam && now.getMinutes() > batasMenit)) {
                    status = 'Terlambat';
                }
            }

            router.post(
                route('absen.checkin'),
                {
                    latitude: pos.coords.latitude,
                    longitude: pos.coords.longitude,
                    status: status,
                },
                {
                    onSuccess: () => {
                        checkinStatus.value = 'Sudah Absen (' + status + ')';
                        stats.value.absenHariIni = status;
                        canCheckout.value = true; // langsung bisa absen pulang
                        showNotification('✅ Absen masuk berhasil!', 'success');
                    },

                    onError: () => showNotification('❌ Gagal absen masuk!', 'error'),
                    onFinish: () => (processingIn.value = false),
                },
            );
        },
        () => {
            showNotification('❌ Akses lokasi ditolak!', 'error');
            processingIn.value = false;
        },
    );
};

// ✅ Absen Pulang
const checkOut = () => {
    if (processingOut.value) return;
    processingOut.value = true;

    navigator.geolocation.getCurrentPosition(
        (pos) => {
            router.post(
                route('absen.checkout'),
                {
                    latitude: pos.coords.latitude,
                    longitude: pos.coords.longitude,
                },
                {
                    onSuccess: () => {
                        checkoutStatus.value = 'Sudah Pulang';
                        showNotification('✅ Absen pulang berhasil!', 'success');
                    },
                    onError: () => showNotification('❌ Gagal absen pulang!', 'error'),
                    onFinish: () => (processingOut.value = false),
                },
            );
        },
        () => {
            showNotification('❌ Akses lokasi ditolak!', 'error');
            processingOut.value = false;
        },
    );
};

// ✅ QR Scanner
const openQRScanner = () => (scannerOpen.value = true);
const onDecode = (result: string) => {
    scannedCode.value = result;
    scannerOpen.value = false;
    router.post(
        route('absen.pelajaran'),
        { kode: result },
        {
            onSuccess: () => showNotification('✅ Absen pelajaran berhasil!', 'success'),
            onError: () => showNotification('❌ QR tidak valid!', 'error'),
        },
    );
};
const onInit = (promise: Promise<void>) =>
    promise.catch(() => {
        showNotification('❌ Kamera tidak bisa diakses!', 'error');
        scannerOpen.value = false;
    });

// ✅ On Mounted
onMounted(() => {
    const now = new Date();
    currentDate.value = now.toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });

    // aktifkan absen pulang kalau sudah absen masuk
    if (checkinStatus.value.includes('Sudah')) {
        canCheckout.value = true;
    }
});
</script>

<template>
    <Head title="Dashboard Siswa" />
    <div class="min-h-screen bg-gray-50 p-6">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 shadow-md">
                    <BookOpen class="h-6 w-6 text-white" />
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Siswa</h1>
                    <p class="text-gray-500">Selamat datang, {{ studentName }}</p>
                </div>
            </div>
            <Link
                as="button"
                method="post"
                :href="route('logout')"
                class="flex items-center gap-2 rounded-xl bg-red-500 px-4 py-2 font-medium text-white transition-all duration-300 hover:bg-red-600"
            >
                <LogOut class="h-4 w-4" />
                Logout
            </Link>
        </div>

        <!-- Stats Grid -->
        <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-4">
            <div
                v-for="(stat, i) in [
                    { icon: Users, value: stats.totalKehadiran, label: 'Total Kehadiran', color: 'blue', change: '+12%' },
                    { icon: CheckCircle, value: stats.persentaseHadir + '%', label: 'Persentase Hadir', color: 'green', change: '+8%' },
                    { icon: BookOpen, value: stats.totalKelas, label: 'Total Kelas', color: 'purple', change: 'Aktif' },
                    { icon: Calendar, value: stats.kelasAktif, label: 'Mata Pelajaran', color: 'orange', change: 'Aktif' },
                ]"
                :key="i"
                class="rounded-2xl border border-gray-200 bg-white p-6 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
            >
                <div class="flex items-center justify-between">
                    <div :class="`h-12 w-12 bg-${stat.color}-100 flex items-center justify-center rounded-2xl shadow-inner`">
                        <component :is="stat.icon" class="h-6 w-6" :class="`text-${stat.color}-600`" />
                    </div>
                    <span class="text-sm font-medium text-green-600">{{ stat.change }}</span>
                </div>
                <div class="mt-4">
                    <p class="text-3xl font-bold text-gray-900">{{ stat.value }}</p>
                    <p class="text-sm text-gray-600">{{ stat.label }}</p>
                </div>
            </div>
        </div>

        <!-- Action Cards -->
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <!-- Aksi Cepat -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div class="mb-6 flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-2xl bg-blue-100 shadow-inner">
                        <CheckCircle class="h-5 w-5 text-blue-600" />
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
                </div>

                <!-- Absen Masuk -->
                <div class="space-y-4">
                    <div class="rounded-2xl border border-gray-200 p-4 transition-all duration-200 hover:bg-gray-50">
                        <h4 class="mb-3 font-medium text-gray-900">Absen Masuk</h4>
                        <select
                            v-model="selectedStatus"
                            class="w-full rounded-lg border border-gray-300 p-2 font-medium text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="Hadir" class="bg-green-100 font-semibold text-green-800">Hadir</option>
                            <option value="Izin" class="bg-yellow-100 font-semibold text-yellow-800">Izin</option>
                            <option value="Sakit" class="bg-red-100 font-semibold text-red-800">Sakit</option>
                        </select>

                        <p class="text-sm font-medium text-gray-900">
                            Status:
                            <span :class="checkinStatus.includes('Sudah') ? 'font-medium text-green-600' : 'text-red-500'">{{ checkinStatus }}</span>
                        </p>
                        <button
                            @click="checkIn"
                            :disabled="checkinStatus.includes('Sudah') || processingIn"
                            class="w-full rounded-xl px-4 py-2 text-sm font-medium transition-all duration-300"
                            :class="
                                checkinStatus.includes('Sudah')
                                    ? 'cursor-not-allowed bg-gray-100 text-gray-500'
                                    : 'bg-blue-600 text-white hover:bg-blue-700 active:scale-95'
                            "
                        >
                            <span v-if="processingIn">Memproses...</span>
                            <span v-else>Absen Masuk</span>
                        </button>
                    </div>

                    <!-- Absen Pulang -->
                    <div class="rounded-2xl border border-gray-200 p-4 transition-all duration-200 hover:bg-gray-50">
                        <h4 class="mb-3 font-medium text-gray-900">Absen Pulang</h4>
                        <p class="mb-3 text-sm font-medium text-gray-900">
                            Status:
                            <span :class="checkoutStatus === 'Sudah Pulang' ? 'font-medium text-green-600' : 'text-gray-600'">{{
                                checkoutStatus
                            }}</span>
                        </p>
                        <button
                            @click="checkOut"
                            :disabled="!canCheckout || checkoutStatus === 'Sudah Pulang' || processingOut"
                            class="w-full rounded-xl px-4 py-2 text-sm font-medium transition-all duration-300"
                            :class="
                                !canCheckout || checkoutStatus === 'Sudah Pulang'
                                    ? 'cursor-not-allowed bg-gray-100 text-gray-500'
                                    : 'bg-green-600 text-white hover:bg-green-700 active:scale-95'
                            "
                        >
                            <span v-if="processingOut">Memproses...</span>
                            <span v-else>Absen Pulang</span>
                        </button>
                    </div>

                    <button
                        @click="openQRScanner"
                        class="mt-4 flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-blue-600 to-purple-600 px-4 py-3 text-white transition-all duration-300 hover:from-blue-700 hover:to-purple-700 active:scale-95"
                    >
                        <QrCode class="h-5 w-5" /> Scan QR Absen
                    </button>

                    <button
                        class="mt-3 flex w-full items-center justify-center gap-2 rounded-2xl bg-green-600 px-4 py-3 text-white transition-all duration-300 hover:bg-green-700 active:scale-95"
                    >
                        <Eye class="h-5 w-5" /> Lihat Absensi
                    </button>
                </div>
            </div>

            <!-- Absensi Terbaru -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                <div class="mb-6 flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-2xl bg-green-100 shadow-inner">
                        <CheckCircle class="h-5 w-5 text-green-600" />
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Absensi Terbaru</h3>
                </div>
                <div class="space-y-4">
                    <div
                        v-for="(item, index) in recentAttendance"
                        :key="index"
                        class="flex items-center gap-4 rounded-2xl p-2 transition-all duration-200 hover:bg-gray-50"
                    >
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100">
                            <span class="text-sm font-medium text-blue-600">{{ item.name.charAt(0) }}</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ item.name }}</h4>
                            <p class="text-sm text-gray-500">{{ item.time }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <div :class="`h-2 w-2 rounded-full ${item.color}`"></div>
                            <span
                                class="text-sm font-medium"
                                :class="item.status === 'Hadir' ? 'text-green-600' : item.status === 'Terlambat' ? 'text-yellow-600' : 'text-red-600'"
                                >{{ item.status }}</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Scanner -->
        <div v-if="scannerOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm">
            <div class="animate-fadeIn w-full max-w-md scale-100 transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all">
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center gap-2">
                        <QrCode class="h-5 w-5 text-blue-600" />
                        <h2 class="text-lg font-semibold text-gray-900">Scan QR Code</h2>
                    </div>
                    <button @click="scannerOpen = false" class="text-gray-400 transition-colors hover:text-gray-600">✕</button>
                </div>
                <div class="p-6">
                    <div class="relative overflow-hidden rounded-2xl border-2 border-dashed border-gray-300">
                        <qrcode-stream camera="rear" @decode="onDecode" @init="onInit" class="aspect-square w-full" />
                    </div>
                    <p class="mt-4 text-center text-sm text-gray-500">Arahkan kamera ke QR Code yang valid</p>
                </div>
                <div class="flex justify-between bg-gray-50 px-6 py-4">
                    <button
                        @click="scannerOpen = false"
                        class="mr-2 w-full rounded-2xl bg-gray-500 py-2 text-white transition-colors hover:bg-gray-600"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Elegant Popup Notification dengan Animasi Centang -->
    <transition name="fade-scale">
        <div v-if="showToast" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-2xl">
                <!-- Success Animation -->
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

                <!-- Title -->
                <h2 class="mb-2 text-xl font-semibold text-gray-900">
                    {{ toastType === 'success' ? 'Berhasil' : 'Gagal' }}
                </h2>

                <!-- Message -->
                <p class="mb-6 text-gray-600">{{ toastMessage }}</p>

                <!-- Action -->
                <button
                    @click="showToast = false"
                    class="w-full rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 px-4 py-2 font-medium text-white shadow hover:from-blue-700 hover:to-purple-700"
                >
                    Tutup
                </button>
            </div>
        </div>
    </transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Animasi centang */
.checkmark-circle {
    stroke: #22c55e; /* green-500 */
    stroke-width: 2;
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    stroke-linecap: round;
    animation: circleStroke 0.6s ease-in-out forwards;
}
.checkmark-check {
    stroke: #22c55e;
    stroke-width: 3;
    stroke-linecap: round;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    animation: checkStroke 0.4s ease-in-out 0.6s forwards;
}

@keyframes circleStroke {
    to {
        stroke-dashoffset: 0;
    }
}
@keyframes checkStroke {
    to {
        stroke-dashoffset: 0;
    }
}

/* Fade scale */
.fade-scale-enter-active,
.fade-scale-leave-active {
    transition: all 0.3s ease;
}
.fade-scale-enter-from {
    opacity: 0;
    transform: scale(0.9);
}
.fade-scale-leave-to {
    opacity: 0;
    transform: scale(0.9);
}


</style>
