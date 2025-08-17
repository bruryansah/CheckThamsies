<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <!-- Full background -->
    <div class="flex min-h-screen flex-col items-center justify-center bg-gradient-to-br from-blue-500 to-blue-700 p-6">
        <div class="flex w-full max-w-md flex-col gap-6">
            <!-- Logo -->
            <Link :href="route('home')" class="flex flex-col items-center gap-2 self-center font-medium">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-blue-100">
                    <AppLogoIcon class="size-10 text-blue-600" />
                </div>
            </Link>

            <!-- Card -->
            <div class="flex flex-col gap-6">
                <Card class="rounded-2xl shadow-xl border-0">
                    <CardHeader class="px-10 pt-8 pb-0 text-center">
                        <CardTitle class="text-2xl font-bold text-gray-800">
                            {{ title }}
                        </CardTitle>
                        <CardDescription class="text-gray-500">
                            {{ description }}
                        </CardDescription>
                        <p class="text-xs text-gray-400 mt-1">SMK Tamansiswa 2 Jakarta</p>
                    </CardHeader>
                    <CardContent class="px-10 py-8">
                        <slot />
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>
