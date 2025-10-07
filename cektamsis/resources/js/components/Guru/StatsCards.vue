<template>
    <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <div
            v-for="(stat, index) in stats"
            :key="index"
            class="group transform rounded-2xl border border-white/30 bg-white/80 p-6 shadow-lg backdrop-blur-md transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl"
            :style="{ animationDelay: `${index * 100}ms` }"
        >
            <div class="mb-4 flex items-center justify-between">
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-xl transition-transform duration-300 group-hover:scale-110"
                    :class="stat.bgColor"
                >
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="stat.icon"></path>
                    </svg>
                </div>
                <span :class="stat.textColor" class="text-sm font-medium">
                    {{ stat.change }}
                </span>
            </div>
            <h3 class="mb-1 text-2xl font-bold text-gray-900">
                {{ stat.value }}
            </h3>
            <p class="text-sm text-gray-600">
                {{ stat.label }}
            </p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    stats: {
        type: Array,
        required: true,
    },
});
</script>
