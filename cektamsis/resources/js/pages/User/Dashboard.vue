<script setup lang="ts">
import type { User as UserType } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import {
    BookOpen, Calendar, CheckCircle, ChevronDown, Lock, LogOut,
    QrCode, Settings, Users, TrendingUp, AlertTriangle, Filter, BarChart3
} from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import { QrcodeStream } from 'vue-qrcode-reader';
import Absensi from '@/components/siswa/absensi.vue'

interface AbsensiPelajaran {
    id: number;
    mata_pelajaran: string;
    status: string;
    tanggal: string;
    waktu: string;
    keterangan?: string;
}

interface StatsPelajaran {
    total: number;
    hadir: number;
    terlambat: number;
    izin: number;
    sakit: number;
    alfa: number;
}

interface Props {
    kehadiransekolah: number;
    persentaseKehadiran: number;
    totalSakit: number;
    totalIzin: number;
    totalAbsensi: number;
    totalAlfa: number;
    totalTerlambat?: number;
    recentAttendance: Array<{ name: string; time: string; status: string; verifikasi: string; color: string }>;
    auth: { user: UserType };
    jadwalData: Array<{
        id: number; idenc: string; mata_pelajaran: string; hari: string;
        jam_selesai: string; jam_mulai: string; nama_kelas: string; nama_guru: string;
    }>;
    // Data absensi pelajaran untuk statistik
    absensiPelajaranData?: AbsensiPelajaran[];
    statsPelajaran?: StatsPelajaran;
}
const props = defineProps<Props>();

const page = usePage();
const studentName = ref(page.props.auth?.user?.name ?? 'siswa');
const showAbsensiModal = ref(false);
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
const showWeekendModal = ref(false);
const canCheckIn = ref(true);
const showLateModal = ref(false);
const lateDescription = ref('');
const lateDescriptionError = ref('');
const selectedMapel = ref('');
const selectedStatus1 = ref('hadir');
const isScanning = ref(false);
const scanResult = ref('');
const errorMessage = ref('');
const dropdownOpen = ref(false);
const showChangePasswordModal = ref(false);
const passwordForm = ref({ current_password: '', new_password: '', new_password_confirmation: '' });
const passwordErrors = ref<Record<string, string[]>>({});
const processingPassword = ref(false);
const selectedStatus = ref('hadir');

// Filter untuk statistik pelajaran
const selectedMapelFilter = ref('all');

// Daftar mata pelajaran unik
const uniqueMapel = computed(() => {
    const mapelSet = new Set<string>();
    props.jadwalData?.forEach(j => mapelSet.add(j.mata_pelajaran));
    props.absensiPelajaranData?.forEach(a => mapelSet.add(a.mata_pelajaran));
    return Array.from(mapelSet).sort();
});

// Statistik absensi pelajaran
const pelajaranStats = computed(() => {
    const data = props.absensiPelajaranData || [];

    const filteredData = selectedMapelFilter.value === 'all'
        ? data
        : data.filter(a => a.mata_pelajaran === selectedMapelFilter.value);

    const total = filteredData.length || 1;

    let hadir = 0, alfa = 0, izin = 0, sakit = 0;

    filteredData.forEach(a => {
        const status = (a.status || '').trim().toLowerCase();
        switch(status) {
            case 'hadir':
                hadir++;
                break;
            case 'alfa':
            case 'alpha':
                alfa++;
                break;
            case 'izin':
                izin++;
                break;
            case 'sakit':
                sakit++;
                break;
            // status 'terlambat' atau lainnya diabaikan
        }
    });

    return {
        hadirPct: Math.round((hadir / total) * 100),
        alfaPct: Math.round((alfa / total) * 100),
        izinPct: Math.round((izin / total) * 100),
        sakitPct: Math.round((sakit / total) * 100),
        totalHadir: hadir,
        totalAlfa: alfa,
        totalIzin: izin,
        totalSakit: sakit,
        totalPertemuan: filteredData.length,
    };
});


// Statistik per mata pelajaran (untuk ringkasan)
const perMapelStats = computed(() => {
    const data = props.absensiPelajaranData || [];
    const statsMap: Record<string, { hadir: number; terlambat: number; alfa: number; izin: number; sakit: number; total: number }> = {};

    data.forEach(a => {
        const status = (a.status || '').toLowerCase();
        if (status === 'terlambat') return; // abaikan 'terlambat' dari statistik per mapel
        const mapel = a.mata_pelajaran;
        if (!statsMap[mapel]) {
            statsMap[mapel] = { hadir: 0, terlambat: 0, alfa: 0, izin: 0, sakit: 0, total: 0 };
        }
        statsMap[mapel].total++;
        if (status === 'hadir') statsMap[mapel].hadir++;
        else if (status === 'alfa' || status === 'alpha') statsMap[mapel].alfa++;
        else if (status === 'izin') statsMap[mapel].izin++;
        else if (status === 'sakit') statsMap[mapel].sakit++;
    });

    return Object.entries(statsMap).map(([mapel, s]) => ({
        mapel,
        ...s,
        hadirPct: Math.round((s.hadir / (s.total || 1)) * 100),
    })).sort((a, b) => b.hadirPct - a.hadirPct);
});

