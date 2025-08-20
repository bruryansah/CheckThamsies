<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthBase title="Login - Sistem Absensi Digital" description="">
        <Head title="Login" />

        <form method="POST" @submit.prevent="submit" class="space-y-5">
            <div>
                <Label for="email">Email</Label>
                <Input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autocomplete="email"
                    placeholder="email@example.com"
                    class="rounded-xl"
                />
                <InputError :message="form.errors.email" />
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <Label for="password">Password</Label>
                    <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-sm text-blue-600 hover:underline">
                        Lupa password?
                    </TextLink>
                </div>
                <Input
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="Password"
                    class="rounded-xl"
                />
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
