<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onMounted, watch } from 'vue';

// Breadcrumb
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Edit Data Siswa XI RPL',
        href: '/siswaxi',
    },
];

// Props dari controller
const props = defineProps<{
    siswa: {
        id_siswa: number; // Pastikan menggunakan id_siswa sesuai database
        user_id: number;
        nama: string;
        nisn: string;
        email: string;
        id_kelas: number;
        id_jurusan: number;
    };
    users: {
        id: number;
        name: string;
        email: string;
    }[];
    kelas: {
        id_kelas: number;
        nama_kelas: string;
    }[];
    jurusan: {
        id_jurusan: number;
        nama_jurusan: string;
    }[];
}>();

// Inertia form
const form = useForm({
    user_id: '',
    nama: '',
    nisn: '',
    email: '',
    id_kelas: '',
    id_jurusan: '',
});

// Isi form dengan data siswa X RPL saat component dimount
onMounted(() => {
    if (props.siswa) {
        form.user_id = props.siswa.user_id?.toString() || '';
        form.nama = props.siswa.nama || '';
        form.nisn = props.siswa.nisn || '';
        form.email = props.siswa.email || '';
        form.id_kelas = props.siswa.id_kelas?.toString() || '';
        form.id_jurusan = props.siswa.id_jurusan?.toString() || '';
    }
});

watch(
    () => form.id_kelas,
    (newKelasId) => {
        if (newKelasId) {
            const selectedKelas = props.kelas.find((k) => k.id_kelas === Number(newKelasId));
            if (selectedKelas) {
                const namaKelas = selectedKelas.nama_kelas.toUpperCase();

                // Peta kode singkat kelas ke nama jurusan di database
                const jurusanMapping: Record<string, string> = {
                    RPL: 'Rekayasa Perangkat Lunak',
                    TKJ: 'Teknik Komputer dan Jaringan',
                    TBSM: 'Teknik Bisnis Sepeda Motor',
                    TITL: 'Teknik Instalasi Tenaga Listrik',
                    TP: 'Teknik Pemesinan',
                    TKR: 'Teknik Kendaraan Ringan',
                };

                // Cari jurusan berdasarkan kode yang muncul di nama_kelas
                const kode = Object.keys(jurusanMapping).find((k) => namaKelas.includes(k));

                if (kode) {
                    const namaJurusan = jurusanMapping[kode];
                    const jurusan = props.jurusan.find((j) => j.nama_jurusan.toLowerCase() === namaJurusan.toLowerCase());
                    if (jurusan) {
                        form.id_jurusan = jurusan.id_jurusan;
                    } else {
                        form.id_jurusan = '';
                    }
                } else {
                    // Jika nama kelas tidak mengandung kode jurusan apa pun
                    form.id_jurusan = '';
                }
            }
        } else {
            form.id_jurusan = '';
        }
    },
);

// Auto isi nama & email kalau ganti user
watch(
    () => form.user_id,
    (newId) => {
        if (newId) {
            const selectedUser = props.users.find((user) => user.id === Number(newId));
            if (selectedUser) {
                form.email = selectedUser.email;
            }
        }
    },
);
</script>

<template>
    <Head title="Edit Data Siswa XI RPL" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-6">
            <div class="mx-auto w-full max-w-2xl rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-lg">
                <!-- Header -->
                <div class="mb-6 flex items-center justify-between">
                    <h1 class="text-xl font-semibold text-white">Form Edit Siswa XI RPL</h1>
                </div>

                <!-- Form -->
                <form @submit.prevent="form.put(`/siswax/update/${props.siswa.id_siswa}`)" class="space-y-5">
                    <!-- Id Users -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-300">Pilih User</label>
                        <select
                            v-model="form.user_id"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2 text-white focus:ring focus:ring-green-500 focus:outline-none"
                        >
                            <option value="">-- Pilih User --</option>
                            <option v-for="user in props.users" :key="user.id" :value="user.id">
                                {{ user.name }}
                            </option>
                        </select>
                        <span v-if="form.errors.user_id" class="text-sm text-red-500">{{ form.errors.user_id }}</span>
                    </div>

                    <!-- Nama -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-300">Nama Siswa</label>
                        <input
                            v-model="form.nama"
                            type="text"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2 text-white focus:ring focus:ring-green-500 focus:outline-none disabled:opacity-50"
                            placeholder="Masukkan nama siswa"
                        />
                        <span v-if="form.errors.nama" class="text-sm text-red-500">{{ form.errors.nama }}</span>
                    </div>

                    <!-- Nisn -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-300">Nisn Siswa</label>
                        <input
                            v-model="form.nisn"
                            type="text"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2 text-white focus:ring focus:ring-green-500 focus:outline-none disabled:opacity-50"
                            placeholder="Masukkan nama siswa"
                        />
                        <span v-if="form.errors.nisn" class="text-sm text-red-500">{{ form.errors.nisn }}</span>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-300">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            :readonly="!!form.user_id"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2 text-white focus:ring focus:ring-green-500 focus:outline-none disabled:opacity-50"
                            placeholder="Pilih nama user terlebih dahulu"
                        />
                        <span v-if="form.errors.email" class="text-sm text-red-500">{{ form.errors.email }}</span>
                    </div>
                    <!-- Kelas -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-300">Kelas</label>
                        <select
                            v-model="form.id_kelas"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2 text-white focus:ring focus:ring-green-500 focus:outline-none"
                        >
                            <option value="">-- Pilih Kelas --</option>
                            <option v-for="k in props.kelas.filter((k) => /^XI\s/.test(k.nama_kelas))" :key="k.id_kelas" :value="k.id_kelas">
                                {{ k.nama_kelas }}
                            </option>
                        </select>
                        <span v-if="form.errors.id_kelas" class="text-sm text-red-500">
                            {{ form.errors.id_kelas }}
                        </span>
                    </div>

                    <!-- Jurusan -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-300">Jurusan</label>
                        <select
                            v-model="form.id_jurusan"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-2 text-white focus:ring focus:ring-green-500 focus:outline-none"
                        >
                            <option value="">-- Pilih Jurusan --</option>
                            <option v-for="j in props.jurusan" :key="j.id_jurusan" :value="j.id_jurusan">
                                {{ j.nama_jurusan }}
                            </option>
                        </select>
                        <span v-if="form.errors.id_jurusan" class="text-sm text-red-500">
                            {{ form.errors.id_jurusan }}
                        </span>
                    </div>

                    <!-- Tombol -->
                    <div class="flex justify-end gap-3">
                        <Link href="/xirpl" class="rounded-lg bg-zinc-700 px-4 py-2 text-sm font-semibold text-white hover:bg-zinc-600"> Batal </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
i
