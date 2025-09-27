<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { BookOpen, Calendar, CheckCircle, ChevronDown, Lock, LogOut, QrCode, Settings, Users } from 'lucide-vue-next';
import { onMounted, onUnmounted, ref, computed } from 'vue';
import { QrcodeStream } from 'vue-qrcode-reader';
import type { User as UserType } from '@/types';

// Props dari Inertia
interface Props {
    kehadiransekolah: number;
    persentaseKehadiran: number;
    totalSakit: number;
    totalIzin: number;
    totalAbsensi: number;
    recentAttendance: Array<{ name: string; time: string; status: string; color: string }>;
    auth: { user: UserType };
}
const props = defineProps<Props>();

const page = usePage();
const studentName = ref(page.props.auth?.user?.name ?? 'siswa');

console.log('Received Props:', props);

const currentDate = ref('');
const checkinStatus = ref('Belum Absen');
const checkoutStatus = ref('Belum Pulang');
const canCheckout = ref(false);
const canScanQR = ref(false);
const processingIn = ref(false);
const processingOut = ref(false);
const checkinDescription = ref('');
const checkinDescriptionError = ref('');
const checkoutDescription = ref('');
const showEarlyCheckoutModal = ref(false);
const checkoutDescriptionError = ref('');
const latestCheckinStatus = ref<string | null>(null);
const showWeekendModal = ref(false); // New modal for weekend restriction

// QR Scanner
const isScanning = ref(false);
const scanResult = ref('');
const errorMessage = ref('');

// Dropdown & Modal
const dropdownOpen = ref(false);
const showChangePasswordModal = ref(false);

// Password form
const passwordForm = ref({
    current_password: '',
    new_password: '',
    new_password_confirmation: '',
});
const passwordErrors = ref<Record<string, string[]>>({});
const processingPassword = ref(false);

// Dropdown functions
const toggleDropdown = () => (dropdownOpen.value = !dropdownOpen.value);
const closeDropdown = () => (dropdownOpen.value = false);

// Logout
const logout = () => {
    router.post(route('logout'), {}, {
        onSuccess: () => {
            checkinStatus.value = 'Belum Absen';
            checkoutStatus.value = 'Belum Pulang';
            canCheckout.value = false;
            canScanQR.value = false;
            latestCheckinStatus.value = null;
            closeDropdown();
        }
    });
};

// Password Modal
const closePasswordModal = () => {
    showChangePasswordModal.value = false;
    passwordForm.value = {
        current_password: '',
        new_password: '',
        new_password_confirmation: '',
    };
    passwordErrors.value = {};
};

// Close Early Checkout Modal
const closeEarlyCheckoutModal = () => {
    showEarlyCheckoutModal.value = false;
    checkoutDescription.value = '';
    checkoutDescriptionError.value = '';
};

// Close Weekend Modal
const closeWeekendModal = () => {
    showWeekendModal.value = false;
};

// Toast Notification
const toastMessage = ref('');
const toastType = ref<'success' | 'error'>('success');
const showToast = ref(false);
const showNotification = (msg: string, type: 'success' | 'error' = 'success') => {
    toastMessage.value = msg;
    toastType.value = type;
    showToast.value = true;
    setTimeout(() => (showToast.value = false), 3000);
};

// Stats
const stats = computed(() => ({
    totalkehadiran: props.kehadiransekolah || 0,
    totalpresentase: props.persentaseKehadiran || 0,
    totalsakit: props.totalSakit || 0,
    totalizin: props.totalIzin || 0,
    absenHariIni: 'Belum Absen',
    waktuAbsen: '',
}));

// Status Select
const selectedStatus = ref('hadir');
const selectedStatus1 = ref('hadir');

// Helper: Tailwind colors for stats
const statColor = (color: string) => {
    switch (color) {
        case 'blue': return 'bg-blue-100 text-blue-600';
        case 'green': return 'bg-green-100 text-green-600';
        case 'purple': return 'bg-purple-100 text-purple-600';
        case 'orange': return 'bg-orange-100 text-orange-600';
    }
    return '';
};

