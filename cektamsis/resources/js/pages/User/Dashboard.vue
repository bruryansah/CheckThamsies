<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import type { User } from '@/types';
import { LogOut } from 'lucide-vue-next';

// Ambil user dari props inertia
const page = usePage();
const user = page.props.auth?.user as User;

// State siswa (ambil dari user, bukan dummy)
const studentName = ref(user?.name || "");
const kelas = ref(user?.id_kelas || "-");
const currentDate = ref("");

// Status absen
const checkinStatus = ref("Belum Absen");
const checkoutStatus = ref("Belum Waktunya");
const canCheckout = ref(false);

// Fungsi untuk absen masuk
const checkIn = () => {
  checkinStatus.value = "Sudah Absen";
};

// Fungsi untuk absen pulang
const checkOut = () => {
  if (canCheckout.value) {
    checkoutStatus.value = "Sudah Pulang";
  }
};

// QR Scanner (dummy sementara)
const openQRScanner = () => {
  alert("Buka Scanner QR...");
};

// Set tanggal otomatis
onMounted(() => {
  const today = new Date();
  currentDate.value = today.toLocaleDateString("id-ID", {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric"
  });

  // Simulasi tombol checkout aktif setelah jam 14:00
  const hour = today.getHours();
  if (hour >= 14) {
    canCheckout.value = true;
    checkoutStatus.value = "Belum Absen Pulang";
  }
});
</script>

<template>
  <div class="container mx-auto px-4 py-6 space-y-6">
    <!-- Header + Logout -->
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
      <Link
        as="button"
        method="post"
        :href="route('logout')"
        class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium shadow-sm transition-all"
      >
        <LogOut class="w-4 h-4" />
        Logout
      </Link>
    </div>

    <!-- Welcome Card -->
    <div class="bg-white rounded-xl shadow p-6 animate-fade-in">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold text-gray-800">
            Selamat Datang, <span class="text-blue-600">{{ studentName }}</span>!
          </h2>
          <p class="text-gray-600 mt-1">Kelas {{ kelas }}</p>
        </div>
        <div class="text-right">
          <p class="text-sm text-gray-500">Hari ini</p>
          <p class="text-base font-semibold text-blue-600">{{ currentDate }}</p>
        </div>
      </div>
    </div>

    <!-- Attendance Buttons -->
    <div class="grid md:grid-cols-2 gap-6">
      <!-- Absen Masuk -->
      <div class="bg-white rounded-xl shadow p-6 animate-fade-in">
        <h3 class="text-lg font-semibold mb-4 flex items-center">
          <i class="fas fa-clock text-green-500 mr-2"></i>Absen Masuk
        </h3>
        <div class="mb-4">
          <p class="text-sm text-gray-600 mb-2">
            Status: <span class="text-red-500">{{ checkinStatus }}</span>
          </p>
          <p class="text-xs text-gray-500">Pastikan lokasi GPS aktif</p>
        </div>
        <button
          @click="checkIn"
          class="w-full bg-green-500 text-white py-3 rounded-lg font-medium hover:bg-green-600 transition-all"
        >
          <i class="fas fa-map-marker-alt mr-2"></i>Absen Masuk
        </button>
      </div>

      <!-- Absen Pulang -->
      <div class="bg-white rounded-xl shadow p-6 animate-fade-in">
        <h3 class="text-lg font-semibold mb-4 flex items-center">
          <i class="fas fa-home text-blue-500 mr-2"></i>Absen Pulang
        </h3>
        <div class="mb-4">
          <p class="text-sm text-gray-600 mb-2">
            Status: <span>{{ checkoutStatus }}</span>
          </p>
          <p class="text-xs text-gray-500">Tersedia setelah jam 14:00</p>
        </div>
        <button 
          @click="checkOut" 
          :disabled="!canCheckout"
          :class="[canCheckout ? 'bg-blue-500 hover:bg-blue-600 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed']"
          class="w-full py-3 rounded-lg font-medium transition-all"
        >
          <i class="fas fa-map-marker-alt mr-2"></i>Absen Pulang
        </button>
      </div>
    </div>

    <!-- QR Scanner for Class Attendance -->
    <div class="bg-white rounded-xl shadow p-6 animate-fade-in">
      <h3 class="text-lg font-semibold mb-4 flex items-center">
        <i class="fas fa-qrcode text-purple-500 mr-2"></i>Absen Pelajaran
      </h3>
      <p class="text-gray-600 mb-4">
        Scan QR Code yang ditampilkan guru untuk absen pelajaran
      </p>
      <button
        @click="openQRScanner"
        class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-3 rounded-lg font-medium transition-all"
      >
        <i class="fas fa-camera mr-2"></i>Buka Scanner QR
      </button>
    </div>
  </div>
</template>