const canSubmitLessonCheckIn = computed(() => {
    if (['izin', 'sakit'].includes(selectedStatus1.value)) {
        return (['hadir', 'terlambat'].includes(latestCheckinStatus.value || '') &&
            selectedMapel.value && checkinDescription.value.trim() && !processingIn.value);
    }
    return false;
});

const toggleDropdown = () => (dropdownOpen.value = !dropdownOpen.value);
const closeDropdown = () => (dropdownOpen.value = false);

const logout = () => {
    router.post(route('logout'), {}, {
        onSuccess: () => {
            checkinStatus.value = 'Belum Absen';
            checkoutStatus.value = 'Belum Pulang';
            canCheckout.value = false;
            canScanQR.value = false;
            latestCheckinStatus.value = null;
            closeDropdown();
        },
    });
};

const closePasswordModal = () => {
    showChangePasswordModal.value = false;
    passwordForm.value = { current_password: '', new_password: '', new_password_confirmation: '' };
    passwordErrors.value = {};
};

const closeEarlyCheckoutModal = () => {
    showEarlyCheckoutModal.value = false;
    checkoutDescription.value = '';
    checkoutDescriptionError.value = '';
};

const closeWeekendModal = () => { showWeekendModal.value = false; };
const closeLateModal = () => {
    showLateModal.value = false;
    lateDescription.value = '';
    lateDescriptionError.value = '';
};

const toastMessage = ref('');
const toastType = ref<'success' | 'error'>('success');
const showToast = ref(false);
const showNotification = (msg: string, type: 'success' | 'error' = 'success') => {
    toastMessage.value = msg;
    toastType.value = type;
    showToast.value = true;
    setTimeout(() => (showToast.value = false), 3000);
};

const stats = computed(() => ({
    totalkehadiran: props.kehadiransekolah || 0,
    totalpresentase: props.persentaseKehadiran || 0,
    totalsakit: props.totalSakit || 0,
    totalizin: props.totalIzin || 0,
    totalalfa: props.totalAlfa || 0,
    absenHariIni: 'Belum Absen',
    waktuAbsen: '',
}));

const statColor = (color: string) => {
    switch (color) {
        case 'blue': return 'bg-blue-100 text-blue-600';
        case 'green': return 'bg-green-100 text-green-600';
        case 'purple': return 'bg-purple-100 text-purple-600';
        case 'orange': return 'bg-orange-100 text-orange-600';
        case 'red': return 'bg-red-100 text-red-600';
    }
    return '';
};

const isWeekend = () => { const today = new Date().getDay(); return today === 0 || today === 6; };

const updateCheckInStatus = () => {
    const now = new Date();
    const timeInMinutes = now.getHours() * 60 + now.getMinutes();
    const startHadir = 6 * 60 + 40, endTerlambat = 13 * 60 + 40;
    if (latestCheckinStatus.value === null) {
        if (timeInMinutes < startHadir) { checkinStatus.value = 'Belum Bisa Absen'; canCheckIn.value = false; }
        else if (timeInMinutes > endTerlambat) { checkinStatus.value = 'Alfa'; canCheckIn.value = false; }
        else { checkinStatus.value = 'Belum Absen'; canCheckIn.value = true; }
    } else { checkinStatus.value = 'Sudah Absen'; canCheckIn.value = false; }
};

const fetchStatus = async () => {
    try {
        const response = await fetch(route('absen.status'));
        const data = await response.json();
        if (data.status === 'belum_masuk') {
            checkinStatus.value = 'Belum Absen'; checkoutStatus.value = 'Belum Pulang';
            canCheckout.value = false; canScanQR.value = false; latestCheckinStatus.value = null;
        } else if (data.status === 'sudah_masuk') {
            checkinStatus.value = 'Sudah Absen'; checkoutStatus.value = 'Belum Pulang';
            const latestStatus = await fetch(route('absen.latest-status')).then((res) => res.json());
            latestCheckinStatus.value = latestStatus.status;
            canCheckout.value = !['izin', 'sakit', 'alfa'].includes(latestStatus.status);
            canScanQR.value = ['hadir', 'terlambat'].includes(latestStatus.status);
        } else if (data.status === 'sudah_pulang') {
            checkinStatus.value = 'Sudah Absen'; checkoutStatus.value = 'Sudah Pulang';
            canCheckout.value = false; canScanQR.value = false; latestCheckinStatus.value = null;
        }
    } catch (error) {
        checkinStatus.value = 'Belum Absen'; checkoutStatus.value = 'Belum Pulang';
        canCheckout.value = false; canScanQR.value = false; latestCheckinStatus.value = null;
    }
    updateCheckInStatus();
};

const refreshAttendance = async () => {
    await router.get(route('user.dashboard'), {}, { onSuccess: () => {} });
};

