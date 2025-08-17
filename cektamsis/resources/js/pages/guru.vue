<script setup lang="ts">
    import TextLink from '@/components/TextLink.vue';
import AppLayout from '@/layouts/AppLayout.vue';
    import {
        type BreadcrumbItem
    } from '@/types';
    import {
        Head
    } from '@inertiajs/vue3';
    import {
        Plus
    } from 'lucide-vue-next';
    import {
        defineProps
    } from 'vue'

    const breadcrumbs: BreadcrumbItem[] = [{
            title: 'Dashboard',
            href: '/dashboard',
        },
        {
            title: 'Data Guru',
            href: '/guru',
        },
    ];
    interface Guru {
        id_guru: number
        nama: string
        email: string
        kelas: string
        mapel: string
    }

    // contoh dummy data
    const props = defineProps < {
        guru: Guru[]
    } > ()
</script>

<template>

    <Head title="Data Guru" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
            <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-lg">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-xl font-semibold text-white">Data Guru</h1>
                    <TextLink :href="route('guru.tambah')"
                        class="flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition">
                        <Plus class="h-4 w-4" />
                        Tambah Guru
                    </TextLink>
                </div>

                <!-- Table -->
                <div class="overflow-hidden rounded-lg border border-zinc-800">
                    <table class="min-w-full divide-y divide-zinc-800">
                        <thead class="bg-zinc-800">
                            <tr>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Id Guru
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Nama
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Email
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Kelas
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Mapel
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800 bg-zinc-900 text-sm text-zinc-200">
                            <tr v-for="guru in props.guru" :key="guru.id_guru"
                                class="hover:bg-zinc-800/60 transition">
                                <td class="px-6 py-4 text-center">{{ guru . id_guru }}</td>
                                <td class="px-6 py-4 text-center">{{ guru . nama }}</td>
                                <td class="px-6 py-4 text-center">{{ guru . email }}</td>
                                <td class="px-6 py-4 text-center">{{ guru . kelas }}</td>
                                <td class="px-6 py-4 text-center">{{ guru . mapel }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <TextLink :href="route('guru.edit', guru.id_guru)"
                                            class="rounded-lg bg-yellow-500 px-3 py-1 text-xs font-semibold text-white hover:bg-yellow-600">
                                            Edit
                                        </TextLink>
                                        <button
                                            class="rounded-lg bg-red-600 px-3 py-1 text-xs font-semibold text-white hover:bg-red-700">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