// Check if today is a weekend (Saturday or Sunday)
const isWeekend = () => {
    const today = new Date().getDay();
    return today === 0 || today === 6; // 0 = Sunday, 6 = Saturday
};

// Fetch attendance status
const fetchStatus = async () => {
    try {
        const response = await fetch(route('absen.status'));
        const data = await response.json();
        if (data.status === 'belum_masuk') {
            checkinStatus.value = 'Belum Absen';
            checkoutStatus.value = 'Belum Pulang';
            canCheckout.value = false;
            canScanQR.value = false;
            latestCheckinStatus.value = null;
        } else if (data.status === 'sudah_masuk') {
            checkinStatus.value = 'Sudah Absen';
            checkoutStatus.value = 'Belum Pulang';
            const latestStatus = await fetch(route('absen.latest-status')).then(res => res.json());
            console.log('Latest Status:', latestStatus.status);
            latestCheckinStatus.value = latestStatus.status;
            canCheckout.value = !['izin', 'sakit'].includes(latestStatus.status);
            canScanQR.value = latestStatus.status === 'hadir';
        } else if (data.status === 'sudah_pulang') {
            checkinStatus.value = 'Sudah Absen';
            checkoutStatus.value = 'Sudah Pulang';
            canCheckout.value = false;
            canScanQR.value = false;
            latestCheckinStatus.value = null;
        }
    } catch (error) {
        checkinStatus.value = 'Belum Absen';
        checkoutStatus.value = 'Belum Pulang';
        canCheckout.value = false;
        canScanQR.value = false;
        latestCheckinStatus.value = null;
        console.error('Error fetching status:', error);
    }
};

// Refresh attendance data
const refreshAttendance = async () => {
    await router.get(route('user.dashboard'), {}, {
        onSuccess: () => {
            console.log('Attendance data refreshed');
        },
    });
};

// Absen Masuk
const checkIn = () => {
    if (isWeekend()) {
        showWeekendModal.value = true;
        return;
    }

    if (processingIn.value) return;

    // Validate description for izin or sakit
    if (['izin', 'sakit'].includes(selectedStatus.value) && !checkinDescription.value.trim()) {
        checkinDescriptionError.value = 'Keterangan diperlukan untuk status Izin atau Sakit';
        showNotification(checkinDescriptionError.value, 'error');
        return;
    }

    processingIn.value = true;

    navigator.geolocation.getCurrentPosition(
        (pos) => {
            router.post(route('absen.checkin'), {
                latitude: pos.coords.latitude,
                longitude: pos.coords.longitude,
                status: selectedStatus.value,
                description: checkinDescription.value,
            }, {
                onSuccess: () => {
                    fetchStatus();
                    stats.value.absenHariIni = selectedStatus.value;
                    latestCheckinStatus.value = selectedStatus.value;
                    if (['izin', 'sakit'].includes(selectedStatus.value)) {
                        canCheckout.value = false;
                        canScanQR.value = false;
                        checkoutStatus.value = 'Tidak Perlu Pulang';
                        showNotification(`✅ Absen ${selectedStatus.value.charAt(0).toUpperCase() + selectedStatus.value.slice(1)} berhasil! Anda tidak perlu absen pulang.`, 'success');
                    } else {
                        canCheckout.value = true;
                        canScanQR.value = true;
                        showNotification('✅ Absen masuk berhasil!', 'success');
                    }
                    checkinDescription.value = '';
                    checkinDescriptionError.value = '';
                    refreshAttendance();
                },
                onError: () => {
                    showNotification('❌ Absensi gagal mungkin data siswa tidak ditemukan, silakan hubungi admin atau guru!', 'error');
                },
                onFinish: () => (processingIn.value = false),
            });
        },
        () => {
            showNotification('❌ Akses lokasi ditolak!', 'error');
            processingIn.value = false;
        },
    );
};

