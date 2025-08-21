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
            title: 'Edit Data Users',
            href: '/user'
        },
    ]

    // Props dari controller - DIPERBAIKI
    const props = defineProps<{
        user: {  // single user untuk diedit
            id: number;
            name: string;
            email: string;
            role: string;
        },
        users?: Array<{  // optional collection untuk referensi
            id: number;
            name: string;
            email: string;
            role: string;
        }>,
    }>()

    // Array role yang tersedia - DITAMBAHKAN
    const availableRoles = ['Admin', 'guru', 'user']

    // Inertia form
    const users = useForm({
        id: '',
        name: '',
        email: '',
        role: '',
    })

    // Isi form dengan data user saat component dimount - DIPERBAIKI
    onMounted(() => {
        if (props.user) {  // gunakan props.user bukan props.users
            users.id = props.user.id.toString() || '';
            users.name = props.user.name || '';
            users.email = props.user.email || '';
            users.role = props.user.role || '';
        }
    });

    // Function untuk submit form - DIPERBAIKI
    const submitForm = () => {
        users.put(`/user/update/${props.user.id}`, {  // gunakan props.user.id
            onSuccess: () => {
                console.log('Data berhasil diperbarui!');
            },
            onError: (errors) => {
                console.error('Error:', errors);
            }
        });
    }
</script>

<template>
    <Head title="Edit Data User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
            <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-lg max-w-2xl w-full mx-auto">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-xl font-semibold text-white">Form Edit User</h1>
                </div>

                <!-- Form -->
                <form @submit.prevent="submitForm" class="space-y-5">
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Nama User</label>
                        <input v-model="users.name" type="text"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none disabled:opacity-50"
                            placeholder="Masukkan nama user" />
                        <span v-if="users.errors.name" class="text-red-500 text-sm">{{ users.errors.name }}</span>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Email User</label>
                        <input v-model="users.email" type="email"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none disabled:opacity-50"
                            placeholder="Masukkan email user" />
                        <span v-if="users.errors.email" class="text-red-500 text-sm">{{ users.errors.email }}</span>
                    </div>

                    <!-- Role - DIPERBAIKI -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Pilih Role</label>
                        <select v-model="users.role"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none">
                            <option value="">-- Pilih Role --</option>
                            <option v-for="role in availableRoles" :key="role" :value="role">
                                {{ role }}
                            </option>
                        </select>
                        <span v-if="users.errors.role" class="text-red-500 text-sm">{{ users.errors.role }}</span>
                    </div>

                    <!-- Tombol -->
                    <div class="flex justify-end gap-3">
                        <Link href="/user"
                            class="rounded-lg bg-zinc-700 px-4 py-2 text-sm font-semibold text-white hover:bg-zinc-600">
                        Batal
                        </Link>
                        <button type="submit" :disabled="users.processing"
                            class="rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700 disabled:opacity-50">
                            {{ users.processing ? 'Menyimpan...' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
