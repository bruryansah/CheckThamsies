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
        watch,
        onMounted
    } from 'vue'

    // Breadcrumb
    const breadcrumbs: BreadcrumbItem[] = [{
            title: 'Dashboard',
            href: '/dashboard'
        },
        {
            title: 'Edit Data Siswa XII RPL',
            href: '/siswaxii'
        },
    ]

    // Props dari controller
    const props = defineProps < {
        siswa: {
            id_siswa: number; // Pastikan menggunakan id_siswa sesuai database
            user_id: number;
            nama: string;
            email: string;
            id_kelas: number;
            id_jurusan: number;
        },
        users: {
            id: number;
            name: string;
            email: string;
        } [],
        kelas: {
            id_kelas: number;
            nama_kelas: string;
        } [],
        jurusan: {
            id_jurusan: number;
            nama_jurusan: string;
        } [],
    } > ()

    // Inertia form
    const form = useForm({
        user_id: '',
        nama: '',
        email: '',
        id_kelas: '',
        id_jurusan: '',
    })

    // Isi form dengan data siswa X RPL saat component dimount
    onMounted(() => {
        if (props.siswa) {
            form.user_id = props.siswa.user_id?.toString() || '';
            form.nama = props.siswa.nama || '';
            form.email = props.siswa.email || '';
            form.id_kelas = props.siswa.id_kelas?.toString() || '';
            form.id_jurusan = props.siswa.id_jurusan?.toString() || '   ';
        }
    });

    // Auto isi nama & email kalau ganti user
    watch(() => form.user_id, (newId) => {
        if (newId) {
            const selectedUser = props.users.find(user => user.id === Number(newId))
            if (selectedUser) {
                form.email = selectedUser.email
            }
        }
    })
</script>

<template>

    <Head title="Edit Data Siswa XII RPL" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
            <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-lg max-w-2xl w-full mx-auto">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-xl font-semibold text-white">Form Edit Siswa X RPL</h1>
                </div>

                <!-- Form -->
                <form @submit.prevent="form.put(`/siswax/update/${props.siswa.id_siswa}`)" class="space-y-5">
                    <!-- Id Users -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Pilih User</label>
                        <select v-model="form.user_id"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none">
                            <option value="">-- Pilih User --</option>
                            <option v-for="user in props.users" :key="user.id" :value="user.id">
                                {{ user . name }}
                            </option>
                        </select>
                        <span v-if="form.errors.user_id" class="text-red-500 text-sm">{{ form . errors . user_id }}</span>
                    </div>

                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Nama Siswa</label>
                        <input v-model="form.nama" type="text"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none disabled:opacity-50"
                            placeholder="Masukkan nama siswa" />
                        <span v-if="form.errors.nama" class="text-red-500 text-sm">{{ form . errors . nama }}</span>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Email</label>
                        <input v-model="form.email" type="email" :readonly="!!form.user_id"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none disabled:opacity-50"
                            placeholder="Pilih nama user terlebih dahulu" />
                        <span v-if="form.errors.email" class="text-red-500 text-sm">{{ form . errors . email }}</span>
                    </div>
                    <!-- Kelas -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Kelas</label>
                        <select v-model="form.id_kelas"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none">
                            <option value="">-- Pilih Kelas --</option>
                            <option v-for="k in props.kelas" :key="k.id_kelas" :value="k.id_kelas">
                                {{ k . nama_kelas }}
                            </option>
                        </select>
                        <span v-if="form.errors.id_kelas" class="text-red-500 text-sm">
                            {{ form . errors . id_kelas }}
                        </span>
                    </div>

                    <!-- Jurusan -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Jurusan</label>
                        <select v-model="form.id_jurusan"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none">
                            <option value="">-- Pilih Jurusan --</option>
                            <option v-for="j in props.jurusan" :key="j.id_jurusan" :value="j.id_jurusan">
                                {{ j . nama_jurusan }}
                            </option>
                        </select>
                        <span v-if="form.errors.id_jurusan" class="text-red-500 text-sm">
                            {{ form . errors . id_jurusan }}
                        </span>
                    </div>

                    <!-- Tombol -->
                    <div class="flex justify-end gap-3">
                        <Link href="/xiirpl"
                            class="rounded-lg bg-zinc-700 px-4 py-2 text-sm font-semibold text-white hover:bg-zinc-600">
                        Batal
                        </Link>
                        <button type="submit" :disabled="form.processing"
                            class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700 disabled:opacity-50">
                            {{ form . processing ? 'Menyimpan...' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
