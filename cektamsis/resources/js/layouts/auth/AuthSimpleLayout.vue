<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <!-- Full background -->
    <div class="flex min-h-screen flex-col items-center justify-center bg-gradient-to-br from-blue-500 to-blue-700 p-6">
        <!-- Card -->
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
            <div class="flex flex-col gap-8">
                <!-- Logo & Title -->
                <div class="flex flex-col items-center gap-4">
                    <Link :href="route('home')" class="flex flex-col items-center gap-2 font-medium">
                        <div class="mb-1 flex h-16 w-16 items-center justify-center rounded-full bg-blue-100">
                            <AppLogoIcon class="size-10 text-blue-600" />
                        </div>
                        <span class="sr-only">{{ title }}</span>
                    </Link>
                    <div class="space-y-2 text-center">
                        <h1 class="text-2xl font-bold text-gray-800">{{ title }}</h1>
                        <p class="text-center text-sm text-gray-500">{{ description }}</p>
                        <p class="text-xs text-gray-400">SMK Tamansiswa 2 Jakarta</p>
                    </div>
                </div>

                <!-- Slot untuk form login/register -->
                <slot />
            </div>
        </div>
    </div>
</template>