// Absen Pulang
const checkOut = () => {
    if (isWeekend()) {
        showWeekendModal.value = true;
        return;
    }

    if (processingOut.value) return;

    // Check if current time is before 13:30 (WIB)
    const now = new Date();
    const hours = now.getHours();
    const minutes = now.getMinutes();
    const isEarlyCheckout = hours < 13 || (hours === 13 && minutes < 30);

    if (isEarlyCheckout) {
        showEarlyCheckoutModal.value = true;
        return;
    }

    // Proceed with normal checkout
    performCheckout();
};

// Perform Checkout with or without keterangan
const performCheckout = () => {
    if (processingOut.value) return;

    // Validate keterangan if modal is open
    if (showEarlyCheckoutModal.value && !checkoutDescription.value.trim()) {
        checkoutDescriptionError.value = 'Keterangan diperlukan untuk pulang cepat';
        showNotification(checkoutDescriptionError.value, 'error');
        return;
    }

    processingOut.value = true;

    navigator.geolocation.getCurrentPosition(
        (pos) => {
            const payload: Record<string, any> = {
                latitude: pos.coords.latitude,
                longitude: pos.coords.longitude,
            };

            if (showEarlyCheckoutModal.value) {
                payload.keterangan = checkoutDescription.value;
            }

            router.post(route('absen.checkout'), payload, {
                onSuccess: () => {
                    fetchStatus();
                    showNotification('✅ Absen pulang berhasil!', 'success');
                    closeEarlyCheckoutModal();
                    refreshAttendance();
                },
                onError: (errors) => {
                    showNotification('❌ Gagal absen pulang! ' + (errors.error || errors.keterangan || ''), 'error');
                },
                onFinish: () => (processingOut.value = false),
            });
        },
        () => {
            showNotification('❌ Akses lokasi ditolak!', 'error');
            processingOut.value = false;
        },
    );
};

// QR Scanner Detect
interface QrCodeResult {
    rawValue: string;
}
const onDetect = (detectedCodes: QrCodeResult[]) => {
    if (detectedCodes.length > 0) {
        scanResult.value = detectedCodes[0].rawValue;
        isScanning.value = false;

        const id_jadwal = scanResult.value.replace('jadwal:', '');
        if (!id_jadwal) {
            errorMessage.value = '❌ Format QR Code tidak valid!';
            showNotification(errorMessage.value, 'error');
            return;
        }

        // Validate description for izin or sakit
        if (['izin', 'sakit'].includes(selectedStatus1.value) && !checkinDescription.value.trim()) {
            checkinDescriptionError.value = 'Keterangan diperlukan untuk status Izin atau Sakit';
            showNotification(checkinDescriptionError.value, 'error');
            return;
        }

        router.post('/absensi-pelajaran/checkin', {
            id_jadwal,
            status: selectedStatus1.value,
            description: checkinDescription.value,
        }, {
            onSuccess: () => {
                fetchStatus();
                showNotification(`✅ Absensi Pelajaran berhasil (${selectedStatus1.value.charAt(0).toUpperCase() + selectedStatus1.value.slice(1)})!`, 'success');
                refreshAttendance();
                checkinDescription.value = ''; // Clear description
                checkinDescriptionError.value = ''; // Clear error
            },
            onError: (errors) => {
                errorMessage.value = errors.message || '❌ Gagal absen, coba lagi!';
                if (errors.message === 'Kamu sudah absen di jadwal ini!') {
                    errorMessage.value = '❌ Kamu sudah absen untuk jadwal ini.';
                } else if (errors.message.includes('expired')) {
                    errorMessage.value = '⏰ QR Code sudah kedaluwarsa.';
                }
                showNotification(errorMessage.value, 'error');
            },
        });
    }
};

