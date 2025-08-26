<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { usePage, Link, router } from '@inertiajs/vue3'
import { QrcodeStream } from 'vue-qrcode-reader'
import { LogOut } from 'lucide-vue-next'

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
  <div class="min-h-screen bg-gradient-to-br from-indigo-100 via-purple-100 to-pink-100 p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
      <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
        📊 Dashboard
      </h1>
      <Link
        as="button"
        method="post"
        :href="route('logout')"
        class="flex items-center gap-2 bg-gradient-to-r from-red-500 to-red-600 hover:scale-105 transition-transform text-white px-5 py-2.5 rounded-full font-medium shadow-lg"
      >
        <LogOut class="w-5 h-5" />
        Keluar
      </Link>
    </div>

    <!-- Welcome -->
    <div
      class="backdrop-blur-xl bg-white/60 rounded-3xl shadow-2xl p-6 mb-10 border border-white/40 transition hover:shadow-3xl"
    >
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <h2 class="text-2xl font-bold text-gray-800">
            👋 Halo, <span class="text-indigo-600">{{ studentName }}</span>
          </h2>
        </div>
        <div class="text-right">
          <p class="text-sm text-gray-500">Hari ini</p>
          <p class="text-lg font-semibold text-purple-600">{{ currentDate }}</p>
        </div>
      </div>
    </div>

    <!-- Grid Buttons -->
    <div class="grid gap-6 md:grid-cols-3">
      <!-- Absen Masuk -->
      <div class="backdrop-blur-xl bg-gradient-to-br from-green-100/80 to-green-50/80 rounded-2xl shadow-xl p-6 border border-green-200/40 hover:shadow-2xl transition">
        <h3 class="text-xl font-semibold mb-4 text-green-700">⏰ Absen Masuk</h3>

        <label class="block mb-2 text-sm font-medium text-gray-700">Pilih Status</label>
        <select
          v-model="selectedStatus"
          class="w-full p-2.5 border rounded-lg text-gray-800 bg-white shadow-sm focus:ring-2 focus:ring-green-400"
        >
          <option value="Hadir">Hadir</option>
          <option value="Izin">Izin</option>
          <option value="Sakit">Sakit</option>
        </select>

        <p class="text-sm mt-3 mb-3">
          Status:
          <span
            :class="checkinStatus.includes('Sudah') ? 'text-green-600 font-semibold' : 'text-red-500'"
          >
            {{ checkinStatus }}
          </span>
        </p>
        <button
          @click="checkIn"
          :disabled="checkinStatus.includes('Sudah') || processingIn"
          class="w-full py-3 rounded-full font-medium shadow-md flex justify-center items-center transition-all"
          :class="checkinStatus.includes('Sudah') ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'bg-gradient-to-r from-green-500 to-green-600 hover:scale-105 text-white'"
        >
          <span v-if="processingIn">⏳ Proses...</span>
          <span v-else>🚀 Absen Masuk</span>
        </button>
      </div>

      <!-- Absen Pulang -->
      <div class="backdrop-blur-xl bg-gradient-to-br from-blue-100/80 to-blue-50/80 rounded-2xl shadow-xl p-6 border border-blue-200/40 hover:shadow-2xl transition">
        <h3 class="text-xl font-semibold mb-4 text-blue-700">🏠 Absen Pulang</h3>
        <p class="text-sm mb-3">
          Status:
          <span
            :class="checkoutStatus === 'Sudah Pulang' ? 'text-blue-600 font-semibold' : 'text-gray-600'"
          >
            {{ checkoutStatus }}
          </span>
        </p>
        <button
          @click="checkOut"
          :disabled="!canCheckout || checkoutStatus === 'Sudah Pulang' || processingOut"
          class="w-full py-3 rounded-full font-medium shadow-md flex justify-center items-center transition-all"
          :class="(!canCheckout || checkoutStatus === 'Sudah Pulang') ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'bg-gradient-to-r from-blue-500 to-blue-600 hover:scale-105 text-white'"
        >
          <span v-if="processingOut">⏳ Proses...</span>
          <span v-else>🔓 Absen Pulang</span>
        </button>
      </div>

      <!-- QR Absen -->
      <div class="backdrop-blur-xl bg-gradient-to-br from-purple-100/80 to-pink-50/80 rounded-2xl shadow-xl p-6 border border-purple-200/40 hover:shadow-2xl transition">
        <h3 class="text-xl font-semibold mb-4 text-purple-700">📷 Absen Pelajaran</h3>
        <p class="text-sm mb-3 text-gray-600">
          Scan QR Code yang ditampilkan guru untuk absen pelajaran
        </p>
        <button
          @click="openQRScanner"
          class="w-full bg-gradient-to-r from-purple-500 to-pink-500 hover:scale-105 text-white py-3 rounded-full font-medium shadow-md transition-all"
        >
          🎯 Buka Scanner QR
        </button>
      </div>
    </div>

    <!-- Modal Scanner -->
    <div
      v-if="scannerOpen"
      class="fixed inset-0 backdrop-blur-md bg-black/60 flex items-center justify-center z-50"
    >
      <div class="bg-white rounded-2xl shadow-xl p-6 w-[90%] md:w-[420px] animate-fadeIn">
        <h2 class="text-lg font-bold mb-4 text-gray-800">🔍 Scan QR Code</h2>
        <qrcode-stream @decode="onDecode" @init="onInit"></qrcode-stream>
        <button
          @click="scannerOpen = false"
          class="mt-6 w-full bg-red-500 hover:bg-red-600 text-white py-3 rounded-full shadow-md transition"
        >
          ❌ Tutup
        </button>
      </div>
    </div>

    <!-- Toast -->
    <transition name="fade">
      <div
        v-if="showToast"
        class="fixed top-6 right-6 z-50 px-5 py-3 rounded-xl shadow-lg flex items-center gap-3 text-white"
        :class="toastType === 'success' ? 'bg-green-500' : 'bg-red-500'"
      >
        <span class="text-lg">{{ toastMessage }}</span>
      </div>
    </transition>
  </div>
</template>

<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.4s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
.animate-fadeIn {
  animation: fadeIn 0.3s ease-out;
}
@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.9); }
  to { opacity: 1; transform: scale(1); }
}
</style>
