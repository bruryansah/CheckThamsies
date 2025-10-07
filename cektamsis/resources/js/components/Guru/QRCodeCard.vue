<template>
    <div v-if="qrImage" class="rounded-2xl border border-white/30 bg-white/80 p-6 shadow-lg backdrop-blur-md">
        <h2 class="mb-4 flex items-center text-xl font-bold text-gray-900">
            <svg class="mr-2 h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M12 12v4.01M12 12V8.01"
                ></path>
            </svg>
            QR Code Absensi
        </h2>
        <div class="text-center">
            <div class="mb-4 inline-block rounded-2xl bg-white p-4 shadow-inner">
                <div
                    id="qrcode"
                    class="mx-auto flex h-48 w-48 items-center justify-center rounded-xl border-4 border-dashed border-gray-300 bg-gray-50"
                >
                    <!-- image injected by generateQRCode -->
                </div>
            </div>
            <p class="mb-2 text-sm text-gray-600">
                {{ selectedJadwalLabel }}
            </p>
            <p class="text-xs text-gray-500">Siswa dapat memindai QR code ini untuk melakukan absensi</p>
            <button
                @click="$emit('refresh')"
                class="mt-4 rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white transition-colors duration-300 hover:bg-blue-600"
            >
                Refresh QR Code
            </button>
        </div>
    </div>
</template>

<script setup>
defineProps({
    qrImage: String,
    selectedJadwalLabel: String,
});

defineEmits(['refresh']);
</script>
