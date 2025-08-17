<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const name = page.props.name;
const quote = page.props.quote;

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="relative grid h-screen lg:grid-cols-2">
        <!-- Left panel -->
        <div class="relative hidden h-full flex-col bg-gradient-to-br from-blue-600 to-blue-800 p-10 text-white lg:flex">
            <!-- Overlay gelap biar teks tetap terbaca -->
            <div class="absolute inset-0 bg-black/40 rounded-none" />

            <!-- Logo + Nama -->
            <Link :href="route('home')" class="relative z-20 flex items-center text-lg font-semibold">
                <AppLogoIcon class="mr-2 size-9 text-white" />
                {{ name }}
            </Link>

            <!-- Quote -->
            <div v-if="quote" class="relative z-20 mt-auto max-w-lg">
                <blockquote class="space-y-3">
                    <p class="text-2xl font-semibold leading-snug">&ldquo;{{ quote.message }}&rdquo;</p>
                    <footer class="text-sm text-blue-100">— {{ quote.author }}</footer>
                </blockquote>
            </div>
        </div>

        <!-- Right panel (form) -->
        <div class="flex items-center justify-center p-8 bg-white">
            <div class="w-full max-w-md space-y-6">
                <div class="text-center space-y-2">
                    <h1 v-if="title" class="text-2xl font-bold text-gray-800">{{ title }}</h1>
                    <p v-if="description" class="text-sm text-gray-500">{{ description }}</p>
                    <p class="text-xs text-gray-400">SMK Tamansiswa 2 Jakarta</p>
                </div>

                <!-- Slot form -->
                <slot />
            </div>
        </div>
    </div>
</template>
