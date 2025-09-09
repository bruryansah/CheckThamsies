<script setup lang="ts">
    import {
        Head,
        router,
        usePage
    } from '@inertiajs/vue3';
    import {
        BookOpen,
        Calendar,
        CheckCircle,
        ChevronDown,
        Lock,
        LogOut,
        QrCode,
        Settings,
        Users
    } from 'lucide-vue-next';
    import {
        onMounted,
        onUnmounted,
        ref,
        computed
    } from 'vue';
    import {
        QrcodeStream
    } from 'vue-qrcode-reader';
    import type {
        User as UserType
    } from '@/types';

    // Props dari Inertia
    const page = usePage();
    const studentName = ref(page.props.auth?.user?.name ?? 'siswa');

    // Status & tanggal
    const currentDate = ref('');
    const checkinStatus = ref('Belum Absen');
    const checkoutStatus = ref('Belum Pulang');
    const canCheckout = ref(false);
    const processingIn = ref(false);
    const processingOut = ref(false);

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
    const passwordErrors = ref < Record < string,
        string[] >> ({});
    const processingPassword = ref(false);

    // Dropdown functions
    const toggleDropdown = () => (dropdownOpen.value = !dropdownOpen.value);
    const closeDropdown = () => (dropdownOpen.value = false);

    // Logout
    const logout = () => {
        router.post(route('logout'));
        closeDropdown();
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

    // Toast Notification
    const toastMessage = ref('');
    const toastType = ref < 'success' | 'error' > ('success');
    const showToast = ref(false);
    const showNotification = (msg: string, type: 'success' | 'error' = 'success') => {
        toastMessage.value = msg;
        toastType.value = type;
        showToast.value = true;
        setTimeout(() => (showToast.value = false), 3000);
    };

    // QR Scanner Detect
    interface QrCodeResult {
        rawValue: string;
    }

    interface props {
        kehadiransekolah: number;
        persentaseKehadiran: number;
        totalPelajaranHariIni: number;
        auth: {
            user: UserType;
        };
    }
    const props = defineProps < props > ();

    // Stats
    const stats = computed(() => ({
        totalkehadiran: props.kehadiransekolah || 0,
        totalpresentase: props.persentaseKehadiran || 0,
        // Removed totalPelajaranHariIni
        absenHariIni: 'Belum Absen',
        waktuAbsen: '',
    }));

    // Recent Attendance
    const recentAttendance = ref([{
            name: 'Matematika',
            time: '07:30',
            status: 'Hadir',
            color: 'bg-green-500'
        },
        {
            name: 'Bahasa Indonesia',
            time: '08:15',
            status: 'Hadir',
            color: 'bg-green-500'
        },
        {
            name: 'Fisika',
            time: '09:00',
            status: 'Terlambat',
            color: 'bg-yellow-500'
        },
        {
            name: 'Kimia',
            time: '10:30',
            status: 'Hadir',
            color: 'bg-green-500'
        },
    ]);

    // Status Select
    const selectedStatus = ref('hadir');

    onMounted(() => {
        const changePassword = () => {
            // Open change password modal
            showChangePasswordModal.value = true;
            closeDropdown();
        };
        // Ambil status absensi dari backend
        fetch(route('absen.status'))
            .then((res) => res.json())
            .then((data) => {
                if (data.status === 'belum_masuk') {
                    checkinStatus.value = 'Belum Absen';
                    checkoutStatus.value = 'Belum Pulang';
                    canCheckout.value = false;
                } else if (data.status === 'sudah_masuk') {
                    checkinStatus.value = 'Sudah Absen';
                    checkoutStatus.value = 'Belum Pulang';
                    canCheckout.value = true;
                } else if (data.status === 'sudah_pulang') {
                    checkinStatus.value = 'Sudah Absen';
                    checkoutStatus.value = 'Sudah Pulang';
                    canCheckout.value = false;
                } else if (data.status === 'izin' || data.status === 'sakit') {
                    checkinStatus.value = `Sudah Absen (${data.status.charAt(0).toUpperCase() + data.status.slice(1)})`;
                    checkoutStatus.value = 'Tidak Perlu Pulang';
                    canCheckout.value = false;
                }
            });
    });

    // Helper: Tailwind colors for stats
    const statColor = (color: string) => {
        switch (color) {
            case 'blue':
                return 'bg-blue-100 text-blue-600';
            case 'green':
                return 'bg-green-100 text-green-600';
            case 'purple':
                return 'bg-purple-100 text-purple-600';
            case 'orange':
                return 'bg-orange-100 text-orange-600';
        }
        return '';
    };

    // Absen Masuk
    const checkIn = () => {
        if (processingIn.value) return;
        processingIn.value = true;

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const now = new Date();
                let status = selectedStatus.value;
                const batasJam = 8;
                const batasMenit = 0;
                if (status === 'hadir' && (now.getHours() > batasJam || (now.getHours() === batasJam && now
                        .getMinutes() > batasMenit))) {
                    status = 'Terlambat';
                }

                router.post(
                    route('absen.checkin'), {
                        latitude: pos.coords.latitude,
                        longitude: pos.coords.longitude,
                        status,
                    }, {
                        onSuccess: () => {
                            checkinStatus.value = 'Sudah Absen (' + status + ')';
                            stats.value.absenHariIni = status;
                            if (status === 'izin' || status === 'sakit') {
                                canCheckout.value = false;
                                checkoutStatus.value = 'Tidak Perlu Pulang';
                                showNotification(`✅ Absen ${status.charAt(0).toUpperCase() + status.slice(1)} berhasil! Anda tidak perlu absen pulang.`, 'success');
                            } else {
                                canCheckout.value = true;
                                showNotification('✅ Absen masuk berhasil!', 'success');
                            }
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

    // Absen Pulang
    const checkOut = () => {
        if (processingOut.value) return;
        processingOut.value = true;

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                router.post(
                    route('absen.checkout'), {
                        latitude: pos.coords.latitude,
                        longitude: pos.coords.longitude,
                    }, {
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

    // QR Scanner Detect
    interface QrCodeResult {
        rawValue: string;
    }

    const onDetect = (detectedCodes: QrCodeResult[]) => {
        if (detectedCodes.length > 0) {
            scanResult.value = detectedCodes[0].rawValue;
            isScanning.value = false;

            // Ambil id_jadwal dari string QR (misal: "jadwal:5")
            const id_jadwal = parseInt(scanResult.value.replace('jadwal:', ''));

            router.post(
                '/absensi-pelajaran/checkin', {
                    id_jadwal: id_jadwal,
                }, {
                    onSuccess: () => {
                        showNotification('✅ Absensi Pelajaran berhasil!', 'success');
                    },
                    onError: () => {
                        errorMessage.value = '❌ Gagal absen, coba lagi!';
                        showNotification('❌ Gagal absen, coba lagi!', 'error');
                    },
                },
            );
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
    onMounted(() => {
        const now = new Date();
        currentDate.value = now.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        // Close dropdown on click outside
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

            <!-- Settings Dropdown -->
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
                                @click="
                                    showChangePasswordModal = true;
                                    closeDropdown();
                                "
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
        <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2">
            <div v-for="(stat, i) in [
                    { icon: Users, value: stats.totalkehadiran, label: 'Total Kehadiran', color: 'blue',  },
                    { icon: CheckCircle, value: stats.totalpresentase + '%', label: 'Persentase Hadir', color: 'green',},
                    
                ]"
                :key="i"
                class="rounded-2xl border border-gray-200 bg-white p-6 shadow-md hover:-translate-y-1 hover:shadow-lg">
                <div class="flex items-center justify-between">
                    <div
                        :class="`${statColor(stat.color)} flex h-12 w-12 items-center justify-center rounded-2xl shadow-inner`">
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
                    <!-- Absen Masuk -->
                    <div class="rounded-2xl border border-gray-200 p-4 hover:bg-gray-50">
                        <h4 class="mb-3 font-medium text-gray-900">Absen Masuk</h4>
                        <select v-model="selectedStatus"
                            class="mb-3 font-medium text-gray-900 w-full rounded-lg border border-gray-300 p-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                            <option class="mb-3 font-medium text-gray-900" value="hadir">Hadir</option>
                            <option class="mb-3 font-medium text-gray-900" value="izin">Izin</option>
                            <option class="mb-3 font-medium text-gray-900" value="sakit">Sakit</option>
                        </select>
                        <p class="text-sm font-medium text-gray-900">
                            Status:
                            <span
                                :class="checkinStatus.includes('Sudah') ? 'font-medium text-green-600' : 'text-red-500'">{{ checkinStatus }}</span>
                        </p>
                        <button @click="checkIn" :disabled="checkinStatus.includes('Sudah') || processingIn"
                            class="w-full rounded-xl px-4 py-2 text-sm font-medium transition-all duration-300"
                            :class="checkinStatus.includes('Sudah') ?
                                'cursor-not-allowed bg-gray-100 text-gray-500' :
                                'bg-blue-600 text-white hover:bg-blue-700 active:scale-95'">
                            <span v-if="processingIn">Memproses...</span>
                            <span v-else>Absen Masuk</span>
                        </button>
                    </div>

                    <!-- Absen Pulang -->
                    <div class="rounded-2xl border border-gray-200 p-4 hover:bg-gray-50">
                        <h4 class="mb-3 font-medium text-gray-900">Absen Pulang</h4>
                        <p class="mb-3 text-sm font-medium text-gray-900">
                            Status:
                            <span
                                :class="checkoutStatus === 'Sudah Pulang' ? 'font-medium text-green-600' : 'text-gray-600'">{{ checkoutStatus }}</span>
                        </p>
                        <button @click="checkOut"
                            :disabled="!canCheckout || checkoutStatus === 'Sudah Pulang' || processingOut"
                            class="w-full rounded-xl px-4 py-2 text-sm font-medium transition-all duration-300"
                            :class="!canCheckout || checkoutStatus === 'Sudah Pulang' ?
                                'cursor-not-allowed bg-gray-100 text-gray-500' :
                                'bg-green-600 text-white hover:bg-green-700 active:scale-95'">
                            <span v-if="processingOut">Memproses...</span>
                            <span v-else>Absen Pulang</span>
                        </button>
                    </div>

                    <!-- QR Scan -->
                    <button
                        @click="
                            isScanning = true;
                            scanResult = '';
                        "
                        class="mt-4 flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-blue-600 to-purple-600 px-4 py-3 text-white hover:from-blue-700 hover:to-purple-700 active:scale-95">
                        <QrCode class="h-5 w-5" /> Scan QR Absen
                    </button>
                </div>
            </div>

            <!-- Recent Attendance -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-md hover:-translate-y-1 hover:shadow-lg">
                <div class="mb-6 flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-2xl bg-green-100 shadow-inner">
                        <CheckCircle class="h-5 w-5 text-green-600" />
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Absensi Terbaru</h3>
                </div>
                <div class="space-y-4">
                    <div v-for="(item, index) in recentAttendance" :key="index"
                        class="flex items-center gap-4 rounded-2xl p-2 hover:bg-gray-50">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100">
                            <span class="text-sm font-medium text-blue-600">{{ item.name.charAt(0) }}</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ item.name }}</h4>
                            <p class="text-sm text-gray-500">{{ item.time }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <div :class="`h-2 w-2 rounded-full ${item.color}`"></div>
                            <span class="text-sm font-medium"
                                :class="item.status === 'Hadir' ? 'text-green-600' : item.status === 'Terlambat' ?
                                    'text-yellow-600' : 'text-red-600'">{{ item.status }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Scanner -->
        <div v-if="isScanning"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm">
            <div
                class="animate-fadeIn w-full max-w-md scale-100 transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all">
                <!-- Header -->
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center gap-2">
                        <QrCode class="h-5 w-5 text-blue-600" />
                        <h2 class="text-lg font-semibold text-gray-900">Scan QR Code</h2>
                    </div>
                    <button @click="isScanning = false"
                        class="text-gray-400 transition-colors hover:text-gray-600">✕</button>
                </div>

                <!-- Scanner Body -->
                <div class="p-6">
                    <div class="relative overflow-hidden rounded-2xl border-2 border-dashed border-gray-300">
                        <QrcodeStream @detect="onDetect" class="aspect-square w-full" />
                    </div>
                    <p class="mt-4 text-center text-sm text-gray-500">Arahkan kamera ke QR Code yang valid</p>

                    <!-- Hasil Scan -->
                    <div v-if="scanResult" class="mt-4 text-center">
                        <p class="font-semibold text-green-600">QR Code Terdeteksi:</p>
                        <p class="rounded bg-gray-100 p-2 font-mono">{{ scanResult }}</p>
                        <button
                            @click="
                        isScanning = true;
                        scanResult = '';
                    "
                            class="mt-4 rounded-lg bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                            Scan Ulang
                        </button>
                    </div>

                    <!-- Error -->
                    <p v-if="errorMessage" class="mt-4 text-center text-red-500">{{ errorMessage }}</p>
                </div>

                <!-- Footer -->
                <div class="flex justify-between bg-gray-50 px-6 py-4">
                    <button @click="isScanning = false"
                        class="mr-2 w-full rounded-2xl bg-gray-500 py-2 text-white transition-colors hover:bg-gray-600">
                        Tutup
                    </button>
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
                        <Lock class="h-5 w-5 text-blue-600" />
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
                            placeholder="Masukkan password baru" autocomplete="new-password" required />
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
        <!-- Elegant Popup Notification dengan Animasi Centang -->
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