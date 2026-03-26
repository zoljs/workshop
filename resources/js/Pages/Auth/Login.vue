<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Checkbox } from '@/Components/ui/checkbox';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
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
    <GuestLayout>
        <Head title="Bejelentkezés" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-zinc-900">Bejelentkezés</h1>
            <p class="mt-1 text-sm text-zinc-500">
                Add meg az adataidat a folytatáshoz.
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div class="space-y-1.5">
                <Label for="email">Email</Label>
                <Input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />
                <p v-if="form.errors.email" class="text-xs text-red-500">
                    {{ form.errors.email }}
                </p>
            </div>

            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <Label for="password">Jelszó</Label>
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm text-zinc-500 underline hover:text-zinc-800"
                    >
                        Elfelejtett jelszó?
                    </Link>
                </div>
                <Input
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />
                <p v-if="form.errors.password" class="text-xs text-red-500">
                    {{ form.errors.password }}
                </p>
            </div>

            <div class="flex items-center gap-2">
                <Checkbox id="remember" v-model:checked="form.remember" />
                <Label for="remember" class="font-normal text-zinc-600"
                    >Emlékezz rám</Label
                >
            </div>

            <Button type="submit" class="w-full" :disabled="form.processing">
                {{ form.processing ? 'Bejelentkezés...' : 'Bejelentkezés' }}
            </Button>

            <p class="text-center text-sm text-zinc-500">
                Még nincs fiókod?
                <Link
                    :href="route('register')"
                    class="underline hover:text-zinc-800"
                    >Regisztráció</Link
                >
            </p>
        </form>
    </GuestLayout>
</template>
