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
        Plus, Pencil, Trash2
    } from 'lucide-vue-next';
    import {
        defineProps,
        ref
    } from 'vue'

    const breadcrumbs: BreadcrumbItem[] = [{
            title: 'Dashboard',
            href: '/dashboard',
        },
        {
            title: 'Data Jurusan',
            href: '/jurusan',
        },
    ];
    interface jurusan {
        id_jurusan: number
        nama_jurusan: string
    }

    // contoh dummy data
    const props = defineProps < {
        jurusan:  {    data: jurusan[]
    links: { url: string | null; label: string; active: boolean }[]}
    } > ()

    // State untuk modal konfirmasi
    const showConfirmModal = ref(false)
    const selectedjurusan = ref<jurusan | null>(null)

    // Function untuk menampilkan konfirmasi hapus
    const confirmDelete = (jurusan: jurusan) => {
        selectedjurusan.value = jurusan
        showConfirmModal.value = true
    }

    // Function untuk membatalkan hapus
    const cancelDelete = () => {
        showConfirmModal.value = false
        selectedjurusan.value = null
    }

    // Function untuk melanjutkan hapus
    const proceedDelete = () => {
        if (selectedjurusan.value) {
            // Redirect ke route hapus
            window.location.href = route('jurusan.hapus', selectedjurusan.value.id_jurusan)
        }
    }
</script>

<template>

    <Head title="Data Jurusan" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
            <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-6 shadow-lg">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-xl font-semibold text-white">Data Jurusan</h1>
                    <TextLink :href="route('jurusan.tambah')"
                        class="flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition">
                        <Plus class="h-4 w-4" />
                        Tambah Jurusan
                    </TextLink>
                </div>

                <!-- Table -->
                <div class="overflow-hidden rounded-lg border border-zinc-800">
                    <table class="min-w-full divide-y divide-zinc-800">
                        <thead class="bg-zinc-800">
                            <tr>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Id Jurusan
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Nama Jurusan
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase text-zinc-300">Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800 bg-zinc-900 text-sm text-zinc-200">
                            <tr v-for="jurusan in props.jurusan.data" :key="jurusan.id_jurusan"
                                class="hover:bg-zinc-800/60 transition">
                                <td class="px-6 py-4 text-center">{{ jurusan.id_jurusan }}</td>
                                <td class="px-6 py-4 text-center">{{ jurusan.nama_jurusan }}</td>
                                <td class="px-6 py-4 text-center">
  <div class="flex justify-center gap-2">
    <TextLink
      :href="route('user.edit', jurusan.id_jurusan)"
      class="rounded-lg bg-yellow-500 p-2 text-white hover:bg-yellow-600"
    >
      <Pencil class="w-4 h-4" />
    </TextLink>
    <button
      @click="confirmDelete(jurusan)"
      class="rounded-lg bg-red-600 p-2 text-white hover:bg-red-700 transition"
    >
      <Trash2 class="w-4 h-4" />
    </button>
  </div>
</td>
                            </tr>
                        </tbody>

                    </table>
                </div>
                <!-- Pagination -->
        <div class="flex justify-center mt-4 gap-2">
          <button
            v-for="link in props.jurusan.links"
            :key="link.label"
            v-html="link.label"
            :disabled="!link.url"
            @click.prevent="link.url && $inertia.visit(link.url)"
            class="px-3 py-1 rounded-lg border text-sm"
            :class="{
              'bg-zinc-700 text-white': link.active,
              'bg-zinc-900 text-zinc-400 hover:bg-zinc-800': !link.active
            }"
          />
        </div>
            </div>
        </div>


        <!-- Modal Konfirmasi Hapus -->
        <div v-if="showConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="cancelDelete"></div>

            <!-- Modal -->
            <div class="relative bg-zinc-900 rounded-lg border border-zinc-800 shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-white">Konfirmasi Hapus</h3>
                    </div>

                    <!-- Content -->
                    <div class="mb-6">
                        <p class="text-zinc-300">
                            Apakah Anda yakin ingin menghapus data user <strong class="text-white">{{ selectedjurusan?.nama_jurusan }}</strong>?
                        </p>
                        <p class="text-sm text-zinc-400 mt-2">
                            Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3">
                        <button @click="cancelDelete"
                            class="px-4 py-2 text-sm font-medium text-zinc-300 bg-zinc-800 rounded-lg hover:bg-zinc-700 transition">
                            Batal
                        </button>
                        <button @click="proceedDelete"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
