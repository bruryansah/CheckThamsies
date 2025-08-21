<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">    pindahin semua Dashboard ke sini (buat user biasa)
</h1>

    <!-- Tombol logout -->
    <button
      @click="logout"
      class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
    >
      Logout
    </button>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'

const logout = () => {
  router.post(route('logout'))
}
</script>
