<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});
</script>

<template>
    <GuestLayout>
        <Head title="Jelszó megerősítése" />

        <div class="space-y-6">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-primary">
                    Jelszó megerősítése
                </h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    Ez egy biztonságos terület. A folytatáshoz erősítsd meg a
                    jelszavadat.
                </p>
            </div>

            <form
                @submit.prevent="
                    form.post(route('password.confirm'), {
                        onFinish: () => form.reset(),
                    })
                "
                class="space-y-4"
            >
                <div class="space-y-2">
                    <Label for="password">Jelszó</Label>
                    <Input
                        id="password"
                        type="password"
                        v-model="form.password"
                        required
                        autofocus
                        autocomplete="current-password"
                    />
                    <p
                        v-if="form.errors.password"
                        class="text-sm text-destructive"
                    >
                        {{ form.errors.password }}
                    </p>
                </div>

                <Button
                    type="submit"
                    class="w-full"
                    :disabled="form.processing"
                >
                    {{ form.processing ? 'Megerősítés...' : 'Megerősítés' }}
                </Button>
            </form>
        </div>
    </GuestLayout>
</template>
