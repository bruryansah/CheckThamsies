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
            title: 'Edit Data Guru',
            href: '/guru'
        },
    ]

    // Props dari controller
    const props = defineProps < {
        guru: {
            id_guru: number; // Pastikan menggunakan id_guru sesuai database
            user_id: number;
            nama: string;
            nip: number;
            email: string;
            id_mapel: number;
            foto: string;
        },
        users: {
            id: number;
            name: string;
            email: string;
        } [],
        mapel: {
            id_mapel: number;
            nama_mapel: string;
        } [],
    } > ()

    // Inertia form
    const form = useForm({
        user_id: '',
        nama: '',
        nip: '',
        email: '',
        id_kelas: '',
        id_mapel: '',
        foto: null as File | null, // WAJIB ada untuk file upload
    })

    // Isi form dengan data guru saat component dimount
    onMounted(() => {
        if (props.guru) {
            form.user_id = props.guru.user_id?.toString() || '';
            form.nama = props.guru.nama || '';
            form.nip = props.guru.nip?.toString() || '';
            form.email = props.guru.email || '';
            form.id_mapel = props.guru.id_mapel?.toString() || '';
            foto: null as File | null; // WAJIB ada untuk file upload
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

    // Function untuk submit form
    const submitForm = () => {
        form.put(`/guru/update/${props.guru.id_guru}`, {
            onSuccess: () => {
                // Optional: tambahkan notifikasi sukses
                console.log('Data berhasil diperbarui!');
            },
            onError: (errors) => {
                console.error('Error:', errors);
            }
        });
    }
</script>

<template>

    <Head title="Edit Data Guru" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
            <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-lg max-w-2xl w-full mx-auto">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-xl font-semibold text-white">Form Edit Guru</h1>
                </div>

                <!-- Form -->
                <form @submit.prevent="submitForm" class="space-y-5">
                    <!-- Foto -->
                    <div class="mb-4">
                        <label class="mb-2 block">Foto Guru</label>
                        <input type="file" class="block w-full rounded border bg-gray-800 p-2"
                            @change="(e: any) => (form.foto = e.target.files[0])" />
                        <div v-if="form.errors.foto" class="text-sm text-red-500">{{ form . errors . foto }}</div>
                    </div>
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
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Nama Guru</label>
                        <input v-model="form.nama" type="text"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none disabled:opacity-50"
                            placeholder="Masukkan nama guru" />
                        <span v-if="form.errors.nama" class="text-red-500 text-sm">{{ form . errors . nama }}</span>
                    </div>

                    <!-- NIP -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">NIP</label>
                        <input v-model="form.nip" type="text"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none disabled:opacity-50"
                            placeholder="Masukkan NIP guru" />
                        <span v-if="form.errors.nip" class="text-red-500 text-sm">{{ form . errors . nip }}</span>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Email</label>
                        <input v-model="form.email" type="email" :readonly="!!form.user_id"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none disabled:opacity-50"
                            placeholder="Masukkan email guru" />
                        <span v-if="form.errors.email" class="text-red-500 text-sm">{{ form . errors . email }}</span>
                    </div>

                    <!-- Mapel -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Pilih Mata Pelajaran</label>
                        <select v-model="form.id_mapel"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none">
                            <option value="">-- Pilih Mapel --</option>
                            <option v-for="mapel in props.mapel" :key="mapel.id_mapel" :value="mapel.id_mapel">
                                {{ mapel . nama_mapel }}
                            </option>
                        </select>
                        <span v-if="form.errors.id_mapel" class="text-red-500 text-sm">{{ form . errors . id_mapel }}</span>
                    </div>

                    <!-- Tombol -->
                    <div class="flex justify-end gap-3">
                        <Link href="/guru"
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
