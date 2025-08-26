<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { usePage, Link, router } from '@inertiajs/vue3'
import { QrcodeStream } from 'vue-qrcode-reader'
import { LogOut, Users, CheckCircle, BookOpen, Calendar, QrCode, Eye } from 'lucide-vue-next'

// Ambil user dari Inertia props
const page = usePage()
const studentName = ref(page.props.auth?.user?.name ?? 'siswa')
const currentDate = ref("")

// Status absen
const checkinStatus = ref("Belum Absen")
const checkoutStatus = ref("Belum Waktunya")
const canCheckout = ref(false)

// State proses
const processingIn = ref(false)
const processingOut = ref(false)

// Scanner
const scannerOpen = ref(false)
const scannedCode = ref("")

// Pilihan status absen masuk
const selectedStatus = ref("Hadir")

// Toast State
const toastMessage = ref("")
const toastType = ref<"success" | "error">("success")
const showToast = ref(false)

// Statistik dashboard
const stats = ref({
  totalKehadiran: 85,
  persentaseHadir: 94,
  totalKelas: 6,
  kelasAktif: 4,
  absenHariIni: "Hadir",
  waktuAbsen: "07:30"
})

// Data absensi terbaru
const recentAttendance = ref([
  { name: "Matematika", time: "07:30", status: "Hadir", color: "bg-green-500" },
  { name: "Bahasa Indonesia", time: "08:15", status: "Hadir", color: "bg-green-500" },
  { name: "Fisika", time: "09:00", status: "Terlambat", color: "bg-yellow-500" },
  { name: "Kimia", time: "10:30", status: "Hadir", color: "bg-green-500" },
])

const showNotification = (msg: string, type: "success" | "error" = "success") => {
  toastMessage.value = msg
  toastType.value = type
  showToast.value = true
  setTimeout(() => showToast.value = false, 3000)
}

// === Absen Masuk ===
const checkIn = () => {
  if (processingIn.value) return
  processingIn.value = true

  navigator.geolocation.getCurrentPosition((pos) => {
    router.post(route('absen.checkin'), {
      latitude: pos.coords.latitude,
      longitude: pos.coords.longitude,
      status: selectedStatus.value,
    }, {
      onSuccess: () => {
        checkinStatus.value = "Sudah Absen (" + selectedStatus.value + ")"
        stats.value.absenHariIni = selectedStatus.value
        showNotification("✅ Absen " + selectedStatus.value + " berhasil!", "success")
      },
      onError: () => {
        showNotification("❌ Gagal absen masuk!", "error")
      },
      onFinish: () => processingIn.value = false
    })
  }, () => {
    showNotification("❌ Akses lokasi ditolak!", "error")
    processingIn.value = false
  })
}

// === Absen Pulang ===
const checkOut = () => {
  if (processingOut.value) return
  processingOut.value = true

  navigator.geolocation.getCurrentPosition((pos) => {
    router.post(route('absen.checkout'), {
      latitude: pos.coords.latitude,
      longitude: pos.coords.longitude,
    }, {
      onSuccess: () => {
        checkoutStatus.value = "Sudah Pulang"
        showNotification("✅ Absen pulang berhasil!", "success")
      },
      onError: () => {
        showNotification("❌ Gagal absen pulang!", "error")
      },
      onFinish: () => processingOut.value = false
    })
  }, () => {
    showNotification("❌ Akses lokasi ditolak!", "error")
    processingOut.value = false
  })
}

// === Buka Scanner ===
const openQRScanner = () => {
  scannerOpen.value = true
}

// === Saat berhasil scan ===
const onDecode = (result: string) => {
  scannedCode.value = result
  scannerOpen.value = false

  router.post(route("absen.pelajaran"), { kode: result }, {
    onSuccess: () => showNotification("✅ Absen pelajaran berhasil!", "success"),
    onError: () => showNotification("❌ QR tidak valid!", "error")
  })
}

// === Error kamera scanner ===
const onInit = (promise: Promise<void>) => {
  promise.catch(() => {
    showNotification("❌ Kamera tidak bisa diakses!", "error")
    scannerOpen.value = false
  })
}

// === Tanggal & jam ===
onMounted(() => {
  const updateTime = () => {
    const now = new Date()
    currentDate.value = now.toLocaleDateString("id-ID", {
      weekday: "long",
      year: "numeric",
      month: "long",
      day: "numeric"
    })
    if (now.getHours() >= 14) {
      canCheckout.value = true
      if (checkoutStatus.value === "Belum Waktunya") {
        checkoutStatus.value = "Belum Absen Pulang"
      }
    }
  }
  updateTime()
  setInterval(updateTime, 60 * 1000)
})
</script>

