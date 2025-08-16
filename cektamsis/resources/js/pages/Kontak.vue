<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 text-gray-800">
    <h1 class="text-3xl font-bold mb-4">Halaman Tentang</h1>
    <p class="text-lg max-w-md text-center">
      Ini adalah halaman tentang website absensi sekolah dengan Laravel + Vue (Inertia).
    </p>

    <Link
      href="/"
      class="mt-6 px-4 py-2 bg-indigo-600 text-white rounded-lg shadow-md hover:bg-indigo-700 transition duration-200"
    >
      Kembali ke Home
    </Link>
  </div>
</template>
