<script setup lang="ts">
    import AppLayout from '@/layouts/AppLayout.vue'
    import {
        Head,
        useForm,
        Link
    } from '@inertiajs/vue3'
    import {
        type BreadcrumbItem
    } from '@/types'

    // Breadcrumb
    const breadcrumbs: BreadcrumbItem[] = [{
            title: 'Dashboard',
            href: '/dashboard'
        },
        {
            title: 'Kelola User',
            href: '/user'
        },
        {
            title: 'Ganti Password',
            href: '/user/password'
        },
    ]

    // ✅ Definisikan props dengan type array of object
    const props = defineProps < {
        users: {
            id: number;email: string
        } []
    } > ()

    // Inertia form
    const form = useForm({
        id: '',
        new_password: '',
        new_password_confirmation: '',
    })

    const submitForm = () => {
        form.post(route('user.updatepass'), {
            onSuccess: () => {
                console.log('Password berhasil diubah!')
                form.reset()
            },
            onError: (errors) => {
                console.error('Error:', errors)
            },
        })
    }
</script>


<template>

    <Head title="Ganti Password User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
            <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-lg max-w-2xl w-full mx-auto">
                <h1 class="text-xl font-semibold text-white mb-6">Form Ganti Password</h1>

                <form @submit.prevent="submitForm" class="space-y-5">
                    <!-- Pilih User -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Pilih User</label>
                        <select v-model="form.id"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none">
                            <option value="">-- Pilih User --</option>
                            <option v-for="user in users" :key="user.id" :value="user.id">
                                {{ user . email }}
                            </option>
                        </select>
                        <span v-if="form.errors.id" class="text-red-500 text-sm">{{ form . errors . id }}</span>
                    </div>

                    <!-- Password Baru -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Password Baru</label>
                        <input v-model="form.new_password" type="password"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none"
                            placeholder="Masukkan password baru" />
                        <span v-if="form.errors.new_password"
                            class="text-red-500 text-sm">{{ form . errors . new_password }}</span>
                    </div>

                    <!-- Konfirmasi Password Baru -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-1">Konfirmasi Password Baru</label>
                        <input v-model="form.new_password_confirmation" type="password"
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-800 text-white px-4 py-2 focus:ring focus:ring-green-500 focus:outline-none"
                            placeholder="Ulangi password baru" />
                        <span v-if="form.errors.new_password_confirmation"
                            class="text-red-500 text-sm">{{ form . errors . new_password_confirmation }}</span>
                    </div>

                    <!-- Tombol -->
                    <div class="flex justify-end gap-3">
                        <Link href="/user"
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
