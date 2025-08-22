<script setup lang="ts">
    import TextLink from '@/components/TextLink.vue'
    import AppLayout from '@/layouts/AppLayout.vue'
    import {
        type BreadcrumbItem
    } from '@/types'
    import {
        Head,
        useForm,
        Link
    } from '@inertiajs/vue3'
    import {
        watch
    } from 'vue'

    const breadcrumbs: BreadcrumbItem[] = [{
            title: 'Dashboard',
            href: '/dashboard'
        },
        {
            title: 'Tambah Data',
            href: '/siswaxi'
        },
    ]

    const props = defineProps < {
        users: {
            id: number;
            name: string;
            email: string;
        } []
        kelas: {
            id_kelas: number;
            nama_kelas: string;
        } []
        jurusan: {
            id_jurusan: number;
            nama_jurusan: string;
        } []
    } > ()

    const form = useForm({
        user_id: '',
        nama: '',
        email: '',
        id_kelas: '', // Default ke ID kelas X RPL
        id_jurusan: '', // Default ke ID jurusan RPL
    })

    // 🔹 Hanya pantau perubahan email berdasarkan user yang dipilih
    watch(() => form.user_id, (newId, oldId) => {
        if (newId) {
            // Cari user yang sesuai dengan id yang dipilih
            const selectedUser = props.users.find(user => user.id === Number(newId))
            if (selectedUser) {
                // Hanya isi email otomatis, nama biarkan manual
                form.email = selectedUser.email
            }
        } else {
            // Kalau tidak pilih user, reset email saja
            form.email = ''
        }
    })
</script>

<template>

    <Head title="Data Siswa" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
            <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-lg max-w-2xl w-full mx-auto">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-xl font-semibold text-white">Form Data Siswa XI RPL</h1>
                </div>

                <!-- Form -->
                <form @submit.prevent="form.post('/siswaxi/tambahstore')" class="space-y-5">
                    <!-- Id Users -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Pilih User</label>
                        <select v-model="form.user_id"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none">
                            <option value="">-- Pilih User --</option>
                            <option v-for="user in props.users" :key="user.id" :value="user.id">
                                {{ user.name }}
                            </option>
                        </select>
                        <span v-if="form.errors.user_id"
                            class="text-red-500 text-sm">{{ form.errors.user_id }}</span>
                    </div>

                    <!-- Nama (Manual Input) -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Nama Siswa</label>
                        <input v-model="form.nama" type="text"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none"
                            placeholder="Masukkan nama siswa secara manual" />
                        <span v-if="form.errors.nama" class="text-red-500 text-sm">{{ form.errors.nama }}</span>
                    </div>

                    <!-- Email (Otomatis dari User) -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Email</label>
                        <input v-model="form.email" type="email" :readonly="!!form.user_id"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none disabled:opacity-50"
                            :placeholder="form.user_id ? 'Email otomatis dari user yang dipilih' : 'Pilih user terlebih dahulu'" />
                        <span v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</span>
                    </div>

                    <!-- Kelas -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Kelas</label>
                        <input type="text" readonly
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none opacity-50"
                            value="XI RPL" />
                        <input type="hidden" v-model="form.id_kelas" />
                        <span v-if="form.errors.id_kelas" class="text-red-500 text-sm">{{ form.errors.id_kelas }}</span>
                    </div>

                    <!-- Jurusan -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Jurusan</label>
                        <input type="text" readonly
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none opacity-50"
                            value="Rekayasa Perangkat Lunak" />
                        <input type="hidden" v-model="form.id_jurusan" />
                        <span v-if="form.errors.id_jurusan" class="text-red-500 text-sm">{{ form.errors.id_jurusan }}</span>
                    </div>

                    <!-- Tombol -->
                    <div class="flex justify-end gap-3">
                        <Link href="/guru"
                            class="rounded-lg bg-zinc-700 px-4 py-2 text-sm font-semibold text-white hover:bg-zinc-600">
                        Batal
                        </Link>
                        <button type="submit" :disabled="form.processing"
                            class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700 disabled:opacity-50">
                            {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