const checkIn = () => {
    if (isWeekend()) { showWeekendModal.value = true; return; }
    if (!canCheckIn.value) { showNotification('❌ Absen tidak diizinkan.', 'error'); return; }
    if (processingIn.value) return;
    const now = new Date();
    const timeInMinutes = now.getHours() * 60 + now.getMinutes();
    const startHadir = 6 * 60 + 40, endHadir = 7 * 60 + 10, endTerlambat = 13 * 60 + 40;
    let determinedStatus = selectedStatus.value;
    if (!['izin', 'sakit'].includes(selectedStatus.value)) {
        if (timeInMinutes < startHadir) { showNotification('⏰ Absensi mulai jam 06:40', 'error'); return; }
        else if (timeInMinutes >= startHadir && timeInMinutes <= endHadir) { determinedStatus = 'hadir'; performCheckIn(determinedStatus, ''); return; }
        else if (timeInMinutes > endHadir && timeInMinutes <= endTerlambat) { showLateModal.value = true; return; }
        else if (timeInMinutes > endTerlambat) { showNotification('❌ Waktu sudah melewati 13:40', 'error'); return; }
    }
    if (['izin', 'sakit'].includes(determinedStatus) && !checkinDescription.value.trim()) {
        checkinDescriptionError.value = 'Keterangan diperlukan'; showNotification(checkinDescriptionError.value, 'error'); return;
    }
    performCheckIn(determinedStatus, checkinDescription.value);
};

const submitLateCheckIn = () => {
    lateDescriptionError.value = '';
    if (!lateDescription.value.trim()) { lateDescriptionError.value = 'Keterangan diperlukan'; showNotification(lateDescriptionError.value, 'error'); return; }
    showLateModal.value = false;
    performCheckIn('terlambat', lateDescription.value);
};

const performCheckIn = (status: string, description: string) => {
    processingIn.value = true;
    navigator.geolocation.getCurrentPosition(
        (pos) => {
            router.post(route('absen.checkin'), { latitude: pos.coords.latitude, longitude: pos.coords.longitude, status, description: description || '' }, {
                onSuccess: () => {
                    fetchStatus(); latestCheckinStatus.value = status;
                    if (['izin', 'sakit', 'alfa'].includes(status)) { canCheckout.value = false; canScanQR.value = false; checkoutStatus.value = 'Tidak Perlu Pulang'; }
                    else { canCheckout.value = true; canScanQR.value = ['hadir', 'terlambat'].includes(status); }
                    showNotification(`✅ Absen ${status} berhasil!`, 'success');
                    checkinDescription.value = ''; lateDescription.value = ''; refreshAttendance();
                },
                onError: (errors) => { showNotification(`❌ ${errors.message || 'Absensi gagal'}`, 'error'); },
                onFinish: () => (processingIn.value = false),
            });
        },
        () => { showNotification('❌ Akses lokasi ditolak!', 'error'); processingIn.value = false; }
    );
};

const checkOut = () => {
    if (isWeekend()) { showWeekendModal.value = true; return; }
    if (processingOut.value) return;
    const now = new Date();
    if (now.getHours() < 13 || (now.getHours() === 13 && now.getMinutes() < 39)) { showEarlyCheckoutModal.value = true; return; }
    performCheckout();
};

const performCheckout = () => {
    if (processingOut.value) return;
    if (showEarlyCheckoutModal.value && !checkoutDescription.value.trim()) { checkoutDescriptionError.value = 'Keterangan diperlukan'; showNotification(checkoutDescriptionError.value, 'error'); return; }
    processingOut.value = true;
    navigator.geolocation.getCurrentPosition(
        (pos) => {
            const payload: Record<string, any> = { latitude: pos.coords.latitude, longitude: pos.coords.longitude };
            if (showEarlyCheckoutModal.value) payload.keterangan = checkoutDescription.value;
            router.post(route('absen.checkout'), payload, {
                onSuccess: () => { fetchStatus(); showNotification('✅ Absen pulang berhasil!', 'success'); closeEarlyCheckoutModal(); refreshAttendance(); },
                onError: (errors) => { showNotification('❌ Gagal absen pulang! ' + (errors.error || ''), 'error'); },
                onFinish: () => (processingOut.value = false),
            });
        },
        () => { showNotification('❌ Akses lokasi ditolak!', 'error'); processingOut.value = false; }
    );
};

const lessonCheckIn = () => {
    checkinDescriptionError.value = '';
    if (['izin', 'sakit'].includes(selectedStatus1.value)) {
        if (!selectedMapel.value) { checkinDescriptionError.value = 'Pilih mata pelajaran'; showNotification(checkinDescriptionError.value, 'error'); return; }
        if (!checkinDescription.value?.trim()) { checkinDescriptionError.value = 'Keterangan diperlukan'; showNotification(checkinDescriptionError.value, 'error'); return; }
    }
    router.post('/absensi-pelajaran/checkin', { id_jadwal: selectedMapel.value, status: selectedStatus1.value, description: checkinDescription.value?.trim() || '' }, {
        onSuccess: () => { fetchStatus?.(); refreshAttendance?.(); showNotification(`✅ Absensi Pelajaran berhasil!`, 'success'); selectedMapel.value = ''; checkinDescription.value = ''; },
        onError: (errors) => { showNotification(errors?.message?.includes('sudah absen') ? '❌ Sudah absen untuk jadwal ini.' : `❌ ${errors?.message || 'Gagal absen'}`, 'error'); },
    });
};

