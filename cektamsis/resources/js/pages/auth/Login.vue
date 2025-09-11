<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle, Eye, EyeOff } from 'lucide-vue-next';
import { ref } from 'vue';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

// State untuk toggle visibilitas password
const showPassword = ref(false);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

// Fungsi untuk toggle visibilitas password
const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};
</script>

<template>
    <AuthBase title="Login - Sistem Absensi Digital" description="">
        <Head title="Login" />

        <form method="POST" @submit.prevent="submit" class="space-y-5">
            <div>
                <Label for="email" class="text-sm text-gray-600">Email</Label>
                <Input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autocomplete="email"
                    placeholder="email@example.com"
                    class="rounded-xl"
                    style="color: #000000;"
                />
                <InputError :message="form.errors.email" />
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <Label for="password" class="text-sm text-gray-600">Password</Label>
                    <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-sm text-gray-600">
                        Lupa password?
                    </TextLink>
                </div>
                <div class="relative">
                    <Input
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        placeholder="Password"
                        class="rounded-xl pr-10"
                        style="color: #000000;"
                    />
                    <button
                        type="button"
                        @click="togglePasswordVisibility"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-600 hover:text-gray-800"
                    >
                        <Eye v-if="!showPassword" class="h-5 w-5" />
                        <EyeOff v-else class="h-5 w-5" />
                    </button>
                </div>
                <InputError :message="form.errors.password" />
            </div>

            <div class="flex items-center space-x-2">
                <Checkbox id="remember" v-model="form.remember" />
                <Label for="remember" class="text-sm text-gray-600">Ingat saya</Label>
            </div>

            <Button type="submit" class="w-full rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium py-2" :disabled="form.processing">
                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin mr-2 inline" />
                Masuk
            </Button>
        </form>
    </AuthBase>
</template>

<style scoped>
/* Pastikan button ikon mata tidak tumpang tindih dengan input */
.relative input {
    padding-right: 2.5rem; /* Sesuaikan dengan lebar ikon */
}
</style>