<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
          <BookOpen class="w-6 h-6 text-white" />
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
        class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition-colors"
      >
        <LogOut class="w-4 h-4" />
        Logout
      </Link>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <!-- Total Kehadiran -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
          <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
            <Users class="w-6 h-6 text-blue-600" />
          </div>
          <span class="text-sm font-medium text-green-600">+12%</span>
        </div>
        <div class="mt-4">
          <p class="text-3xl font-bold text-gray-900">{{ stats.totalKehadiran }}</p>
          <p class="text-gray-600 text-sm">Total Kehadiran</p>
        </div>
      </div>

      <!-- Persentase Hadir -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
          <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
            <CheckCircle class="w-6 h-6 text-green-600" />
          </div>
          <span class="text-sm font-medium text-green-600">+8%</span>
        </div>
        <div class="mt-4">
          <p class="text-3xl font-bold text-gray-900">{{ stats.persentaseHadir }}%</p>
          <p class="text-gray-600 text-sm">Persentase Hadir</p>
        </div>
      </div>

      <!-- Total Kelas -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
          <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
            <BookOpen class="w-6 h-6 text-purple-600" />
          </div>
          <span class="text-sm font-medium text-blue-600">Aktif</span>
        </div>
        <div class="mt-4">
          <p class="text-3xl font-bold text-gray-900">{{ stats.totalKelas }}</p>
          <p class="text-gray-600 text-sm">Total Kelas</p>
        </div>
      </div>

      <!-- Mata Pelajaran -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
          <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
            <Calendar class="w-6 h-6 text-orange-600" />
          </div>
          <span class="text-sm font-medium text-green-600">Aktif</span>
        </div>
        <div class="mt-4">
          <p class="text-3xl font-bold text-gray-900">{{ stats.kelasAktif }}</p>
          <p class="text-gray-600 text-sm">Mata Pelajaran</p>
        </div>
      </div>
    </div>

    <!-- Action Cards and Recent Attendance -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Aksi Cepat -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-3 mb-6">
          <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
            <CheckCircle class="w-5 h-5 text-blue-600" />
          </div>
          <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
        </div>

        <div class="space-y-4">
          <!-- Absen Masuk -->
          <div class="border border-gray-200 rounded-lg p-4">
            <h4 class="font-medium text-gray-900 mb-3">Absen Masuk</h4>
<select
  v-model="selectedStatus"
  class="w-full p-2 border border-black-300 rounded-lg text-sm mb-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium text-gray-900 mb-3"
>
  <option value="Hadir"  class="font-medium text-gray-900 mb-3">Hadir</option>
  <option value="Izin"  class="font-medium text-gray-900 mb-3">Izin</option>
  <option value="Sakit" class="font-medium text-gray-900 mb-3">Sakit</option>
</select>
            <p class="text-sm font-medium text-gray-900 mb-3">
              Status: 
              <span :class="checkinStatus.includes('Sudah') ? 'text-green-600 font-medium' : 'text-red-500'">
                {{ checkinStatus }}
              </span>
            </p>
            <button
              @click="checkIn"
              :disabled="checkinStatus.includes('Sudah') || processingIn"
              class="w-full py-2 px-4 rounded-lg text-sm font-medium transition-all"
              :class="checkinStatus.includes('Sudah') ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700 text-white'"
            >
              <span v-if="processingIn">Memproses...</span>
              <span v-else>Absen Masuk</span>
            </button>
          </div>

          <!-- Absen Pulang -->
          <div class="border border-gray-200 rounded-lg p-4">
            <h4 class="font-medium text-gray-900 mb-3">Absen Pulang</h4>
            <p class="text-sm font-medium text-gray-900 mb-3">
              Status: 
              <span :class="checkoutStatus === 'Sudah Pulang' ? 'text-green-600 font-medium' : 'text-gray-600'">
                {{ checkoutStatus }}
              </span>
            </p>
            <button
              @click="checkOut"
              :disabled="!canCheckout || checkoutStatus === 'Sudah Pulang' || processingOut"
              class="w-full py-2 px-4 rounded-lg text-sm font-medium transition-all"
              :class="(!canCheckout || checkoutStatus === 'Sudah Pulang') ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700 text-white'"
            >
              <span v-if="processingOut">Memproses...</span>
              <span v-else>Absen Pulang</span>
            </button>
          </div>
        </div>

        <button
          @click="openQRScanner"
          class="w-full mt-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white py-3 px-4 rounded-lg font-medium flex items-center justify-center gap-2 transition-all"
        >
          <QrCode class="w-5 h-5" />
          Scan QR Absen
        </button>

        <button
          class="w-full mt-3 bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-medium flex items-center justify-center gap-2 transition-all"
        >
          <Eye class="w-5 h-5" />
          Lihat Absensi
        </button>
      </div>

      <!-- Absensi Terbaru -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-3 mb-6">
          <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
            <CheckCircle class="w-5 h-5 text-green-600" />
          </div>
          <h3 class="text-lg font-semibold text-gray-900">Absensi Terbaru</h3>
        </div>

        <div class="space-y-4">
          <div v-for="(item, index) in recentAttendance" :key="index" class="flex items-center gap-4">
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100">
              <span class="text-sm font-medium text-blue-600">{{ item.name.charAt(0) }}</span>
            </div>
            <div class="flex-1">
              <h4 class="font-medium text-gray-900">{{ item.name }}</h4>
              <p class="text-sm text-gray-500">{{ item.time }}</p>
            </div>
            <div class="flex items-center gap-2">
              <div :class="`w-2 h-2 rounded-full ${item.color}`"></div>
              <span class="text-sm font-medium" 
                    :class="item.status === 'Hadir' ? 'text-green-600' : item.status === 'Terlambat' ? 'text-yellow-600' : 'text-red-600'">
                {{ item.status }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Scanner -->
    <div
      v-if="scannerOpen"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
    >
      <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
        <div class="p-6">
          <h2 class="text-lg font-semibold mb-4 text-gray-900">Scan QR Code</h2>
          <div class="mb-4">
            <qrcode-stream @decode="onDecode" @init="onInit" class="rounded-lg overflow-hidden"></qrcode-stream>
          </div>
          <button
            @click="scannerOpen = false"
            class="w-full bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg transition-colors"
          >
            Tutup
          </button>
        </div>
      </div>
    </div>

    <!-- Toast -->
    <transition name="fade">
      <div
        v-if="showToast"
        class="fixed top-6 right-6 z-50 px-4 py-3 rounded-lg shadow-lg text-white max-w-sm"
        :class="toastType === 'success' ? 'bg-green-500' : 'bg-red-500'"
      >
        <p class="font-medium">{{ toastMessage }}</p>
      </div>
    </transition>
  </div>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>