interface QrCodeResult { rawValue: string; }
const onDetect = (detectedCodes: QrCodeResult[]) => {
    if (detectedCodes.length > 0) {
        scanResult.value = detectedCodes[0].rawValue;
        isScanning.value = false;
        const id_jadwal = scanResult.value.replace('jadwal:', '');
        if (!id_jadwal) { errorMessage.value = '❌ Format QR tidak valid!'; showNotification(errorMessage.value, 'error'); return; }
        if (['izin', 'sakit'].includes(selectedStatus1.value) && !checkinDescription.value.trim()) { checkinDescriptionError.value = 'Keterangan diperlukan'; showNotification(checkinDescriptionError.value, 'error'); return; }
        router.post('/absensi-pelajaran/checkin', { id_jadwal, status: selectedStatus1.value, description: checkinDescription.value }, {
            onSuccess: () => { fetchStatus(); showNotification(`✅ Absensi Pelajaran berhasil!`, 'success'); refreshAttendance(); checkinDescription.value = ''; },
            onError: (errors) => { errorMessage.value = errors.message || '❌ Gagal absen!'; showNotification(errorMessage.value, 'error'); },
        });
    }
};

const submitPasswordChange = () => {
    if (processingPassword.value) return;
    processingPassword.value = true; passwordErrors.value = {};
    router.put(route('student.password.update'), passwordForm.value, {
        onSuccess: () => { showNotification('✅ Password berhasil diubah!', 'success'); closePasswordModal(); },
        onError: (errors) => { passwordErrors.value = Object.fromEntries(Object.entries(errors).map(([key, val]) => [key, Array.isArray(val) ? val : [val]])); showNotification('❌ Gagal mengubah password!', 'error'); },
        onFinish: () => (processingPassword.value = false),
    });
};

