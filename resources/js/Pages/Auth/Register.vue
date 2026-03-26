<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Regisztráció" />

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-zinc-900">Regisztráció</h1>
            <p class="mt-1 text-sm text-zinc-500">
                Add meg az adataidat a folytatáshoz.
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div class="space-y-1.5">
                <Label for="name">Név</Label>
                <Input
                    id="name"
                    type="text"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />
                <p v-if="form.errors.name" class="text-xs text-red-500">
                    {{ form.errors.name }}
                </p>
            </div>

            <div class="space-y-1.5">
                <Label for="email">Email</Label>
                <Input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />
                <p v-if="form.errors.email" class="text-xs text-red-500">
                    {{ form.errors.email }}
                </p>
            </div>

            <div class="space-y-1.5">
                <Label for="password">Jelszó</Label>
                <Input
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />
                <p v-if="form.errors.password" class="text-xs text-red-500">
                    {{ form.errors.password }}
                </p>
            </div>

            <div class="space-y-1.5">
                <Label for="password_confirmation">Jelszó megerősítése</Label>
                <Input
                    id="password_confirmation"
                    type="password"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />
                <p
                    v-if="form.errors.password_confirmation"
                    class="text-xs text-red-500"
                >
                    {{ form.errors.password_confirmation }}
                </p>
            </div>

            <Button type="submit" class="w-full" :disabled="form.processing">
                {{ form.processing ? 'Regisztráció...' : 'Regisztráció' }}
            </Button>

            <p class="text-center text-sm text-zinc-500">
                Már van fiókod?
                <Link
                    :href="route('login')"
                    class="underline hover:text-zinc-800"
                    >Bejelentkezés</Link
                >
            </p>
        </form>
    </GuestLayout>
</template>