// Password Change
const submitPasswordChange = () => {
    if (processingPassword.value) return;
    processingPassword.value = true;
    passwordErrors.value = {};

    router.put(route('student.password.update'), passwordForm.value, {
        onSuccess: () => {
            showNotification('✅ Password berhasil diubah!', 'success');
            closePasswordModal();
        },
        onError: (errors) => {
            passwordErrors.value = Object.fromEntries(Object.entries(errors).map(([key, val]) => [
                key, Array.isArray(val) ? val : [val]
            ]));
            showNotification('❌ Gagal mengubah password!', 'error');
        },
        onFinish: () => (processingPassword.value = false),
    });
};

// Lifecycle
onMounted(async () => {
    const now = new Date().toLocaleString('id-ID', {
        timeZone: 'Asia/Jakarta',
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    currentDate.value = now;

    fetchStatus();

    const handleClick = (e: Event) => {
        const dropdown = document.querySelector('.dropdown-container');
        if (dropdown && !dropdown.contains(e.target as Node)) closeDropdown();
    };
    document.addEventListener('click', handleClick);

    onUnmounted(() => document.removeEventListener('click', handleClick));
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
            <div class="dropdown-container relative">
                <button @click="toggleDropdown"
                    class="flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2 font-medium text-gray-700 hover:bg-gray-200">
                    <Settings class="h-4 w-4" /> Pengaturan
                    <ChevronDown class="h-4 w-4" :class="{ 'rotate-180': dropdownOpen }" />
                </button>
                <transition name="dropdown">
                    <div v-if="dropdownOpen"
                        class="absolute top-full right-0 z-10 mt-2 w-48 rounded-xl border border-gray-200 bg-white shadow-lg">
                        <div class="p-2">
                            <button
                                @click="showChangePasswordModal = true; closeDropdown()"
                                class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left text-gray-700 hover:bg-gray-100">
                                <Lock class="h-4 w-4" /> Ubah Password
                            </button>
                            <button @click="logout"
                                class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left text-red-600 hover:bg-red-50">
                                <LogOut class="h-4 w-4" /> Logout
                            </button>
                        </div>
                    </div>
                </transition>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            <div v-for="(stat, i) in [
                    { icon: Users, value: stats.totalkehadiran, label: 'Total Kehadiran', color: 'blue' },
                    { icon: Users, value: stats.totalsakit, label: 'Total Sakit', color: 'purple' },
                    { icon: Users, value: stats.totalizin, label: 'Total Izin', color: 'orange' },
                    { icon: CheckCircle, value: stats.totalpresentase + '%', label: 'Persentase Kehadiran', color: 'green' },
                ]" :key="i" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-md hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-center justify-between">
                    <div :class="`${statColor(stat.color)} flex h-12 w-12 items-center justify-center rounded-2xl shadow-inner`">
                        <component :is="stat.icon" class="h-6 w-6" />
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-3xl font-bold text-gray-900">{{ stat.value }}</p>
                    <p class="text-sm text-gray-600">{{ stat.label }}</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-md hover:-translate-y-1 hover:shadow-lg">
                <div class="mb-6 flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-2xl bg-blue-100 shadow-inner">
                        <CheckCircle class="h-5 w-5 text-blue-600" />
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
                </div>
                <div class="space-y-4">
                    <div class="rounded-2xl border border-gray-200 p-4 hover:bg-gray-50">
                        <h4 class="mb-3 font-medium text-gray-900">Absen Masuk</h4>
                        <select v-model="selectedStatus" class="mb-3 w-full rounded-lg border border-gray-300 p-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-gray-900">
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                        </select>
                        <div v-if="['izin', 'sakit'].includes(selectedStatus)" class="mb-3">
                            <label for="checkin_description" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                            <textarea id="checkin_description" v-model="checkinDescription" class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none" :class="{ 'border-red-500': checkinDescriptionError }" placeholder="Masukkan keterangan" required></textarea>
                            <p v-if="checkinDescriptionError" class="text-red-500 text-sm mt-1">{{ checkinDescriptionError }}</p>
                        </div>
                        <p class="text-sm font-medium text-gray-900">
                            Status: <span :class="checkinStatus.includes('Sudah') ? 'text-green-600' : 'text-red-500'">{{ checkinStatus }}</span>
                        </p>
                        <button @click="checkIn" :disabled="checkinStatus.includes('Sudah') || processingIn" class="w-full rounded-xl px-4 py-2 text-sm font-medium transition-all duration-300" :class="checkinStatus.includes('Sudah') ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : 'bg-blue-600 text-white hover:bg-blue-700 active:scale-95'">
                            <span v-if="processingIn">Memproses...</span>
                            <span v-else>Absen Masuk</span>
                        </button>
                    </div>
                    <div class="rounded-2xl border border-gray-200 p-4 hover:bg-gray-50">
                        <h4 class="mb-3 font-medium text-gray-900">Absen Pulang</h4>
                        <p class="mb-3 text-sm font-medium text-gray-900">
                            Status: <span :class="checkoutStatus === 'Sudah Pulang' ? 'text-green-600' : 'text-gray-600'">{{ checkoutStatus }}</span>
                        </p>
                        <button @click="checkOut" :disabled="!canCheckout || checkoutStatus === 'Sudah Pulang' || processingOut" class="w-full rounded-xl px-4 py-2 text-sm font-medium transition-all duration-300" :class="!canCheckout || checkoutStatus === 'Sudah Pulang' ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : 'bg-green-600 text-white hover:bg-green-700 active:scale-95'">
                            <span v-if="processingOut">Memproses...</span>
                            <span v-else>Absen Pulang</span>
                        </button>
                    </div>
                    <div class="mt-4">
                        <select v-model="selectedStatus1" class="mb-3 w-full rounded-lg border border-gray-300 p-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-gray-900">
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                        </select>
                        <div v-if="['izin', 'sakit'].includes(selectedStatus1)" class="mb-3">
                            <label for="checkin_description" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                            <textarea id="checkin_description" v-model="checkinDescription" class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none" :class="{ 'border-red-500': checkinDescriptionError }" placeholder="Masukkan keterangan" required></textarea>
                            <p v-if="checkinDescriptionError" class="text-red-500 text-sm mt-1">{{ checkinDescriptionError }}</p>
                        </div>
                        <button @click="isScanning = true; scanResult = ''" :disabled="!canScanQR" class="flex w-full items-center justify-center gap-2 rounded-2xl px-4 py-3 text-white transition-all duration-300" :class="canScanQR ? 'bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 active:scale-95' : 'bg-gray-300 cursor-not-allowed'">
                            <QrCode class="h-5 w-5" /> Scan QR Absen
                        </button>
                        <p v-if="!canScanQR && checkinStatus === 'Sudah Absen' && ['izin', 'sakit'].includes(latestCheckinStatus ?? '')" class="mt-2 text-center text-sm text-red-500">Kamu izin/sakit tidak bisa absen pelajaran</p>
                        <p v-if="!canScanQR && checkinStatus === 'Belum Absen'" class="mt-2 text-center text-sm text-red-500">Absen Hadir dahulu sebelum absen pelajaran.</p>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-md hover:-translate-y-1 hover:shadow-lg">
                <div class="mb-6 flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-2xl bg-green-100 shadow-inner">
                        <CheckCircle class="h-5 w-5 text-green-600" />
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Absensi Pelajaran Terbaru</h3>
                </div>
                <div class="space-y-4">
                    <div v-for="(attendance, index) in props.recentAttendance.slice(0, 4)" :key="index" class="rounded-2xl border border-gray-200 p-4 hover:bg-gray-50">
                        <p class="text-sm font-medium text-gray-900">Mata Pelajaran: {{ attendance.name }}</p>
                        <p class="text-xs text-gray-500">Waktu: {{ attendance.time }}</p>
                        <p class="text-sm font-medium text-gray-900">Status: <span :class="`text-${attendance.color}-600`">{{ attendance.status }}</span></p>
                    </div>
                    <p v-if="props.recentAttendance.length === 0" class="text-center text-gray-500">Tidak ada data absensi pelajaran terbaru.</p>
                </div>
            </div>
        </div>

        <!-- Weekend Restriction Modal -->
        <div v-if="showWeekendModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm" @click.self="closeWeekendModal">
            <div class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white shadow-2xl" @click.stop>
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center gap-2">
                        <Calendar class="h-4 w-4 text-red-600" />
                        <h2 class="text-lg font-semibold text-gray-900">Aksi Tidak Diizinkan</h2>
                    </div>
                    <button @click="closeWeekendModal" type="button" class="text-gray-400 transition-colors hover:text-gray-600">✕</button>
                </div>
                <div class="p-6 space-y-4">
                    <p class="text-gray-600">Maaf, absensi tidak dapat dilakukan pada hari Sabtu atau Minggu.</p>
                    <button @click="closeWeekendModal" class="w-full rounded-xl bg-blue-600 py-3 text-white font-medium transition-colors hover:bg-blue-700">Tutup</button>
                </div>
            </div>
        </div>

        <!-- Modal Scanner -->
        <div v-if="isScanning" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm">
            <div class="animate-fadeIn w-full max-w-md scale-100 transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all">
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center gap-2">
                        <QrCode class="h-5 w-5 text-blue-600" />
                        <h2 class="text-lg font-semibold text-gray-900">Scan QR Code</h2>
                    </div>
                    <button @click="isScanning = false" class="text-gray-400 transition-colors hover:text-gray-600">✕</button>
                </div>
                <div class="p-6">
                    <div class="relative overflow-hidden rounded-2xl border-2 border-dashed border-gray-300">
                        <QrcodeStream @detect="onDetect" class="aspect-square w-full" />
                    </div>
                    <p class="mt-4 text-center text-sm text-gray-500">Arahkan kamera ke QR Code yang valid</p>
                    <div v-if="scanResult" class="mt-4 text-center">
                        <p class="font-semibold text-green-600">QR Code Terdeteksi:</p>
                        <p class="rounded bg-gray-100 p-2 font-mono">{{ scanResult }}</p>
                        <button @click="isScanning = true; scanResult = ''" class="mt-4 rounded-lg bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">Scan Ulang</button>
                    </div>
                    <p v-if="errorMessage" class="mt-4 text-center text-red-500">{{ errorMessage }}</p>
                </div>
                <div class="flex justify-between bg-gray-50 px-6 py-4">
                    <button @click="isScanning = false" class="mr-2 w-full rounded-2xl bg-gray-500 py-2 text-white transition-colors hover:bg-gray-600">Tutup</button>
                </div>
            </div>
        </div>

        <!-- Early Checkout Modal -->
        <div v-if="showEarlyCheckoutModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm" @click.self="closeEarlyCheckoutModal">
            <div class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white shadow-2xl" @click.stop>
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center gap-2">
                        <CheckCircle class="h-4 w-4 text-green-600" />
                        <h2 class="text-lg font-semibold text-gray-900">Kenapa Pulang Cepat?</h2>
                    </div>
                    <button @click="closeEarlyCheckoutModal" type="button" class="text-gray-400 transition-colors hover:text-gray-600">✕</button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label for="checkout_description" class="block text-sm font-medium text-gray-700 mb-2">Keterangan Pulang Cepat</label>
                        <textarea id="checkout_description" v-model="checkoutDescription" class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 placeholder-gray-400 focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:outline-none" :class="{ 'border-red-500': checkoutDescriptionError }" placeholder="Masukkan alasan pulang cepat" required></textarea>
                        <p v-if="checkoutDescriptionError" class="text-red-500 text-sm mt-1">{{ checkoutDescriptionError }}</p>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="closeEarlyCheckoutModal" class="flex-1 rounded-xl bg-gray-500 py-3 text-white font-medium transition-colors hover:bg-gray-600">Batal</button>
                        <button type="button" @click="performCheckout" :disabled="processingOut" class="flex-1 rounded-xl bg-green-600 py-3 text-white font-medium transition-colors hover:bg-green-700 disabled:bg-green-400">
                            <span v-if="processingOut">Memproses...</span>
                            <span v-else>Kirim</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Change Password Modal -->
        <div v-if="showChangePasswordModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm" @click.self="closePasswordModal">
            <div class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white shadow-2xl" @click.stop>
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center gap-2">
                        <Lock class="h-4 w-4 text-blue-600" />
                        <h2 class="text-lg font-semibold text-gray-900">Ubah Password</h2>
                    </div>
                    <button @click="closePasswordModal" type="button" class="text-gray-400 transition-colors hover:text-gray-600">✕</button>
                </div>
                <form @submit.prevent="submitPasswordChange" class="p-6 space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                        <input id="current_password" v-model="passwordForm.current_password" type="password" class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none" :class="{ 'border-red-500': passwordErrors.current_password }" placeholder="Masukkan password saat ini" autocomplete="current-password" required>
                        <p v-if="passwordErrors.current_password" class="text-red-500 text-sm mt-1">{{ passwordErrors.current_password[0] }}</p>
                    </div>
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                        <input id="new_password" v-model="passwordForm.new_password" type="password" class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none" :class="{ 'border-red-500': passwordErrors.new_password }" placeholder="Masukkan password baru" autocomplete="new-password" required>
                        <p v-if="passwordErrors.new_password" class="text-red-500 text-sm mt-1">{{ passwordErrors.new_password[0] }}</p>
                    </div>
                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                        <input id="new_password_confirmation" v-model="passwordForm.new_password_confirmation" type="password" class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none" :class="{ 'border-red-500': passwordErrors.new_password_confirmation }" placeholder="Konfirmasi password baru" autocomplete="new-password" required>
                        <p v-if="passwordErrors.new_password_confirmation" class="text-red-500 text-sm mt-1">{{ passwordErrors.new_password_confirmation[0] }}</p>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="closePasswordModal" class="flex-1 rounded-xl bg-gray-500 py-3 text-white font-medium transition-colors hover:bg-gray-600">Batal</button>
                        <button type="submit" :disabled="processingPassword" class="flex-1 rounded-xl bg-blue-600 py-3 text-white font-medium transition-colors hover:bg-blue-700 disabled:bg-blue-400">
                            <span v-if="processingPassword">Mengubah...</span>
                            <span v-else>Ubah Password</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Toast Notification -->
        <transition name="fade-scale">
            <div v-if="showToast" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
                <div class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-2xl">
                    <div v-if="toastType === 'success'" class="mx-auto mb-4 h-16 w-16">
                        <svg class="mx-auto h-16 w-16" viewBox="0 0 52 52">
                            <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none" />
                            <path class="checkmark-check" fill="none" d="M14 27l7 7 16-16" />
                        </svg>
                    </div>
                    <div v-else class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-100">
                        <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <h2 class="mb-2 text-xl font-semibold text-gray-900">{{ toastType === 'success' ? 'Berhasil' : 'Gagal' }}</h2>
                    <p class="mb-6 text-gray-600">{{ toastMessage }}</p>
                    <button @click="fetchStatus(); showToast = false" class="w-full rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 px-4 py-2 font-medium text-white shadow hover:from-blue-700 hover:to-purple-700">Tutup</button>
                </div>
            </div>
        </transition>
    </div>
</template>

<style scoped>
/* Checkmark animation for toast notification */
.checkmark-circle {
    stroke: #4CAF50;
    stroke-width: 2;
    stroke-miterlimit: 10;
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
}

.checkmark-check {
    stroke: #4CAF50;
    stroke-width: 2;
    stroke-miterlimit: 10;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
}

@keyframes stroke {
    100% {
        stroke-dashoffset: 0;
    }
}
</style>