onMounted(async () => {
    currentDate.value = new Date().toLocaleString('id-ID', { timeZone: 'Asia/Jakarta', weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' });
    fetchStatus(); updateCheckInStatus();
    const interval = setInterval(() => { fetchStatus(); updateCheckInStatus(); }, 60000);
    const handleClick = (e: Event) => { const dropdown = document.querySelector('.dropdown-container'); if (dropdown && !dropdown.contains(e.target as Node)) closeDropdown(); };
    document.addEventListener('click', handleClick);
    onUnmounted(() => { document.removeEventListener('click', handleClick); clearInterval(interval); });
});

// Tambahan untuk collapsible ringkasan per mapel
const showPerMapelSummary = ref(false);
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
                <button @click="toggleDropdown" class="flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2 font-medium text-gray-700 hover:bg-gray-200">
                    <Settings class="h-4 w-4" /> Pengaturan <ChevronDown class="h-4 w-4" :class="{ 'rotate-180': dropdownOpen }" />
                </button>
                <transition name="dropdown">
                    <div v-if="dropdownOpen" class="absolute top-full right-0 z-10 mt-2 w-48 rounded-xl border border-gray-200 bg-white shadow-lg">
                        <div class="p-2">
                            <button @click="showChangePasswordModal = true; closeDropdown();" class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left text-gray-700 hover:bg-gray-100">
                                <Lock class="h-4 w-4" /> Ubah Password
                            </button>
                            <button @click="logout" class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left text-red-600 hover:bg-red-50">
                                <LogOut class="h-4 w-4" /> Logout
                            </button>
                        </div>
                    </div>
                </transition>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-5">
            <div v-for="(stat, i) in [
                { icon: Users, value: stats.totalkehadiran, label: 'Total Kehadiran', color: 'blue' },
                { icon: Users, value: stats.totalalfa, label: 'Total Alfa', color: 'red' },
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
            <!-- Card Aksi Cepat -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-md hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                <div class="mb-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-2xl bg-blue-100 shadow-inner">
                            <CheckCircle class="h-5 w-5 text-blue-600" />
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
                    </div>
                    <button @click="showAbsensiModal = true" class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-xl shadow-sm transition-colors duration-200">
                        Lihat Data Absensi
                    </button>
                </div>
                <div class="space-y-4">
                    <!-- Absensi Masuk -->
                    <div class="rounded-2xl border border-gray-200 p-4 hover:bg-gray-50">
                        <h4 class="mb-3 font-medium text-gray-900">Absensi Masuk</h4>
                        <select v-model="selectedStatus" class="mb-3 w-full rounded-lg border border-gray-300 p-2 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                        </select>
                        <div v-if="['izin', 'sakit'].includes(selectedStatus)" class="mb-3">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea v-model="checkinDescription" class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none" :class="{ 'border-red-500': checkinDescriptionError }" placeholder="Masukkan keterangan" required></textarea>
                            <p v-if="checkinDescriptionError" class="mt-1 text-sm text-red-500">{{ checkinDescriptionError }}</p>
                        </div>
                        <p class="text-sm font-medium text-gray-900">Status: <span :class="{ 'font-semibold text-green-600': checkinStatus === 'Sudah Absen', 'font-semibold text-orange-600': checkinStatus === 'Terlambat', 'font-semibold text-red-600': checkinStatus === 'Alfa', 'text-gray-600': checkinStatus === 'Belum Absen' }">{{ checkinStatus }}</span></p>
                        <button @click="checkIn" :disabled="!canCheckIn || checkinStatus.includes('Sudah') || processingIn" class="w-full rounded-xl px-4 py-2 text-sm font-medium transition-all duration-300" :class="!canCheckIn || checkinStatus.includes('Sudah') ? 'cursor-not-allowed bg-gray-100 text-gray-500' : 'bg-blue-600 text-white hover:bg-blue-700 active:scale-95'">
                            <span v-if="processingIn">Memproses...</span><span v-else>Absensi Masuk</span>
                        </button>
                    </div>
                    <!-- Absensi Pulang -->
                    <div class="rounded-2xl border border-gray-200 p-4 hover:bg-gray-50">
                        <h4 class="mb-3 font-medium text-gray-900">Absensi Pulang</h4>
                        <p class="mb-3 text-sm font-medium text-gray-900">Status: <span :class="checkoutStatus === 'Sudah Pulang' ? 'text-green-600' : 'text-gray-600'">{{ checkoutStatus }}</span></p>
                        <button @click="checkOut" :disabled="!canCheckout || checkoutStatus === 'Sudah Pulang' || processingOut" class="w-full rounded-xl px-4 py-2 text-sm font-medium transition-all duration-300" :class="!canCheckout || checkoutStatus === 'Sudah Pulang' ? 'cursor-not-allowed bg-gray-100 text-gray-500' : 'bg-green-600 text-white hover:bg-green-700 active:scale-95'">
                            <span v-if="processingOut">Memproses...</span><span v-else>Absensi Pulang</span>
                        </button>
                    </div>
                    <!-- QR Scan / Izin Pelajaran -->
                    <div class="mt-4">
                        <select v-model="selectedStatus1" class="mb-3 w-full rounded-lg border border-gray-300 p-2 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                        </select>
                        <div v-if="['izin', 'sakit'].includes(selectedStatus1)" class="mb-3">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Pilih Mata Pelajaran</label>
                            <select v-model="selectedMapel" class="mb-3 w-full rounded-lg border border-gray-300 p-2 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                <option value="" disabled>Pilih mata pelajaran</option>
                                <option v-for="jadwal in props.jadwalData" :key="jadwal.id" :value="jadwal.id">{{ jadwal.mata_pelajaran }} - {{ jadwal.nama_guru }} ({{ jadwal.nama_kelas }}) ({{ jadwal.jam_mulai }}–{{ jadwal.jam_selesai }})</option>
                            </select>
                            <label class="mb-2 block text-sm font-medium text-gray-700">Keterangan</label>
                            <textarea v-model="checkinDescription" class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none" :class="{ 'border-red-500': checkinDescriptionError }" placeholder="Masukkan keterangan" required></textarea>
                            <p v-if="checkinDescriptionError" class="mt-1 text-sm text-red-500">{{ checkinDescriptionError }}</p>
                            <button @click="lessonCheckIn" :disabled="!canSubmitLessonCheckIn" class="flex w-full items-center justify-center gap-2 rounded-2xl px-4 py-3 text-white transition-all duration-300" :class="canSubmitLessonCheckIn ? 'bg-blue-600 hover:bg-blue-700 active:scale-95' : 'cursor-not-allowed bg-gray-300'">
                                <CheckCircle class="h-5 w-5" /> OK
                            </button>
                        </div>
                        <div v-else>
                            <button @click="isScanning = true; scanResult = '';" :disabled="!canScanQR" class="flex w-full items-center justify-center gap-2 rounded-2xl px-4 py-3 text-white transition-all duration-300" :class="canScanQR ? 'bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 active:scale-95' : 'cursor-not-allowed bg-gray-300'">
                                <QrCode class="h-5 w-5" /> Scan QR Absen
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Column Kanan -->
            <div class="space-y-6">
                <!-- Card Absensi Terbaru -->
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-md hover:-translate-y-1 hover:shadow-lg">
                    <div class="mb-6 flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-2xl bg-green-100 shadow-inner">
                            <CheckCircle class="h-5 w-5 text-green-600" />
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Absensi Pelajaran Terbaru</h3>
                    </div>
                    <div class="space-y-4">
                        <div v-for="(attendance, index) in props.recentAttendance.slice(0, 4)" :key="index" class="rounded-2xl border border-gray-200 p-4 hover:bg-gray-50 transition-all duration-200">
                            <div class="flex items-center justify-between mb-1">
                                <p class="font-semibold text-gray-900">{{ attendance.name }}</p>
                                <span class="text-xs font-medium rounded-full px-2 py-0.5" :class="{ 'bg-green-100 text-green-600': attendance.color === 'green', 'bg-orange-100 text-orange-600': attendance.color === 'orange', 'bg-purple-100 text-purple-600': attendance.color === 'purple', 'bg-red-100 text-red-600': attendance.color === 'red', 'bg-gray-100 text-gray-600': attendance.color === 'gray' }">{{ attendance.status }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mb-1">Waktu: {{ attendance.time }}</p>
                            <p v-if="['Izin', 'Sakit'].includes(attendance.status)" class="text-xs text-gray-500">Verifikasi: <span :class="{ 'bg-green-100 text-green-700 border border-green-300': attendance.verifikasi === 'approve', 'bg-red-100 text-red-700 border border-red-300': attendance.verifikasi === 'disapprove', 'bg-yellow-100 text-yellow-700 border border-yellow-300': attendance.verifikasi === 'menunggu' || !attendance.verifikasi }" class="px-2 py-0.5 rounded-full text-[11px] font-medium">{{ attendance.verifikasi || 'Menunggu' }}</span></p>
                        </div>
                        <p v-if="props.recentAttendance.length === 0" class="text-center text-gray-500 text-sm">Tidak ada data absensi pelajaran terbaru.</p>
                    </div>
                </div>

                <!-- ⭐ Card Statistik Absensi Pelajaran -->
                <details class="rounded-2xl border border-gray-200 bg-white p-6 shadow-md hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                    <summary class="flex items-center justify-between cursor-pointer mb-4 list-none">
                        <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-2xl bg-indigo-100 shadow-inner">
                            <BarChart3 class="h-5 w-5 text-indigo-600" />
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Statistik Absensi Pelajaran</h3>
                        </div>
                        <span class="text-gray-500">▼</span>
                    </summary>

                    <!-- Filter Mata Pelajaran -->
                    <div class="mb-4">
                        <div class="flex items-center gap-2 mb-2">
                        <Filter class="h-4 w-4 text-gray-500" />
                        <label class="text-sm font-medium text-gray-700">Filter Mata Pelajaran</label>
                        </div>
                        <select v-model="selectedMapelFilter" class="w-full rounded-lg border border-gray-300 p-2 text-sm text-gray-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                        <option value="all">📚 Semua Mata Pelajaran</option>
                        <option v-for="mapel in uniqueMapel" :key="mapel" :value="mapel">{{ mapel }}</option>
                        </select>
                    </div>

                    <!-- Progress Bars -->
                    <div class="space-y-3">
                        <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Hadir</span>
                            <span class="text-sm font-semibold text-green-600">{{ pelajaranStats.hadirPct }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full transition-all duration-500" :style="{ width: `${pelajaranStats.hadirPct}%` }"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">{{ pelajaranStats.totalHadir }} pertemuan</p>
                        </div>
                        <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Alfa</span>
                            <span class="text-sm font-semibold text-red-600">{{ pelajaranStats.alfaPct }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-red-500 h-2 rounded-full transition-all duration-500" :style="{ width: `${pelajaranStats.alfaPct}%` }"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">{{ pelajaranStats.totalAlfa }} pertemuan</p>
                        </div>
                        <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Izin</span>
                            <span class="text-sm font-semibold text-blue-600">{{ pelajaranStats.izinPct }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full transition-all duration-500" :style="{ width: `${pelajaranStats.izinPct}%` }"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">{{ pelajaranStats.totalIzin }} pertemuan</p>
                        </div>
                        <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Sakit</span>
                            <span class="text-sm font-semibold text-purple-600">{{ pelajaranStats.sakitPct }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-500 h-2 rounded-full transition-all duration-500" :style="{ width: `${pelajaranStats.sakitPct}%` }"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">{{ pelajaranStats.totalSakit }} pertemuan</p>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="grid grid-cols-2 gap-3">
                        <div class="text-center p-3 bg-indigo-50 rounded-xl">
                            <p class="text-xl font-bold text-indigo-600">{{ pelajaranStats.totalPertemuan }}</p>
                            <p class="text-xs text-gray-600">Total Pertemuan</p>
                        </div>
                        <div class="text-center p-3 bg-green-50 rounded-xl">
                            <p class="text-xl font-bold text-green-600">{{ pelajaranStats.hadirPct }}%</p>
                            <p class="text-xs text-gray-600">Tingkat Kehadiran</p>
                        </div>
                        </div>
                    </div>

                    <!-- Ringkasan Per Mapel -->
                    <div v-if="selectedMapelFilter === 'all' && perMapelStats.length > 0" class="mt-4 pt-4 border-t border-gray-200">
                        <button @click="showPerMapelSummary = !showPerMapelSummary" class="flex items-center justify-between w-full text-sm font-semibold text-gray-700 mb-3 hover:text-gray-900 transition-colors">
                        📊 Ringkasan Per Mata Pelajaran
                        <ChevronDown class="h-4 w-4 transition-transform duration-300" :class="{ 'rotate-180': showPerMapelSummary }" />
                        </button>
                        <transition name="fade-scale">
                        <div v-if="showPerMapelSummary" class="space-y-2 max-h-48 overflow-y-auto">
                            <div v-for="(item, idx) in perMapelStats" :key="idx" class="flex items-center justify-between p-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer" @click="selectedMapelFilter = item.mapel">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate">{{ item.mapel }}</p>
                                <p class="text-xs text-gray-500">{{ item.total }} pertemuan</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-16 bg-gray-200 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full transition-all duration-300" :class="item.hadirPct >= 80 ? 'bg-green-500' : item.hadirPct >= 60 ? 'bg-yellow-500' : 'bg-red-500'" :style="{ width: `${item.hadirPct}%` }"></div>
                                </div>
                                <span class="text-xs font-semibold w-10 text-right" :class="item.hadirPct >= 80 ? 'text-green-600' : item.hadirPct >= 60 ? 'text-yellow-600' : 'text-red-600'">{{ item.hadirPct }}%</span>
                            </div>
                            </div>
                        </div>
                        </transition>
                    </div>

                    <!-- Alerts -->
                    <div v-if="pelajaranStats.alfaPct > 15" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-xl flex items-start gap-2">
                        <AlertTriangle class="h-5 w-5 text-red-500 flex-shrink-0 mt-0.5" />
                        <div>
                        <p class="text-sm font-medium text-red-700">Perhatian!</p>
                        <p class="text-xs text-red-600">Tingkat alfa pada {{ selectedMapelFilter === 'all' ? 'beberapa mata pelajaran' : selectedMapelFilter }} cukup tinggi.</p>
                        </div>
                    </div>
                    <div v-else-if="pelajaranStats.hadirPct >= 85" class="mt-4 p-3 bg-green-50 border border-green-200 rounded-xl flex items-start gap-2">
                        <CheckCircle class="h-5 w-5 text-green-500 flex-shrink-0 mt-0.5" />
                        <div>
                        <p class="text-sm font-medium text-green-700">Bagus! 🎉</p>
                        <p class="text-xs text-green-600">Kehadiran {{ selectedMapelFilter === 'all' ? 'kamu' : 'di ' + selectedMapelFilter }} sangat baik!</p>
                        </div>
                    </div>

                    </details>

            </div>
        </div>

        <Absensi v-if="showAbsensiModal" @close="showAbsensiModal = false" />

        <!-- Late Modal -->
        <Teleport to="body">
            <div v-if="showLateModal" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm" @click.self="closeLateModal">
                <div class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white shadow-2xl" @click.stop>
                    <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                        <div class="flex items-center gap-2">
                            <CheckCircle class="h-5 w-5 text-orange-600" />
                            <h2 class="text-lg font-semibold text-gray-900">Kenapa Terlambat?</h2>
                        </div>
                        <button @click="closeLateModal" type="button" class="text-gray-400 hover:text-gray-600 text-2xl">✕</button>
                    </div>
                    <div class="space-y-4 p-6">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">Keterangan Terlambat <span class="text-red-500">*</span></label>
                            <textarea v-model="lateDescription" class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 placeholder-gray-400 focus:border-orange-500 focus:ring-2 focus:ring-orange-500 focus:outline-none" :class="{ 'border-red-500': lateDescriptionError }" placeholder="Contoh: Terlambat bangun, macet, dll." rows="4" required autofocus></textarea>
                            <p v-if="lateDescriptionError" class="mt-1 text-sm text-red-500">{{ lateDescriptionError }}</p>
                        </div>
                        <div class="flex gap-3 pt-4">
                            <button type="button" @click="closeLateModal" class="flex-1 rounded-xl bg-gray-500 py-3 font-medium text-white hover:bg-gray-600">Batal</button>
                            <button type="button" @click="submitLateCheckIn" :disabled="processingIn" class="flex-1 rounded-xl bg-orange-600 py-3 font-medium text-white hover:bg-orange-700 disabled:bg-orange-400">
                                <span v-if="processingIn">Memproses...</span><span v-else>Kirim Absensi</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>

        <!-- Weekend Modal -->
        <div v-if="showWeekendModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm" @click.self="closeWeekendModal">
            <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl" @click.stop>
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center gap-2"><Calendar class="h-4 w-4 text-red-600" /><h2 class="text-lg font-semibold text-gray-900">Aksi Tidak Diizinkan</h2></div>
                    <button @click="closeWeekendModal" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>
                <div class="space-y-4 p-6">
                    <p class="text-gray-600">Absensi tidak dapat dilakukan pada hari Sabtu atau Minggu.</p>
                    <button @click="closeWeekendModal" class="w-full rounded-xl bg-blue-600 py-3 font-medium text-white hover:bg-blue-700">Tutup</button>
                </div>
            </div>
        </div>

        <!-- Scanner Modal -->
        <div v-if="isScanning" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl">
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center gap-2"><QrCode class="h-5 w-5 text-blue-600" /><h2 class="text-lg font-semibold text-gray-900">Scan QR Code</h2></div>
                    <button @click="isScanning = false" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>
                <div class="p-6">
                    <div class="rounded-2xl border-2 border-dashed border-gray-300 overflow-hidden"><QrcodeStream @detect="onDetect" class="aspect-square w-full" /></div>
                    <p class="mt-4 text-center text-sm text-gray-500">Arahkan kamera ke QR Code</p>
                    <div v-if="scanResult" class="mt-4 text-center">
                        <p class="font-semibold text-green-600">QR Terdeteksi:</p>
                        <p class="rounded bg-gray-100 p-2 font-mono text-sm">{{ scanResult }}</p>
                    </div>
                    <p v-if="errorMessage" class="mt-4 text-center text-red-500">{{ errorMessage }}</p>
                </div>
                <div class="bg-gray-50 px-6 py-4"><button @click="isScanning = false" class="w-full rounded-2xl bg-gray-500 py-2 text-white hover:bg-gray-600">Tutup</button></div>
            </div>
        </div>

        <!-- Early Checkout Modal -->
        <div v-if="showEarlyCheckoutModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm" @click.self="closeEarlyCheckoutModal">
            <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl" @click.stop>
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center gap-2"><CheckCircle class="h-4 w-4 text-green-600" /><h2 class="text-lg font-semibold text-gray-900">Kenapa Pulang Cepat?</h2></div>
                    <button @click="closeEarlyCheckoutModal" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>
                <div class="space-y-4 p-6">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Keterangan Pulang Cepat</label>
                        <textarea v-model="checkoutDescription" class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 placeholder-gray-400 focus:border-green-500 focus:ring-2 focus:ring-green-500 focus:outline-none" :class="{ 'border-red-500': checkoutDescriptionError }" placeholder="Masukkan alasan" required></textarea>
                        <p v-if="checkoutDescriptionError" class="mt-1 text-sm text-red-500">{{ checkoutDescriptionError }}</p>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button @click="closeEarlyCheckoutModal" class="flex-1 rounded-xl bg-gray-500 py-3 font-medium text-white hover:bg-gray-600">Batal</button>
                        <button @click="performCheckout" :disabled="processingOut" class="flex-1 rounded-xl bg-green-600 py-3 font-medium text-white hover:bg-green-700 disabled:bg-green-400">
                            <span v-if="processingOut">Memproses...</span><span v-else>Kirim</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Change Password Modal -->
        <div v-if="showChangePasswordModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm" @click.self="closePasswordModal">
            <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl" @click.stop>
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center gap-2"><Lock class="h-4 w-4 text-blue-600" /><h2 class="text-lg font-semibold text-gray-900">Ubah Password</h2></div>
                    <button @click="closePasswordModal" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>
                <form @submit.prevent="submitPasswordChange" class="space-y-4 p-6">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Password Saat Ini</label>
                        <input v-model="passwordForm.current_password" type="password" class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none" :class="{ 'border-red-500': passwordErrors.current_password }" placeholder="Password saat ini" required />
                        <p v-if="passwordErrors.current_password" class="mt-1 text-sm text-red-500">{{ passwordErrors.current_password[0] }}</p>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Password Baru</label>
                        <input v-model="passwordForm.new_password" type="password" class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none" :class="{ 'border-red-500': passwordErrors.new_password }" placeholder="Password baru" required />
                        <p v-if="passwordErrors.new_password" class="mt-1 text-sm text-red-500">{{ passwordErrors.new_password[0] }}</p>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                        <input v-model="passwordForm.new_password_confirmation" type="password" class="w-full rounded-lg border border-gray-300 p-3 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none" :class="{ 'border-red-500': passwordErrors.new_password_confirmation }" placeholder="Konfirmasi password" required />
                        <p v-if="passwordErrors.new_password_confirmation" class="mt-1 text-sm text-red-500">{{ passwordErrors.new_password_confirmation[0] }}</p>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="closePasswordModal" class="flex-1 rounded-xl bg-gray-500 py-3 font-medium text-white hover:bg-gray-600">Batal</button>
                        <button type="submit" :disabled="processingPassword" class="flex-1 rounded-xl bg-blue-600 py-3 font-medium text-white hover:bg-blue-700 disabled:bg-blue-400">
                            <span v-if="processingPassword">Mengubah...</span><span v-else>Ubah Password</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Toast -->
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
                    <button @click="fetchStatus(); showToast = false;" class="w-full rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 px-4 py-2 font-medium text-white shadow hover:from-blue-700 hover:to-purple-700">Tutup</button>
                </div>
            </div>
        </transition>
    </div>
</template>

<style scoped>
.checkmark-circle { stroke: #4caf50; stroke-width: 2; stroke-miterlimit: 10; stroke-dasharray: 166; stroke-dashoffset: 166; animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards; }
.checkmark-check { stroke: #4caf50; stroke-width: 2; stroke-miterlimit: 10; stroke-dasharray: 48; stroke-dashoffset: 48; animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards; }
@keyframes stroke { 100% { stroke-dashoffset: 0; } }
.fade-scale-enter-active, .fade-scale-leave-active { transition: all 0.3s ease; }
.fade-scale-enter-from, .fade-scale-leave-to { opacity: 0; transform: scale(0.9); }
</style>
