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
            title: 'Edit Data Mapel',
            href: '/mapel'
        },
    ]

    // Props dari controller
    const props = defineProps < {
        mapel: {
            id_mapel: number; // Pastikan menggunakan id_siswa sesuai database
            nama_mapel: string;
        },
    } > ()

    // Inertia form
    const form = useForm({
        nama_mapel: '',
    })

    // Isi form dengan data siswa X RPL saat component dimount
    onMounted(() => {
        if (props.mapel) {
            form.nama_mapel = props.mapel.nama_mapel || '';
        }
    });

    // Function untuk submit form
    const submitForm = () => {
        form.put(`/siswax/update/${props.mapel.id_mapel}`, {
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

    <Head title="Edit Data Mapel" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
            <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-lg max-w-2xl w-full mx-auto">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-xl font-semibold text-white">Form Edit mapel</h1>
                </div>

                <!-- Form -->
                <form @submit.prevent="form.put(`/mapel/update/${props.mapel.id_mapel}`)" class="space-y-5">
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Nama Mapel</label>
                        <input v-model="form.nama_mapel" type="text"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none disabled:opacity-50"
                            placeholder="Masukkan nama mapel" />
                        <span v-if="form.errors.nama_mapel" class="text-red-500 text-sm">{{ form.errors.nama_mapel }}</span>
                    </div>
                    <!-- Tombol -->
                    <div class="flex justify-end gap-3">
                        <Link href="/mapel"
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
